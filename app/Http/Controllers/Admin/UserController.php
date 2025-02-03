<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Models\User;
use App\UserController\SubmissionSms;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

use App\Models\ModelHasRoles;
use App\Models\QuestionnaireMast;
use App\Models\BankFinancialDetails;
use App\Models\BusinessActivityValue;
use App\Models\QuestionValue;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Validator;
use CURLFile;
use Mail;


class UserController extends Controller
{
    
    
    public function index()
    {
        return view('admin.home');
    }


    public function dash()
    {

        $client = new Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/common/oauth2/token', [
            'form_params' => [
                'grant_type' => 'password',
                'username' => 'pbview@ifciltd.com',
                'password' => 'Dashboard@1948',
                'client_id' => '3646f7fa-262f-48ae-8fef-6c6b9213ee76',
                'client_secret' => 'gLg8Q~bqahzcstbKX~YqRnOisIOyjRyG~0eA5cEx',
                'resource' => 'https://analysis.windows.net/powerbi/api'
            ]
        ]);

        $access_token = json_decode($response->getBody()->getContents())->access_token;

       // 147f03af-5aa8-48c6-bc2d-931933dd84ba https://app.powerbi.com/groups/c21ff94e-1642-40ad-90a0-bdff6451faf6/reports/3c1ab36d-424e-4e15-9d83-474de6fa7421/aa5fb15a0c4758bfedbd?experience=power-bi


       $response = $client->request('GET', 'https://api.powerbi.com/v1.0/myorg/groups/c21ff94e-1642-40ad-90a0-bdff6451faf6/reports/147f03af-5aa8-48c6-bc2d-931933dd84ba', [

            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ],
            'json' => [
                'accessLevel' => 'View',
                'allowSaveAs' => 'false'
            ]
        ]);

        $embed_url = json_decode($response->getBody()->getContents())->embedUrl;
            // dd($embed_url);
        $response = $client->request('POST', 'https://api.powerbi.com/v1.0/myorg/groups/c21ff94e-1642-40ad-90a0-bdff6451faf6/reports/147f03af-5aa8-48c6-bc2d-931933dd84ba/GenerateToken', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ],
            'json' => [
                'accessLevel' => 'View',
                'allowSaveAs' => 'false'
            ]
        ]);

        $user=Auth::user()->id;

        $embed_token = json_decode($response->getBody()->getContents())->token;
        // return view('user.report', compact('embed_url', 'embed_token','repemp'));
        return view('admin.dashboard', compact('embed_url', 'embed_token','user'));
    }

    public function dash_v2()
    {
        return view('admin.dash_v2');
    }

    public function user_index()
    {

        $user = Auth::user();
        $bank_esd = DB::table('bank_esd_details')->where('bank_user_id', $user->id)->first();

        // $user_detail = DB::table('users')
        //                 ->join('bank_esd_details as bed','bed.bank_user_id','users.created_by')
        //                 ->join('sector_master as sm','sm.id','users.sector_id')
        //                 ->join('comp_type_master as ctm','ctm.id','users.comp_type_id')
        //                 ->where(DB::RAW("is_normal_user(users.id)"), 1)
        //                 ->where('users.created_by',Auth::user()->id)
        //                 ->orderby('users.id', 'Desc')
        //                 ->get(['users.*','sm.name as sector','ctm.name as comp_type']);

        $corp_detail = DB::table('bank_financial_details as bfd')
                            ->join('users as u','u.id','bfd.com_id')
                            ->join('sector_master as sm','sm.id','u.sector_id')
                            ->join('comp_type_master as ctm','ctm.id','u.comp_type_id')
                            ->where('bfd.bank_id',$user->id)
                            ->where('u.borrower_type','C')
                            ->distinct('bfd.com_id')
                            // ->orderby('bfd.id','Desc')
                            ->get(['u.*','sm.name as sector','ctm.name as comp_type']);

        $retail_detail = DB::table('bank_financial_details as bfd')
                            ->join('users as u','u.id','bfd.com_id')
                            ->where('bfd.bank_id',$user->id)
                            ->where('u.borrower_type','R')
                            ->distinct('bfd.com_id')
                            // ->orderby('u.id','Desc')
                            ->get(['u.*']);
        // $bank_check =

       // dd($corp_detail,$retail_detail);

        return view('admin.user.index', compact('corp_detail','retail_detail'));

    }

    public function adduser()
    {

        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','Desc')->get();
        // dd($zone);
        // return view('admin.user.createuser', compact('sector','zone','fys'));
        return view('admin.user.adduser', compact('sector','zone'));
    }

    public function retail_adduser()
    {
        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','Desc')->first();
        // $fys = DB::table('fy_masters')->orderby('id','Desc')->get();
        // dd($fys);

        $type = DB::table('comp_type_master')->where('status',1)->orderby('id')->get();

        $class_type = DB::table('class_type_master')->where('loan_type','R')->where('status',1)->orderby('id')->get();

        // dd($zone);
        return view('admin.user.retail_adduser', compact('sector','zone'));
        // return view('admin.user.retail_createuser', compact('sector','zone','type','class_type','fys'));
    }

    protected $baseUrl = 'http://35.154.82.89:3000/';

    // Generate Token
    public function generateToken()
    {
        $response = Http::post("{$this->baseUrl}/api/token/", [
            'username' => 'ifci_user',
            'password' => 'Ifci$@987',
        ]);

        // Check if the response is successful
        if ($response->successful()) {
            return $response->json()['access'];  // Assuming token is returned in 'access'
        }

        throw new \Exception('Error generating token: ' . $response->body());
    }

    public function resetToken($refreshToken)
    {
        $response = Http::post("{$this->baseUrl}/api/token/refresh/", [
            'refresh' => $refreshToken,
        ]);

        if ($response->successful()) {
            return $response->json()['access'];  // Assuming new token is returned in 'access'
        }

        throw new \Exception('Error refreshing token: ' . $response->body());
    }

    public function getCompanyByCIN($token, $cin)
    {
        $response = Http::withToken($token)->post("{$this->baseUrl}/company-name/", [
            'cin' => $cin,
        ]);

        if ($response->successful()) {
            return $response->json();  // Assuming response is in JSON format
        }

        throw new \Exception('Error fetching company data: ' . $response->body());
    }

    public function locuz1()
    {
        try {
            // Step 1: Generate Token
            $token = $this->generateToken();

            // Step 2: Fetch company data by CIN
            $cin = 'U61100GJ2023PLC138017';  // Example CIN
            $companyData = $this->getCompanyByCIN($token, $cin);
                dd($companyData);
            return $companyData;  // Display or use the fetched data
        } catch (\Exception $e) {
            // Handle errors here
            return $e->getMessage();
        }
    }

    public function locuz()
    {
        $baseUrl = 'http://35.154.82.89:3000';
        $username = 'ifci_user';
        $password = 'Ifci$@987';

        // Step 1: Generate Token
        $response = Http::post("{$baseUrl}/api/token/", [
            'username' => $username,
            'password' => $password,
        ]);

        // Decode the token response
        $responseData = $response->json();

        if (isset($responseData['access'])) {
            $accessToken = $responseData['access'];  // Get the access token
        } else {
            throw new \Exception('Error generating token: ' . json_encode($responseData));
        }

        // Step 2: Call CIN API using the token
        $cin = ["U50300MH1992PTC065232"];  // Example CIN value

        $requestData = [
            'cin' => $cin,  // CIN array
            'action_name' => 'Contact_Details'  // Example action_name
        ];

        // Make the API call
        $response = Http::withToken($accessToken)->post("{$baseUrl}/company-name/", $requestData);

        // Decode the API response
        $jdecode = $response->json();
            dd($jdecode);
        // Check if the response was successful
        if ($response->successful()) {
            return $jdecode;  // Return the data fetched by the API
        }

        throw new \Exception('Error fetching data: ' . $response->body());
    }


    public function signzyelectricityapi()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-preproduction.signzy.app/api/v3/electricitybills/fetch');

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: oRV2nEAvwsL7rnHeCCqfUjawHudhgscM',
            'x-client-unique-id: tushar.agnihotri@ifciltd.com',
            'Content-Type: application/json'
        ]);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'consumerNo' => '173241540245',
            'electricityProvider' => 'MAHAVITRAN'
        ]));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        // dd($response);

        curl_close($ch);

        // Decode the response
        $jdecode = json_decode($response);
        dd($jdecode);

        // URL
        // $url = 'https://api-preproduction.signzy.app/api/v3/electricitybills/fetch';

        // // API Key and other headers
        // $api_key = 'oRV2nEAvwsL7rnHeCCqfUjawHudhgscM';
        // $client_unique_id = 'tushar.agnihotri@ifciltd.com';

        // $headers = [
        //     'Authorization: ' . $api_key,
        //     'x-client-unique-id: ' . $client_unique_id,
        //     'Content-Type: application/json'
        // ];

        // // Data payload for the request
        // $data = [
        //     'consumerNo' => '173241540245',
        //     'electricityProvider' => 'MAHAVITRAN'
        // ];

        // // Convert the data to JSON format
        // $jsonData = json_encode($data);

        // // Initialize cURL session
        // $ch = curl_init();

        // // Set cURL options
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Attach the JSON data
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // // Execute cURL session and get the response
        // $response = curl_exec($ch);

        // // Check for cURL errors
        // if (curl_errno($ch)) {
        //     $error_msg = curl_error($ch);
        //     curl_close($ch);
        //     alert()->error('CURL Error: ' . $error_msg, 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

        // // Close the cURL session
        // curl_close($ch);

        // // Decode the response
        // $jdecode = json_decode($response);
        // dd($jdecode);

        // // Check for JSON decoding errors
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     alert()->error('Invalid response format!', 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

        // // Handle response based on content
        // if (isset($jdecode->message) && $jdecode->message === 'Company does not exist') {
        //     alert()->error('Company does not exist!', 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

        // if (!isset($jdecode->data)) {
        //     alert()->error('No Data Found!', 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

        // Continue processing with the $jdecode object...



        // return view('admin.user.createuser', compact('sector','zone','jdecode','fys'));



    }

    public function signzyGstapi()
    {
       // Initialize cURL session
        $ch = curl_init();

        // Set the URL for the API request
        curl_setopt($ch, CURLOPT_URL, 'https://api-preproduction.signzy.app/api/v3/gstn/gstndetailed');

        // Set headers including Authorization, unique client ID, and content type
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: oRV2nEAvwsL7rnHeCCqfUjawHudhgscM',  // Your API key
            'x-client-unique-id: tushar.agnihotri@ifciltd.com',  // Unique client ID
            'Content-Type: application/json'
        ]);

        // Set the request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set the POST fields with JSON encoded data
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "gstin" => "20AGRPG2101P1ZL",
            "returnFilingFrequency" => true
        ]));

        // Set the option to return the response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        $jdecode = json_decode($response);

        // Handle the response (for debugging purposes)
        dd($jdecode);

    }

    public function signzyGstfetchreport()
    {

        //         curl --location 'https://api-preproduction.signzy.app/api/v3/gstr-analytics/fetch-data' \
        // --header 'Authorization: oRV2nEAvwsL7rnHeCCqfUjawHudhgscM' \
        // --header 'x-client-unique-id: tushar.agnihotri@ifciltd.com' \
        // --header 'Content-Type: application/json' \
        // --data-raw '{
        //     "requestId": "148ae6c2-56d6-4b1c-b3f3-c858f0c8a86d"
        // }'
       // Initialize cURL session
        $ch = curl_init();

        // Set the URL for the API request
        // curl_setopt($ch, CURLOPT_URL, 'https://api.signzy.app/api/v3/gstr-analytics/fetch-report');
        curl_setopt($ch, CURLOPT_URL, 'https://api-preproduction.signzy.app/api/v3/gstr-analytics/fetch-data');

        // Set headers including Authorization, unique client ID, and content type
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: oRV2nEAvwsL7rnHeCCqfUjawHudhgscM',  // Your API key
            'x-client-unique-id: tushar.agnihotri@ifciltd.com',  // Unique client ID
            'Content-Type: application/json'
        ]);

        // Set the request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set the POST fields with JSON encoded data
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "requestId" => "148ae6c2-56d6-4b1c-b3f3-c858f0c8a86d",
        ]));

        // Set the option to return the response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        $jdecode = json_decode($response);

        // Handle the response (for debugging purposes)
        dd($jdecode);

        // Check if result exists in response
        if (isset($jdecode->result)) {
            // Extract URLs from the response
            $jsonDataUrl = $jdecode->result->jsonDataUrl;
            $excelUrl = $jdecode->result->excelUrl;

            // Function to download a file and save it locally
            function downloadFile($url, $savePath) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                curl_close($ch);

                file_put_contents($savePath, $data);
            }

            // Define the local file paths to save the downloaded files
            $jsonFilePath = 'C:\Users\Tushar.agnihotri\Downloads\file1.json';  // Replace with your desired path
            $excelFilePath = 'C:\Users\Tushar.agnihotri\Downloads\file2.xlsx';  // Replace with your desired path

            // Download JSON and Excel files
            downloadFile($jsonDataUrl, $jsonFilePath);
            downloadFile($excelUrl, $excelFilePath);

            echo "Files downloaded successfully!";
        } else {
            echo "Failed to fetch result from API.";
        }
        dd($jdecode);


    }

    public function signzyGstfetchdata()
    {
       // Initialize cURL session
            // dd('f');
        $ch = curl_init();

        // Set the URL for the API request
        curl_setopt($ch, CURLOPT_URL, 'https://api-preproduction.signzy.app/api/v3/gstr-analytics/fetch-data');

        // Set headers including Authorization, unique client ID, and content type
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: oRV2nEAvwsL7rnHeCCqfUjawHudhgscM',  // Your API key
            'x-client-unique-id: tushar.agnihotri@ifciltd.com',  // Unique client ID
            'Content-Type: application/json'
        ]);

        // Set the request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set the POST fields with JSON encoded data
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "requestId" => "148ae6c2-56d6-4b1c-b3f3-c858f0c8a86d",
        ]));

        // Set the option to return the response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        $jdecode = json_decode($response);

        // Handle the response (for debugging purposes)
        dd($jdecode);

    }



    public function apidata(Request $request)
    {
         
        $valid = Validator::make($request->all(),[
            'pan' => 'required|regex:/^([A-Za-z]{5})([0-9]{4})([A-Za-z]{1})$/'
        ]);

        if($valid->fails()) {
            return back()->withErrors($valid)->withInput();
        }

        $panExists = User::where('pan', $request->pan)->exists();

        if($panExists)
        {
            $user=User::where('pan', $request->pan)->first();

            alert()->success('This Company is already Registered by another Bank', 'Data Fetched!')->persistent('Close');
            return redirect()->route('admin.user.existuser',['id' => encrypt($user->id)]);
        }



        // Sandbox details
        // $urlcin = 'https://api.probe42.in/probe_pro_sandbox/companies/'.$cin.'/base-details'
        $url = 'https://api.probe42.in/probe_pro_sandbox/companies/'.$request->pan.'/comprehensive-details?identifier_type=PAN';
        $api_key = '07wvsOWBoq9iwpjhMm2C22eKOymlpqht9WmtYEFb';

        // Production Details
        // API URL
        // $url = 'https://api.probe42.in/probe_pro/companies/'.$request->pan.'/comprehensive-details?identifier_type=PAN';

        // // API Key
        // $api_key = '6NM20CtNSx6J22J4NgG6fH2bZN51hnt8EYmtRpRc';

        // Headers
        $headers = [
        'Accept: application/json',
        'x-api-key: ' . $api_key,
        'x-api-version: 1.3'
        ];

        // Initialize cURL session
        $ch = curl_init();
        // dd($ch);

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response = curl_exec($ch);
        // dd(json_decode($response));
        curl_close($ch);
        // dd($response);
        $jdecode =  json_decode($response);
        //  $vardumb =  var_dump(json_decode($response));
        // dd($jdecode);
        // dd($jdecode,$jdecode->data->financials,$jdecode->data->nbfc_financials);
        // dd($jdecode->data->financials,$jdecode->data->financials[0]->bs->liabilities->long_term_borrowings);
        // dd($jdecode->data->financials[0]->bs->liabilities->long_term_borrowings);
        // dd($jdecode,$jdecode->data,$jdecode->data->company,$jdecode->data->authorized_signatories);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log the error if needed
            // \Log::error('JSON Decode Error: ' . json_last_error_msg());

            // Handle the error
           // alert()->error('Invalid response format!', 'Attention!')->persistent('Close');
            $error="Invalid response format!";
            return redirect()->back()->withErrors($error);
        }

        // Check if the message indicates that the company does not exist
        if (isset($jdecode->message) && $jdecode->message === 'Company does not exist') {
            // Log the error if needed
            // \Log::error('Company does not exist!');

            // Handle the error
            alert()->error('Company does not exist!', 'Attention!')->persistent('Close');
            return redirect()->back();
        }

        if(!isset($jdecode->data)){
            alert()->error('No Data Found!', 'Attention!')->persistent('Close');
            return redirect()->back();
        }


        // Convert the response to JSON
        // $jsonContent = json_encode($jdecode, JSON_PRETTY_PRINT);

        // // Create a filename
        // $filename = 'company_data_' . $request->pan . '.json';

        // // Set headers for file download
        // $headers = [
        //     'Content-Type' => 'application/json',
        //     'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        // ];

        // return Response::make($jsonContent, 200, $headers);

        // dd($jdecode->data->financials); //total exposure
        // dd($jdecode,$jdecode->data,$jdecode->data->company,$jdecode->data->authorized_signatories);

        // dd("1",$response,"2",$jdecode,"3",$vardumb);
        // Check for errors
        // if(curl_errno($ch)) {
        // echo 'Error: ' . curl_error($ch);
        // }

        // Close cURL session

        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','desc')->get();

        $type = DB::table('comp_type_master')->where('status',1)->orderby('id')->get();

        $class_type = DB::table('class_type_master')->where('loan_type','C')->where('status',1)->orderby('id')->get();
            //dd($class_type);
        // dd($jdecode->data,$fys);


        return view('admin.user.createuser', compact('sector','zone','jdecode','fys','type','class_type'));



    }

    public function retail_apidata(Request $request)
    {
        // dd($request);
        $valid = Validator::make($request->all(),[
            'pan' => 'required|regex:/^([A-Za-z]{5})([0-9]{4})([A-Za-z]{1})$/'
        ]);

        if($valid->fails()) {
            return back()->withErrors($valid)->withInput();
        }

        $panExists = User::where('pan', $request->pan)->exists();

        if($panExists)
        {
            $user=User::where('pan', $request->pan)->first();

            alert()->success('This Customer is already Registered by another Bank', 'Data Fetched!')->persistent('Close');
            return redirect()->route('admin.retail.existuser',['id' => encrypt($user->id)]);
        }

        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','Desc')->first();
        // $fys = DB::table('fy_masters')->orderby('id','Desc')->get();
        // dd($fys);

        $type = DB::table('comp_type_master')->where('status',1)->orderby('id')->get();

        $class_type = DB::table('class_type_master')->where('loan_type','R')->where('status',1)->orderby('id')->get();

        $pan=$request->pan;

        // dd($jdecode->data,$fys)

        return view('admin.user.retail_createuser', compact('sector','zone','fys','type','class_type','pan'));

    }


    public function env_mis()
    {
        $client = new Client();

        $response = $client->request('POST', 'https://login.microsoftonline.com/common/oauth2/token', [
            'form_params' => [
                'grant_type' => 'password',
                'username' => 'pbview@ifciltd.com',
                'password' => 'Dashboard@1948',
                'client_id' => '3646f7fa-262f-48ae-8fef-6c6b9213ee76',
                'client_secret' => 'gLg8Q~bqahzcstbKX~YqRnOisIOyjRyG~0eA5cEx',
                'resource' => 'https://analysis.windows.net/powerbi/api'
            ]
        ]);


        $access_token = json_decode($response->getBody()->getContents())->access_token;
        // dd($access_token);

       // https://app.powerbi.com/groups/c21ff94e-1642-40ad-90a0-bdff6451faf6/reports/7cb55549-5e87-47d5-8fad-502db729ca77/cbad1e4fbe9798916933?experience=power-bi


       $response = $client->request('GET', 'https://api.powerbi.com/v1.0/myorg/groups/c21ff94e-1642-40ad-90a0-bdff6451faf6/reports/7cb55549-5e87-47d5-8fad-502db729ca77', [

            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ],
            'json' => [
                'accessLevel' => 'View',
                'allowSaveAs' => 'false'
            ]
        ]);

        $embed_url = json_decode($response->getBody()->getContents())->embedUrl;
            // dd($embed_url);
        $response = $client->request('POST', 'https://api.powerbi.com/v1.0/myorg/groups/c21ff94e-1642-40ad-90a0-bdff6451faf6/reports/7cb55549-5e87-47d5-8fad-502db729ca77/GenerateToken', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ],
            'json' => [
                'accessLevel' => 'View',
                'allowSaveAs' => 'false'
            ]
        ]);

        $user=Auth::user()->id;

        $embed_token = json_decode($response->getBody()->getContents())->token;
        // return view('user.report', compact('embed_url', 'embed_token','repemp'));
        // return view('admin.dashboard', compact('embed_url', 'embed_token','user'));
        // dd('d');
        return view('admin.user.env_mis',compact('embed_url', 'embed_token','user'));
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

    public function retail_store(Request $request)
    {
        // dd($request,$request->fy);

        // try {

            $user = Auth::user();

            $bank_esd = DB::table('bank_esd_details')->where('bank_user_id', $user->id)->first();

            $newuser = new User;

            DB::transaction(function () use ($newuser,$request,$bank_esd)
            {

                    // $randomString = $this->generateRandomString(5);
                    $newuser->name = $request->cust_name;
                    $newuser->email = $request->email;
                    // $newuser->password = Hash::make($randomString);
                    $newuser->password = '$2y$10$vTj1GhEjFcL0duMu1AqmGebo48zWZoxIuG8ThKXNfEDw7ltrUobTC';    // India@1234
                    $newuser->mobile = $request->mobile;
                    $newuser->pan = $request->pan;
                    $newuser->created_by = Auth::user()->id ;
                    $newuser->status = 'D' ;
                    $newuser->reg_off_add = $request->address;
                    $newuser->reg_off_pin = $request->pincode;
                    $newuser->reg_off_state = $request->state;
                    $newuser->reg_off_city = $request->city;
                    $newuser->borrower_type = 'R';
                    // $newuser->unique_login_id = $bank_esd->esd . '/' . $request->pan;
                    $newuser->isapproved = 'Y';
                    $newuser->mobile_verified_at = Carbon::now();
                    $newuser->email_verified_at = Carbon::now();

                    // dd($newuser);
                    $newuser->save();

                    foreach ($request->fy as $value) {
                        $fincial = new BankFinancialDetails;
                            $fincial->fy_id = $value['fy_id'];
                            $fincial->com_id = $newuser->id;
                            $fincial->bank_id = Auth::user()->id ;
                            $fincial->zone = $request->zone ;
                            $fincial->class_type_id = $value['asset_class'] ;
                            $fincial->bank_exposure = $value['bank_exposure'];
                            $fincial->value_asset = $value['value_asset'];
                            $fincial->loan_tenure = $value['loan_tenure'];
                        $fincial->save();
                    }

            });
            alert()->success('Record Inserted', 'Success!')->persistent('Close');
            return redirect()->route('admin.retail.edituser',['id' => encrypt($newuser->id)]);
        // } catch (\Exception $e) {
        //     alert()->warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     // errorMail($e, $request->id, Auth::user()->id);
        //     return redirect()->back();
        // }
    }

    public function store(Request $request)
    {
        // dd($request,$request->fy);

        $valid = Validator::make($request->all(),[
            'email' => array('unique:users,email'),
            'mobile' => array('unique:users,mobile'),
            'pan' => array('unique:users,pan'),
            'cin' => array('nullable', 'unique:users,cin_llpin'),
            'categories.*' => 'in:E,S,G'
            // 'bank_exposure' => 'required|min:0'
        ]);

        if($valid->fails()) {
            return back()->withErrors($valid)->withInput();
        }
        $user = Auth::user();
        // try {

            // $bank_esd = DB::table('bank_esd_details')->where('bank_user_id', $user->id)->first();

            // dd($bank_esd);
            // Validate unique email and mobile
            $emailExists = User::where('email', $request->email)->exists();
            $mobileExists = User::where('mobile', $request->mobile)->exists();
            $panExists = User::where('pan', $request->pan)->exists();
            // $cinExists = User::where('cin_llpin', $request->cin)->exists();

            // if ($emailExists || $mobileExists || $panExists) {
            //     // dd($emailExists,$mobileExists);
            //     alert()->error('Email, Mobile, PAN should be unique!', 'Attention!')->persistent('Close');
            //     return redirect()->back();
            // }


                // dd($randomString);

                $newuser = new User;

                // dd($newuser);

            DB::transaction(function () use ($newuser,$request,$panExists)
            {

                    // $randomString = $this->generateRandomString(5);
                    $newuser->name = $request->comp_name;
                    $newuser->email = $request->email;
                    // $newuser->password = Hash::make($randomString);
                    $newuser->password = '$2y$10$vTj1GhEjFcL0duMu1AqmGebo48zWZoxIuG8ThKXNfEDw7ltrUobTC';    // India@1234
                    $newuser->mobile = $request->mobile;
                    $newuser->designation = $request->designation;
                    $newuser->pan = $request->pan;
                    $newuser->contact_person = $request->auth_name;
                    $newuser->comp_type_id = $request->comp_type ;
                    $newuser->created_by = Auth::user()->id ;
                    $newuser->sector_id = $request->sector ;
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
                    $newuser->borrower_type = 'C';
                    // $newuser->unique_login_id = $bank_esd->esd . '/' . $request->pan;
                    // $newuser->unique_login_id = $bank_esd->esd . '/' . $request->cin;
                    // $categories = $request->input('categories', []);
                    // $newuser->purpose = implode(',', $categories);
                    $newuser->isapproved = 'Y';
                    $newuser->mobile_verified_at = Carbon::now();
                    $newuser->email_verified_at = Carbon::now();

                    // dd($newuser);
                    $newuser->save();

                    foreach ($request->fy as $value) {
                        $fincial = new BankFinancialDetails;
                            $fincial->fy_id = $value['fy_id'];
                            $fincial->com_id = $newuser->id;
                            $fincial->bank_id = Auth::user()->id ;
                            $fincial->zone = $request->zone ;
                            $fincial->class_type_id = $request->asset_class ;
                            $fincial->borrowings = $value['borrowings'];
                            $fincial->bank_exposure = $value['bank_exposure'];
                            $fincial->total_equity = $value['total_equity'];
                            $fincial->net_revenue = $value['net_revenue'];
                            $fincial->profit_after_tax = $value['profit_after_tax'];
                            $fincial->rating = $value['rating'];
                            $fincial->rating_date = $value['rating_date'];
                            $fincial->rating_agency = $value['rating_agency'];
                        $fincial->save();
                    }

            });
            alert()->success('Record Inserted', 'Success!')->persistent('Close');
            return redirect()->route('admin.user.edituser',['id' => encrypt($newuser->id)]);
        // } catch (\Exception $e) {
        //     alert()->warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     // errorMail($e, $request->id, Auth::user()->id);
        //     return redirect()->back();
        // }
    }

    public function edituser($id)
    {
        $id=decrypt($id);
        // dd($id);

        $user = User::find($id);
        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->get();
        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',Auth::user()->id)
                        ->get(['bfd.*','fm.fy']);

        $type = DB::table('comp_type_master')->where('status',1)->orderby('id')->get();

        $class_type = DB::table('class_type_master')->where('loan_type','C')->where('status',1)->orderby('id')->get();

        // dd($id,$financial);

        return view('admin.user.edituser', compact('user','sector','zone','fys','financial','type','class_type'));
    }

    public function retail_edituser($id)
    {
        $id=decrypt($id);
        // dd($id);

        $user = User::find($id);
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','Desc')->first();

        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        // ->join('class_type_master as ctm','ctm.id','bfd.class_type_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',Auth::user()->id)
                        ->get(['bfd.*','fm.fy']);

        // dd($financial);

        $class_type = DB::table('class_type_master')->where('loan_type','R')->where('status',1)->orderby('id')->get();

        // dd($id,$financial);

        return view('admin.user.retail_edituser', compact('user','zone','fys','financial','class_type'));
    }

    public function existuser($id)
    {
        $id=decrypt($id);
        // dd($id);

        $user = User::find($id);
        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->get();

        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',$user->created_by)
                        ->get(['bfd.*','fm.fy']);

        $type = DB::table('comp_type_master')->where('status',1)->orderby('id')->get();

        $class_type = DB::table('class_type_master')->where('loan_type','C')->where('status',1)->orderby('id')->get();

        // dd($id,$financial);

        return view('admin.user.existuser', compact('user','sector','zone','fys','financial','type','class_type'));
    }

    public function retail_existuser($id)
    {
        $id=decrypt($id);
        // dd($id);

        $user = User::find($id);
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','Desc')->first();

        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        // ->join('class_type_master as ctm','ctm.id','bfd.class_type_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',Auth::user()->id)
                        ->get(['bfd.*','fm.fy']);

        // dd($financial);

        $class_type = DB::table('class_type_master')->where('loan_type','R')->where('status',1)->orderby('id')->get();

        // dd($id,$financial);

        return view('admin.user.retail_existuser', compact('user','zone','fys','financial','class_type'));
    }

    public function existuser_edit($id)
    {
        $id=decrypt($id);


        $user = User::find($id);
        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->get();

        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',Auth::user()->id)
                        ->get(['bfd.*','fm.fy']);

        $type = DB::table('comp_type_master')->where('status',1)->orderby('id')->get();

        $class_type = DB::table('class_type_master')->where('loan_type','C')->where('status',1)->orderby('id')->get();

        // dd($id,$financial);

        return view('admin.user.existuser_edit', compact('user','sector','zone','fys','financial','type','class_type'));
    }

    public function retail_existuser_edit($id)
    {
        $id=decrypt($id);
        // dd($id);

        $user = User::find($id);
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->orderby('id','Desc')->first();

        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        // ->join('class_type_master as ctm','ctm.id','bfd.class_type_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',Auth::user()->id)
                        ->get(['bfd.*','fm.fy']);

        // dd($financial);

        $class_type = DB::table('class_type_master')->where('loan_type','R')->where('status',1)->orderby('id')->get();

        // dd($id,$financial);

        return view('admin.user.retail_existuser_edit', compact('user','zone','fys','financial','class_type'));
    }

    public function delete($id)
    {
        // $id=decrypt($id);
        // dd($id);
        DB::transaction(function () use ($id)
        {
            $user = User::find($id);
            // dd($user);
            BankFinancialDetails::where('com_id', $id)->where('bank_id', Auth::user()->id)->delete();

            BusinessActivityValue::where('com_id', $id)->delete();

            QuestionValue::where('com_id', $id)->delete();

            $user->delete();
        });
            return true;
        // dd($id,$financial);

        // return view('admin.user.edituser', compact('user','sector','zone','fys','financial'));
    }


    public function viewuser($id)
    {
        $id=decrypt($id);
        // dd($id);

        $user = User::find($id);
        $user->purpose = explode(',', $user->purpose);
        $sector = DB::table('sector_master')->get();
        $zone = DB::table('tbl_zone_master')->get();
        $fys = DB::table('fy_masters')->get();
        $financial = DB::table('bank_financial_details as bfd')
                        ->join('fy_masters as fm','fm.id','bfd.fy_id')
                        ->where('bfd.com_id',$id)
                        ->where('bfd.bank_id',Auth::user()->id)
                        ->get(['bfd.*','fm.fy']);
        // dd($user);

        return view('admin.user.viewuser', compact('user','sector','zone','fys','financial'));
        // return view('admin.user.edit', compact('user','sector','zone','fys'));

    }

    public function update(Request $request)
    {
        // dd($request);
        try{

            DB::transaction(function () use ($request)
            {

                $user = User::find($request->user_id);

                    if($user->sector_id != $request->sector || $user->comp_type_id != $request->comp_type){
                        // dd('d');

                        BusinessActivityValue::where('com_id', $user->id)->delete();

                        QuestionValue::where('com_id', $user->id)->delete();
                    }

                    $user->email = $request->email;
                    // $user->password = Hash::make($request->password);
                    $user->mobile = $request->mobile;
                    $user->designation = $request->designation;
                    $user->comp_type_id = $request->comp_type;
                    $user->pan = $request->pan;
                    $user->contact_person = $request->auth_name;
                    $user->sector_id = $request->sector ;
                $user->save();


                foreach ($request->fy as $value) {
                    $fincial = BankFinancialDetails::find($value['row_id']);
                        // $fincial->borrowings = $value['borrowings'];
                        $fincial->bank_exposure = $value['bank_exposure'];
                        $fincial->zone = $request->zone ;
                        $fincial->class_type_id = $request->asset_class ;
                        // $fincial->total_equity = $value['total_equity'];
                        // $fincial->net_revenue = $value['net_revenue'];
                        // $fincial->profit_after_tax = $value['profit_after_tax'];
                        $fincial->updated_at = carbon::now();
                    $fincial->save();
                }
            });

            alert()->success('Data Updated Successfully', 'Success!')->persistent('Close');
            return redirect()->back();
        }catch (\Exception $e)
        {
            alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
            return redirect()->back();
        }

        // return view('admin.user.edituser', compact('user'));

    }

    public function retail_update(Request $request)
    {
        // dd($request);
        // try{

            DB::transaction(function () use ($request)
            {

                $user = User::find($request->user_id);
                    $user->name = $request->cust_name;
                    $user->pan = $request->pan;
                    $user->email = $request->email;
                    // $user->password = Hash::make($request->password);
                    $user->mobile = $request->mobile;
                    $user->reg_off_add = $request->address;
                    $user->reg_off_pin = $request->pincode;
                    $user->reg_off_state = $request->state;
                    $user->reg_off_city = $request->city;
                $user->save();

                foreach ($request->fy as $value) {
                    if(array_key_exists('row_id', $value)){


                        $fincial = BankFinancialDetails::find($value['row_id']);

                            if($fincial->class_type_id != $value['asset_class']){
                                dd('d');
                                // BusinessActivityValue::where('com_id', $user->id)->delete();

                                // QuestionValue::where('com_id', $user->id)->delete();
                            }

                            $fincial->zone = $request->zone ;
                            $fincial->class_type_id = $value['asset_class'] ;
                            $fincial->bank_exposure = $value['bank_exposure'];
                            $fincial->value_asset = $value['value_asset'];
                            $fincial->loan_tenure = $value['loan_tenure'];
                            $fincial->updated_at = carbon::now();
                        $fincial->save();
                    }
                    else{
                        $fincial = new BankFinancialDetails;
                            $fincial->fy_id = $value['fy_id'];
                            $fincial->com_id = $user->id;
                            $fincial->bank_id = Auth::user()->id ;
                            $fincial->zone = $request->zone ;
                            $fincial->class_type_id = $value['asset_class'] ;
                            $fincial->bank_exposure = $value['bank_exposure'];
                            $fincial->value_asset = $value['value_asset'];
                            $fincial->loan_tenure = $value['loan_tenure'];
                        $fincial->save();
                    }
                }
            });

            alert()->success('Data Updated Successfully', 'Success!')->persistent('Close');
            return redirect()->back();
        // }catch (\Exception $e)
        // {
        //     alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     return redirect()->back();
        // }

        // return view('admin.user.edituser', compact('user'));

    }

    public function existupdate(Request $request)
    {
        // dd($request);
        try{

            DB::transaction(function () use ($request)
            {

                foreach ($request->fy as $value) {
                    $fincial = BankFinancialDetails::find($value['row_id']);
                        $fincial->bank_exposure = $value['bank_exposure'];
                        $fincial->zone = $request->zone ;
                        $fincial->class_type_id = $request->asset_class ;
                        $fincial->updated_at = carbon::now();
                    $fincial->save();
                }
            });

            //alert()->success('Data Updated Successfully', 'Success!')->persistent('Close');
                session()->flash('success', 'Data Updated Successfully');
            return redirect()->back();
        }catch (\Exception $e)
        {
            alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
            return redirect()->back();
        }

        // return view('admin.user.edituser', compact('user'));

    }

    public function retail_existupdate(Request $request)
    {
        // dd($request);
        // try{

            DB::transaction(function () use ($request)
            {

                foreach ($request->fy as $value) {
                    if(array_key_exists('row_id', $value)){

                        $fincial = BankFinancialDetails::find($value['row_id']);

                            if($fincial->class_type_id != $value['asset_class']){
                                // dd('d');

                                // BusinessActivityValue::where('com_id', $user->id)->delete();

                                // QuestionValue::where('com_id', $user->id)->delete();
                            }

                            $fincial->zone = $request->zone ;
                            $fincial->class_type_id = $value['asset_class'] ;
                            $fincial->bank_exposure = $value['bank_exposure'];
                            $fincial->value_asset = $value['value_asset'];
                            $fincial->loan_tenure = $value['loan_tenure'];
                            $fincial->updated_at = carbon::now();
                        $fincial->save();
                    }
                    else{
                        $fincial = new BankFinancialDetails;
                            $fincial->fy_id = $value['fy_id'];
                            $fincial->com_id = $request->user_id;
                            $fincial->bank_id = Auth::user()->id ;
                            $fincial->zone = $request->zone ;
                            $fincial->class_type_id = $value['asset_class'] ;
                            $fincial->bank_exposure = $value['bank_exposure'];
                            $fincial->value_asset = $value['value_asset'];
                            $fincial->loan_tenure = $value['loan_tenure'];
                        $fincial->save();
                    }
                }
            });

            alert()->success('Data Updated Successfully', 'Success!')->persistent('Close');
            return redirect()->back();
        // }catch (\Exception $e)
        // {
        //     alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     return redirect()->back();
        // }

        // return view('admin.user.edituser', compact('user'));

    }

    public function existsubmit(Request $request)
    {
         dd($request);
        try{

            DB::transaction(function () use ($request)
            {
                foreach ($request->fy as $value) {
                    $fincial = new BankFinancialDetails;
                        $fincial->fy_id = $value['fy_id'];
                        $fincial->com_id = $request->user_id;
                        $fincial->bank_id = Auth::user()->id;
                        $fincial->zone = $request->zone ;
                        $fincial->class_type_id = $request->asset_class ;
                        $fincial->borrowings = $value['borrowings'];
                        $fincial->bank_exposure = $value['bank_exposure'];
                        $fincial->total_equity = $value['total_equity'];
                        $fincial->net_revenue = $value['net_revenue'];
                        $fincial->profit_after_tax = $value['profit_after_tax'];
                        $fincial->rating = $value['rating'];
                        $fincial->rating_date = $value['rating_date'];
                        $fincial->rating_agency = $value['rating_agency'];
                    $fincial->save();
                }

                $user = User::find($request->user_id);

                // $data = array('name'=>$user->name,'email'=>$user->email, 'bank_name'=>Auth::user()->name);

                //             //  dd($data);

                // Mail::send('emails.existuser_mail', $data, function($message) use($data) {
                //    $message->to($data ['email'],$data ['name'])->subject
                //        ('ESG - Prakrit ');
                //         // $message->cc('pliwg@ifciltd.com');
                //         // $message->bcc('shweta.rai@ifciltd.com');
                //   });
            });

            // alert()->success('Data Inserted Successfully', 'Success!')->persistent('Close');
               session()->flash('success', 'Data Inserted Successfully');
            return redirect()->route('admin.user.existuser_edit',['id' => encrypt($request->user_id)]);
            // return redirect()->back();
        }catch (\Exception $e)
        {
            alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
            return redirect()->back();
        }


    }

    public function retail_existsubmit(Request $request)
    {
        // dd($request);
        try{

            DB::transaction(function () use ($request)
            {
                foreach ($request->fy as $value) {
                    $fincial = new BankFinancialDetails;
                        $fincial->fy_id = $value['fy_id'];
                        $fincial->com_id = $request->user_id;
                        $fincial->bank_id = Auth::user()->id;
                        $fincial->zone = $request->zone;
                        $fincial->class_type_id = $value['asset_class'];
                        $fincial->bank_exposure = $value['bank_exposure'];
                        $fincial->value_asset = $value['value_asset'];
                        $fincial->loan_tenure = $value['loan_tenure'];
                    $fincial->save();
                }

                $user = User::find($request->user_id);

                // $data = array('name'=>$user->name,'email'=>$user->email, 'bank_name'=>Auth::user()->name);

                //             //  dd($data);

                // Mail::send('emails.existuser_mail', $data, function($message) use($data) {
                //    $message->to($data ['email'],$data ['name'])->subject
                //        ('ESG - Prakrit ');
                //         // $message->cc('pliwg@ifciltd.com');
                //         // $message->bcc('shweta.rai@ifciltd.com');
                //   });
            });

            alert()->success('Data Inserted Successfully', 'Success!')->persistent('Close');
            return redirect()->route('admin.retail.existuser_edit',['id' => encrypt($request->user_id)]);
            // return redirect()->back();
        }catch (\Exception $e)
        {
            alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
            return redirect()->back();
        }


    }

    public function submit(Request $request)
    {
        // dd($request);

        // try{
            DB::transaction(function () use ($request)
            {
                // $randomString = $this->generateRandomString(5);
                $randomString = 'India@1234';


                $user = User::find($request->user_id);
                    $user->status = 'S' ;
                    $user->isactive = 'Y' ;
                    $user->password=Hash::make($randomString);
                $user->save();

                $data = array('role_id' => 4, 'model_type' => 'App\User', 'model_id' => $user->id);
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

            });
            alert()->success('New Borrower Created', 'Success!')->persistent('Close');
            return redirect()->route('admin.user.index');
        // }catch (\Exception $e)
        // {
        //     alert()->error('Something Went Wrong!', 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

    }

    public function retail_submit(Request $request)
    {
        // dd($request);

        // try{
            DB::transaction(function () use ($request)
            {
                // $randomString = $this->generateRandomString(5);
                $randomString = 'India@1234';


                $user = User::find($request->user_id);
                    $user->status = 'S' ;
                    $user->isactive = 'Y' ;
                    $user->password=Hash::make($randomString);
                $user->save();

                $data = array('role_id' => 4, 'model_type' => 'App\User', 'model_id' => $user->id);
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

            });
            alert()->success('New Borrower Created', 'Success!')->persistent('Close');
            return redirect()->route('admin.user.index');
        // }catch (\Exception $e)
        // {
        //     alert()->error('Something Went Wrong!', 'Attention!')->persistent('Close');
        //     return redirect()->back();
        // }

    }
}
