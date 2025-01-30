<?php

namespace App\Http\Controllers\Admin\BgTracker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Carbon\Carbon;

use App\BgTracker;
use App\BgUpload;
use App\DocumentMaster;
use App\DocumentUploads;

use App\Exports\BgExport;
use Maatwebsite\Excel\Facades\Excel;

class BgTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apps = DB::table('approved_users')
            ->leftJoin('bg_trackers',
                function($join){
                    $join->on('bg_trackers.app_id', '=', 'approved_users.app_id')
                    ->where('bg_trackers.submit', '=', 'Y')
                    ->orderBy('bg_trackers.created_at', 'DESC');
                })
            ->where(DB::RAW("is_normal_user(approved_users.id)"), 1)
            ->distinct('approved_users.app_id', 'approved_users.name')
            ->orderBy('approved_users.name')
        ->get(['approved_users.name', 'approved_users.target_segment', 'approved_users.app_id', 'approved_users.round', 'bg_trackers.id', 'bg_trackers.bg_no', 'bg_trackers.issued_dt', 'bg_trackers.expiry_dt', 'bg_trackers.bg_status']);

        $date = Carbon::now()->addDay(15);
        $today = Carbon::now();

        return view('admin.bgtracker.bg_tracker_dashboard', compact('apps', 'date', 'today'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($app_id)
    {
        $appMast=DB::table('approved_users')->where('app_id', $app_id)->first();

        return view('admin.bgtracker.bg_tracker_create', compact('appMast'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $doctypes = DocumentMaster::pluck('doc_type', 'doc_id')->toArray();

        DB::transaction(function () use ($doctypes, $request) {
            BgTracker::where('app_id', $request->app_id)->update(['submit' => 'N']);
            $bg = new BgTracker;
                $bg->app_id = $request->app_id;
                $bg->user_id = Auth::user()->id;
                $bg->bank_name = $request->bank_name;
                $bg->branch_address = $request->branch_address;
                $bg->bg_no = $request->bg_no;
                $bg->bg_amount = $request->bg_amount;
                $bg->issued_dt = $request->issued_dt;
                $bg->expiry_dt = $request->expiry_dt;
                $bg->claim_dt = $request->claim_dt;
                $bg->bg_status = $request->bg_status;
                $bg->bg_bank_conf = $request->bg_bank_conf;
                $bg->remark = $request->remark;
            $bg->save();

            foreach ($doctypes as $docid => $doctype) {
                if ($request->hasfile($doctype)) {
                    $newDoc = $request->file($doctype);

                    $doc = new DocumentUploads;
                        $doc->app_id = $request->app_id;
                        $doc->doc_id = $docid;
                        $doc->mime = $newDoc->getMimeType();
                        $doc->file_size = $newDoc->getSize();
                        $doc->updated_at = Carbon::now();
                        $doc->user_id = Auth::user()->id;
                        $doc->created_at = Carbon::now();
                        $doc->file_name = $newDoc->getClientOriginalName();
                        $doc->uploaded_file = fopen($newDoc->getRealPath(), 'r');
                    $doc->save();

                    $bgUpload = new BgUpload;
                        $bgUpload->app_id = $request->app_id;
                        $bgUpload->user_id = Auth::user()->id;
                        $bgUpload->bg_id = $bg->id;
                        $bgUpload->doc_id = $docid;
                        $bgUpload->upload_id = $doc->id;
                        $bgUpload->doc_name = $doctype;
                        $bgUpload->mime_type = $newDoc->getMimeType();
                    $bgUpload->save();
                }
            }
            alert()->success('BG Data Saved', 'Success')->persistent('Close');
        });
        return redirect()->route('admin.bgtracker.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($app_id, $bg_id)
    {
        $bgView=BgTracker::join('approved_users', 'bg_trackers.app_id', '=', 'approved_users.app_id')
            ->where('bg_trackers.id', $bg_id)
            ->where('bg_trackers.app_id', $app_id)
            ->select('approved_users.app_id', 'approved_users.app_no', 'approved_users.name', 'approved_users.target_segment', 'bg_trackers.*')
        ->first();

        $bgs = BGTracker::where('app_id', $app_id)
            ->orderBy('id', 'DESC')
        ->get();

        $exp_date = Carbon::now()->addDay(15);
        $today = Carbon::now();

        $uploadIds = BgUpload::where('app_id', $app_id)
            ->where('bg_id', $bg_id)
            ->whereIn('doc_id', array(30,31))
        ->pluck('upload_id', 'doc_id');

        return view('admin.bgtracker.bg_tracker_view', compact('bgView', 'bgs', 'exp_date', 'today', 'uploadIds'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($bg_id)
    {
        $bgView=DB::table('approved_users')
            ->join('bg_trackers', 'bg_trackers.app_id', '=', 'approved_users.app_id')
            ->where('bg_trackers.id',$bg_id)
            ->select('approved_users.*', 'bg_trackers.*')
        ->first();
        // dd($bgView);
        return view('admin.bgtracker.bg_tracker_edit', compact('bgView'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bg_id)
    {
        // $bg = BgTracker::where('id', $bg_id)->first();

        $doctypes = DocumentMaster::pluck('doc_type', 'doc_id')->toArray();

        // $uploadIds;

        DB::transaction(function () use ($bg_id, $doctypes, $request) {
            BgTracker::where('app_id', $request->app_id)->where('id', '!=', $bg_id)->update(['submit' => 'N']);
            $bg = BgTracker::find($bg_id);
                $bg->bank_name = $request->bank_name;
                $bg->branch_address = $request->branch_address;
                $bg->bg_no = $request->bg_no;
                $bg->bg_amount = $request->bg_amount;
                $bg->issued_dt = $request->issued_dt;
                $bg->expiry_dt = $request->expiry_dt;
                $bg->claim_dt = $request->claim_dt;
                $bg->bg_status = $request->bg_status;
                $bg->bg_bank_conf = $request->bg_bank_conf;
                $bg->remark = $request->remark;
            $bg->save();

            foreach ($doctypes as $doc_id => $doctype) {

                if ($request->hasfile($doctype)) {

                    $newDoc = $request->file($doctype);

                    $upload_id = BgUpload::where('bg_id', $bg_id)->where('doc_id', $doc_id)->value('upload_id');
                    $doc = DocumentUploads::where('id',$upload_id)->first();
                        if (empty($doc)) {
                            $doc = new DocumentUploads;
                            $doc->app_id = $request->app_id;
                            $doc->doc_id = $doc_id;
                        }
                        $doc->mime = $newDoc->getMimeType();
                        $doc->file_size = $newDoc->getSize();
                        $doc->updated_at = Carbon::now();
                        $doc->user_id = Auth::user()->id;
                        $doc->file_name = $newDoc->getClientOriginalName();
                        $doc->uploaded_file = fopen($newDoc->getRealPath(), 'r');
                    $doc->save();

                    $bgUpload = BgUpload::where('bg_id', $bg_id)->where('upload_id', $upload_id)->first();
                        if (empty($bgUpload)) {
                            $bgUpload = new BgUpload;
                            $bgUpload->app_id = $request->app_id;
                            $bgUpload->bg_id = $bg->id;
                            $bgUpload->doc_id = $doc_id;
                            $bgUpload->upload_id = $doc->id;
                            $bgUpload->doc_name = $doctype;
                        }
                        $bgUpload->user_id = Auth::user()->id;
                        $bgUpload->mime_type = $newDoc->getMimeType();
                    $bgUpload->save();
                }
            }

            alert()->success('Data Update', 'Success')->persistent('Close');
        });

        return redirect()->route('admin.bgtracker.index');
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
    public function downloadFile($id)
    {
        $doc = DB::table('document_uploads as du')
            ->join('document_master as dm','dm.doc_id','=','du.doc_id')
            ->where('du.id',$id)
            ->select('du.mime','du.file_name','du.uploaded_file','dm.doc_type')
        ->first();

        ob_start();
        fpassthru($doc->uploaded_file);
        $docc= ob_get_contents();
        ob_end_clean();
        $ext = '';

        if ($doc->mime == "application/pdf") {
            $ext = 'pdf';
        } elseif ($doc->mime == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            $ext = 'docx';
        } elseif ($doc->mime == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $ext = 'xlsx';
        } elseif ($doc->mime == "image/png") {
            $ext = 'png';
        } elseif ($doc->mime == "image/jpeg") {
            $ext = 'jpg';
        }

        return response($docc)
        ->header('Cache-Control', 'no-cache private')
        ->header('Content-Description', 'File Transfer')
        ->header('Content-Type', $doc->mime)
        ->header('Content-length', strlen($docc))
        ->header('Content-Disposition', 'attachment; filename='.$doc->doc_type.'.'.$ext)
        ->header('Content-Transfer-Encoding', 'binary');
    }

    public function bgExport()
    {
        return Excel::download(new BgExport, 'bgTracker.xlsx');
    }
}
