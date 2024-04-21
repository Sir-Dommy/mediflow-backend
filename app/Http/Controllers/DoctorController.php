<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\patients;
use App\Models\appointments;
use App\Models\tests;
use App\Models\payments;
use App\Models\drug_orders;

class DoctorController extends Controller
{
    //get all patient details
    public function getPatientDetails(){
        try{
           $all = patients::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
        
    }
    
    // get patient by id
    public function getPatientById($id){
        try{
           $all = patients::where('id', $id)
                ->get();
            
            if(count($all)<1){
                return response()->json(["error"=>"user not found"], 404);
            }
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
    }
    
    //function to create a patient
    public function createPatient(Request $request){
        try{
            DB::beginTransaction();
            
            patients::create([
                'name'=> $request->name,
                'gender'=> $request->gender,
                'phone'=> $request->phone,
                'email'=> $request->email,
                'county'=> $request->county,
                'sub_county'=> $request->sub_county,
                'district'=> $request->district,
                'location'=> $request->location,
                'dob'=> $request->dob,
                ]);
                
            $all = patients::where('phone', $request->phone)
                ->get();
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['patient_id' => $all[0]->id, 'message'=>'patient created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    public function createAppointment(Request $request, $patient_id, $doctor_id){
        try{
            DB::beginTransaction();
            
            appointments::create([
                'patient_id'=>$patient_id,
                'symptoms'=>$request->symptoms,
                'medicated'=>$request->medicated,
                'medications'=>$request->medications,
                'doctor_id'=>$doctor_id,
                'appointment_date'=>$request->appointment_date,
                ]);
                
            $all = appointments::where('patient_id', $patient_id)
                ->where('doctor_id', $doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->get();
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['appointment_id' => $all[0]->id, 'message'=>'appointment created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    // get all appointments
    public function getAppointments(){
        try{
           $all = appointments::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
        
    }
    
    public function createTest(Request $request, $appointment_id, $test_type){
        try{
            DB::beginTransaction();
            
            tests::create([
                'appointment_id'=>$appointment_id,
                'status'=> $request->status,
                'test_type'=>$test_type,
                'samples'=> $request->samples,
                'payment_status'=> $request->payment_status,
                'date_of_testing'=>Carbon::now(),
                ]);
                
            $all = tests::where('appointment_id', $appointment_id)
                ->where('test_type', $test_type)
                ->get();
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['test_id' => $all[0]->id, 'message'=>'test created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    // get all tests
    public function getTests(){
        try{
           $all = tests::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
        
    }
    
    public function createPayment(Request $request){
        try{
            DB::beginTransaction();
            
            if(isset($request->order_id)){
                payments::create([
                'amount'=>$request->amount,
                'date'=>Carbon::now(),
                'status'=>$request->status,
                'test_id'=>$request->test_id,
                'order_id'=>$request->order_id,
                'payment_method'=>$request->payment_method
                ]);
            }
            else{
                tests::create([
                'amount'=>$request->amount,
                'date'=>Carbon::now(),
                'status'=>$request->status,
                'test_id'=>$request->test_id,
                'payment_method'=>$request->payment_method
                ]);
            }
            
                
            $all = payments::where('test_id', $request->test_id)
                ->where('date', Carbon::now())
                ->get();
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['payment' => $all[0]->id, 'message'=>'payment created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    // get all payments
    public function getPayments(){
        try{
           $all = payments::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
        
    }
    
    // create drug order
    public function createDrugOrder(Request $request){
        try{
            DB::beginTransaction();
            

            drug_orders::create([
                'test_id'=>$request->test_id,
                'drugs'=>$request->drugs,
                'prescribed_by'=>$request->prescribed_by,
            ]);
            
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json([ 'message'=>'drug order created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    // get all payments
    public function getDrugOrders(){
        try{
           $all = drug_orders::all();
        
            return response()->json($all, 200); 
        }
        catch (\Exception $e){
            return response()->json(["error"=>$e->getMessage()], 200); 
        }
        
    }
    
    
}
