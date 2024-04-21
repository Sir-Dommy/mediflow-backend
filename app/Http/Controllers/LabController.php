<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\DefaultController;
use App\Models\flights;
use App\Models\results;
use App\Models\tests;
use App\Models\User;
use App\Models\appointments;

class LabController extends Controller
{
    //create flight
    public function createFlight(Request $request, $test_id){
        try{
            DB::beginTransaction();
            
            flights::create([
                'test_id'=>$test_id,
                'status'=>$request->status,
                'package'=>$request->package,
                'date_of_flight'=>Carbon::now(),
                ]);
            
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['message'=>'flight created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    //get all flight
    public function getFlights(){
        try{
           $all = flights::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
    }
    
    //create result
    public function createResult(Request $request, $test_id, $user_id){
        try{
            DB::beginTransaction();
            $all = results::where('test_id',$test_id)
                ->where('added_by', $user_id)
                ->get();
                
                if(count($all)>0){
                    DB::rollBack();
                    return response()->json(['Error' => "test results already exist, update instead"], 400);
                }
            
            $patient = appointments::join('tests', 'tests.appointment_id', '=', 'appointments.id')
                    ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                    ->where('tests.id', $test_id)
                    ->select('appointments.symptoms', 'patients.phone')
                    ->get();
                
            // Instantiate an instance of the controller where the gemini() function is defined
            $controller = app()->make(DefaultController::class);
        
            $prompt = "what is the illness from: symptoms = ".$patient[0]->symptoms." test results = ".$request->results." generate reason for your giagnosis";
            // Call the gemini() function
            $ai_response = $controller->gemini($prompt);
            
            results::create([
                'test_id'=>$test_id,
                'results'=>$request->results,
                'ai_response'=>$ai_response,
                'added_by'=>$user_id,
                ]);
            
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['message'=>'Test Results created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    //get all flight
    public function getResults(){
        try{
           $all = results::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
    }
    
    //get results for a specific patient
    //get all flight
    public function getPatientResults($id){
        try{
           $all = results::where('id',$id)
           ->get();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
    }
    
    //update doctor diagnosis
    public function updateResult(Request $request, $id){
        try{
            
            DB::beginTransaction();
            results::where('id', $id)
                    ->update(['updated_by'=> $request->user_id,
                        'doctor_response'=> $request->doctor_response,
                        ]);
                
            // Commit the transaction if all operations are successful
            DB::commit();
            return response()->json(['id' => $id, 'message'=>'updated'], 200);
        }
        
        catch (\Exception $e){
            // Rollback the transaction if any error occurs
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
}
