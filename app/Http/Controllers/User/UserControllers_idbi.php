<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use App\User;
use App\QuestionValue;
use App\UnsdgValue;

use Illuminate\Support\Facades\File;
use SimpleXMLElement;

class UserController extends Controller
{
    public function fy()
    {
        $user = Auth::user();

        $ques = QuestionValue::where('com_id', $user->id)->get();

        $fys = DB::table('fy_masters')->get();

        // dd($ques);

        return view('user.fy', compact('fys','ques'));

    }

    public function xml()
    {
        $user = Auth::user();

        $ques = QuestionValue::where('com_id', $user->id)->get();

        $fys = DB::table('fy_masters')->get();

        // dd($ques);

        return view('user.xml', compact('fys','ques'));

    }

    public function xml_store(Request $request)
    {

        // ------------------------------------ 1st

        $xmlString = File::get($request->pan);
        $xml = new SimpleXMLElement($xmlString);

        // Process the XML data as needed
        // Example: Retrieve values from specific elements
        $title = (string) $xml->title;
        $author = (string) $xml->author;

        // Return the extracted data or perform any further actions
        // return response()->json([
        //     'title' => $title,
        //     'author' => $author,
        // ]);



        // ----------------------------------- 2nd
        $xmlString = file_get_contents($request->pan);
        $xmlObject = simplexml_load_string($xmlString);

        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);


        // ---------------------------------------

        $xml=simplexml_load_file($request->pan);
        $json = json_encode($xml);
        $phpArray = json_decode($json, true);

        dd($xml,$json,$phpArray);


        dd($phpArray,$xmlString,$xmlObject,$xml,$title,$author );


        $user = Auth::user();

        $ques = QuestionValue::where('com_id', $user->id)->get();

        $fys = DB::table('fy_masters')->get();

        // dd($ques);

        return view('user.xml', compact('fys','ques'));

    }

    public function addquestionnaire($fy_id)
    {
        $fy_id = decrypt($fy_id);

        $user = Auth::user();
        $sector = DB::table('sector_master')->where('id',$user->sector_id)->first();


        $fys = DB::table('fy_masters')->where('id',$fy_id)->first();
        // dd($fys);

        //   if(isset($ques)){
        //      return redirect()->route('user.editquestionnaire');
        //     }

        $ques_mast = DB::table('question_master')->orderby('id')->get();


        $ques_value = DB::table('question_value')->where('com_id',$user->id)->get();

        // dd($ques_mast);

        $subques_mast = DB::table('subques_master')->whereIn('sector_id',[1,$user->sector_id])->orderby('id')->get();

        return view('user.addquestionnaire', compact('ques_mast','subques_mast','fy_id','user','ques_value','sector','fys'));

    }

    public function getSubQuesData($ques_id,$sect_id)
    {
        // $quesId = $request->input('ques_id');
        // dd($ques_id,$sect_id);
        $user = Auth::user();
        $subques_mast = DB::table('subques_master')->where('ques_id', $ques_id)->whereIn('sector_id',[1,$sect_id])->get();
        // dd($subques_mast);

        // dd($ques_val);

        return response()->json(['data' => $subques_mast]);
    }

    public function getSubQuesData_view($ques_id,$fy_id)
    {
        // $quesId = $request->input('ques_id');
        $user = Auth::user();
        $ques_val = DB::table('question_value')
                            ->join('subques_master as sm','sm.id','question_value.subques_id')
                            ->where('question_value.ques_id', $ques_id)
                            ->where('question_value.fy_id',$fy_id)
                            ->where('question_value.com_id',$user->id)
                            ->get(['question_value.*','sm.particular','sm.unit','sm.descrption']);

        // dd($ques_val);

        return response()->json(['data' => $ques_val]);
    }

    public function store(Request $request)
    {
        // dd($request);
        // try {
            DB::transaction(function () use ($request)
            {
                foreach ($request->ques as $value) {
                    // if(isset($value['check']))
                    // {
                        $ques_detail = new QuestionValue;
                            $ques_detail->com_id = Auth::user()->id;
                            $ques_detail->ques_id = $request->ques_id;
                            $ques_detail->subques_id = isset($value['subques_id']) ? $value['subques_id'] : null;
                            $ques_detail->is_checked = isset($value['check']) ? 1 : 0;
                            $ques_detail->value = isset($value['value']) ? $value['value'] : null ;
                            $ques_detail->fy_id = $request->fy_id;
                        $ques_detail->save();
                    // }
                }
            });
            alert()->success('Record Inserted', 'Success!')->persistent('Close');
            return redirect()->back();
            // return redirect()->route('user.addquestionnaire');
        // } catch (\Exception $e) {
        //     alert()->warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     // errorMail($e, $request->id, Auth::user()->id);
        //     return redirect()->back();
        // }
    }


    public function update(Request $request)
    {
        // dd($request);
        // try{
            DB::transaction(function () use ($request)
            {
                foreach ($request->ques as $value) {
                    $data = QuestionValue::where('id', $value['row_id'])->first();
                    $data->fill([
                        'is_checked' =>isset($value['check']) ? 1 : 0,
                        'value' => isset($value['value']) ? $value['value'] : null ,
                        'updated_at'=>Carbon::now(),
                    ]);
                    $data->save();
                    // dd($data);
                }
            });

            alert()->success('Data Updated Successfully', 'Success!')->persistent('Close');
            return redirect()->back();
        // }catch (\Exception $e)
        // {
        //     alert()->Warning('Something Went Wrong', 'Warning!')->persistent('Close');
        //     return redirect()->back();
        // }
    }

}
