<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Models\BankDetails;
use App\Models\User;
use DB;
use Auth;
use Carbon\Carbon;
use Mail;
use Hash;
use App\Models\TargetSegment;
use App\ApplicantCinPan;
use App\Mail\NewRegistration;
use Log;
use App\Models\BankFinancialDetails;
class CompanyBulkUploadController extends Controller
{


    public function createBulkCompany(Request $request){


           return view('admin.user.bulk_company_user_create');
    }


public function deleteCorp($file)
    {
        $path = 'uploads/' . $file;
    Log::info("Attempting to delete file: $path");

    if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
        return response()->json(['message' => 'File deleted successfully']);
    }

    Log::error("File not found: $path");
    return response()->json(['error' => 'File not found'], 404);
    }


    public function storeCorp(Request $request)
    {

       // dd(DB::select('select * from bank_branch_details' ));
        $userInput = $request->all();
        
        $rules = [
            'file' => 'required|file|mimes:csv|max:20480'
        ];
       
        $validator = Validator::make($request->only('file'), $rules);


        if ($validator->fails()) {
   
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $file = $request->file('file');


        $name = time().'-'.$file->getClientOriginalName();
        try {

            $arraydata=$this->csvToArray($file);
            $keyarray=array();

            $i=1;
           foreach ($arraydata as $key => $value) {
            $i=$i+1;

                                      
            if ((!isset($value['Pan'])) ||  (!isset($value['ContactPerson'])) ||  (!isset($value['Designation']))||  (!isset($value['Email']))||  (!isset($value['Mobile']))||  (!isset($value['BankZone']))||  (!isset($value['TypeAssetClass'])) ||  (!isset($value['CompanyType']))||  (!isset($value['Sector']))||  (!isset($value['Financialyear'])) ||  (!isset($value['BankExposure']))) {
                $validator->errors()->add('customError', 'The column names "Pan","BankName","Email","ContactPerson","Designation","Mobile","BankZone" , "TypeAssetClass", " CompanyType", "Sector" ,"Financialyear" and "BankExposure" should appear in the first row of the CSV.');
                return redirect()->back()->withErrors($validator);         
            }
            if (empty(trim($value['ContactPerson']))) {
                $validator->errors()->add('customError', 'Column "ContactPerson" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }
            if (empty(trim($value['Email'])) || !filter_var($value['Email'], FILTER_VALIDATE_EMAIL)) {
                    $validator->errors()->add('customError',  "Invalid email or empty field. Please check  row no:- ".$i);
                                return redirect()->back()->withErrors($validator);
            } 

            if (empty(trim($value['Designation']))) {
                $validator->errors()->add('customError', 'Column "Designation" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
           }
                $pattern = "/^[6789]\d{9}$/";

            // Check if the mobile number is empty or invalid
                if (empty($value['Mobile']) || !preg_match($pattern, $value['Mobile'])) {
      
                 $validator->errors()->add('customError', 'Invalid Mobile No or empty field!.Please check  row no:- '.$i );
                            return redirect()->back()->withErrors($validator);
            } 
           if (empty($value['BankZone'])) {
             $validator->errors()->add('customError', 'Column "BankZone" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 

            if (empty($value['Pan']) || !preg_match("/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", $value['Pan'])) {
                $validator->errors()->add('customError', 'Invalid Pan or empty field! . Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['TypeAssetClass'])) {
             $validator->errors()->add('customError', 'Column "TypeAssetClass" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['CompanyType'])) {
             $validator->errors()->add('customError', 'Column "CompanyType" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['Sector'])) {
             $validator->errors()->add('customError', 'Column "Sector" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['Financialyear'])) {
             $validator->errors()->add('customError', 'Column "Financialyear" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['BankExposure'])) {
             $validator->errors()->add('customError', 'Column "BankExposure" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 



        }

             foreach ($arraydata as $key => $value) {


                DB::table('users_details_temp')->insert([
                 'name' => trim($value['ContactPerson']),
                        'contact_person' => trim($value['ContactPerson']),
                        'email' =>trim($value['Email']),
                        'pan' =>trim( $value['Pan']),
                        'designation' => trim($value['Designation']),
                        'mobile' =>trim($value['Mobile']),
                        'zone' => trim($value['BankZone']),
                        'class_type_id' => trim($value['TypeAssetClass']),
                        'comp_type_id' => trim($value['CompanyType']),
                        'sector_id' => trim($value['Sector']),
                        'fy_id' => trim($value['Financialyear']),
                        'bank_exposure' => trim($value['BankExposure']),
                        'created_by' => Auth::user()->id ,
                        'bank_id' => Auth::user()->id ,
                        'borrower_type' => 'C'
                        ]);


            }

            return redirect()->back()->with('success', 'Your Excel file was uploaded successfully!');
        } catch (Exception $e) {
                $validator->errors()->add('customError',"Something went wrong. Please try again after some time");
                return redirect()->back()->withErrors($validator);
        }

    }



    public function storeRetail(Request $request)
    {



       // dd(DB::select('select * from bank_branch_details' ));
        $userInput = $request->all();
        $rules = [
            'files' => 'required|file|mimes:csv,txt|max:20480'
        ];
       
        $validator = Validator::make($request->only('files'), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $file = $request->file('files');
        $name = time().'-'.$file->getClientOriginalName();
        try {

            $arraydata=$this->csvToArray($file);
            $keyarray=array();

            $i=1;
           foreach ($arraydata as $key => $value) {
            $i=$i+1;               
            if ((!isset($value['Pan'])) ||  (!isset($value['CustomerName'])) ||  (!isset($value['ValueAsset']))||  (!isset($value['Email']))||  (!isset($value['Mobile']))||  (!isset($value['BankZone']))||  (!isset($value['TypeAssetClass'])) ||  (!isset($value['LoanTenure']))||  (!isset($value['Financialyear'])) ||  (!isset($value['BankExposure'])) ||  (!isset($value['Pincode'])) ||  (!isset($value['State'])) ||  (!isset($value['City']))||  (!isset($value['Address']))) {


                $validator->errors()->add('customError', 'The column names "Pan","BankName","Email","CustomerName","ValueAsset","Mobile","BankZone" , "TypeAssetClass", "LoanTenure" ,"Financialyear" and "BankExposure","Address","State","Pincode", "City" should appear in the first row of the CSV.');
                return redirect()->back()->withErrors($validator);         
            }
            if (empty(trim($value['CustomerName']))) {
                $validator->errors()->add('customError', 'Column "CustomerName" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }
            if (empty(trim($value['Email'])) || !filter_var($value['Email'], FILTER_VALIDATE_EMAIL)) {
                    $validator->errors()->add('customError',  "Invalid email or empty field. Please check  row no:- ".$i);
                                return redirect()->back()->withErrors($validator);
            } 

            if (empty(trim($value['ValueAsset']))) {
                $validator->errors()->add('customError', 'Column "ValueAsset" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
           }
                $pattern = "/^[6789]\d{9}$/";

            // Check if the mobile number is empty or invalid
                if (empty($value['Mobile']) || !preg_match($pattern, $value['Mobile'])) {
      
                 $validator->errors()->add('customError', 'Invalid Mobile No or empty field!.Please check  row no:- '.$i );
                            return redirect()->back()->withErrors($validator);
            } 
           if (empty($value['BankZone'])) {
             $validator->errors()->add('customError', 'Column "BankZone" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 

            if (empty($value['Pan']) || !preg_match("/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", $value['Pan'])) {
                $validator->errors()->add('customError', 'Invalid Pan or empty field! . Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['TypeAssetClass'])) {
             $validator->errors()->add('customError', 'Column "TypeAssetClass" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 

               if (empty($value['LoanTenure'])) {
             $validator->errors()->add('customError', 'Column "LoanTenure" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['Financialyear'])) {
             $validator->errors()->add('customError', 'Column "Financialyear" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
               if (empty($value['BankExposure'])) {
             $validator->errors()->add('customError', 'Column "BankExposure" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
              if (empty(trim($value['Address']))) {
                $validator->errors()->add('customError', 'Column "Address" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }
              if (empty(trim($value['Pincode']))) {
                $validator->errors()->add('customError', 'Column "Pincode" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }
              if (empty(trim($value['State']))) {
                $validator->errors()->add('customError', 'Column "State" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }
              if (empty(trim($value['City']))) {
                $validator->errors()->add('customError', 'Column "City" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }


        }

             foreach ($arraydata as $key => $value) {


                DB::table('users_details_temp')->insert([
                                'name' => trim($value['CustomerName']),
                                'contact_person' => trim($value['CustomerName']),
                                'email' =>trim($value['Email']),
                                'pan' =>trim( $value['Pan']),
                                'mobile' =>trim($value['Mobile']),
                                'zone' => trim($value['BankZone']),
                                'class_type_id' => trim($value['TypeAssetClass']),
                                'fy_id' => trim($value['Financialyear']),
                                'bank_exposure' => trim($value['BankExposure']),
                                'value_asset' => trim($value['ValueAsset']),
                                'loan_tenure' => trim($value['LoanTenure']),
                                'reg_off_add' => trim($value['Address']),
                                'reg_off_pin' => trim($value['Pincode']),
                                'reg_off_state' => trim($value['State']),
                                'reg_off_city' => trim($value['City']),
                                'created_by' => Auth::user()->id ,
                                'bank_id' => Auth::user()->id ,
                                'borrower_type' => 'R'
                        ]);


            }

            return redirect()->back()->with('success', 'Your Excel file was uploaded successfully!');
        } catch (Exception $e) {
                $validator->errors()->add('customError',"Something went wrong. Please try again after some time");
                return redirect()->back()->withErrors($validator);
        }

    }

 public function FinalInsertCorp()
{
    try {

        $brrower_create_limit = config('constants.brrower_create_limit');


        if (empty($brrower_create_limit)) {
            throw new \Exception('Brrower create limit is not set in the configuration');
        }

        // Fetch records from the temporary table
       $records = DB::table('users_details_temp')
            ->where('borrower_type','C')
            ->orderBy('id', 'ASC')
            ->take($brrower_create_limit)
            ->get();
     
        // Check if no records were found
        if ($records->isEmpty()) {
            Log::info('No records found in users_details_temp table.');
            return 'No records to process.';
        }

        foreach ($records as $key => $value) {
            
            try {
          
                 $newuser=User::where('pan', $value->pan)->first();
                  $randomString = $this->generateRandomString(8);
                //DB::beginTransaction();
                if (empty($newuser)) {
                            $newuser = new User;
                            
                    $newuser->name = $value->name;
                    $newuser->email = $value->email;
                    // $newuser->password = Hash::make($randomString);
                    $newuser->password = '$2y$10$vTj1GhEjFcL0duMu1AqmGebo48zWZoxIuG8ThKXNfEDw7ltrUobTC';    // India@1234
              
                    $newuser->mobile = $value->mobile;
                    $newuser->designation = $value->designation;
                    $newuser->pan = $value->pan;
                    $newuser->contact_person = $value->name;
                    $newuser->comp_type_id = $value->comp_type_id ;
                    $newuser->created_by = $value->created_by; 
                    $newuser->sector_id = $value->sector_id ;
                    $newuser->status = 'D' ;
                    $newuser->cin_llpin = $request->cin;
                    $newuser->reg_off_add = $request->reg_address;
                    $newuser->reg_off_pin = $request->pincode;
                    $newuser->reg_off_state = $request->state;
                    $newuser->reg_off_city = $request->city;
                    $newuser->co_off_add = $request->co_off_add;
                    $newuser->co_off_pin = $request->co_off_pin;
                    $newuser->co_off_state = $request->co_off_state;
                    $newuser->co_off_city = $request->co_off_city;
                    $newuser->unique_login_id =  $value->pan;
                    $newuser->borrower_type = 'C';
         
                    // $categories = $request->input('categories', []);
                    // $newuser->purpose = implode(',', $categories);
                    $newuser->isapproved = 'Y';
                    $newuser->mobile_verified_at = Carbon::now();
                    $newuser->email_verified_at = Carbon::now();

                    // dd($newuser);
                    $newuser->save();
                        }
                 $fincial = new BankFinancialDetails;
                        $fincial->fy_id = $value->fy_id;
                        $fincial->class_type_id = $value->class_type_id ;
                        $fincial->com_id = $newuser->id;
                        $fincial->bank_id = $value->bank_id; 
                        $fincial->zone = $request->zone ;
                        
                        $fincial->borrowings = $value['borrowings'];
                        $fincial->bank_exposure = $value->bank_exposure;
                        $fincial->total_equity = $value['total_equity'];
                        $fincial->net_revenue = $value['net_revenue'];
                        $fincial->profit_after_tax = $value['profit_after_tax'];
                        $fincial->rating = $value['rating'];
                        $fincial->rating_date = $value['rating_date'];
                        $fincial->rating_agency = $value['rating_agency'];
                    $fincial->save();

                $data = array('role_id' => 5, 'model_type' => 'App\User', 'model_id' => $newuser->id);
                DB::table('model_has_roles')->insert($data);

                // $data = array('name'=>$user->name,'email'=>$user->email,'unique_id'=>$user->unique_login_id,'password'=>$randomString,
                //              'bank_name'=>Auth::user()->name);

                //             //  dd($data);

                // Mail::send('emails.email_credentials', $data, function($message) use($data) {
                //    $message->to($data ['email'],$data ['name'])->subject
                //        ('Account Created | ESG - Dashboard ');
                //         // $message->cc('pliwg@ifciltd.com');
                //         // $message->bcc('shweta.rai@ifciltd.com');
                //   });

                // $SMS = new SubmissionSms();
                // $module = "Company-Created";
                // $com_name = $user->name;
                // $user_id = $user->unique_login_id;
                // $password = $randomString;
                // $bank_name = Auth::user()->name;
                // $smsResponse = $SMS->sendSMS($user->mobile, $module, $com_name, $user_id, $password, $bank_name);



                // DB::commit();
            } catch (\Exception $e) {
                            DB::table('users_details_junk')->insert([
                                'name' => trim($value->name),
                                'contact_person' => trim($value->name),
                                'email' =>trim($value->email),
                                'pan' =>trim( $value->pan),
                                'mobile' =>trim($value->mobile),
                                'zone' => trim($value->zone),
                                'class_type_id' => trim($value->class_type_id),
                                'fy_id' => trim($value->fy_id),
                                'bank_exposure' => trim($value->bank_exposure),
                                'value_asset' => trim($value->value_asset),
                                'loan_tenure' => trim($value->loan_tenure),
                                'reg_off_add' => trim($value->reg_off_add),
                                'reg_off_pin' => trim($value->reg_off_pin),
                                'reg_off_state' => trim($value->reg_off_state),
                                'reg_off_city' => trim($value->reg_off_city),
                                'created_by' => $value->created_by,
                                'bank_id' => $value->bank_id ,
                                'borrower_type' => 'R'
                        ]);
                            
                          // Rollback in case of error and log the error
               // DB::rollBack();
                Log::error("Error processing record ID: " . $value->id . " - " . $e->getMessage());
            }
             DB::table('users_details_temp')->where('id', $value->id)->delete();
        }

        // Log success after processing all records
        Log::info('Successfully processed and inserted records from bank_details_temp.');

        return 'Process completed successfully.';

    } catch (\Exception $e) {
        // Log general errors (e.g., configuration issues)
        Log::error('Error during FinalInsert process: ' . $e->getMessage());
        return 'Error during the insert process: ' . $e->getMessage();
    }
}   

 public function FinalInsertRetail()
{
    try {
    
        $brrower_create_limit = config('constants.brrower_create_limit');
        if (empty($brrower_create_limit)) {
            throw new \Exception('Bank create limit is not set in the configuration');
        }

        // Fetch records from the temporary table
        $records = DB::table('users_details_temp')
                ->where('borrower_type','R')
            ->orderBy('id', 'ASC')
            ->take($brrower_create_limit)
            ->get();

        // Check if no records were found
        if ($records->isEmpty()) {
            Log::info('No records found in users_details_temp table.');
            return 'No records to process.';
        }

        foreach ($records as $key => $value) {
            
            try {
          
                 $newuser=User::where('pan', $value->pan)->first();
                  $randomString = $this->generateRandomString(8);
                //DB::beginTransaction();
                if (empty($newuser)) {
                            $newuser = new User;
                            
                            $newuser->name = $value->name;
                            $newuser->email = $value->email;
                            // $newuser->password = Hash::make($randomString);
                            $newuser->password = '$2y$10$vTj1GhEjFcL0duMu1AqmGebo48zWZoxIuG8ThKXNfEDw7ltrUobTC';    // India@1234
                            $newuser->mobile = $value->mobile;
                            $newuser->pan = trim($value->pan);
                            $newuser->created_by = $value->created_by ;
                            $newuser->status = 'S' ;
                            $newuser->reg_off_add = $value->reg_off_add;
                            $newuser->reg_off_pin = $value->reg_off_pin;
                            $newuser->reg_off_state = $value->reg_off_state;
                            $newuser->reg_off_city = $value->reg_off_city;
                            $newuser->borrower_type = 'R';
                            $newuser->unique_login_id =  trim($value->pan);
                            $newuser->isapproved = 'Y';
                            $newuser->isactive = 'Y';
                            $newuser->mobile_verified_at = Carbon::now();
                            $newuser->email_verified_at = Carbon::now();
                            $newuser->save();
                        }
    
                        $fincial = new BankFinancialDetails;
                            $fincial->fy_id = $value->fy_id;;
                            $fincial->com_id = $newuser->id;
                            $fincial->bank_id = $value->bank_id; 
                            $fincial->zone = $value->zone; 
                            $fincial->class_type_id = $value->class_type_id; 
                            $fincial->bank_exposure = $value->bank_exposure;
                            $fincial->value_asset = $value->value_asset;
                            $fincial->loan_tenure = $value->loan_tenure;
                            $fincial->save();

                $data = array('role_id' => 5, 'model_type' => 'App\User', 'model_id' => $newuser->id);
                DB::table('model_has_roles')->insert($data);

                // $data = array('name'=>$user->name,'email'=>$user->email,'unique_id'=>$user->unique_login_id,'password'=>$randomString,
                //              'bank_name'=>Auth::user()->name);

                //             //  dd($data);

                // Mail::send('emails.email_credentials', $data, function($message) use($data) {
                //    $message->to($data ['email'],$data ['name'])->subject
                //        ('Account Created | ESG - Dashboard ');
                //         // $message->cc('pliwg@ifciltd.com');
                //         // $message->bcc('shweta.rai@ifciltd.com');
                //   });

                // $SMS = new SubmissionSms();
                // $module = "Company-Created";
                // $com_name = $user->name;
                // $user_id = $user->unique_login_id;
                // $password = $randomString;
                // $bank_name = Auth::user()->name;
                // $smsResponse = $SMS->sendSMS($user->mobile, $module, $com_name, $user_id, $password, $bank_name);



                // DB::commit();
            } catch (\Exception $e) {
                            DB::table('users_details_junk')->insert([
                                'name' => trim($value->name),
                                'contact_person' => trim($value->name),
                                'email' =>trim($value->email),
                                'pan' =>trim( $value->pan),
                                'mobile' =>trim($value->mobile),
                                'zone' => trim($value->zone),
                                'class_type_id' => trim($value->class_type_id),
                                'fy_id' => trim($value->fy_id),
                                'bank_exposure' => trim($value->bank_exposure),
                                'value_asset' => trim($value->value_asset),
                                'loan_tenure' => trim($value->loan_tenure),
                                'reg_off_add' => trim($value->reg_off_add),
                                'reg_off_pin' => trim($value->reg_off_pin),
                                'reg_off_state' => trim($value->reg_off_state),
                                'reg_off_city' => trim($value->reg_off_city),
                                'created_by' => $value->created_by,
                                'bank_id' => $value->bank_id ,
                                'borrower_type' => 'R'
                        ]);
                            
                          // Rollback in case of error and log the error
               // DB::rollBack();
                Log::error("Error processing record ID: " . $value->id . " - " . $e->getMessage());
            }
             DB::table('users_details_temp')->where('id', $value->id)->delete();
        }

        // Log success after processing all records
        Log::info('Successfully processed and inserted records from bank_details_temp.');

        return 'Process completed successfully.';

    } catch (\Exception $e) {
        // Log general errors (e.g., configuration issues)
        Log::error('Error during FinalInsert process: ' . $e->getMessage());
        return 'Error during the insert process: ' . $e->getMessage();
    }
}   
    // CSV to ArrayData 
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
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
}
