<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankDetails;
use App\Models\User;
use App\Models\AdminUser;
use DB;
use Auth;
use Carbon\Carbon;
use Mail;
use Hash;
use Validator;

class BankController extends Controller
{

    public function index()
    {
        // $bank_part = DB::table('bank_particulars')->get();
        $user = Auth::user();
        $bank_details = DB::table('admin_users')
            ->where('created_by', $user->id)
            ->orderby('id')->get();


        // dd($bank_details);
        return view('admin.bank.index', compact('bank_details'));

    }

    public function create()
    {
        $services = DB::table('services_master')->get();

        return view('admin.bank.addbank', compact('services'));

    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'designation' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'pan' => 'required|string',
            'contact_person' => 'required|string|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'mobile' => 'required|digits:10|regex:/^[0-9]{10}$/',
            'services' => 'nullable|array',
            'services.*' => 'exists:services_master,id',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userInput = $request->all();

        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);

        $user = Auth::user();

        // try {

        $emailExists = AdminUser::where('email', $request->email)->orwhere('mobile', $request->mobile)->exists();

        if ($emailExists) {
            // dd($emailExists,$mobileExists);
            // alert()->error('Email, Mobile should be unique! These are already Registered', 'Attention!')->persistent('Close');
            // return redirect()->back();

            return redirect()->back()->withErrors(['message' => 'Email, Mobile should be unique! These are already Registered'])->withInput();
        }
        $newuser = new AdminUser;
        // $randomString = $this->generateRandomString(5);
        $newuser->name = $request->bank_name;
        $newuser->pan = $request->pan;
        $newuser->email = $request->email;
        // $newuser->password = Hash::make($randomString);
        //$newuser->password = '$2y$10$vTj1GhEjFcL0duMu1AqmGebo48zWZoxIuG8ThKXNfEDw7ltrUobTC';    // India@1234
        $newuser->mobile = $request->mobile;
        $newuser->altr_mobile = $request->altr_mobile;
        $newuser->designation = $request->designation;
        $newuser->contact_person = $request->contact_person;
        $newuser->services = json_encode($request->services);
        $newuser->status = 'D';
        $newuser->created_by = $user->id;
        DB::transaction(function () use ($newuser) {
            $newuser->save();
        });
        // alert()->success('Record Inserted', 'Success!')->persistent('Close');

        session()->flash('success', 'Data saved successfully!');
        return redirect()->route('admin.new_admin.edit', ['id' => encrypt($newuser->id)]);
        // } catch (\Exception $e) {
        //     alert()->warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     // errorMail($e, $request->id, Auth::user()->id);
        //     return redirect()->back();
        // }
    }



    private function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $services = DB::table('services_master')->get();
        $bank_details = AdminUser::find($id);
        $storedServices = json_decode($bank_details->services, true);
        // dd($bank_details,$services);
        return view('admin.bank.editbank', compact('bank_details', 'storedServices', 'services'));

    }

    public function update(Request $request)
    {
        // dd($request);
        // try{

        DB::transaction(function () use ($request) {
            $user = AdminUser::find($request->user_id);
            $user->name = $request->bank_name;
            $user->pan = $request->pan;
            $user->email = $request->email;
            $user->contact_person = $request->contact_person;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->altr_mobile = $request->altr_mobile;
            $user->services = json_encode($request->input('services', []));
            // $purpose = $request->input('purpose', []);
            // $user->purpose = implode(',', $purpose);
            $user->save();

        });

        alert()->success('Data Updated Successfully', 'Success!')->persistent('Close');
        return redirect()->back()->with('success', 'Data successfully Updated');
        // }catch (\Exception $e)
        // {
        //     alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     return redirect()->back();
        // }

        return view('admin.user.edituser', compact('user'));

    }

    public function submit(Request $request)
    {
        // dd($request);

        // try{

        DB::transaction(function () use ($request) {
            $randomString = $this->generateRandomString(5);

            $user = AdminUser::find($request->user_id);
            $user->isactive = 'Y';
            $user->status = 'S';
            // $user->password=Hash::make($randomString);
            $user->password = '$2y$10$vTj1GhEjFcL0duMu1AqmGebo48zWZoxIuG8ThKXNfEDw7ltrUobTC';    // India@1234

            $user->save();

            $data_role1 = array('role_id' => 2, 'model_type' => 'App\User', 'model_id' => $user->id);
            $data_role2 = array('role_id' => 3, 'model_type' => 'App\User', 'model_id' => $user->id);
            DB::table('model_has_roles')->insert([$data_role1, $data_role2]);


            // $esd_det = array('bank_user_id' =>  $user->id, 'esd' => 'ESG/'.$user->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now());
            // DB::table('bank_esd_details')->insert($esd_det);

            // dd($user);
            // $user->password=Hash::make($randomString);
            // dd($randomString);

            // $data = array('name'=>$user->name, 'unique_id'=>$user->email, 'password'=>$randomString, 'bank_name'=>'IFCI LTD.');

            //             //  dd($data);

            // Mail::send('emails.email_credentials', $data, function($message) use($data) {
            //    $message->to($data ['unique_id'],$data ['name'])->subject
            //        ('Account Created | ESG - Dashboard ');
            //         // $message->cc('pliwg@ifciltd.com');
            //         // $message->bcc('shivam.shukla@ifciltd.com');
            //   });

        });
        alert()->success('New Bank Created', 'Success!')->persistent('Close');
        return redirect()->route('admin.new_admin.index');
        return redirect()->back()->with('success', 'Data successfully Submitted');
        // }catch (\Exception $e)
        // {
        //     alert()->error('Something Went Wrong!', 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

    }
   
    public function com_list($bank_id)
    {
        $bank_id = decrypt($bank_id);
        // dd($bank_id);
        $user = Auth::user();
        $comp = DB::table('users')
            ->where('created_by', $bank_id)
            ->where('status', 'S')
            ->orderby('id')->get();
        // dd($comp);
        return view('admin.bank.company_list', compact('comp'));

    }



}
