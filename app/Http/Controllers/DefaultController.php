<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use AfricasTalking\SDK\AfricasTalking;
use GeminiAPI\Laravel\Facades\Gemini;
use App\Models\User;

class DefaultController extends Controller
{
    //callback to acknowledge send messages
    public function callbackUrl(Request $request){
            
        // return response()->json($response, 200);
        
    }
    
    public function gemini($prompt){
        // Prepare request data
        $requestData = [
            'prompt' => $prompt
        ];
        
        // Make the POST request using Laravel's Http facade
        $response = Http::post('https://mediflow-six.vercel.app/api/gemini', $requestData);
    
        // Get the response body
        $body = $response->body();
        $body = json_decode($body, true);
    
        // Handle the response as needed
        return $body['response'];
    }
    
    public function getUser($phoneNumber){
        $all = patients::where('phone', $phoneNumber)
            ->get();
    }
    
    // ussd callback
    public function ussdCallback(){
        //45358b69a0cb3681de4dfdc223fbd2c6a39027d4f60419989404c941ac0ef161

        $sessionId = $_POST["sessionId"];
        $serviceCode = $_POST["serviceCode"];
        $phoneNumber = $_POST["phoneNumber"];
        $text = $_POST["text"];
        
        $text = $this->back($text);
        
        $details = explode("*", $text);
        
        
        $phone_1 = substr($phoneNumber,(strlen($phoneNumber) - 9), 9);
        
        
        
        $all = User::where('phone', 'like', '%' . $phone_1)
            ->get(); 
            
        if(count($all) > 0){
            
            if($text==""){
            
            $arr = [];
            $message = "Welcome sir";
            
            // $this->sendSMS($phoneNumber, $message);
            
                $response = "CON Welcome to Mediflow Kenya Daktari\n".
                        "1. Order test  \n".
                        "2. Sample collection \n".
                        "3. Check history ";
            
            }
            
            else if($text=="1"){
            
                $response = "CON Enter patient name, age and gender seperated by            comma\n";
            
            }
            
            
            else if($details[0]==1 and strlen($details[1])>6 and count($details)==2 ){
            $response = "CON Enter test category\n".
                        "1. Hematology \n".
                        "2. Immunology \n".
                        "3. Clinical chemistry ";
            }
            else if($details[0]==1 and $details[2]==1 and count($details)==3){
                $response = "CON Enter test subcategory\n".
                            "1. CBC \n".
                            "2. WBC \n".
                            "3. HB ";
                }
                
            else if($details[0]==1 and $details[3]==1 and count($details)==4){
                $response = "CON Enter patient phone number for payment\n";
                
                }
                
            else if($details[0]==1 and $details[3]==1 and count($details)==5){
                $response = "END Patient has been prompted to pay, We will notify you via SMS once payment is complete\n";
                
                }
            
            
            else if(strlen($text) > 6){
                if(strpos($text, ',') !== false){
                    $arr = explode(',', $text);
                    if(count($arr) == 3){
                        // $response = "CON Invalid details please retry \n".$text;
                    }
                    else{
                        $response = "CON Invalid details please retry\n";
                    }
                }
                else{
                    $response = "CON Invalid details please retry\n";
                }
                
                
            }
           
 
        }
        
        
        // patient area
        else{
           if($text==""){
            
            $message = "Welcome sir";
            
            // $this->sendSMS($phoneNumber, $message);
            
            $response = "CON Welcome to Mediflow Kenya please select a Language (Changua Lugha)\n".
                        "1. English \n".
                        "2. Swahili";
            
            }
            else if($text == "1"){
                $response = "CON Select a service\n".
                            "1. Emergency Response \n".
                            "2. Consultancy\n".
                            "2. Lab Request\n".
                            "00. Back";
            }
            else if($text == "1*1"){
                $response = "CON Select Emergency type\n".
                            "1. Accidents/Injuries \n".
                            "2. Sickness \n".
                            "3. Other \n".
                            "00. Back";
            }
            else if($text == "1*1*1" || $text == "1*1*2" || $text == "1*1*3"){
                $response = "END We have submitted your details to emergency services, emergency team will be arriving soon\n";
            }
            else if($text == "1*2"){
                $response = "CON Select service you need\n".
                            "1. First Aid \n".
                            "2. Other \n".
                            "00. Back";
            }
            else if($text == "1*2*1"){
                $response = "CON Select illness\n".
                            "1. Food poisoning \n".
                            "2. Burns \n".
                            "3. Broken bones \n".
                            "4. Cuts \n".
                            "5. Underlying conditions\n".
                            "00. Back";
            }
            else if($text == "1*2*1*1"){
                $details = $this->gemini('Give a brief step first aid for food poisoning.');
                $response = "END Instructions have been sent to your SMS inbox. \n Thank you for choosing MediFlow.";
                
                $this->sendSMS($phoneNumber, $details);
            }
            else if($text == "1*2*1*2"){
                $details = $this->gemini('Give a brief step first aid for burns');
                $response = "END Instructions have been sent to your SMS inbox. \n Thank you for choosing MediFlow.";
                
                $this->sendSMS($phoneNumber, $details);
            }
            else if($text == "1*2*1*3"){
                $details = $this->gemini('Give a brief step first aid for broken bones');
                $response = "END Instructions have been sent to your SMS inbox. \n Thank you for choosing MediFlow.";
                
                $this->sendSMS($phoneNumber, $details);
            }
            else if($text == "1*2*1*4"){
                $details = $this->gemini('Give a brief step first aid for cuts');
                $response = "END Instructions have been sent to your SMS inbox. \n Thank you for choosing MediFlow.";
                
                $this->sendSMS($phoneNumber, $details);
            }
            else if($text == "1*2*1*5"){
                $details = $this->gemini('Give a brief step first aid for seizure');
                $response = "END Instructions have been sent to your SMS inbox. \n Thank you for choosing MediFlow.";
                
                $this->sendSMS($phoneNumber, $details);
            }
            else if(substr($text, 0, 5) == "1*2*1" && strlen($text)>5){
                $response = "End Do the following: \n". $text;
                // if(substr($text, 8) == 1){
                //   $details = $this->gemini('Food poisoning first aid');
                //   $response = "End Do the following:\n".
                //             $details;
                // }
            }
            else if($text == "2"){
                $response = "CON Chagua huduma \n".
                            "1. Majibu ya Dharura \n".
                            "2. Ushauri \n".
                            "2. ombi la maabara \n".
                            "00. Rudi nyuma";
            }
            else if($text == "2*1"){
                $response = "CON Chagua aina ya dharura\n".
                            "1. Ajali/Majeruhi \n".
                            "2. Ugonjwa \n".
                            "3. Nyingine \n".
                            "00. Rudi nyuma";
            }
            else if($text == "2*1*1" || $text == "2*1*2" || $text == "2*1*3"){
                $response = "END Tumewasilisha maelezo yako kwa huduma za dharura, timu ya dharura itawasili hivi karibuni \n";
            }
            else if($text == "2*2"){
                $response = "CON Chagua huduma unayohitaji\n".
                            "1. Huduma ya kwanza \n".
                            "2. Nyingine \n".
                            "00. Rudi nyuma";
            }
            else if($text == "2*2*1"){
                $response = "CON Chagua ugonjwa\n".
                            "1. Sumu ya chakula \n".
                            "2. Kuungua \n".
                            "3. Mifupa iliyovunjika \n".
                            "4. Jeraha la kukata \n".
                            "5. Hali za kiafya za msingi\n".
                            "00. Rudi nyuma";
            }
             
        }

        
        
        header('Content-type: text/plain');
        echo $response;

    }
    
    public function back($text){
        // if(strlen($text)>6){
        //     if(strpos($text, ',') !== false){
        //         $arr = explode(',', $text);
        //         if(count($arr) == 3){
        //             return join("1*2*3");
        //         }
        //     }
            
        // }
        // else{
        //     return $text;
        // }
        $arr = explode("*",$text);
       while(array_search("00",$arr)){
           $postion = array_search("00",$arr);
           array_splice($arr,$postion-1,2);
       }
       return join("*",$arr);
        
    }
    
    
    public function sendSMS($phoneNumber, $message){
        $username = 'rodwell.leo'; 
        $apiKey   = '3bc7e9a1f8eed63603614a97eace24e3015265ad05372474051de11a96e153a9'; 
        $AT       = new AfricasTalking($username, $apiKey);
        
        // Get one of the services
        $sms      = $AT->sms();
        
        // Use the service
        $result   = $sms->send([
            'to'      => $phoneNumber,
            'message' => $message
        ]);
        
        DB::table('callback')->insert([
            'response' => json_encode($result),
        ]);
    }
    
    public function createUser(Request $request){
        try{
            DB::beginTransaction();
            
            User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'role'=>$request->role,
                'password'=> bcrypt($request->password),
                ]);
                
            $all = User::where('email', $request->email)
                ->get();
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['id' => $all[0]->id, 'message'=>'user created'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
    
    public function login(Request $request){
        try{
            $credentials= request(['email','password']);
            if(!Auth::attempt($credentials)){
                return response()->json(['message'=>'Unauthorized'],403);
            }
                
            $all = User::where('email', $request->email)
                ->get();
                
            // Commit the transaction if all operations are successful
            DB::commit();
            
            
            
            return response()->json(['id' => $all[0]->id, 'message'=>'user authenticated'], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(['Error!!!' => $e->getMessage()], 500);
        }
    }
}
