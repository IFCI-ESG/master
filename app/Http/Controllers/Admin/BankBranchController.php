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
use Exception;

class BankBranchController extends Controller
{
    // Index method to list all branches
    public function index()
    {
        $user = Auth::user();
        $branch_details = DB::table('admin_users')
                            ->where('created_by', $user->id)
                            ->orderby('id')->get();

        return view('admin.bank_branch.index', compact('branch_details'));
    }

    // Create method for bulk upload
    public function create()
    {
        return view('admin.bank_branch.create_bulk');
    }

    // Add method for adding a single branch
    public function add()
    {
        return view('admin.bank_branch.addbranch'); 
    }

    

    // Method to store CSV data
    public function store(Request $request)
    {

// Validate the request data
$request->validate([
    'branch_name' => 'required|string',
    'email' => 'required|email',
    'contact_person' => 'required|string',
    'designation' => 'nullable|string',
    'mobile' => 'required|digits:10',
    'ifsc_code' => 'required|string|max:11',
    'pincode' => 'required|digits:6',
]);

// Save data to the database
$branch = new BankBranch();
$branch->branch_name = $request->branch_name;
$branch->email = $request->email;
$branch->contact_person = $request->contact_person;
$branch->designation = $request->designation;
$branch->mobile = $request->mobile;
$branch->ifsc_code = $request->ifsc_code;
$branch->pincode = $request->pincode;
$branch->save();

return redirect()->route('admin.bank_branch_bulk.index')->with('success', 'Branch saved successfully');

    }

    // Additional helper function to validate each row of data
    private function validateRowData($value, $i, $validator)
    {
        if (empty(trim($value['BankName']))) {
            $validator->errors()->add('customError', 'Column "BankName" cannot be null. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }

        if (empty(trim($value['Email'])) || !filter_var($value['Email'], FILTER_VALIDATE_EMAIL)) {
            $validator->errors()->add('customError', 'Invalid or empty Email. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }

        if (empty(trim($value['ContactPerson']))) {
            $validator->errors()->add('customError', 'Column "ContactPerson" cannot be null. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }

        if (empty(trim($value['Designation']))) {
            $validator->errors()->add('customError', 'Column "Designation" cannot be null. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }

        // Mobile validation pattern for 10-digit mobile number starting with 6-9
        $pattern = "/^[6789]\d{9}$/";
        if (empty($value['Mobile']) || !preg_match($pattern, $value['Mobile'])) {
            $validator->errors()->add('customError', 'Invalid Mobile No or empty field. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }

        if (empty($value['IfscCode'])) {
            $validator->errors()->add('customError', 'Column "IfscCode" cannot be null. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }

        // PinCode validation
        if (empty($value['PinCode']) || !preg_match("/^\d{5}|\d{6}$/", $value['PinCode'])) {
            $validator->errors()->add('customError', 'Invalid PinCode or empty field. Please check row no: ' . $i);
            return redirect()->back()->withErrors($validator);
        }
    }

    // Method to convert CSV file to array
    public function csvToArray($filename = '', $delimiter = ',')
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

    // Method for transferring records from temp table to final table
    public function FinalInsert()
    {
        try {
            // Fetch records from the temporary table, limit based on the config setting
            $bank_create_limit = config('constants.bank_create_limit');
            if (empty($bank_create_limit)) {
                throw new \Exception('Bank create limit is not set in the configuration');
            }

            $records = DB::table('bank_details_temp')
                ->orderBy('id', 'ASC')
                ->take($bank_create_limit)
                ->get();

            // Check if no records were found
            if ($records->isEmpty()) {
                Log::info('No records found in bank_details_temp table.');
                return 'No records to process.';
            }

            // Process each record
            foreach ($records as $key => $value) {
                $value = (array)$value; // Convert object to array

                try {
                    // Start a database transaction
                    DB::beginTransaction();

                    // Insert record into the final table (bank_details_final)
                    $insertedId = DB::table('bank_details_final')->insertGetId([
                        'bankname' => trim($value['bankname']),
                        'email' => trim($value['email']),
                        'contactperson' => trim($value['contactperson']),
                        'designation' => trim($value['designation']),
                        'mobile' => trim($value['mobile']),
                        'ifsccode' => trim($value['ifsccode']),
                        'pincode' => trim($value['pincode']),
                    ]);

                    // If insert fails, move to junk table
                    if (empty($insertedId)) {
                        Log::warning("Insert failed for record with ID: " . $value['id']);
                        DB::table('bank_details_junk')->insert([
                            'bankname' => trim($value['bankname']),
                            'email' => trim($value['email']),
                            'contactperson' => trim($value['contactperson']),
                            'designation' => trim($value['designation']),
                            'mobile' => trim($value['mobile']),
                            'ifsccode' => trim($value['ifsccode']),
                            'pincode' => trim($value['pincode']),
                        ]);
                    }

                    // Delete the record from the temp table after processing
                    DB::table('bank_details_temp')->where('id', $value['id'])->delete();

                    // Commit the transaction
                    DB::commit();
                } catch (\Exception $e) {
                    // Rollback in case of error
                    DB::rollBack();
                    Log::error("Error processing record ID: " . $value['id'] . " - " . $e->getMessage());
                }
            }

            // Log success
            Log::info('Successfully processed and inserted records from bank_details_temp.');
            return 'Process completed successfully.';
            
        } catch (\Exception $e) {
            // Handle general errors
            Log::error('Error during FinalInsert process: ' . $e->getMessage());
            return 'Error during the insert process: ' . $e->getMessage();
        }
    }
}
