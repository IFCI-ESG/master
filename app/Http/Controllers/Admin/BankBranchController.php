<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\BankDetails;
use App\User;
use DB;
use Auth;
use Carbon\Carbon;
use Mail;
use Hash;
use App\TargetSegment;
use App\ApplicantCinPan;
use App\Mail\NewRegistration;
use Log;

class BankBranchController extends Controller
{

    public function index()
    {
        $user=Auth::user();
        $branch_details = DB::table('admin_users')
                            ->where('created_by',$user->id)
                            ->orderby('id')->get();


        return view('admin.bank_branch.index',compact('branch_details'));
    }
    public function create()
    {
        return view('admin.bank_branch.create_bulk');
    }

    public function store(Request $request)
    {
       // dd(DB::select('select * from bank_branch_details' ));
        $userInput = $request->all();

        //dd($userInput);
        $rules = [
            'files' => 'required|file|mimes:csv,txt|max:20480'
        ];
       
        $validator = Validator::make($request->only('files'), $rules);
        if ($validator->fails()) {

             return response()->json([
                'success' => false,
                'message' => "Vlidation error",
            ], 500);
           // dd($validator);
//return redirect()->back()->withErrors($validator)->withInput();
        }

       // dd("ok");
        $file = $request->file('files');
        $name = time().'-'.$file->getClientOriginalName();
        try {

            $arraydata=$this->csvToArray($file);
            $keyarray=array();
           // dd( $arraydata);
            $i=1;
           foreach ($arraydata as $key => $value) {
            $i=$i+1;
            if ((!isset($value['BankName'])) ||  (!isset($value['Email'])) ||  (!isset($value['ContactPerson']))||  (!isset($value['Designation']))||  (!isset($value['Mobile']))||  (!isset($value['IfscCode']))||  (!isset($value['PinCode'])) ) {
                $validator->errors()->add('customError', 'The column names "BankName","Email","ContactPerson","Designation","Mobile","IfscCode"and "PinCode" should appear in the first row of the CSV.');
                return redirect()->back()->withErrors($validator);         
            }
            if (empty(trim($value['BankName']))) {
                $validator->errors()->add('customError', 'Column "BankName" cannot be null. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            }
            if (empty(trim($value['Email'])) || !filter_var($value['Email'], FILTER_VALIDATE_EMAIL)) {
                    $validator->errors()->add('customError',  "Invalid email or empty field. Please check  row no:- ".$i);
                                return redirect()->back()->withErrors($validator);
            } 
            if (empty(trim($value['ContactPerson']))) {
                $validator->errors()->add('customError', 'Column "ContactPerson" cannot be null. Please check  row no:- '.$i);
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
           if (empty($value['IfscCode'])) {
             $validator->errors()->add('customError', 'Column "IfscCode" cannot be null!. Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 

            if (empty($value['PinCode']) || !preg_match("/^\d{5}|\d{6}$/", $value['PinCode'])) {
                $validator->errors()->add('customError', 'Invalid PinCode or empty field! . Please check  row no:- '.$i);
                return redirect()->back()->withErrors($validator);
            } 
                
            DB::table('bank_details_temp')->insert([
                        'bankname' => trim($value['BankName']),
                        'email' =>trim($value['Email']),
                        'contactperson' =>trim( $value['ContactPerson']),
                        'designation' => trim($value['Designation']),
                        'mobile' =>trim($value['Mobile']),
                        'ifsccode' => trim($value['IfscCode']),
                        'pincode' => trim($value['PinCode']),
                        ]);

        }


 return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file' => $name,
            ]);
       // dd("ok");

//return redirect()->back()->with('success', 'Your Excel file was uploaded successfully!');
        } catch (Exception $e) {
                $validator->errors()->add('customError',"Something went wrong. Please try again after some time");
                return redirect()->back()->withErrors($validator);
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

public function FinalInsert()
{
    try {
    
        $bank_create_limit = config('constants.bank_create_limit');
        if (empty($bank_create_limit)) {
            throw new \Exception('Bank create limit is not set in the configuration');
        }

        // Fetch records from the temporary table
        $records = DB::table('bank_details_temp')
            ->orderBy('id', 'ASC')
            ->take($bank_create_limit)
            ->get();

        // Check if no records were found
        if ($records->isEmpty()) {
            Log::info('No records found in bank_details_temp table.');
            return 'No records to process.';
        }

        foreach ($records as $key => $value) {
            $value = (array) $value; // Convert object to array

            try {
                // Start the transaction
                DB::beginTransaction();

                // Insert into the final table
                $insertedId = DB::table('bank_details_final')->insertGetId([
                    'bankname'     => trim($value['bankname']),
                    'email'        => trim($value['email']),
                    'contactperson'=> trim($value['contactperson']),
                    'designation'  => trim($value['designation']),
                    'mobile'       => trim($value['mobile']),
                    'ifsccode'     => trim($value['ifsccode']),
                    'pincode'      => trim($value['pincode']), // Use pincode1 here
                ]);

                // If insert fails, move to junk table
                if (empty($insertedId)) {
                    Log::warning("Insert failed for record with ID: " . $value['id']);
                    DB::table('bank_details_junk')->insert([
                        'bankname'     => trim($value['bankname']),
                        'email'        => trim($value['email']),
                        'contactperson'=> trim($value['contactperson']),
                        'designation'  => trim($value['designation']),
                        'mobile'       => trim($value['mobile']),
                        'ifsccode'     => trim($value['ifsccode']),
                        'pincode'      => trim($value['pincode']), // Use pincode here
                    ]);
                }

                // Delete the record from the temp table after processing
                DB::table('bank_details_temp')->where('id', $value['id'])->delete();

                // Commit the transaction
                DB::commit();

            } catch (\Exception $e) {
                // Rollback in case of error and log the error
                DB::rollBack();
                Log::error("Error processing record ID: " . $value['id'] . " - " . $e->getMessage());
            }
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

    

}
