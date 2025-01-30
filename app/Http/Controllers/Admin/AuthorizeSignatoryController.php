<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\CategoryMaster;
use App\AuthorizedPersonDetail;
use App\DocumentMaster;
use App\DocumentUploads;
use App\User;
use App\AdditionalInfo;
use Mail;
use App\Mail\AuthPersonDetMail;


class AuthorizeSignatoryController extends Controller
{

    public function list($catId ='',$targetId='')
    {
        if($catId=='' && $targetId==''){
           $appMast = User::where(DB::RAW("is_normal_user(id)"), 1)
           ->where('isactive','Y')
           ->orderBy('id')
           ->get(['users.*']);
            $targetSegment = [];
        }else{
            $query = User::where(DB::RAW("is_normal_user(id)"), 1)
           ->where('isactive','Y');

            if(isset($catId) && in_array($catId,[1,2,3,4])){
                $query->whereRaw("string_to_array(users.category, ',') && array['$catId']");           
            }

            if(isset($targetId) && in_array($targetId,[1,2,3,4,5,6,9,10,11])){
                $query->whereRaw("string_to_array(users.target_segment, ',') && array['$targetId']");
            }
        
           

            $query->orderBy('id');
            $appMast=$query->get(['users.*']);
           
            $targetSegment =  DB::table('target_segments')->where('category_id',$catId)->orderBy('id')->get();
        }

        $category = CategoryMaster::orderBy('id')->get();
        $allTargetSegment =  DB::table('target_segments')->orderBy('id')->get();
        return view('admin.users.authorizeApplicantList', compact('appMast','category','targetSegment','allTargetSegment','catId','targetId'));
    }

    public function asTransaction($user_id)
    {
        $authPersons = AuthorizedPersonDetail::where('user_id', $user_id)->get();

        $userDetails = DB::table('users')->where('id',$user_id)->first();

        return view('admin.users.authorizeChangeDetail',compact('authPersons','userDetails'));
    }

    public function updateAuthorization(Request $request)
    {
        DB::transaction(function () use ($request) {
            $User = User::find($request->user_id);

            $uploadId = array();

            if ($request->hasfile('authorizationLetter')) {
                $newDoc = $request->file('authorizationLetter');
                
                $doc = new DocumentUploads;
                    $doc->doc_id = 73;
                    $doc->mime = $newDoc->getMimeType();
                    $doc->file_size = $newDoc->getSize();
                    $doc->updated_at = Carbon::now();
                    $doc->user_id = $request->user_id;
                    $doc->created_at = Carbon::now();
                    $doc->file_name = $newDoc->getClientOriginalName();
                    $doc->uploaded_file = fopen($newDoc->getRealPath(), 'r');
                    $doc->remarks = '';
                $doc->save();

                array_push($uploadId, $doc->id);
            }
            
            $authDetail = new AuthorizedPersonDetail();
                $authDetail->user_id = $request->user_id;
                $authDetail->new_contact_person = $request->contact_person;
                $authDetail->new_designation = $request->designation;
                $authDetail->new_email = $request->email;
                $authDetail->new_mobile = $request->mobile;
                $authDetail->upload_id =  $uploadId;
                $authDetail->old_contact_person = $User->contact_person;
                $authDetail->old_designation = $User->designation;
                $authDetail->old_email = $User->email;
                $authDetail->old_mobile = $User->mobile;
                $authDetail->status='1';
                $authDetail->admin_created_by =Auth::user()->id;
                $authDetail->admin_created_at =Carbon::now();
            $authDetail->save();

            $User->email = $request->email;
            $User->email_verified_at = null;
            $User->mobile = $request->mobile;
            $User->mobile_verified_at = null;
            $User->contact_person = $request->contact_person;
            $User->designation = $request->designation;
            $User->save();

            Mail::to($User->email)
            ->send(new AuthPersonDetMail($User));
        });
        alert()->success('Authorization Details has been Updated', 'Success')->persistent('Close');

        return redirect()->route('admin.authsignatory.list');
    }

    public function downloadAuthorizationLetter($upload_id)
    {
        $upload_id = decrypt($upload_id);
        $doc =  DocumentUploads::where('id', $upload_id)->first();
        
        ob_start();
        fpassthru($doc->uploaded_file);
        $docc= ob_get_contents();
        ob_end_clean();

        $ext = '';

        if($doc->mime == "application/pdf") {
            $ext = 'pdf';
        }elseif ($doc->mime == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            $ext = 'docx';
        }elseif ($doc->mime == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $ext = 'xlsx';
        } elseif ($doc->mime == "image/png") {
            $ext = 'png';
        } elseif ($doc->mime == "image/jpeg") {
            $ext = 'jpg';
        }
        $doc_name = 'authorizationLetter';
        
        return response($docc)
            ->header('Cache-Control', 'no-cache private')
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Type', $doc->mime)
            ->header('Content-length', strlen($docc))
            ->header('Content-Disposition', 'attachment; filename='.$doc_name.'.'.$ext)
        ->header('Content-Transfer-Encoding', 'binary');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
