
<?php

 function webhookreceive(Request $request){
$test = file_get_contents('php://input');
       
       $Chatoken = ChatToken::first();
   
           $token = $Chatoken->token;
           $variabletype = gettype($token);
       
           // Log::debug($variabletype);
   
       Log::debug('Token is '.$token);
            
   
       $response = json_decode($test);
       $user=json_decode( json_encode($response->user), true);
       // Log::debug($user['parent_param']);
   
       // if(empty($user['parent_param'])){
       //     Log::debug('Parent param is empty');
       // }
       // else{
       //     Log::debug('Parent param not empty');
       // }
        Log::debug($test);
   
       if($response->action=="book.online.consultation"){
           
            if(empty($user['parent_param']) || $user['parent_param'] == null){
              // if($user['parent_param'] ==  NULL || $parent_param == null){
               //  Log::debug('Parent parma is empty');
               //SEND FIRST RESPONSE BACK TO USER
               
               //   Log::debug($user['identifier']);
   
               ///////Check if user identify not equall to null 
   
               if(property_exists((object)$user, 'identifier') && $user['identifier'] != null){
   
                   ///////////
   
                   $userr = User::where('phone',$user['identifier'])->first();
                   $firstname = User::where('phone',$user['identifier'])->value('firstname');
                   $update = Count::first()->update([
                       'count' => 1
                   ]);
   
                  
                       //do this process to booking appointment
                       
                       //check if the user is registered on embanqo
   
                       $user_string =  serialize($userr);
   
                       // $responsed = Http::withoutVerifying()->withHeaders([
                       //     'token'=>$token,
                           
                       // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //     "platform" => $user['platform'],
                       //     "agent_id" => $user['agent_id'],
                       //     "message" => "Welcome ".$firstname .",  Be rest assured that the information provided will be handled with the utmost confidentiality",
                       //     "msg_type" => "text",
                       //     "user_code" => $response->user_code,
                       //     "parent_param" => null,
                       //     "quick_replies" => [],
                       //     "buttons" => [],
                       //     "use_cache" => true,
                       //     "reply_internal" => true,
                       //     "action" =>  $response->action,
                       //     "intent_id" => $user['intent_id']
                       // ]);
   
   
                       
                       
                       // Log::debug($responsed);
                       
                      
   
                           //send another bot message
                           
   
                           //get request to get all specialization on Nello
                           $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');
   
                           $resjson = $response_spec->json();
   
                            //Log::debug($resjson);
                           $specialization = [];
   
                           foreach($resjson  as $doc_spec){
                               $specialization[] = [
                                   "content_type" => "text",
                                   "title" => $doc_spec["aos"],
                                   "payload" => $doc_spec["aos"],
                                   "image_url" =>  null
                               ];
                           } 
                           
                           //  Log::debug($specialization);
   
                            $specialization[] = [
                               "content_type" => "text",
                               "title" => "Cancel",
                               "payload" => "Cancel",
                               "image_url" =>  null
                            ];
                           
                        
   
                         
                        
   
   
   
   
                         
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "To help you find the right care, please select the kind of specialist that you’d like to see: ",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'reason',
                                   'user_data' => $userr,
   
                               ],
                               "quick_replies" => $specialization,
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
                           
                           // Log::debug($responsed);
               
                           
                       // end of if first message is successful
           
                       
   
   
   
                   // end of if user exists in the database
   
   
                   /////
               }
               /////
   
               else{
                   $responsed = Http::withoutVerifying()->withHeaders([
                       'token'=> $token,
                       
                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       "platform" => $user['platform'],
                       "agent_id" => $user['agent_id'],
                       "message" => "Kindly Provide your phone Number registered on Nello ",
                       "msg_type" => "text",
                       "user_code" => $response->user_code,
                       "parent_param" => [
                           'next_step' => 'checkauth',
                       ],
                       "quick_replies" => [],
                       "buttons" => [],
                       "use_cache" => true,
                       "reply_internal" => true,
                       "action" =>  $response->action,
                       "intent_id" => $user['intent_id']
                   ]);
               }
   
               // $responsed = Http::withoutVerifying()->withHeaders([
               //     'token'=> $token,
                   
               // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
               //     "platform" => $user['platform'],
               //     "agent_id" => $user['agent_id'],
               //     "message" => "Kindly Provide your phone Number registered on Nello ",
               //     "msg_type" => "text",
               //     "user_code" => $response->user_code,
               //     "parent_param" => [
               //         'next_step' => 'checkauth',
               //     ],
               //     "quick_replies" => [],
               //     "buttons" => [],
               //     "use_cache" => true,
               //     "reply_internal" => true,
               //     "action" =>  $response->action,
               //     "intent_id" => $user['intent_id']
               // ]);
               
               
               // Log::debug($responsed);
   
               //END OF FIRST RESPONSE
   
      }
   
           //was else 
           else{
               // $param = $response->parent_param;
   
               // $parent_param = json_encode($param);
               // $res = json_decode($response);
               $parent_param=json_decode(($response->parent_param), true);
               // Log::debug($parent_param['next_step']);
               $phone = $response->query;
               //  Log::debug($parent_param);
   
               // Use Switch Case.
   
               switch ($parent_param['next_step']) {
                   case "checkauth":
                     
                       //check if user with phone number exist on Nello
                       $count = Count::first();
                       $count = $count->count;
   
                       // Log::debug($count);
   
                       //validate the phone
                       if(preg_match('/^[0-9]{11}+$/', $phone)){
   
                           ////
   
                       $userr = User::where('phone',$phone)->first();
                       $email = User::where('phone',$phone)->value('email');
                       $firstname = User::where('phone',$phone)->value('firstname');
                       $lastname = User::where('phone',$phone)->value('lastname');
                       $update = Count::first()->update([
                           'count' => 1
                       ]);
   
                       if($userr){
                           //do this process to booking appointment
                           
                           //check if the user is registered on embanqo
   
                           //Create the user on Embanqo if user phone exists
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/user',[
                              
                       
                               "email" => $email,
                          "identifier" => $phone,
                           "name" => $firstname ." ". $lastname,
                          "user_type" => "user",
                          "phone" => $phone,
                          "user_code" => $response->user_code,
                          "platform" => $user['platform'],
                          
                            "agent_id" => $user['agent_id'],
                            "meta" => null
                       
                           ]);
                       
                           // Log::debug($responsed);
                           //
   
                           if($responsed['status'] == 'success'){
                               //if user created successfully
                               $user_string =  serialize($userr);
   
   
   
                               // $responsed = Http::withoutVerifying()->withHeaders([
                               //     'token'=>$token,
                                   
                               // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               //     "platform" => $user['platform'],
                               //     "agent_id" => $user['agent_id'],
                               //     "message" => "Welcome ".$firstname .",  Be rest assured that the information provided will be handled with the utmost confidentiality",
                               //     "msg_type" => "text",
                               //     "user_code" => $response->user_code,
                               //     "parent_param" => null,
                               //     "quick_replies" => [],
                               //     "buttons" => [],
                               //     "use_cache" => true,
                               //     "reply_internal" => true,
                               //     "action" =>  $response->action,
                               //     "intent_id" => $user['intent_id']
                               // ]);
   
                               
   
                                   //send another bot message
                                   
       
                                   //get request to get all specialization on Nello
                                   $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');
       
                                   $resjson = $response_spec->json();
       
                                    //Log::debug($resjson);
                                   $specialization = [];
       
                                   foreach($resjson  as $doc_spec){
                                       $specialization[] = [
                                           "content_type" => "text",
                                           "title" => $doc_spec["aos"],
                                           "payload" => $doc_spec["aos"],
                                           "image_url" =>  null
                                       ];
                                   } 
                                   
                                   //  Log::debug($specialization);
       
                                    $specialization[] = [
                                       "content_type" => "text",
                                       "title" => "Cancel",
                                       "payload" => "Cancel",
                                       "image_url" =>  null
                                    ];
                                   
                                
       
                                 
                                
       
       
       
       
                                 
       
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "To help you find the right care, please select the kind of specialist that you’d like to see: ",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'reason',
                                           'user_data' => $userr,
       
                                       ],
                                       "quick_replies" => $specialization,
                                           
                                             
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
                                   
                                   // Log::debug($responsed);
                       
                                   
                               // end of if first message is successful
   
                           }
   
                           // $user_string =  serialize($userr);
   
   
   
                           // $responsed = Http::withoutVerifying()->withHeaders([
                           //     'token'=>$token,
                               
                           // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //     "platform" => $user['platform'],
                           //     "agent_id" => $user['agent_id'],
                           //     "message" => "Great. Let’s book your online appointment with a doctor.
                           //     Be rest assured that the information provided will be handled with the utmost confidentiality
                           //     ",
                           //     "msg_type" => "text",
                           //     "user_code" => $response->user_code,
                           //     "parent_param" => null,
                           //     "quick_replies" => [],
                           //     "buttons" => [],
                           //     "use_cache" => true,
                           //     "reply_internal" => true,
                           //     "action" =>  $response->action,
                           //     "intent_id" => $user['intent_id']
                           // ]);
   
   
                           
                           
                           // Log::debug($responsed);
                           
                           // if($responsed['status'] == 'success'){
   
                           //     //send another bot message
                               
   
                           //     //get request to get all specialization on Nello
                           //     $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');
   
                           //     $resjson = $response_spec->json();
   
                           //      //Log::debug($resjson);
                           //     $specialization = [];
   
                           //     foreach($resjson  as $doc_spec){
                           //         $specialization[] = [
                           //             "content_type" => "text",
                           //             "title" => $doc_spec["aos"],
                           //             "payload" => $doc_spec["aos"],
                           //             "image_url" =>  null
                           //         ];
                           //     } 
                               
                           //     //  Log::debug($specialization);
   
                           //      $specialization[] = [
                           //         "content_type" => "text",
                           //         "title" => "Cancel",
                           //         "payload" => "Cancel",
                           //         "image_url" =>  null
                           //      ];
                               
                            
   
                             
                            
   
   
   
   
                             
   
                           //     $responsed = Http::withoutVerifying()->withHeaders([
                           //         'token'=>$token,
                                   
                           //     ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //         "platform" => $user['platform'],
                           //         "agent_id" => $user['agent_id'],
                           //         "message" => "To help you find the right care, please select the kind of specialist that you’d like to see: ",
                           //         "msg_type" => "quick_reply",
                           //         "user_code" => $response->user_code,
                           //         "parent_param" => [
                           //             'next_step' => 'reason',
                           //             'user_data' => $userr,
   
                           //         ],
                           //         "quick_replies" => $specialization,
                                       
                                         
                           //         "buttons" => [],
                           //         "use_cache" => true,
                           //         "reply_internal" => true,
                           //         "action" =>  $response->action,
                           //         "intent_id" => $user['intent_id']
                           //     ]);
                               
                               
                           //     // Log::debug($responsed);
                   
                               
                           // } // end of if first message is successful
               
                           
   
   
   
                       } // end of if user exists in the database
   
                       else{
                           //prompt the user to register
                           // Log::debug('Not registered');
   
                           //Create Registration
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Phone Number not registered on Nello, to complete your registration, I will need some of your basic information. ",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => [],
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
   
                           if($responsed['status'] == 'success'){
                           //    Log::debug($responsed['status']);
   
                              $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => " To start, please provide your firstname ",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   "next_step" => "lastname_doctor"
                               ],
                               "quick_replies" => [],
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           }
   
                           
   
   
                       } //end of Registration
   
                           ///
                       } // if phone number is valid
   
                       else {
                           
                        if($count < 3){
                           $update = Count::first()->update([
                               'count' => $count + 1
                           ]);
   
                           //count 
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Invalid phone number, kindly input a correct phone number ",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'checkauth',
                               ],
                               "quick_replies" => [],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
   
                        }
   
                        else {
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
   
   
                        }
                           
                           // $count->count += 1;
                           // $count->save();
   
                           
                           
                       }
   
   
   
   
   
                       //Log::debug($user);
   
                       // if($user){
                       //     $person = User::where('phone',$phone)->first();
   
   
                       //     $responsed = Http::withoutVerifying()->withHeaders([
                       //         'token'=>$token,
                               
                       //     ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //         "platform" => $user['platform'],
                       //         "agent_id" => $user['agent_id'],
                       //         "message" => "Great. Let’s book your online appointment with a doctor ",
                       //         "msg_type" => "text",
                       //         "user_code" => $response->user_code,
                       //         "parent_param" => [
                       //             'next_step' => 'select_specialization',
                       //             'data'=>[
                       //                 'user' => $person,
                       //             ]
                       //         ],
                       //         "quick_replies" => [],
                       //         "buttons" => [],
                       //         "use_cache" => true,
                       //         "reply_internal" => true,
                       //         "action" =>  $response->action,
                       //         "intent_id" => $user['intent_id']
                       //     ]);
                           
                           
                       //     Log::debug($responsed);
   
                       // }
                       // else {
   
                       // }
                       
                     break;
                    case "reason":
   
   
                       
   
                       // Reason Next Step
                       $appointment_specialization = $response->query;
                       // Log::debug($appointment_specialization);
   
                       //parent params data
                       $user_data = $parent_param['user_data'];
   
                       $options = json_decode($user['options_temp'], true);
   
                      
                          
                       $count = Count::first();
                       $count = $count->count;
   
                       if(in_array($appointment_specialization,array_column($options,'value'))){
                              
   
                           //update count back to one
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
   
                       $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "Please input the reason for the appointment
                           ",
                           "msg_type" => "text",
                           "user_code" => $response->user_code,
                           "parent_param" => [
                               'next_step' => 'date',
                               'user_data' => $user_data,
                               'appointment_specialization' => $appointment_specialization,
   
                           ],
                           "quick_replies" => [],
                           "buttons" => [],
                           "use_cache" => true,
                           "reply_internal" => true,
                           "action" =>  $response->action,
                           "intent_id" => $user['intent_id']
                       ]);
   
                           
                      }  
                      else{
                          
   
                          //if count is lesser than 3
                          if($count < 3){
                           $update = Count::first()->update([
                               'count' => $count + 1
                           ]);
   
                            //get request to get all specialization on Nello
                            $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');
       
                            $resjson = $response_spec->json();
   
                             //Log::debug($resjson);
                            $specialization = [];
   
                            foreach($resjson  as $doc_spec){
                                $specialization[] = [
                                    "content_type" => "text",
                                    "title" => $doc_spec["aos"],
                                    "payload" => $doc_spec["aos"],
                                    "image_url" =>  null
                                ];
                            } 
                            
                            //  Log::debug($specialization);
   
                             $specialization[] = [
                                "content_type" => "text",
                                "title" => "Cancel",
                                "payload" => "Cancel",
                                "image_url" =>  null
                             ];
                            
                         
   
                          
                         
   
   
   
   
                          
   
                            $responsed = Http::withoutVerifying()->withHeaders([
                                'token'=>$token,
                                
                            ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                "platform" => $user['platform'],
                                "agent_id" => $user['agent_id'],
                                "message" => "Invalid input, please select the kind of specialist that you’d like to see from below options ",
                                "msg_type" => "quick_reply",
                                "user_code" => $response->user_code,
                                "parent_param" => [
                                    'next_step' => 'reason',
                                    'user_data' => $user_data,
   
                                ],
                                "quick_replies" => $specialization,
                                    
                                      
                                "buttons" => [],
                                "use_cache" => true,
                                "reply_internal" => true,
                                "action" =>  $response->action,
                                "intent_id" => $user['intent_id']
                            ]);
                            
                            
   
   
                           //send message to select from below button
   
                        }
   
                        else {
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           //Back To Menu
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
                        }
                      }  
   
                       
   
                       // $responsed = Http::withoutVerifying()->withHeaders([
                       //     'token'=>$token,
                           
                       // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //     "platform" => $user['platform'],
                       //     "agent_id" => $user['agent_id'],
                       //     "message" => "Please input the reason for the appointment
                       //     ",
                       //     "msg_type" => "text",
                       //     "user_code" => $response->user_code,
                       //     "parent_param" => [
                       //         'next_step' => 'date',
                       //         'user_data' => $user_data,
                       //         'appointment_specialization' => $appointment_specialization,
   
                       //     ],
                       //     "quick_replies" => [],
                       //     "buttons" => [],
                       //     "use_cache" => true,
                       //     "reply_internal" => true,
                       //     "action" =>  $response->action,
                       //     "intent_id" => $user['intent_id']
                       // ]);
                       
                       
                       //Log::debug($responsed);
                     break;
   
   
   
   
   
                   case "date":
   
                        // Date Next Step
                        $reason = $response->query;
   
                        
                        $count = Count::first();
                        $count= $count->count;
   
   
                        //reasondate
   
                       //  if($parent_param['reason'] != NULL){
                       //      $reason  = $parent_param['reason'];
                       //  }
   
                        
   
                        //parent params details
                        $user_data = $parent_param['user_data'];
                        $appointment_specialization = $parent_param['appointment_specialization'];
   
                       //  Log::debug($reason);
   
                        if(preg_match("/^([a-zA-Z' ]+)$/",$reason)){
                           $updated = Count::first()->update([
                               'count' => 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Please provide your preferred date for the appointment with a $appointment_specialization i.e 20/09/2022",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'checkdate',
                                   'user_data' => $user_data,
                                   'appointment_specialization' => $appointment_specialization,
                                   'reason'=> $reason
       
                               ],
                               "quick_replies" => [],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
   
   
                        }
   
                        else{
                            if($count < 3){
                               $updated = Count::first()->update([
                                   'count' => $count + 1
                               ]);  
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid input, Please input the reason for the appointment
                                   ",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'date',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                               
   
   
                            }
   
                            else{
                               $updated = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                            }
                        }
    
                        
                        
                        //Log::debug($responsed);
                     
                     break;
   
                     //check date
                     case "checkdate":
   
                       $date_selected = $response->query;
   
                       //parent params details
                       $user_data = $parent_param['user_data'];
                       $appointment_specialization = $parent_param['appointment_specialization'];
                       $reason = $parent_param['reason'];
   
                       //Log::debug($reason);
   
                       $count = Count::first();
                       $count = $count->count;
   
   
   
                       if(preg_match("/^([a-zA-Z' ]+)$/",$date_selected) || preg_match('/-/', $date_selected) || ctype_alnum($date_selected)){
                           $updated = Count::first()->update([
                               'count' => 1
                           ]);
   
                           ///
   
                           if($count < 3){
                               $updated = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid date input,  provide your preferred date for the appointment with a $appointment_specialization i.e 20/09/2022",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'checkdate',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
                                       'reason'=> $reason
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                               
       
   
                               
                              
                           }
   
                           else{
                               //Back to Menu
   
                               $updated = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                           }
   
                       }
   
   
   
                       $date_selected = Carbon::createFromFormat('d/m/Y', $date_selected)->format('d-m-Y');
   
   
   
   
   
                       $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "Please hold on for a second, while I check for available appointments",
                           "msg_type" => "text",
                           "user_code" => $response->user_code,
                           "parent_param" => [
                               'next_step' => 'checkdate',
                               'user_data' => $user_data,
                               'appointment_specialization' => $appointment_specialization,
                               'reason'=> $reason
   
                           ],
                          
                           "quick_replies" => [],
                           "buttons" => [],
                           "use_cache" => true,
                           "reply_internal" => true,
                           "action" =>  $response->action,
                           "intent_id" => $user['intent_id']
                       ]);
   
                       // if message went through call the Online DoctorList Api
                       if($responsed['status'] == 'success'){
                           $response_onlinedoc = Http::get('https://admin.asknello.com/api/onlinedoctors',[
                               "specialization" => $appointment_specialization,
                               "date" => $date_selected
                           ]);
   
                           $response_onlinedoc = $response_onlinedoc->json();
   
                         // Log::debug($response_onlinedoc);
   
                           //check if status is failed,, go return search again, Cancel
   
   
                        if($response_onlinedoc["status"] == "failed"){
   
                           if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid or past date ",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'redate',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
                                       'reason'=> $reason
           
                                   ],
                                  
                                   "quick_replies" => [
                                       [
                                           "content_type" => "text",
                                           "title" => "Search Again",
                                           "payload" => "Search Again",
                                           "image_url" =>  null
                                       ],
                                      
                                       [
                                           "content_type" => "text",
                                           "title" => "Cancel",
                                           "payload" => "Cancel",
                                           "image_url" =>  null
                                       ],
   
                                       [
                                           "content_type" => "text",
                                           "title" => "Chat Support",
                                           "payload" => "Chat Support",
                                           "image_url" =>  null
                                       ],
   
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                           }
   
                           else{
                               $update = Count::first()->update([
                                   'count' =>  1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                           }
   
   
                               
                           }
   
                           // if status doesn't fail and there is data and docss is empty
                           elseif($response_onlinedoc["status"] == "success" && count($response_onlinedoc['docss'])== 0) {
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "There are no doctor appointments that match your selection ",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'redate',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
                                       'reason'=> $reason
           
                                   ],
                                  
                                   "quick_replies" => [
                                       [
                                           "content_type" => "text",
                                           "title" => "Search Again",
                                           "payload" => "Search Again",
                                           "image_url" =>  null
                                       ],
                                      
                                       [
                                           "content_type" => "text",
                                           "title" => "Cancel",
                                           "payload" => "Cancel",
                                           "image_url" =>  null
                                       ],
   
                                       [
                                           "content_type" => "text",
                                           "title" => "Chat Support",
                                           "payload" => "Chat Support",
                                           "image_url" =>  null
                                       ],
   
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                               
   
                           }
                           elseif($response_onlinedoc["status"] == "success" && count($response_onlinedoc['docss']) > 0) {
                               //Log::debug($response_onlinedoc);
   
                               $update = Count::first()->update([
                                   'count' =>  1
                               ]);
   
                               $doctors_docss = [];
                                         
                                    
                                  
   
                               foreach($response_onlinedoc['docss']  as $doc_docss){
                                   $title = $doc_docss['date'] . ' - ' . ' Dr '.' '. $doc_docss['firstname'];
                                   
                                   $doctors_docss[] = [
                                       // "content_type" => "text",
                                       // "title" => $title,
                                       // "payload" => serialize($doc_docss),
                                       // "image_url" =>  null
   
                                       "title" => "Dr. ".$doc_docss['firstname'],
                                       "description" => "AOS - ".$appointment_specialization ." , Fee - N ".$doc_docss['fee'],
                                       "image_url" => "https://res.cloudinary.com/edifice-solutions/image/upload/v1665568822/415_vv6cco.jpg",
                                       "suggestions" => [
                                        
                                         [
                                           "title" =>  $doc_docss['date'],
                                           "payload" => serialize($doc_docss),
                                           "type" => "postback",
                                           "url" => null
                                         ],
                                         [
                                           "title" =>  "Start Again",
                                           "payload" => "Cancel",
                                           "type" => "postback",
                                           "url" => null
                                         ]
                                       ]
                                     
   
                                     
                                   ];
                               } 
   
                               
   
                                 $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Here’s a list of available doctors
                               ",
                               "msg_type" => "carousel",
                               "user_code" => $response->user_code,
                                    "parent_param" => [
                                       'next_step' => 'displaytime',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
                                       'reason'=> $reason,
                                       'date_selected' => $date_selected,
   
                                   ],
                               "quick_replies" => null,
                               "buttons" => [],
                               "use_cache" => null,
                               "reply_internal" => true,
                               "label" => null,
                               "attachments" => [],
                               "template" => null,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id'],
   
                               "carousels" => $doctors_docss,
   
                           ]);
                           
                               
                               
                               // Log::debug($responsed);
   
   
                           }
                       }
                       
                       break;
   
                     //check date
                     case "redate":
   
                       if($response->query == "Chat Support"){
                           // Trigger intent to chat support
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/trigger/intent',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "instantqueue",
               
                               "user_code" => $response->user_code,
                              
                              
                           ]);
                           
                       }
   
                       elseif($response->query == "Search Again") {
   
                            //parent params details
                         $reason = $parent_param['reason'];
                         $user_data = $parent_param['user_data'];
                         $appointment_specialization = $parent_param['appointment_specialization'];
    
                       //   Log::debug($reason);
     
                         $responsed = Http::withoutVerifying()->withHeaders([
                             'token'=>$token,
                             
                         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                             "platform" => $user['platform'],
                             "agent_id" => $user['agent_id'],
                             "message" => "Please provide your preferred date for the appointment with a $appointment_specialization i.e 20/09/2022",
                             "msg_type" => "text",
                             "user_code" => $response->user_code,
                             "parent_param" => [
                                 'next_step' => 'checkdate',
                                 'user_data' => $user_data,
                                 'appointment_specialization' => $appointment_specialization,
                                 'reason'=> $reason
     
                             ],
                             "quick_replies" => [],
                             "buttons" => [],
                             "use_cache" => true,
                             "reply_internal" => true,
                             "action" =>  $response->action,
                             "intent_id" => $user['intent_id']
                         ]);
                         
   
                       }
   
                        
   
                       break;
   
                       case "displaytime":
                           //Display Time
   
   
   
                           $input = $response->query;
                           //$date_selected = $response->query;
   
                           //parent params details
                          
   
                           $reason = $parent_param['reason'];
                           $user_data = $parent_param['user_data'];
                           $appointment_specialization = $parent_param['appointment_specialization'];
                           $date_selected = $parent_param['date_selected'];
   
   
                           $options = json_decode($user['options_temp'], true);
   
                      
                          
                           $count = Count::first();
                           $count = $count->count;
   
   
                           ///////////////////Check ///////////////////
   
                           if(in_array($input,array_column($options,'value'))){
                                  
       
                               //update count back to one
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
   
                               //Do this
   
                               $doc_docss = unserialize($response->query);
   
                            
          
                               // Log::debug($doc_docss);
       
                              
                               
       
                               
       
       
       
       
       
                               //times quick reply
       
                               $today = Carbon::now()->format('d-m-Y'); //get todays date
                                $now = Carbon::now()->timezone('Africa/Lagos')->format('H'); //get time in hour
       
       
                                if($doc_docss['date'] == $today){
                                    //if today date is same as $doc_docss date
       
                                    $display_time =
                                    array_filter($doc_docss['time'], function($e) use($now){
                                        
                                
                                            $hour = $e;
                                                           $delimiter = ':';
                                               $words = explode($delimiter, $hour);
                                               
                                                   $mytime = $words[0];
                                               
                                                       if($mytime > $now){
                                                           return $e;
                                
                                           
                                
                                           
                                        }
                                
                                
                                      
                                    });
       
                                   //  Log::debug(count($display_time));
       
                                    //check if the display_time count is 0 or more
       
                                    if(count($display_time) == 0){
                                        //go to search again date
       
                                        $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "Available time are already passed, Kindly Search Again to continue",
                                           "msg_type" => "quick_reply",
                                           "user_code" => $response->user_code,
                                           "parent_param" => [
                                               'next_step' => 'redate',
                                               'user_data' => $user_data,
                                               'appointment_specialization' => $appointment_specialization,
                                               'reason'=> $reason
                   
                                           ],
                                          
                                           "quick_replies" => [
                                               [
                                                   "content_type" => "text",
                                                   "title" => "Search Again",
                                                   "payload" => "Search Again",
                                                   "image_url" =>  null
                                               ],
                                              
                                               [
                                                   "content_type" => "text",
                                                   "title" => "Cancel",
                                                   "payload" => "Cancel",
                                                   "image_url" =>  null
                                               ],
           
                                               [
                                                   "content_type" => "text",
                                                   "title" => "Chat Support",
                                                   "payload" => "Chat Support",
                                                   "image_url" =>  null
                                               ],
           
                                           ],
                                           "buttons" => [],
                                           "use_cache" => true,
                                           "reply_internal" => true,
                                           "action" =>  $response->action,
                                           "intent_id" => $user['intent_id']
                                       ]);
                                    }
       
                                    elseif(count($display_time) > 0){
                                        //dispaly the time to select
       
                                        $times = [];
       
                                        //check if today then chooose time that is greater than now
                
                                        //
                
                                        foreach($display_time  as $time){
                                           
                                            $times[] = [
                                                "content_type" => "text",
                                                "title" => $time,
                                                "payload" => $time,
                                                "image_url" =>  null
                                            ];
                                        } 
       
                                        $times[]= [
                                           "content_type" => "text",
                                           "title" => "Cancel",
                                           "payload" =>  "Cancel",
                                           "image_url" =>  null
                                        ];
       
                                        $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "Select preferred appointment time.",
                                           "msg_type" => "quick_reply",
                                           "user_code" => $response->user_code,
                                           "parent_param" => [
                                               'next_step' => 'checkavailability',
                                               'user_data' => $user_data,
                                               'appointment_specialization' => $appointment_specialization,
                                               'reason'=> $reason,
                                               'doc_docss' => $doc_docss,
                   
                                           ],
                                           "quick_replies" => $times,
                                           "buttons" => [],
                                           "use_cache" => true,
                                           "reply_internal" => true,
                                           "action" =>  $response->action,
                                           "intent_id" => $user['intent_id']
                                       ]);
                                       
                 
       
       
       
       
                                    }
                                
       
       
                                }
       
                                elseif($doc_docss['date'] != $today){
       
                                   $times = [];
       
                                   //check if today then chooose time that is greater than now
           
                                   //
           
                                   foreach($doc_docss['time']  as $time){
                                      
                                       $times[] = [
                                           "content_type" => "text",
                                           "title" => $time,
                                           "payload" => $time,
                                           "image_url" =>  null
                                       ];
                                   } 
               
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Select Preferred appointment time.",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'checkavailability',
                                           'user_data' => $user_data,
                                           'appointment_specialization' => $appointment_specialization,
                                           'reason'=> $reason,
                                           'doc_docss' => $doc_docss,
               
                                       ],
                                       "quick_replies" => $times,
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
             
       
                                }
       
   
                           }
   
                           else {
   
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
   
                                   // Error to render
   
                                   $response_onlinedoc = Http::get('https://admin.asknello.com/api/onlinedoctors',[
                                       "specialization" => $appointment_specialization,
                                       "date" => $date_selected
                                   ]);
           
                                   $response_onlinedoc = $response_onlinedoc->json();
   
                                   if($response_onlinedoc["status"] == "success" && count($response_onlinedoc['docss']) > 0) {
                                       //Log::debug($response_onlinedoc);
           
                                     
           
                                       $doctors_docss = [];
                                                 
                                            
                                          
           
                                       foreach($response_onlinedoc['docss']  as $doc_docss){
                                           $title = $doc_docss['date'] . ' - ' . ' Dr '.' '. $doc_docss['firstname'];
                                           
                                           $doctors_docss[] = [
                                               // "content_type" => "text",
                                               // "title" => $title,
                                               // "payload" => serialize($doc_docss),
                                               // "image_url" =>  null
           
                                               "title" => "Dr. ".$doc_docss['firstname'],
                                               "description" => "AOS - ".$appointment_specialization ." , Fee - N ".$doc_docss['fee'],
                                               "image_url" => "https://res.cloudinary.com/edifice-solutions/image/upload/v1665568822/415_vv6cco.jpg",
                                               "suggestions" => [
                                                
                                                 [
                                                   "title" =>  $doc_docss['date'],
                                                   "payload" => serialize($doc_docss),
                                                   "type" => "postback",
                                                   "url" => null
                                                 ],
                                                 [
                                                   "title" =>  "Start Again",
                                                   "payload" => "Cancel",
                                                   "type" => "postback",
                                                   "url" => null
                                                 ]
                                               ]
                                             
           
                                             
                                           ];
                                       } 
           
                                       
           
                                         $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You are expected to select from  list of available doctors
                                       ",
                                       "msg_type" => "carousel",
                                       "user_code" => $response->user_code,
                                            "parent_param" => [
                                               'next_step' => 'displaytime',
                                               'user_data' => $user_data,
                                               'appointment_specialization' => $appointment_specialization,
                                               'reason'=> $reason,
                                               'date_selected' => $date_selected,
           
                                           ],
                                       "quick_replies" => null,
                                       "buttons" => [],
                                       "use_cache" => null,
                                       "reply_internal" => true,
                                       "label" => null,
                                       "attachments" => [],
                                       "template" => null,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id'],
           
                                       "carousels" => $doctors_docss,
           
                                   ]);
                                   
                                       
                                       
                                       // Log::debug($responsed);
           
           
                                   }
   
   
   
   
   
                                   ///////
                               }
   
                               else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
                                   //Back to Menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
   
   
                               }
                           }
   
   
   
   
                           //////////////////////////////////////////
   
   
   
   
   
   
   
                           // $doc_docss = unserialize($response->query);
   
                           // $reason = $parent_param['reason'];
                           // $user_data = $parent_param['user_data'];
                           // $appointment_specialization = $parent_param['appointment_specialization'];
      
                           // // Log::debug($doc_docss);
   
                          
                           
   
                           
   
   
   
   
   
                           // //times quick reply
   
                           // $today = Carbon::now()->format('d-m-Y'); //get todays date
                           //  $now = Carbon::now()->timezone('Africa/Lagos')->format('H'); //get time in hour
   
   
                           //  if($doc_docss['date'] == $today){
                           //      //if today date is same as $doc_docss date
   
                           //      $display_time =
                           //      array_filter($doc_docss['time'], function($e) use($now){
                                    
                            
                           //              $hour = $e;
                           //                             $delimiter = ':';
                           //                 $words = explode($delimiter, $hour);
                                           
                           //                     $mytime = $words[0];
                                           
                           //                         if($mytime > $now){
                           //                             return $e;
                            
                                       
                            
                                       
                           //          }
                            
                            
                                  
                           //      });
   
                           //     //  Log::debug(count($display_time));
   
                           //      //check if the display_time count is 0 or more
   
                           //      if(count($display_time) == 0){
                           //          //go to search again date
   
                           //          $responsed = Http::withoutVerifying()->withHeaders([
                           //             'token'=>$token,
                                       
                           //         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //             "platform" => $user['platform'],
                           //             "agent_id" => $user['agent_id'],
                           //             "message" => "Available time are already passed, Kindly Search Again to continue",
                           //             "msg_type" => "quick_reply",
                           //             "user_code" => $response->user_code,
                           //             "parent_param" => [
                           //                 'next_step' => 'redate',
                           //                 'user_data' => $user_data,
                           //                 'appointment_specialization' => $appointment_specialization,
                           //                 'reason'=> $reason
               
                           //             ],
                                      
                           //             "quick_replies" => [
                           //                 [
                           //                     "content_type" => "text",
                           //                     "title" => "Search Again",
                           //                     "payload" => "Search Again",
                           //                     "image_url" =>  null
                           //                 ],
                                          
                           //                 [
                           //                     "content_type" => "text",
                           //                     "title" => "Cancel",
                           //                     "payload" => "Cancel",
                           //                     "image_url" =>  null
                           //                 ],
       
                           //                 [
                           //                     "content_type" => "text",
                           //                     "title" => "Chat Support",
                           //                     "payload" => "Chat Support",
                           //                     "image_url" =>  null
                           //                 ],
       
                           //             ],
                           //             "buttons" => [],
                           //             "use_cache" => true,
                           //             "reply_internal" => true,
                           //             "action" =>  $response->action,
                           //             "intent_id" => $user['intent_id']
                           //         ]);
                           //      }
   
                           //      elseif(count($display_time) > 0){
                           //          //dispaly the time to select
   
                           //          $times = [];
   
                           //          //check if today then chooose time that is greater than now
            
                           //          //
            
                           //          foreach($display_time  as $time){
                                       
                           //              $times[] = [
                           //                  "content_type" => "text",
                           //                  "title" => $time,
                           //                  "payload" => $time,
                           //                  "image_url" =>  null
                           //              ];
                           //          } 
   
                           //          $times[]= [
                           //             "content_type" => "text",
                           //             "title" => "Cancel",
                           //             "payload" =>  "Cancel",
                           //             "image_url" =>  null
                           //          ];
   
                           //          $responsed = Http::withoutVerifying()->withHeaders([
                           //             'token'=>$token,
                                       
                           //         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //             "platform" => $user['platform'],
                           //             "agent_id" => $user['agent_id'],
                           //             "message" => "Select preferred appointment time.",
                           //             "msg_type" => "quick_reply",
                           //             "user_code" => $response->user_code,
                           //             "parent_param" => [
                           //                 'next_step' => 'checkavailability',
                           //                 'user_data' => $user_data,
                           //                 'appointment_specialization' => $appointment_specialization,
                           //                 'reason'=> $reason,
                           //                 'doc_docss' => $doc_docss,
               
                           //             ],
                           //             "quick_replies" => $times,
                           //             "buttons" => [],
                           //             "use_cache" => true,
                           //             "reply_internal" => true,
                           //             "action" =>  $response->action,
                           //             "intent_id" => $user['intent_id']
                           //         ]);
                                   
             
   
   
   
   
                           //      }
                            
   
   
                           //  }
   
                           //  elseif($doc_docss['date'] != $today){
   
                           //     $times = [];
   
                           //     //check if today then chooose time that is greater than now
       
                           //     //
       
                           //     foreach($doc_docss['time']  as $time){
                                  
                           //         $times[] = [
                           //             "content_type" => "text",
                           //             "title" => $time,
                           //             "payload" => $time,
                           //             "image_url" =>  null
                           //         ];
                           //     } 
           
                           //     $responsed = Http::withoutVerifying()->withHeaders([
                           //         'token'=>$token,
                                   
                           //     ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //         "platform" => $user['platform'],
                           //         "agent_id" => $user['agent_id'],
                           //         "message" => "Select Preferred appointment time.",
                           //         "msg_type" => "quick_reply",
                           //         "user_code" => $response->user_code,
                           //         "parent_param" => [
                           //             'next_step' => 'checkavailability',
                           //             'user_data' => $user_data,
                           //             'appointment_specialization' => $appointment_specialization,
                           //             'reason'=> $reason,
                           //             'doc_docss' => $doc_docss,
           
                           //         ],
                           //         "quick_replies" => $times,
                           //         "buttons" => [],
                           //         "use_cache" => true,
                           //         "reply_internal" => true,
                           //         "action" =>  $response->action,
                           //         "intent_id" => $user['intent_id']
                           //     ]);
                               
         
   
                           //  }
   
                          
   
                       break;
   
                       case "checkavailability":
                       
                       $selected_time = $response->query; // fetch the selected time
   
                       $reason = $parent_param['reason'];
                       $user_data = $parent_param['user_data'];
                       $appointment_specialization = $parent_param['appointment_specialization'];
   
                       $doc_docss = $parent_param['doc_docss'];
   
   
   
                       $options = json_decode($user['options_temp'], true);
   
                      
                          
                           $count = Count::first();
                           $count = $count->count;
   
   
                       //check if the option temps exist
   
                       if(in_array($selected_time,array_column($options,'value'))){
                              
   
                           //update count back to one
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           //////////////////
   
                                  //check if appointment where selected time and date and doctor exists in the database
   
                           // $checkappointment = Appointment::where('time',$selected_time)->where('date',%)
   
                          
                           $doc_id = $doc_docss['id'];
                           $date = $doc_docss['date'];
                           $time = $selected_time;
                           $phone = $user_data['phone'];
                           $uuid = $doc_docss['uuid'];
                           $fee = $doc_docss['fee'];
                           $reasons = $reason;
   
   
                           // Log::debug($doc_id);
                           // Log::debug($date);
                           // Log::debug($time);
                           // Log::debug($phone);
   
   
                          $dateformat= Carbon::parse($date)->format('Y-m-d');
   
                           $checkappointment = Appointment::where('date',$dateformat)->where('time',$time)->where('doctor_id',$doc_id)->first();
   
                           if($checkappointment){
   
                               // Log::debug("Not Available");
   
                               //already booked
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Seems scheduled appointment time has already been booked.",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'alreadybooked',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
                                       'reason'=> $reason,
                                       'doc_docss' => $doc_docss,
           
                                   ],
                                   "quick_replies" => [
                                       [
                                           "content_type" => "text",
                                           "title" => "Change Time",
                                           "payload" => "Change Time",
                                           "image_url" =>  null
                                       ],
   
                                       [
                                           "content_type" => "text",
                                           "title" => "Search Again",
                                           "payload" => "Search Again",
                                           "image_url" =>  null
                                       ],
                                      
                                       [
                                           "content_type" => "text",
                                           "title" => "Cancel",
                                           "payload" => "Cancel",
                                           "image_url" =>  null
                                       ],
   
                                       [
                                           "content_type" => "text",
                                           "title" => "Chat Support",
                                           "payload" => "Chat Support",
                                           "image_url" =>  null
                                       ],
   
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               
                           }
   
                           else{
                               // Log::debug("Available");
   
                               //draft temporary online booking
   
                               $doc_id = $doc_docss['id'];
                               $date = $doc_docss['date'];
                               $time = $selected_time;
                               $phone = $user_data['phone'];
                               $uuid = $doc_docss['uuid'];
                               $fee = $doc_docss['fee'];
                               $reasons = $reason;
   
                               $money = User::where('uuid',$uuid)->value('fee');
                               $name = User::where('uuid',$uuid)->value('firstname');
   
                               // Log::debug($name);
                               // Log::debug($money);
   
                               $message = 'Proceed to make payment of '. ' ₦ '.$money . ' as consultation fee. Please note confirmation will be sent via mail after payment is verified.';
   
                               //draft online api
                               $drafbooking = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://admin.asknello.com/api/draftbooking',[
                                   
                                   'date'=> $date,
                                   'time' => $time,
                                   'phone' => $phone,
                                   'uuid' =>$uuid,
                                   'reason' => $reason,
                                   'fee' => $money
                               ]);
   
   
                               // Log::debug($drafbooking['temp_id']);
   
                               if($drafbooking['temp_id']){
   
   
                                   ////
                                        ///
                                        $temp_id = $drafbooking['temp_id'];
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => $message,
                                   "msg_type" => "link",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'paymentcompleted',
                                       'user_data' => $user_data,
                                       'appointment_specialization' => $appointment_specialization,
                                       'reason'=> $reason,
                                       'doc_docss' => $doc_docss,
                                       'temp_id' => $temp_id,
           
                                   ],
                                   "quick_replies" => [
                                       
   
                                   ],
                                   "buttons" => [
                                       [
                                           "url" => "https://mw.asknello.com/servicepay/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&temp_id=".$temp_id."&cost=".$doc_docss['fee']."&email=".$user_data['email'],
                                           "title" => "Make Payment"
                                       ]
                                   ],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
   
                                   //
   
                               }
   
                               
   
   
   
                              
                           }
   
   
                           
                           
   
                           ///////
   
   
   
                       }
   
                       else {
   
                           Log::debug('Not it');
   
                           /// if the user input not same as option temp
                           if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
   
                               //Fetch back the time
   
                               $today = Carbon::now()->format('d-m-Y'); //get todays date
                               $now = Carbon::now()->timezone('Africa/Lagos')->format('H'); //get time in hour
      
      
                               if($doc_docss['date'] == $today){
                                   //if today date is same as $doc_docss date
      
                                   $display_time =
                                   array_filter($doc_docss['time'], function($e) use($now){
                                       
                               
                                           $hour = $e;
                                                          $delimiter = ':';
                                              $words = explode($delimiter, $hour);
                                              
                                                  $mytime = $words[0];
                                              
                                                      if($mytime > $now){
                                                          return $e;
                               
                                          
                               
                                          
                                       }
                               
                               
                                     
                                   });
   
                                   $times = [];
   
                                    //check if today then chooose time that is greater than now
            
                                    //
            
                                    foreach($display_time  as $time){
                                       
                                        $times[] = [
                                            "content_type" => "text",
                                            "title" => $time,
                                            "payload" => $time,
                                            "image_url" =>  null
                                        ];
                                    } 
   
                                    $times[]= [
                                       "content_type" => "text",
                                       "title" => "Cancel",
                                       "payload" =>  "Cancel",
                                       "image_url" =>  null
                                    ];
   
                                    $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Select preferred appointment time.",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'checkavailability',
                                           'user_data' => $user_data,
                                           'appointment_specialization' => $appointment_specialization,
                                           'reason'=> $reason,
                                           'doc_docss' => $doc_docss,
               
                                       ],
                                       "quick_replies" => $times,
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
   
                                   //end of if date is equal to today
   
                                   Log::debug($responsed);
   
                               }
   
                               elseif($doc_docss['date'] != $today){
   
                                   $times = [];
       
                                   //check if today then chooose time that is greater than now
           
                                   //
           
                                   foreach($doc_docss['time']  as $time){
                                      
                                       $times[] = [
                                           "content_type" => "text",
                                           "title" => $time,
                                           "payload" => $time,
                                           "image_url" =>  null
                                       ];
                                   } 
               
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Select Preferred appointment time.",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'checkavailability',
                                           'user_data' => $user_data,
                                           'appointment_specialization' => $appointment_specialization,
                                           'reason'=> $reason,
                                           'doc_docss' => $doc_docss,
               
                                       ],
                                       "quick_replies" => $times,
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
                                   Log::debug($responsed);
       
                                }
   
                               
   
   
   
   
   
                               ///
                           }
   
                           else{
   
                               //Back To Menu
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
   
                               Log::debug($responsed);
                           }
                       }
   
   
   
                       //
   
   
   
   
                       
                       // //check if appointment where selected time and date and doctor exists in the database
   
                       //     // $checkappointment = Appointment::where('time',$selected_time)->where('date',%)
   
                          
                       //     $doc_id = $doc_docss['id'];
                       //     $date = $doc_docss['date'];
                       //     $time = $selected_time;
                       //     $phone = $user_data['phone'];
                       //     $uuid = $doc_docss['uuid'];
                       //     $fee = $doc_docss['fee'];
                       //     $reasons = $reason;
   
   
                       //     // Log::debug($doc_id);
                       //     // Log::debug($date);
                       //     // Log::debug($time);
                       //     // Log::debug($phone);
   
   
                       //    $dateformat= Carbon::parse($date)->format('Y-m-d');
   
                       //     $checkappointment = Appointment::where('date',$dateformat)->where('time',$time)->where('doctor_id',$doc_id)->first();
   
                       //     if($checkappointment){
   
                       //         // Log::debug("Not Available");
   
                       //         //already booked
   
                       //         $responsed = Http::withoutVerifying()->withHeaders([
                       //             'token'=>$token,
                                   
                       //         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //             "platform" => $user['platform'],
                       //             "agent_id" => $user['agent_id'],
                       //             "message" => "Seems scheduled appointment time has already been booked.",
                       //             "msg_type" => "quick_reply",
                       //             "user_code" => $response->user_code,
                       //             "parent_param" => [
                       //                 'next_step' => 'alreadybooked',
                       //                 'user_data' => $user_data,
                       //                 'appointment_specialization' => $appointment_specialization,
                       //                 'reason'=> $reason,
                       //                 'doc_docss' => $doc_docss,
           
                       //             ],
                       //             "quick_replies" => [
                       //                 [
                       //                     "content_type" => "text",
                       //                     "title" => "Change Time",
                       //                     "payload" => "Change Time",
                       //                     "image_url" =>  null
                       //                 ],
   
                       //                 [
                       //                     "content_type" => "text",
                       //                     "title" => "Search Again",
                       //                     "payload" => "Search Again",
                       //                     "image_url" =>  null
                       //                 ],
                                      
                       //                 [
                       //                     "content_type" => "text",
                       //                     "title" => "Cancel",
                       //                     "payload" => "Cancel",
                       //                     "image_url" =>  null
                       //                 ],
   
                       //                 [
                       //                     "content_type" => "text",
                       //                     "title" => "Chat Support",
                       //                     "payload" => "Chat Support",
                       //                     "image_url" =>  null
                       //                 ],
   
                       //             ],
                       //             "buttons" => [],
                       //             "use_cache" => true,
                       //             "reply_internal" => true,
                       //             "action" =>  $response->action,
                       //             "intent_id" => $user['intent_id']
                       //         ]);
   
                               
                       //     }
   
                       //     else{
                       //         // Log::debug("Available");
   
                       //         //draft temporary online booking
   
                       //         $doc_id = $doc_docss['id'];
                       //         $date = $doc_docss['date'];
                       //         $time = $selected_time;
                       //         $phone = $user_data['phone'];
                       //         $uuid = $doc_docss['uuid'];
                       //         $fee = $doc_docss['fee'];
                       //         $reasons = $reason;
   
                       //         $money = User::where('uuid',$uuid)->value('fee');
                       //         $name = User::where('uuid',$uuid)->value('firstname');
   
                       //         // Log::debug($name);
                       //         // Log::debug($money);
   
                       //         $message = 'Proceed to make payment of '. ' N '.$money . ' as consultation fee. Please note confirmation will be sent via mail after payment is verified.';
   
                       //         //draft online api
                       //         $drafbooking = Http::withoutVerifying()->withHeaders([
                       //             'token'=>$token,
                                   
                       //         ])->post('https://admin.asknello.com/api/draftbooking',[
                                   
                       //             'date'=> $date,
                       //             'time' => $time,
                       //             'phone' => $phone,
                       //             'uuid' =>$uuid,
                       //             'reason' => $reason,
                       //             'fee' => $money
                       //         ]);
   
   
                       //         // Log::debug($drafbooking['temp_id']);
   
                       //         if($drafbooking['temp_id']){
   
   
                       //             ////
                       //                  ///
                       //                  $temp_id = $drafbooking['temp_id'];
   
                       //         $responsed = Http::withoutVerifying()->withHeaders([
                       //             'token'=>$token,
                                   
                       //         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //             "platform" => $user['platform'],
                       //             "agent_id" => $user['agent_id'],
                       //             "message" => $message,
                       //             "msg_type" => "link",
                       //             "user_code" => $response->user_code,
                       //             "parent_param" => [
                       //                 'next_step' => 'paymentcompleted',
                       //                 'user_data' => $user_data,
                       //                 'appointment_specialization' => $appointment_specialization,
                       //                 'reason'=> $reason,
                       //                 'doc_docss' => $doc_docss,
           
                       //             ],
                       //             "quick_replies" => [
                                       
   
                       //             ],
                       //             "buttons" => [
                       //                 [
                       //                     "url" => "https://mw.asknello.com/servicepay/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&temp_id=".$temp_id."&cost=".$doc_docss['fee']."&email=".$user_data['email'],
                       //                     "title" => "Make Payment"
                       //                 ]
                       //             ],
                       //             "use_cache" => true,
                       //             "reply_internal" => true,
                       //             "action" =>  $response->action,
                       //             "intent_id" => $user['intent_id']
                       //         ]);
   
   
                       //             //
   
                       //         }
   
                               
   
   
   
                              
                       //     }
   
   
                           
                           
   
   
   
                       
                       
                       break;
   
   
                       case "alreadybooked":
   
                           if($response->query == "Change Time"){
   
                               //change time
   
                               $doc_docss = $parent_param['doc_docss'];
   
                               $reason = $parent_param['reason'];
                               $user_data = $parent_param['user_data'];
                               $appointment_specialization = $parent_param['appointment_specialization'];
          
                               // Log::debug($doc_docss);
       
                               //times quick reply
       
                               $today = Carbon::now()->format('d-m-Y'); //get todays date
                                $now = Carbon::now()->timezone('Africa/Lagos')->format('H'); //get time in hour
       
       
                                if($doc_docss['date'] == $today){
                                    //if today date is same as $doc_docss date
       
                                    $display_time =
                                    array_filter($doc_docss['time'], function($e) use($now){
                                        
                                
                                            $hour = $e;
                                            $delimiter = ':';
                                $words = explode($delimiter, $hour);
                                
                                    $mytime = $words[0];
                                
                                        if($mytime > $now){
                                            return $e;
                                
                                           
                                
                                           
                                        }
                                
                                
                                      
                                    });
       
                                   //  Log::debug(count($display_time));
       
                                    //check if the display_time count is 0 or more
       
                                    if(count($display_time) == 0){
                                        //go to search again date
       
                                        $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "Available time are already passed, Kindly Search Again to continue",
                                           "msg_type" => "quick_reply",
                                           "user_code" => $response->user_code,
                                           "parent_param" => [
                                               'next_step' => 'redate',
                                               'user_data' => $user_data,
                                               'appointment_specialization' => $appointment_specialization,
                                               'reason'=> $reason
                   
                                           ],
                                          
                                           "quick_replies" => [
                                               [
                                                   "content_type" => "text",
                                                   "title" => "Search Again",
                                                   "payload" => "Search Again",
                                                   "image_url" =>  null
                                               ],
                                              
                                               [
                                                   "content_type" => "text",
                                                   "title" => "Cancel",
                                                   "payload" => "Cancel",
                                                   "image_url" =>  null
                                               ],
           
                                               [
                                                   "content_type" => "text",
                                                   "title" => "Chat Support",
                                                   "payload" => "Chat Support",
                                                   "image_url" =>  null
                                               ],
           
                                           ],
                                           "buttons" => [],
                                           "use_cache" => true,
                                           "reply_internal" => true,
                                           "action" =>  $response->action,
                                           "intent_id" => $user['intent_id']
                                       ]);
                                    }
       
                                    elseif(count($display_time) > 0){
                                        //dispaly the time to select
       
                                        $times = [];
       
                                        //check if today then chooose time that is greater than now
                
                                        //
                
                                        foreach($display_time  as $time){
                                           
                                            $times[] = [
                                                "content_type" => "text",
                                                "title" => $time,
                                                "payload" => $time,
                                                "image_url" =>  null
                                            ];
                                        } 
       
                                        $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "Select preferred appointment time.",
                                           "msg_type" => "quick_reply",
                                           "user_code" => $response->user_code,
                                           "parent_param" => [
                                               'next_step' => 'checkavailability',
                                               'user_data' => $user_data,
                                               'appointment_specialization' => $appointment_specialization,
                                               'reason'=> $reason,
                                               'doc_docss' => $doc_docss,
                   
                                           ],
                                           "quick_replies" => $times,
                                           "buttons" => [],
                                           "use_cache" => true,
                                           "reply_internal" => true,
                                           "action" =>  $response->action,
                                           "intent_id" => $user['intent_id']
                                       ]);
                                       
                 
       
       
       
       
                                    }
                                
       
       
                                }
       
                                elseif($doc_docss['date'] != $today){
       
                                   $times = [];
       
                                   //check if today then chooose time that is greater than now
           
                                   //
           
                                   foreach($doc_docss['time']  as $time){
                                      
                                       $times[] = [
                                           "content_type" => "text",
                                           "title" => $time,
                                           "payload" => $time,
                                           "image_url" =>  null
                                       ];
                                   } 
               
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Select Preferred appointment time.",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'checkavailability',
                                           'user_data' => $user_data,
                                           'appointment_specialization' => $appointment_specialization,
                                           'reason'=> $reason,
                                           'doc_docss' => $doc_docss,
               
                                       ],
                                       "quick_replies" => $times,
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
             
       
                                }
       
   
   
                           }
   
                           elseif($response->query == "Search Again"){
   
                               //redate... research date
   
                                  //parent params details
                         $reason = $parent_param['reason'];
                         $user_data = $parent_param['user_data'];
                         $appointment_specialization = $parent_param['appointment_specialization'];
    
                       //   Log::debug($reason);
     
                         $responsed = Http::withoutVerifying()->withHeaders([
                             'token'=>$token,
                             
                         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                             "platform" => $user['platform'],
                             "agent_id" => $user['agent_id'],
                             "message" => "Please provide your preferred date for the appointment with a $appointment_specialization i.e 20/09/2022",
                             "msg_type" => "text",
                             "user_code" => $response->user_code,
                             "parent_param" => [
                                 'next_step' => 'checkdate',
                                 'user_data' => $user_data,
                                 'appointment_specialization' => $appointment_specialization,
                                 'reason'=> $reason
     
                             ],
                             "quick_replies" => [],
                             "buttons" => [],
                             "use_cache" => true,
                             "reply_internal" => true,
                             "action" =>  $response->action,
                             "intent_id" => $user['intent_id']
                         ]);
                         
   
                           }
   
                           elseif($response->query == "Chat Support"){
                                // Trigger intent to chat support
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/trigger/intent',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "instantqueue",
               
                               "user_code" => $response->user_code,
                              
                              
                           ]);
   
                           }
   
                       break;
   
                       case "lastname_doctor":
                       
   
                           //get user last name
   
                           $firstname = $response->query;
   
                           $count = Count::first();
                           $count = $count->count;
   
                           
   
                           if(preg_match("/^([a-zA-Z' ]+)$/",$firstname)){
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Next, Please Input your last name",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'email_doctor',
                                       'firstname' => $firstname,
                                       
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
   
                           }
   
                           else{
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => " Invalid input, please provide a valid firstname ",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           "next_step" => "lastname_doctor"
                                       ],
                                       "quick_replies" => [],
                                           
                                             
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
   
                               }
                               else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                   //Back to menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
   
                               }
                           }
   
                          
                           
     
   
   
   
                       break;
   
                       case "email_doctor":
   
                           //do something
                           $lastname = $response->query;
                           $firstname = $parent_param['firstname'];
   
                           $count = Count::first();
                           $count = $count->count;
   
   
                           if(preg_match("/^([a-zA-Z' ]+)$/",$lastname)){
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Please input your email",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_email_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                               
                               // Log::debug($responsed);
                               
   
                           }
   
                           else{
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid input, Please enter a valid lastname",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'email_doctor',
                                           'firstname' => $firstname,
                                           
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                               }
   
                               else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                   //Back to menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
                               }
   
                           }
   
                           //Log::debug($firstname);
   
                           
   
                       break;
                       
   
                       case "validate_email_doctor":
                       
                           // input email
   
                           $email = $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
   
                           $count = Count::first();
                           $count = $count->count;
   
   
   
                           if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                               // Email Is Validated
   
                               $useremail = User::where('email',$email)->first();
   
                               if($useremail){
   
                                   //Log::debug('Email Exists');
                                   //if the email already exist
   
                                   if($count < 3){
                                       $update = Count::first()->update([
                                           'count' => $count + 1
                                       ]);
   
                                       $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "Email already registered in our database, Try another email.",
                                           "msg_type" => "text",
                                           "user_code" => $response->user_code,
                                           "parent_param" => [
                                               'next_step' => 'validate_email_doctor',
                                              'firstname' => $firstname,
                                              'lastname' => $lastname,
                   
                                           ],
                                           "quick_replies" => [],
                                           "buttons" => [],
                                           "use_cache" => true,
                                           "reply_internal" => true,
                                           "action" =>  $response->action,
                                           "intent_id" => $user['intent_id']
                                       ]);
                                   }
   
                                   else{
   
                                       $update = Count::first()->update([
                                           'count' => 1
                                       ]);
   
                                       //Back to menu
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
   
   
                                   }
   
                                   
                                   
   
                               }
   
                               elseif(!$useremail){
                                  // Log::debug('Good');
   
                                  $update = Count::first()->update([
                                      'count' => 1
                                  ]);
                                     $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Enter your phone Number",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_phone_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               }
                             
                             } else {
                               // Email Not Valida
   
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid Email Format, input a valid email address",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'validate_email_doctor',
                                          'firstname' => $firstname,
                                          'lastname' => $lastname,
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
       
   
   
                               }
                               else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
                                   //Back to menu
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
                               }
   
                              
   
                             }
                           
     
   
                           break;
   
                           case "validate_phone_doctor":
   
                           //validate the phone number length
                           $phone = $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
                           $email = $parent_param['email'];
   
                           $count = Count::first();
                           $count = $count->count;
   
                           $existed = User::where('phone',$phone)->exists();
   
                           if($existed){
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
       
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Your with phone number already exist, try another phone number",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'validate_phone_doctor',
                                          'firstname' => $firstname,
                                          'lastname' => $lastname,
                                          'email' => $email,
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
       
       
                                  }
       
                                  else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
       
                                   //Back to menu
       
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
                                  }
       
                           }
   
   
                           elseif(preg_match('/^[0-9]{11}+$/', $phone)){
   
   
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                              // Log::debug('Valid Phone');
                              $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Select your gender",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'date_of_birth_doctor',
                                  'firstname' => $firstname,
                                  'lastname' => $lastname,
                                  'email' => $email,
                                  'phone' => $phone,
       
                               ],
                               "quick_replies" => [
                                   [
                                       "content_type" => "text",
                                   "title" => "Male",
                                   "payload" =>"Male",
                                   "image_url" =>  null
                                   ],
   
                                   [
                                       "content_type" => "text",
                                   "title" => "Female",
                                   "payload" =>"Female",
                                   "image_url" =>  null
                                   ],
   
                                   [
                                       "content_type" => "text",
                                   "title" => "Cancel",
                                   "payload" =>"Cancel",
                                   "image_url" =>  null
                                   ],
   
                               ],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
   
                           }
   
                           
                           else {
                              // Log::debug('Invalid Phone');
   
                              if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid phone number, Kindly enter a valid phone number",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_phone_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
   
                              }
   
                              else{
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                              }
   
                              
                           }
   
                           break;
   
   
                           case "date_of_birth_doctor":
                           
                          
                           
                           
                           
   
                           $options = json_decode($user['options_temp'], true);
   
                      
                          
                       $count = Count::first();
                           $count = $count->count;
   
                           $gender = $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
                           $email = $parent_param['email'];
                           $phone = $parent_param['phone'];
   
                           if(in_array($gender,array_column($options,'value'))){
                              
   
                               //update count back to one
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Kindly enter date of birth, i.e 30/06/1996. (dd/mm/yyyy)",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_date_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
                                      'phone' => $phone,
                                      'gender' => $gender,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
       
                          }  
                          else{
                              
   
                              //if count is lesser than 3
                              if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid Input, Kindly select gender from options below",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'date_of_birth_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
                                      'phone' => $phone,
           
                                   ],
                                   "quick_replies" => [
                                       [
                                           "content_type" => "text",
                                       "title" => "Male",
                                       "payload" =>"Male",
                                       "image_url" =>  null
                                       ],
       
                                       [
                                           "content_type" => "text",
                                       "title" => "Female",
                                       "payload" =>"Female",
                                       "image_url" =>  null
                                       ],
       
                                       [
                                           "content_type" => "text",
                                       "title" => "Cancel",
                                       "payload" =>"Cancel",
                                       "image_url" =>  null
                                       ],
       
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
       
   
                               //send message to select from below button
   
                            }
   
                            else {
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               //Back To Menu
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                            }
                          }  
   
                           // $responsed = Http::withoutVerifying()->withHeaders([
                           //     'token'=>$token,
                               
                           // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //     "platform" => $user['platform'],
                           //     "agent_id" => $user['agent_id'],
                           //     "message" => "Kindly enter date of birth, i.e 30-06-1996. (dd-mm-yyyy)",
                           //     "msg_type" => "text",
                           //     "user_code" => $response->user_code,
                           //     "parent_param" => [
                           //         'next_step' => 'validate_date_doctor',
                           //        'firstname' => $firstname,
                           //        'lastname' => $lastname,
                           //        'email' => $email,
                           //        'phone' => $phone,
                           //        'gender' => $gender,
       
                           //     ],
                           //     "quick_replies" => [],
                           //     "buttons" => [],
                           //     "use_cache" => true,
                           //     "reply_internal" => true,
                           //     "action" =>  $response->action,
                           //     "intent_id" => $user['intent_id']
                           // ]);
   
                           
   
                       break;
   
                       case "validate_date_doctor":
   
                           $dob= $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
                           $email = $parent_param['email'];
                           $phone = $parent_param['phone'];
                           $gender = $parent_param['gender'];
   
                           $count = Count::first();
                           $count = $count->count;
   
   
                          // $mydate = Carbon::createFromFormat('d-m-y')
   
                       //    if (preg_match('/-/', $dob))
                       //     {
                       //         // one or more of the 'special characters' found in $string
                       //     }
   
                       
   
                          if(preg_match("/^([a-zA-Z' ]+)$/",$dob) || preg_match('/-/', $dob) || ctype_alnum($dob) ){
                           $updated = Count::first()->update([
                               'count' => 1
                           ]);
   
                           ///
   
                           if($count < 3){
                               $updated = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid date input, kindly enter date of birth, i.e 30/06/1996. (dd/mm/yyyy)",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_date_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
                                      'phone' => $phone,
                                      'gender' => $gender,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
       
   
                              
                           }
   
                           else{
                               //Back to Menu
   
                               $updated = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                           }
   
                       }
   
   
                          $dob = Carbon::createFromFormat('d/m/Y', $dob)->format('d-m-Y');
                          Log::debug($dob);
                          $dateformat= Carbon::parse($dob)->format('Y-m-d');
                           $result = Carbon::parse($dateformat)->lte(Carbon::now());
   
   
   
                           if($dateformat == Carbon::now()->format('Y-m-d')){
   
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid date, date must be a past date",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'validate_date_doctor',
                                          'firstname' => $firstname,
                                          'lastname' => $lastname,
                                          'email' => $email,
                                          'phone' => $phone,
                                          'gender' => $gender,
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                               }
   
                               else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                   //Back to menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
                               }
                               
                               
                           }
   
                           elseif($result){
                              //passed date 
   
                              $update = Count::first()->update([
                                  'count' => 1
                              ]);
                              
   
                              $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Kindly click on the below button to setup your password",
                               "msg_type" => "link",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_password_doctor',
                                  'firstname' => $firstname,
                                  'lastname' => $lastname,
                                  'email' => $email,
                                  'phone' => $phone,
                                  'gender' => $gender,
                                  'dob' => $dob,
       
                               ],
                               "quick_replies" => [],
                               "buttons" => [
                                   [
                                       "url" => "https://admin.asknello.com/embanqo/password/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&firstname=".$firstname."&lastname=".$lastname."&email=".$email."&phone=".$phone."&gender=".$gender."&dob=".$dob,
                                       "title" => "Create Password"
                                   ]
                               ],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                              
                           }
   
                           
   
                           elseif(!$result){
                               //Log::debug('Correct');
                              // when date is future date back to error
   
                              //go to password 
   
                              if($count < 3){
                                  $update = Count::first()->update([
                                      'count' => $count + 1
                                  ]);
   
                                  $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid date, date must be a past date",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_date_doctor',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
                                      'phone' => $phone,
                                      'gender' => $gender,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
   
                              }
   
                              else{
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                              }
   
                              
                             
   
                        
                           }
   
   
                           
                       break;
   
   
                       case "validate_password_doctor":
   
                           //validate password entered
   
                          
   
                           $password = $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
                           $email = $parent_param['email'];
                           $phone = $parent_param['phone'];
                           $gender = $parent_param['gender'];
                           $dob = $parent_param['dob'];
                               $count = Count::first();
                               $count = $count->count;
   
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
   
   if($password){
   
       if($count < 3){
           $update = Count::first()->update([
               'count' => $count + 1
           ]);
   
           //render button again
   
           $responsed = Http::withoutVerifying()->withHeaders([
               'token'=>$token,
               
           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
               "platform" => $user['platform'],
               "agent_id" => $user['agent_id'],
               "message" => "You are expected to click on the generated button below",
               "msg_type" => "link",
               "user_code" => $response->user_code,
               "parent_param" => [
                   'next_step' => 'validate_password_doctor',
                  'firstname' => $firstname,
                  'lastname' => $lastname,
                  'email' => $email,
                  'phone' => $phone,
                  'gender' => $gender,
                  'dob' => $dob,
   
               ],
               "quick_replies" => [],
               "buttons" => [
                   [
                       "url" => "https://admin.asknello.com/embanqo/password/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&firstname=".$firstname."&lastname=".$lastname."&email=".$email."&phone=".$phone."&gender=".$gender."&dob=".$dob,
                       "title" => "Create Password"
                   ]
               ],
               "use_cache" => true,
               "reply_internal" => true,
               "action" =>  $response->action,
               "intent_id" => $user['intent_id']
           ]);
       }
   
       else{
           $update = Count::first()->update([
               'count' => 1
           ]);
   
           //Back to menu
   
           $responsed = Http::withoutVerifying()->withHeaders([
               'token'=> $token,
               // Back to menu
           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
               "platform" => $user['platform'],
               "agent_id" => $user['agent_id'],
               "message" => "You have exceeded you error limits of 3, kindly start again",
               "msg_type" => "quick_reply",
               "user_code" => $response->user_code,
               "parent_param" => null,
               "quick_replies" => null,
               // "buttons" => [],
               // "use_cache" => true,
               // "reply_internal" => true,
               // "action" =>  $response->action,
               // "intent_id" => $user['intent_id']
           ]);
       }
   }
   
                       break;
   
                       case "password_confirm_doctor":
   
                       $password_confirmation = $response->query;
                       $firstname = $parent_param['firstname'];
                       $lastname = $parent_param['lastname'];
                       $email = $parent_param['email'];
                       $phone = $parent_param['phone'];
                       $gender = $parent_param['gender'];
                       $dob = $parent_param['dob'];
                       $password = $parent_param['password'];
   
   
                       if($password_confirmation == $password){
                           //if the two passwords are simipler
   
                           //Log::debug('Similar');
   
                           $register = Http::withoutVerifying()->post('https://mw.asknello.com/api/auth/register',[
                              "firstname" => $firstname,
                              "lastname" => $lastname,
                              "email" => $email,
                              "phone" => $phone,
                              "gender" => $gender,
                              "password" => $password,
                              "password_confirmation" => $password_confirmation,
                              "dob" => $dob,
                           ]);
   
                           if($register['token']){
   
                               //if the User is Created
                               //display welcome message
   
                                $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have successfully registered on Nello, your personal healthcare assistance!!! ",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               if($responsed["status"] == "success"){
                                   //if when message sent,, ask user to provide their phone number
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Kindly Provide your phone Number registered on Nello ",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'checkauth',
                                      
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               }
   
                               
   
   
                               
   
                               
   
                           }
                       }
                       elseif($password_confirmation != $password) {
                           //If the Two Password are not similar
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Passwords not matched, Try again.",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_password_doctor',
                                  'firstname' => $firstname,
                                  'lastname' => $lastname,
                                  'email' => $email,
                                  'phone' => $phone,
                                  'gender' => $gender,
                                  'dob' => $dob,
       
                               ],
                               "quick_replies" => [],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
                       }
   
   
   
                       //confirm password 
   
                       break;
   
                       case "paymentcompleted":
   
   
                           //do this
       
                           $input = $response->query;
                           
                           $temp_id = $parent_param['temp_id'];
       
                           $reason = $parent_param['reason'];
                           $user_data = $parent_param['user_data'];
                           $appointment_specialization = $parent_param['appointment_specialization'];
       
                           $doc_docss = $parent_param['doc_docss'];
       
       
       
                           $count = Count::first();
                           $count = $count->count;
       
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
       
       
       if($input){
       
       if($count < 3){
       $update = Count::first()->update([
           'count' => $count + 1
       ]);
       $doc_id = $doc_docss['id'];
       $date = $doc_docss['date'];
       // $time = $selected_time;
       $phone = $user_data['phone'];
       $uuid = $doc_docss['uuid'];
       $fee = $doc_docss['fee'];
       $reasons = $reason;
   
       $money = User::where('uuid',$uuid)->value('fee');
       $name = User::where('uuid',$uuid)->value('firstname');
   
       // Log::debug($name);
       // Log::debug($money);
   
       $message = 'Proceed to make payment of '. ' ₦ '.$money . ' as consultation fee. Please note confirmation will be sent via mail after payment is verified.';
       //render button again
       
       $responsed = Http::withoutVerifying()->withHeaders([
           'token'=>$token,
           
       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
           "platform" => $user['platform'],
           "agent_id" => $user['agent_id'],
           "message" => $message,
           "msg_type" => "link",
           "user_code" => $response->user_code,
           "parent_param" => [
               'next_step' => 'paymentcompleted',
               'user_data' => $user_data,
               'appointment_specialization' => $appointment_specialization,
               'reason'=> $reason,
               'doc_docss' => $doc_docss,
               'temp_id' => $temp_id,
       
           ],
           "quick_replies" => [
               
       
           ],
           "buttons" => [
               [
                   "url" => "https://mw.asknello.com/servicepay/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&temp_id=".$temp_id."&cost=".$doc_docss['fee']."&email=".$user_data['email'],
                   "title" => "Make Payment"
               ]
           ],
           "use_cache" => true,
           "reply_internal" => true,
           "action" =>  $response->action,
           "intent_id" => $user['intent_id']
       ]);
       
       
       
       
       
       }
       
       else{
       $update = Count::first()->update([
           'count' => 1
       ]);
       
       //Back to menu
       
       $responsed = Http::withoutVerifying()->withHeaders([
           'token'=> $token,
           // Back to menu
       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
           "platform" => $user['platform'],
           "agent_id" => $user['agent_id'],
           "message" => "You have exceeded you error limits of 3, kindly start again",
           "msg_type" => "quick_reply",
           "user_code" => $response->user_code,
           "parent_param" => null,
           "quick_replies" => null,
           // "buttons" => [],
           // "use_cache" => true,
           // "reply_internal" => true,
           // "action" =>  $response->action,
           // "intent_id" => $user['intent_id']
       ]);
       }
       }
       
       
       
                           
                          
       
        break;
   
   
                   default:
                     echo "Your favorite color is neither red, blue, nor green!";
                 }
   
           } //when Parent param nont null
   
   
   
       }
   
   
       //check if action is equal to Book FACILITY VISIT
   
       elseif($response->action == "book.a.facility.visit"){
           // Log::debug($user);
           //check if param para is null,,, that means the first  aspect of the webhook message
           if(empty($user['parent_param'])){
               //if($response->parameter->isEmpty()){  
               //SEND FIRST RESPONSE BACK TO USER
   
               // Log::debug('Parent parma is empty');
   
                   /// Check if the user identifer exists on Embanqo
   
                   // if(property_exists((object)$user, 'identifier')){
                   //     Log::debug('exists');
                   // }
   
                   // else{
                   //     Log::debug('not exists');
                   // }
   
                   if(property_exists((object)$user, 'identifier') && $user['identifier'] != null){
                       /////////
                       $userr = User::where('phone',$user['identifier'])->first();
                                        $firstname = User::where('phone',$user['identifier'])->value('firstname');
   
                                        $update = Count::first()->update([
                                           'count' => 1
                                       ]);
                                       
   
                                   
                                       //do this process to booking appointment
                                       
                                       //check if the user is registered on embanqo
   
                                       $user_string =  serialize($userr);
   
                                       // $responsed = Http::withoutVerifying()->withHeaders([
                                       //     'token'=>$token,
                                           
                                       // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       //     "platform" => $user['platform'],
                                       //     "agent_id" => $user['agent_id'],
                                       //     "message" => "Welcome ".$firstname .",  Be rest assured that the information provided will be handled with the utmost confidentiality",
                                       //     "msg_type" => "text",
                                       //     "user_code" => $response->user_code,
                                       //     "parent_param" => null,
                                       //     "quick_replies" => [],
                                       //     "buttons" => [],
                                       //     "use_cache" => true,
                                       //     "reply_internal" => true,
                                       //     "action" =>  $response->action,
                                       //     "intent_id" => $user['intent_id']
                                       // ]);
                                       
   
                                       $med_specialization = MedSchedule::distinct()->get(['specialization']);
   
   
                                       
                                       
                                       
   
                                           //send another bot message
                                           
   
                                           //  //get request to get all specialization on Nello
                                           //  $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');
   
                                           //  $resjson = $response_spec->json();
   
                                           //Log::debug($resjson);
                                           $specialization = [];
   
                                           foreach($med_specialization  as $med_spec){
                                               $specialization[] = [
                                                   "content_type" => "text",
                                                   "title" => $med_spec["specialization"],
                                                   "payload" => $med_spec["specialization"],
                                                   "image_url" =>  null
                                               ];
                                           } 
                                           
                                           // Log::debug($specialization);
   
                                           $specialization[] = [
                                               "content_type" => "text",
                                               "title" => "Start Again",
                                               "payload" => "Cancel",
                                               "image_url" =>  null
                                           ];
                                           
                                       
   
                                       
                                       
   
   
   
   
                                       
   
                                           $responsed = Http::withoutVerifying()->withHeaders([
                                               'token'=>$token,
                                               
                                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                               "platform" => $user['platform'],
                                               "agent_id" => $user['agent_id'],
                                               "message" => "Please choose what type of care that you’d like to receive ",
                                               "msg_type" => "quick_reply",
                                               "user_code" => $response->user_code,
                                               "parent_param" => [
                                                   'next_step' => 'getstate_facility',
                                                   'user_data' => $userr,
   
                                               ],
                                               "quick_replies" => $specialization,
                                                   
                                                   
                                               "buttons" => [],
                                               "use_cache" => true,
                                               "reply_internal" => true,
                                               "action" =>  $response->action,
                                               "intent_id" => $user['intent_id']
                                           ]);
                                           
                                           
                                           // Log::debug($responsed);
                               
                                           
                                        // end of if first message is successful
                           
                                       
   
   
   
                                   // end of if user ex
   
                       //////
                   }
   
   
                   //End of Check User Identierfier
   
                   else {
                       $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "Kindly Provide your phone Number registered on Nello ",
                           "msg_type" => "text",
                           "user_code" => $response->user_code,
                           "parent_param" => [
                               'next_step' => 'checkauth_facility',
                           ],
                           "quick_replies" => [],
                           "buttons" => [],
                           "use_cache" => true,
                           "reply_internal" => true,
                           "action" =>  $response->action,
                           "intent_id" => $user['intent_id']
                       ]);
                   }
   
   
               // $responsed = Http::withoutVerifying()->withHeaders([
               //     'token'=>$token,
                   
               // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
               //     "platform" => $user['platform'],
               //     "agent_id" => $user['agent_id'],
               //     "message" => "Kindly Provide your phone Number registered on Nello ",
               //     "msg_type" => "text",
               //     "user_code" => $response->user_code,
               //     "parent_param" => [
               //         'next_step' => 'checkauth_facility',
               //     ],
               //     "quick_replies" => [],
               //     "buttons" => [],
               //     "use_cache" => true,
               //     "reply_internal" => true,
               //     "action" =>  $response->action,
               //     "intent_id" => $user['intent_id']
               // ]);
               
               
               // Log::debug($responsed);
   
               //END OF FIRST RESPONSE
   
           }
   
           //end of when parent param is null
   
           else {
   
   
           //when parent param is not null
   
            // $param = $response->parent_param;
   
               // $parent_param = json_encode($param);
               // $res = json_decode($response);
               $parent_param=json_decode(($response->parent_param), true);
               // Log::debug($parent_param['next_step']);
               $phone = $response->query;
               // Log::debug($query);
   
               // Use Switch Case.
   
               switch ($parent_param['next_step']) {
                   case "checkauth_facility":
   
                
                       
   
                       $count = Count::first();
                       $count = $count->count;
   
   
                                   //do this
   
                                   //check if user with phone number exist on Nello
   
                                   if(preg_match('/^[0-9]{11}+$/', $phone)){
                                        $userr = User::where('phone',$phone)->first();
                                        $firstname = User::where('phone',$phone)->value('firstname');
                                       $lastname = User::where('phone',$phone)->value('lastname');
                                       $email =  User::where('phone',$phone)->value('email');
                                        $update = Count::first()->update([
                                           'count' => 1
                                       ]);
                                       
   
                                   if($userr){
                                       //do this process to booking appointment
                                       
                                       //check if the user is registered on embanqo
   
                                       $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/user',[
                                          
                                   
                                           "email" => $email,
                                      "identifier" => $phone,
                                       "name" => $firstname ." ". $lastname,
                                      "user_type" => "user",
                                      "phone" => $phone,
                                      "user_code" => $response->user_code,
                                      "platform" => $user['platform'],
                                      
                                        "agent_id" => $user['agent_id'],
                                        "meta" => null
                                   
                                       ]);
                                   
                                       // Log::debug($responsed);
                                       //
               
                                       if($responsed['status'] == 'success'){
                                           $user_string =  serialize($userr);
   
                                           // $responsed = Http::withoutVerifying()->withHeaders([
                                           //     'token'=>$token,
                                               
                                           // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           //     "platform" => $user['platform'],
                                           //     "agent_id" => $user['agent_id'],
                                           //     "message" => "Welcome ".$firstname .",  Be rest assured that the information provided will be handled with the utmost confidentiality",
                                           //     "msg_type" => "text",
                                           //     "user_code" => $response->user_code,
                                           //     "parent_param" => null,
                                           //     "quick_replies" => [],
                                           //     "buttons" => [],
                                           //     "use_cache" => true,
                                           //     "reply_internal" => true,
                                           //     "action" =>  $response->action,
                                           //     "intent_id" => $user['intent_id']
                                           // ]);
                                           
       
                                           $med_specialization = MedSchedule::distinct()->get(['specialization']);
       
                                           
                                           
   
                                               //send another bot message
                                               
       
                                               //  //get request to get all specialization on Nello
                                               //  $response_spec = Http::get('http://mw.asknello.com/api/doctors/specializations');
       
                                               //  $resjson = $response_spec->json();
       
                                               //Log::debug($resjson);
                                               $specialization = [];
       
                                               foreach($med_specialization  as $med_spec){
                                                   $specialization[] = [
                                                       "content_type" => "text",
                                                       "title" => $med_spec["specialization"],
                                                       "payload" => $med_spec["specialization"],
                                                       "image_url" =>  null
                                                   ];
                                               } 
                                               
                                               // Log::debug($specialization);
       
                                               $specialization[] = [
                                                   "content_type" => "text",
                                                   "title" => "Start Again",
                                                   "payload" => "Cancel",
                                                   "image_url" =>  null
                                               ];
                                               
                                           
       
                                           
                                           
       
       
       
       
                                           
       
                                               $responsed = Http::withoutVerifying()->withHeaders([
                                                   'token'=>$token,
                                                   
                                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                                   "platform" => $user['platform'],
                                                   "agent_id" => $user['agent_id'],
                                                   "message" => "Please choose what type of care that you’d like to receive ",
                                                   "msg_type" => "quick_reply",
                                                   "user_code" => $response->user_code,
                                                   "parent_param" => [
                                                       'next_step' => 'getstate_facility',
                                                       'user_data' => $userr,
       
                                                   ],
                                                   "quick_replies" => $specialization,
                                                       
                                                       
                                                   "buttons" => [],
                                                   "use_cache" => true,
                                                   "reply_internal" => true,
                                                   "action" =>  $response->action,
                                                   "intent_id" => $user['intent_id']
                                               ]);
                                               
                                               
                                               // Log::debug($responsed);
                                   
                                               
                                            // end of if first message is successful
                               
                                           
                                       }
   
                                       // $user_string =  serialize($userr);
   
                                       // $responsed = Http::withoutVerifying()->withHeaders([
                                       //     'token'=>$token,
                                           
                                       // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       //     "platform" => $user['platform'],
                                       //     "agent_id" => $user['agent_id'],
                                       //     "message" => "Welcome ".$firstname .",  Be rest assured that the information provided will be handled with the utmost confidentiality",
                                       //     "msg_type" => "text",
                                       //     "user_code" => $response->user_code,
                                       //     "parent_param" => null,
                                       //     "quick_replies" => [],
                                       //     "buttons" => [],
                                       //     "use_cache" => true,
                                       //     "reply_internal" => true,
                                       //     "action" =>  $response->action,
                                       //     "intent_id" => $user['intent_id']
                                       // ]);
                                       
   
                                       // $med_specialization = MedSchedule::distinct()->get(['specialization']);
   
   
                                       
                                       
                                       
                                      
                                       
   
   
   
                                   } // end of if user exists in the database
   
                               else{
                                      
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Phone Number not registered on Nello, to complete your registration, I will need some of your basic information. ",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => [],
                                           
                                           
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
   
                                   if($responsed['status'] == 'success'){
                                   // Log::debug($responsed['status']);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => " To start, please provide your firstname ",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           "next_step" => "lastname_facility"
                                       ],
                                       "quick_replies" => [],
                                           
                                           
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   }
   
                                   
   
                            }
                                   }
   
                                   else{
                                       //not valid
   
                                       if($count < 3){
   
                                           $update = Count::first()->update([
                                               'count' => $count + 1
                                           ]);
   
                                           $responsed = Http::withoutVerifying()->withHeaders([
                                               'token'=> $token,
                                               
                                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                               "platform" => $user['platform'],
                                               "agent_id" => $user['agent_id'],
                                               "message" => "Invalid phone number, kindly input a correct phone number ",
                                               "msg_type" => "text",
                                               "user_code" => $response->user_code,
                                               "parent_param" => [
                                                   'next_step' => 'checkauth_facility',
                                               ],
                                               "quick_replies" => [],
                                               "buttons" => [],
                                               "use_cache" => true,
                                               "reply_internal" => true,
                                               "action" =>  $response->action,
                                               "intent_id" => $user['intent_id']
                                           ]);
                   
                                       }
   
                                       else {
                                           $update = Count::first()->update([
                                               'count' => 1
                                           ]);
                   
                                           $responsed = Http::withoutVerifying()->withHeaders([
                                               'token'=> $token,
                                               // Back to menu
                                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                               "platform" => $user['platform'],
                                               "agent_id" => $user['agent_id'],
                                               "message" => "You have exceeded you error limits of 3, kindly start again",
                                               "msg_type" => "quick_reply",
                                               "user_code" => $response->user_code,
                                               "parent_param" => null,
                                               "quick_replies" => null,
                                               // "buttons" => [],
                                               // "use_cache" => true,
                                               // "reply_internal" => true,
                                               // "action" =>  $response->action,
                                               // "intent_id" => $user['intent_id']
                                           ]);
                                       }
   
                                      
                   
                                           //count 
                                         
                                        
                   
                                       
                                       
                                   }
   
                          
   
   
                   break;
   
                   case "getstate_facility":
   
                   //do something to get state
   
                   $caretype = $response->query;
                   
   
                       //parent params data
                    $user_data = $parent_param['user_data'];
   
   
                    $options = json_decode($user['options_temp'], true);
   
                      
                          
                    $count = Count::first();
                    $count = $count->count;
   
                    ///////////////Option Temps///////////////////////////
   
                    if(in_array($caretype,array_column($options,'value'))){
                              
   
                       //update count back to one
                       $update = Count::first()->update([
                           'count' => 1
                       ]);
   
   
                       ////////////////////////////// if exists in option_temps
   
                            //call the state api 
   
                    $response_state = Http::get('https://admin.asknello.com/api/getstates');
   
                    $resjson = $response_state->json();
                    // Log::debug($resjson);
    
                    // Log::debug(count($resjson));
    
                    //when the state is only Lagos
    
                    if(count($resjson) > 0) {
    
                        //Log::debug($resjson[0]['state']);
    
                        $state = $resjson[0]['state'];
    
                        //call the get Location api
    
                        $response_location = Http::get('https://admin.asknello.com/api/getlocation?state='.$state.'&specialization='.$caretype);
    
                        $resjsonlocation = $response_location->json();
    
                        $location = [];
    
                        foreach($resjsonlocation as $facility_location){
                            $location[] = [
                                "content_type" => "text",
                                "title" => $facility_location["lga"],
                                "payload" => $facility_location["lga"],
                                "image_url" =>  null
                            ];
                        } 
                        
                        $location[] = [
                            "content_type" => "text",
                                "title" => "Cancel",
                                "payload" => "Cancel",
                                "image_url" =>  null
                        ];
    
                        $responsed = Http::withoutVerifying()->withHeaders([
                            'token'=>$token,
                            
                        ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                            "platform" => $user['platform'],
                            "agent_id" => $user['agent_id'],
                            "message" => "Please choose your preferred location in ".$state. " for this visit.",
                            "msg_type" => "quick_reply",
                            "user_code" => $response->user_code,
                            "parent_param" => [
                                'next_step' => 'get_facility',
                                'user_data' => $user_data,
                                'care_type' => $caretype,
                                'state' => $state,
                               
   
                            ],
                            "quick_replies" => $location,
                                
                                  
                            "buttons" => [],
                            "use_cache" => true,
                            "reply_internal" => true,
                            "action" =>  $response->action,
                            "intent_id" => $user['intent_id']
                        ]);
                        
                        
                        // Log::debug($responsed);
                         
    
    
                       
    
                        
    
                    }
    
                    else {
                        //when the state is more than 1
                    }
    
                     
    
                       ///////////////////////
   
                    }
   
                    else{
                         //if count is lesser than 3
                         if($count < 3){
                           $update = Count::first()->update([
                               'count' => $count + 1
                           ]);
   
   
                           $userr = User::where('phone',$user['identifier'])->first();
                           $med_specialization = MedSchedule::distinct()->get(['specialization']);
   
   
   
                           $specialization = [];
   
                           foreach($med_specialization  as $med_spec){
                               $specialization[] = [
                                   "content_type" => "text",
                                   "title" => $med_spec["specialization"],
                                   "payload" => $med_spec["specialization"],
                                   "image_url" =>  null
                               ];
                           } 
                           
                           // Log::debug($specialization);
   
                           $specialization[] = [
                               "content_type" => "text",
                               "title" => "Start Again",
                               "payload" => "Cancel",
                               "image_url" =>  null
                           ];
                           
                       
   
                       
                       
   
   
   
   
                       
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Kindly choose the care type from the below options ",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'getstate_facility',
                                   'user_data' => $userr,
   
                               ],
                               "quick_replies" => $specialization,
                                   
                                   
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
                           
   
   
   
   
                       }
   
                       else{
                           //Back to menu
   
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
   
   
   
                       }
                    }
   
                    ////////////////////////////////////////////////
   
   
   
   
   
   
   
   
                   //  //call the state api 
   
                   //  $response_state = Http::get('https://admin.asknello.com/api/getstates');
   
                   // $resjson = $response_state->json();
                   // // Log::debug($resjson);
   
                   // // Log::debug(count($resjson));
   
                   // //when the state is only Lagos
   
                   // if(count($resjson) > 0) {
   
                   //     //Log::debug($resjson[0]['state']);
   
                   //     $state = $resjson[0]['state'];
   
                   //     //call the get Location api
   
                   //     $response_location = Http::get('https://admin.asknello.com/api/getlocation?state='.$state.'&specialization='.$caretype);
   
                   //     $resjsonlocation = $response_location->json();
   
                   //     $location = [];
   
                   //     foreach($resjsonlocation as $facility_location){
                   //         $location[] = [
                   //             "content_type" => "text",
                   //             "title" => $facility_location["lga"],
                   //             "payload" => $facility_location["lga"],
                   //             "image_url" =>  null
                   //         ];
                   //     } 
                       
                   //     $location[] = [
                   //         "content_type" => "text",
                   //             "title" => "Cancel",
                   //             "payload" => "Cancel",
                   //             "image_url" =>  null
                   //     ];
   
                   //     $responsed = Http::withoutVerifying()->withHeaders([
                   //         'token'=>$token,
                           
                   //     ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                   //         "platform" => $user['platform'],
                   //         "agent_id" => $user['agent_id'],
                   //         "message" => "Please choose your preferred location in ".$state. " for this visit.",
                   //         "msg_type" => "quick_reply",
                   //         "user_code" => $response->user_code,
                   //         "parent_param" => [
                   //             'next_step' => 'get_facility',
                   //             'user_data' => $user_data,
                   //             'care_type' => $caretype,
                   //             'state' => $state,
   
                   //         ],
                   //         "quick_replies" => $location,
                               
                                 
                   //         "buttons" => [],
                   //         "use_cache" => true,
                   //         "reply_internal" => true,
                   //         "action" =>  $response->action,
                   //         "intent_id" => $user['intent_id']
                   //     ]);
                       
                       
                   //     // Log::debug($responsed);
                        
   
   
                      
   
                       
   
                   // }
   
                   // else {
                   //     //when the state is more than 1
                   // }
   
                    
   
   
   
                   break;
   
                   case "get_facility":
   
                       //do this for get facility
   
                   
                       $location = $response->query;
   
                   //parent params data
                    $user_data = $parent_param['user_data'];
                    $care_type = $parent_param['care_type'];
                    $state = $parent_param['state'];
   
                    $options = json_decode($user['options_temp'], true);
   
                      
                          
                    $count = Count::first();
                    $count = $count->count;
   
                 
   
                  
   
   
                    ///////////////////////////Check Option Temps////////////////
   
                    if(in_array($location,array_column($options,'value'))){
                              
   
                       //update count back to one
                       $update = Count::first()->update([
                           'count' => 1
                       ]);
   
   
                       /////////////////////If everything checks //////////////////
                        //get the specialization api
   
                    $response_facility = Http::get('https://admin.asknello.com/api/getfacilities',[
                       'state' => $state,
                       'location' => $location,
                       'specialization' => $care_type
   
                   ]);
   
   
                   //end of specialization api
   
                   $response_facility= $response_facility->json();
   
                  //  Log::debug($response_facility);
   
                   if(count($response_facility) < 1){
                      //  Log::debug(ount($response_facility));
                   }
   
   
                   //facility array
   
                   $facility = [];
   
                   foreach($response_facility['facilities'] as $facilities){
                       $facility[] = [
                           "content_type" => "text",
                           "title" => $facilities["name"],
                           "payload" => $facilities["uuid"],
                           "image_url" =>  null
                       ];
                   } 
   
                   $facility[] = [
                      "content_type" => "text",
                      "title" => "Cancel",
                      "payload" => "Cancel",
                      "image_url" =>  null
                   ];
   
   
                   $responsed = Http::withoutVerifying()->withHeaders([
                      'token'=>$token,
                      
                  ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                      "platform" => $user['platform'],
                      "agent_id" => $user['agent_id'],
                      "message" => "Please select which facility that you’d like to visit in ".$location . " for ".$care_type,
                      "msg_type" => "quick_reply",
                      "user_code" => $response->user_code,
                      "parent_param" => [
                          'next_step' => 'reason_facility',
                          'user_data' => $user_data,
                          'care_type' => $care_type,
                          'state' => $state,
                          'location' =>$location,
   
                      ],
                      "quick_replies" => $facility,
                          
                            
                      "buttons" => [],
                      "use_cache" => true,
                      "reply_internal" => true,
                      "action" =>  $response->action,
                      "intent_id" => $user['intent_id']
                  ]);
                  
                  
                  // Log::debug($responsed);
   
                   
   
   
                       ///////////////////End of if everything checks//////////////
   
   
                    }
   
                    else {
                       if($count < 3){
                           $update = Count::first()->update([
                               'count' => $count + 1
                           ]);
   
   
                           ////////////////////Error to re render
   
                                    //call the state api 
   
                    $response_state = Http::get('https://admin.asknello.com/api/getstates');
   
                    $resjson = $response_state->json();
                    // Log::debug($resjson);
    
                    // Log::debug(count($resjson));
    
                    //when the state is only Lagos
    
                    if(count($resjson) > 0) {
    
                        //Log::debug($resjson[0]['state']);
    
                        $state = $resjson[0]['state'];
    
                        //call the get Location api
    
                        $response_location = Http::get('https://admin.asknello.com/api/getlocation?state='.$state.'&specialization='.$care_type);
    
                        $resjsonlocation = $response_location->json();
    
                        $location = [];
    
                        foreach($resjsonlocation as $facility_location){
                            $location[] = [
                                "content_type" => "text",
                                "title" => $facility_location["lga"],
                                "payload" => $facility_location["lga"],
                                "image_url" =>  null
                            ];
                        } 
                        
                        $location[] = [
                            "content_type" => "text",
                                "title" => "Cancel",
                                "payload" => "Cancel",
                                "image_url" =>  null
                        ];
    
                        $responsed = Http::withoutVerifying()->withHeaders([
                            'token'=>$token,
                            
                        ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                            "platform" => $user['platform'],
                            "agent_id" => $user['agent_id'],
                            "message" => "Kindly choose your preferred location in ".$state. " for this visit, from the below options",
                            "msg_type" => "quick_reply",
                            "user_code" => $response->user_code,
                            "parent_param" => [
                                'next_step' => 'get_facility',
                                'user_data' => $user_data,
                                'care_type' => $care_type,
                                'state' => $state,
                               
   
                            ],
                            "quick_replies" => $location,
                                
                                  
                            "buttons" => [],
                            "use_cache" => true,
                            "reply_internal" => true,
                            "action" =>  $response->action,
                            "intent_id" => $user['intent_id']
                        ]);
                        
                        
                        // Log::debug($responsed);
                         
    
    
                       
    
                        
    
                    }
   
                           ////////////////////////////
   
                       }
   
                       else{
                           //Back to menu
                            //update count back to one
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
   
   
   
   
                       }
                    }
   
   
   
                    //////////////////////End of Check option Temps ///////////////
   
   
   
   
   
   
   
   
   
                   //  //get the specialization api
   
                   //  $response_facility = Http::get('https://admin.asknello.com/api/getfacilities',[
                   //      'state' => $state,
                   //      'location' => $location,
                   //      'specialization' => $care_type
   
                   //  ]);
   
   
                   //  //end of specialization api
   
                   //  $response_facility= $response_facility->json();
   
                   // //  Log::debug($response_facility);
   
                   //  if(count($response_facility) < 1){
                   //     //  Log::debug(ount($response_facility));
                   //  }
   
   
                   //  //facility array
   
                   //  $facility = [];
   
                   //  foreach($response_facility['facilities'] as $facilities){
                   //      $facility[] = [
                   //          "content_type" => "text",
                   //          "title" => $facilities["name"],
                   //          "payload" => $facilities["uuid"],
                   //          "image_url" =>  null
                   //      ];
                   //  } 
   
                   //  $facility[] = [
                   //     "content_type" => "text",
                   //     "title" => "Cancel",
                   //     "payload" => "Cancel",
                   //     "image_url" =>  null
                   //  ];
   
   
                   //  $responsed = Http::withoutVerifying()->withHeaders([
                   //     'token'=>$token,
                       
                   // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                   //     "platform" => $user['platform'],
                   //     "agent_id" => $user['agent_id'],
                   //     "message" => "Please select which facility that you’d like to visit in ".$location . " for ".$care_type,
                   //     "msg_type" => "quick_reply",
                   //     "user_code" => $response->user_code,
                   //     "parent_param" => [
                   //         'next_step' => 'reason_facility',
                   //         'user_data' => $user_data,
                   //         'care_type' => $care_type,
                   //         'state' => $state,
                   //         'location' =>$location,
   
                   //     ],
                   //     "quick_replies" => $facility,
                           
                             
                   //     "buttons" => [],
                   //     "use_cache" => true,
                   //     "reply_internal" => true,
                   //     "action" =>  $response->action,
                   //     "intent_id" => $user['intent_id']
                   // ]);
                   
                   
                   // // Log::debug($responsed);
   
                    
   
   
   
   
                   
                   break;
   
   
                   case "reason_facility":
   
                       //do something reason
   
                       $facility_uuid = $response->query;
   
   
                       
   
   
                       //parent params data
                        $user_data = $parent_param['user_data'];
                        $care_type = $parent_param['care_type'];
                        $state = $parent_param['state'];
                        $location = $parent_param['location'];
   
   
                        $options = json_decode($user['options_temp'], true);
   
                      
                          
                        $count = Count::first();
                        $count = $count->count;
       
                     
       
                      
       
       
                        ///////////////////////////Check Option Temps////////////////
       
                        if(in_array($facility_uuid,array_column($options,'value'))){
                                  
       
                           //update count back to one
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
   
                           /////////////When all checkes
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Please input the reason for the appointment:",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'date_facility',
                                   'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' =>$location,
                                   'facility_uuid' =>$facility_uuid
           
                               ],
                               "quick_replies" => [],
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
                           
                           // Log::debug($responsed);
       
       
                           
   
                           //////////////////////
   
                        }
   
   
                        else{
   
                           if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               ////////Error to Render
   
                                   //get the specialization api
   
                    $response_facility = Http::get('https://admin.asknello.com/api/getfacilities',[
                       'state' => $state,
                       'location' => $location,
                       'specialization' => $care_type
   
                   ]);
   
   
                   //end of specialization api
   
                   $response_facility= $response_facility->json();
   
                  //  Log::debug($response_facility);
   
                   if(count($response_facility) < 1){
                      //  Log::debug(ount($response_facility));
                   }
   
   
                   //facility array
   
                   $facility = [];
   
                   foreach($response_facility['facilities'] as $facilities){
                       $facility[] = [
                           "content_type" => "text",
                           "title" => $facilities["name"],
                           "payload" => $facilities["uuid"],
                           "image_url" =>  null
                       ];
                   } 
   
                   $facility[] = [
                      "content_type" => "text",
                      "title" => "Cancel",
                      "payload" => "Cancel",
                      "image_url" =>  null
                   ];
   
   
                   $responsed = Http::withoutVerifying()->withHeaders([
                      'token'=>$token,
                      
                  ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                      "platform" => $user['platform'],
                      "agent_id" => $user['agent_id'],
                      "message" => "Kindly select which facility that you’d like to visit in ".$location . " for ".$care_type. "from below option",
                      "msg_type" => "quick_reply",
                      "user_code" => $response->user_code,
                      "parent_param" => [
                          'next_step' => 'reason_facility',
                          'user_data' => $user_data,
                          'care_type' => $care_type,
                          'state' => $state,
                          'location' =>$location,
   
                      ],
                      "quick_replies" => $facility,
                          
                            
                      "buttons" => [],
                      "use_cache" => true,
                      "reply_internal" => true,
                      "action" =>  $response->action,
                      "intent_id" => $user['intent_id']
                  ]);
                  
                  
   
   
                               ///////////////
   
                           }
   
   
                           else {
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
   
   
       
   
                           }
   
   
   
                        }
   
                        ////////////////////////////////////////////////////
       
   
                       //  $responsed = Http::withoutVerifying()->withHeaders([
                       //     'token'=>$token,
                           
                       // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //     "platform" => $user['platform'],
                       //     "agent_id" => $user['agent_id'],
                       //     "message" => "Please input the reason for the appointment:",
                       //     "msg_type" => "text",
                       //     "user_code" => $response->user_code,
                       //     "parent_param" => [
                       //         'next_step' => 'date_facility',
                       //         'user_data' => $user_data,
                       //         'care_type' => $care_type,
                       //         'state' => $state,
                       //         'location' =>$location,
                       //         'facility_uuid' =>$facility_uuid
       
                       //     ],
                       //     "quick_replies" => [],
                               
                                 
                       //     "buttons" => [],
                       //     "use_cache" => true,
                       //     "reply_internal" => true,
                       //     "action" =>  $response->action,
                       //     "intent_id" => $user['intent_id']
                       // ]);
                       
                       
                       // // Log::debug($responsed);
   
   
                       
   
                   
   
                   break;
   
                   case "date_facility":
   
                   //do something
   
                   $count = Count::first();
   
                   $count = $count->count;
                   
                       $reason = $response->query;
                  
   
                   
   
                   //parent params data
                    $user_data = $parent_param['user_data'];
                    $care_type = $parent_param['care_type'];
                    $state = $parent_param['state'];
                    $location = $parent_param['location'];
                    $facility_uuid = $parent_param['facility_uuid'];
   
                    //validate reason here
   
                    if(preg_match("/^([a-zA-Z' ]+)$/",$reason)){
                        $updated = Count::first()->update([
                            'count' => 1
                        ]);
   
                    $responsed = Http::withoutVerifying()->withHeaders([
                       'token'=>$token,
                       
                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       "platform" => $user['platform'],
                       "agent_id" => $user['agent_id'],
                       "message" => "Please provide your preferred date for the appointment i.e 20/09/2022",
                       "msg_type" => "text",
                       "user_code" => $response->user_code,
                       "parent_param" => [
                           'next_step' => 'validate_date_facility',
                           'user_data' => $user_data,
                           'care_type' => $care_type,
                           'state' => $state,
                           'location' => $location,
                           'facility_uuid' => $facility_uuid,
                           'reason' => $reason
   
                       ],
                       "quick_replies" => [],
                           
                             
                       "buttons" => [],
                       "use_cache" => true,
                       "reply_internal" => true,
                       "action" =>  $response->action,
                       "intent_id" => $user['intent_id']
                   ]);
                    }
                    else{
                       //Log::debug('alphabet Not valid');
   
                       if($count < 3){
   
                           $updated = Count::first()->update([
                               'count' => $count + 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Invalid reason , enter a valid reason ",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'date_facility',
                                   'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' =>$location,
                                   'facility_uuid' =>$facility_uuid
           
                               ],
                               "quick_replies" => [],
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
                       }
   
                       else{
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
   
                       }
                    }
   
   
   
   
   
   
   
                   break;
   
   
                   case "validate_date_facility":
   
                   //validate user inputed date
   
                   $count = Count::first();
                   $count = $count->count;
   
   
                   $date = $response->query;
   
                   //parent params data
                    $user_data = $parent_param['user_data'];
                    $care_type = $parent_param['care_type'];
                    $state = $parent_param['state'];
                    $location = $parent_param['location'];
                    $facility_uuid = $parent_param['facility_uuid'];
                    $reason = $parent_param['reason'];
   
   
                    if(preg_match("/^([a-zA-Z' ]+)$/",$date) || preg_match('/-/', $date) || ctype_alnum($date)){
                       $updated = Count::first()->update([
                           'count' => 1
                       ]);
   
                       ///
   
                       if($count < 3){
                           $updated = Count::first()->update([
                               'count' => $count + 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Invalid date input,  provide your preferred date for the appointment in format i.e 20/09/2022",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_date_facility',
                                   'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' => $location,
                                   'facility_uuid' => $facility_uuid,
                                   'reason' => $reason
           
                               ],
                               "quick_replies" => [],
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
   
                          
   
                       }
   
                       else{
                           //Back to Menu
   
                           $updated = Count::first()->update([
                               'count' => 1
                           ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
                       }
   
                   }
   
   
   
   
   
                    $date = Carbon::createFromFormat('d/m/Y', $date)->format('d-m-Y');
   
                    $response_available = Http::get('https://admin.asknello.com/api/checkavailability',[
                       "specialization" => $care_type,
                       "date" => $date,
                       "uuid" => $facility_uuid
                   ]);
   
                   $response_available = $response_available->json();
   
   
                    //check if date is passed date
                   
                    if($response_available["status"] == "failed"){
                        //if error with date format or passed date
   
                        $updated = Count::first()->update([
                            'count' => $count + 1
                        ]);
   
                        if($count < 3){
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid or past date ",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'redate_facility',
                                       'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' => $location,
                                   'facility_uuid' => $facility_uuid,
                                   'reason' => $reason,
                                   
   
                                   ],
                               
                                   "quick_replies" => [
                                       [
                                           "content_type" => "text",
                                           "title" => "Search Again",
                                           "payload" => "Search Again",
                                           "image_url" =>  null
                                       ],
                                   
                                       [
                                           "content_type" => "text",
                                           "title" => "Cancel",
                                           "payload" => "Cancel",
                                           "image_url" =>  null
                                       ],
   
                                       [
                                           "content_type" => "text",
                                           "title" => "Chat Support",
                                           "payload" => "Chat Support",
                                           "image_url" =>  null
                                       ],
   
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                        }
   
                        else {
                           $updated = Count::first()->update([
                               'count' => 1
                           ]);
   
                           //date back
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=> $token,
                               // Back to menu
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "You have exceeded you error limits of 3, kindly start again",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => null,
                               "quick_replies" => null,
                               // "buttons" => [],
                               // "use_cache" => true,
                               // "reply_internal" => true,
                               // "action" =>  $response->action,
                               // "intent_id" => $user['intent_id']
                           ]);
                        }
   
                        
   
   
                    }
   
   
   
   
   
   
                    //end of check if date is passed date
   
   
                    //check if date is not passed but no availaility 
                    elseif($response_available["status"] == "success" && count($response_available['available'])== 0) {
                       $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "There are no time slots that match your selection. You can search for another facility or another time, or chat with a support representative who can help you book an appointment",
                           "msg_type" => "quick_reply",
                           "user_code" => $response->user_code,
                           "parent_param" => [
                               'next_step' => 'redate_facility',
                               'user_data' => $user_data,
                           'care_type' => $care_type,
                           'state' => $state,
                           'location' => $location,
                           'facility_uuid' => $facility_uuid,
                           'reason' => $reason,
                           
   
                           ],
                          
                           "quick_replies" => [
                               [
                                   "content_type" => "text",
                                   "title" => "Search Again",
                                   "payload" => "Search Again",
                                   "image_url" =>  null
                               ],
                              
                               [
                                   "content_type" => "text",
                                   "title" => "Cancel",
                                   "payload" => "Cancel",
                                   "image_url" =>  null
                               ],
   
                               [
                                   "content_type" => "text",
                                   "title" => "Chat Support",
                                   "payload" => "Chat Support",
                                   "image_url" =>  null
                               ],
   
                           ],
                           "buttons" => [],
                           "use_cache" => true,
                           "reply_internal" => true,
                           "action" =>  $response->action,
                           "intent_id" => $user['intent_id']
                       ]);
   
                    }
   
   
                    // end no availablity
   
   
   
   
                    
   
                    
   
                    //when everthing checks
   
                    elseif($response_available["status"] == "success" && count($response_available['available']) > 0) {
   
                       $updated = Count::first()->update([
                           'count' =>  1
                       ]);
   
   
                       ////////////////////////////////////
                           //checking availability 
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Please hold on for a second, while I check for available appointments",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'checkifavailable_facility_test',
                                   'user_data' => $user_data,
                               'care_type' => $care_type,
                               'state' => $state,
                               'location' => $location,
                               'facility_uuid' => $facility_uuid,
                               'reason' => $reason,
                               'searched_date' => $date,
                               
       
                               ],
                              
                               "quick_replies" => [],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
       
                           // if message went through call the Online DoctorList Api
                           if($responsed['status'] == 'success'){
                               $available = [];
                               $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
           
                               foreach($response_available['available'] as $availability){
                                   $availability_date = [
                                       "date" => $availability['date'],
                                       "time" => $availability['time'],
                                       "day" => $availability['day'],
                                       "cost" => $availability['cost']
           
                                   ];
                                   $availability_date = serialize($availability_date);
           
                                   $title = $availability['day'].' - '.$availability['date'].' - '.$availability['time'] ;
                                   $available[] = [
                                       // "content_type" => "text",
                                       // "title" => $title,
                                       // "payload" => $availability_date,
                                       // "image_url" =>  null
           
                                       "title" =>$availability['day']."(".$availability['date'].")",
                                       "description" => $centername ." - ".$care_type .", - N ".$availability['cost'],
                                       "image_url" => "https://res.cloudinary.com/edifice-solutions/image/upload/v1665572286/Wavy_Med-04_Single-10-min_t1lrrz.jpg",
                                       "suggestions" => [
                                        
                                         [
                                           "title" =>  $availability['time'],
                                           "payload" => $availability_date,
                                           "type" => "postback",
                                           "url" => null
                                         ],
                                         [
                                           "title" =>  "Start Again",
                                           "payload" => "Cancel",
                                           "type" => "postback",
                                           "url" => null
                                         ]
                                       ]
                                   ];
                               } 
           
           
                              
           
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Kindly Choose preferred time to schedule the appointment
                                   ",
                                   "msg_type" => "carousel",
                                   "user_code" => $response->user_code,
                                       "parent_param" => [
                                       'next_step' => 'checkifavailable_facility',
                                       'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' => $location,
                                   'facility_uuid' => $facility_uuid,
                                   'reason' => $reason,
                                   'searched_date' => $date,
                                   
           
                                   ],
                                   "quick_replies" => null,
                                   "buttons" => [],
                                   "use_cache" => null,
                                   "reply_internal" => true,
                                   "label" => null,
                                   "attachments" => [],
                                   "template" => null,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id'],
           
                                   "carousels" => $available,
           
                               ]);
                               
                                   
                           }
   
   
                       //////////////////////////////////
   
                       // $available = [];
                       // $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
   
                       // foreach($response_available['available'] as $availability){
                       //     $availability_date = [
                       //         "date" => $availability['date'],
                       //         "time" => $availability['time'],
                       //         "day" => $availability['day'],
                       //         "cost" => $availability['cost']
   
                       //     ];
                       //     $availability_date = serialize($availability_date);
   
                       //     $title = $availability['day'].' - '.$availability['date'].' - '.$availability['time'] ;
                       //     $available[] = [
                       //         // "content_type" => "text",
                       //         // "title" => $title,
                       //         // "payload" => $availability_date,
                       //         // "image_url" =>  null
   
                       //         "title" =>$availability['day']."(".$availability['date'].")",
                       //         "description" => $centername ." - ".$care_type .", - N ".$availability['cost'],
                       //         "image_url" => "https://res.cloudinary.com/edifice-solutions/image/upload/v1665572286/Wavy_Med-04_Single-10-min_t1lrrz.jpg",
                       //         "suggestions" => [
                                
                       //           [
                       //             "title" =>  $availability['time'],
                       //             "payload" => $availability_date,
                       //             "type" => "postback",
                       //             "url" => null
                       //           ],
                       //           [
                       //             "title" =>  "Start Again",
                       //             "payload" => "Cancel",
                       //             "type" => "postback",
                       //             "url" => null
                       //           ]
                       //         ]
                       //     ];
                       // } 
   
   
                      
   
                       // $responsed = Http::withoutVerifying()->withHeaders([
                       //     'token'=>$token,
                           
                       // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                       //     "platform" => $user['platform'],
                       //     "agent_id" => $user['agent_id'],
                       //     "message" => "Kindly Choose preferred time to schedule the appointment
                       //     ",
                       //     "msg_type" => "carousel",
                       //     "user_code" => $response->user_code,
                       //         "parent_param" => [
                       //         'next_step' => 'checkifavailable_facility',
                       //         'user_data' => $user_data,
                       //     'care_type' => $care_type,
                       //     'state' => $state,
                       //     'location' => $location,
                       //     'facility_uuid' => $facility_uuid,
                       //     'reason' => $reason,
                       //     'searched_date' => $date,
                           
   
                       //     ],
                       //     "quick_replies" => null,
                       //     "buttons" => [],
                       //     "use_cache" => null,
                       //     "reply_internal" => true,
                       //     "label" => null,
                       //     "attachments" => [],
                       //     "template" => null,
                       //     "action" =>  $response->action,
                       //     "intent_id" => $user['intent_id'],
   
                       //     "carousels" => $available,
   
                       // ]);
                       
                           
   
   
   
   
      
   
                    }
   
                    //end of when everthing checks
   
   
   
                   break;
   
                   case "redate_facility":
                   
                       //do something
   
                       
   
                       $output = $response->query;
   
                       //parent params data
                        $user_data = $parent_param['user_data'];
                        $care_type = $parent_param['care_type'];
                        $state = $parent_param['state'];
                        $location = $parent_param['location'];
                        $facility_uuid = $parent_param['facility_uuid'];
                        $reason = $parent_param['reason'];
   
                        if($output == "Chat Support"){
                            // Trigger intent to chat support
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/trigger/intent',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "instantqueue",
               
                               "user_code" => $response->user_code,
                              
                              
                           ]);
                        }
                        
                        elseif($output == "Search Again"){
   
                           //go back to date_facility
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Please provide your preferred date for the appointment i.e 20/09/2022",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_date_facility',
                                   'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' =>$location,
                                   'facility_uuid' =>$facility_uuid,
                                   'reason' => $reason,
           
                               ],
                               "quick_replies" => [],
                                   
                                     
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
   
                        }
   
   
   
                   break;
   
                   case "checkifavailable_facility":
   
                   //check if the appointment whered date and time and facility id exist
   
                   $user_data = $parent_param['user_data'];
                   $care_type = $parent_param['care_type'];
                   $state = $parent_param['state'];
                   $location = $parent_param['location'];
                   $facility_uuid = $parent_param['facility_uuid'];
                   $reason = $parent_param['reason'];
                   $searched_date = $parent_param['searched_date'];
   
                   ///////
                  
   
                   ////
   
                   $input  = $response->query;
   
   
                   $options = json_decode($user['options_temp'], true);
   
                      
                          
                    $count = Count::first();
                       $count = $count->count;
   
                       if(in_array($input,array_column($options,'value'))){
                              
   
                           //update count back to one
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                           //Do this
   
                           $availability_date = unserialize($response->query);
   
                           // Log::debug("Check down");
                           // Log::debug($availability_date);
                           // Log::debug($response->query);
           
                           $day = $availability_date['day'];
                           $time = $availability_date['time'];
                           $date = $availability_date['date'];
                           $cost = $availability_date['cost'];
           
                           
                           //check if appointment exist already
           
                           $dateformat= Carbon::parse($date)->format('Y-m-d');
           
                           $checkappointment = Appointment::where('date',$dateformat)->where('time',$time)->where('center_uuid',$facility_uuid)->first();
           
                           if($checkappointment){
           
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Seems scheduled appointment for ".$day. " the ".$date." at ".$time." has just been booked, Kindly Change time or Search Again.",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'redate_with_time_facility',
                                       'user_data' => $user_data,
                                   'care_type' => $care_type,
                                   'state' => $state,
                                   'location' => $location,
                                   'facility_uuid' => $facility_uuid,
                                   'reason' => $reason,
                                   'searched_date' => $searched_date
                                   
           
                                   ],
                                  
                                   "quick_replies" => [
           
                                       [
                                           "content_type" => "text",
                                           "title" => "Change Time",
                                           "payload" => "Change Time",
                                           "image_url" =>  null
                                       ],
           
                                       [
                                           "content_type" => "text",
                                           "title" => "Search Again",
                                           "payload" => "Search Again",
                                           "image_url" =>  null
                                       ],
                                      
                                       [
                                           "content_type" => "text",
                                           "title" => "Cancel",
                                           "payload" => "Cancel",
                                           "image_url" =>  null
                                       ],
           
                                       [
                                           "content_type" => "text",
                                           "title" => "Chat Support",
                                           "payload" => "Chat Support",
                                           "image_url" =>  null
                                       ],
           
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                               
                           }
                           // end of check if appointment exists
           
           
                           //When appointment doest exist Draft temporay appointment
           
                           else{
           
                               //draft temporary booking 
           
                               // $doc_id = $doc_docss['id'];
                               // $date = $doc_docss['date'];
                               // $time = $selected_time;
                               // $phone = $user_data['phone'];
                               // $uuid = $doc_docss['uuid'];
                               // $fee = $doc_docss['fee'];
                               // $reasons = $reason;
           
                               $date = $date;
                               $time = $time;
                               $phone = $user_data['phone'];
                               $uuid = $facility_uuid;
                               $reason = $reason;
                               $fee = $cost;
           
           
                                //draft online api
                                $drafbooking = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://admin.asknello.com/api/facilitybook',[
                                   
                                   'date'=> $date,
                                   'time' => $time,
                                   'phone' => $phone,
                                   'uuid' =>$uuid,
                                   'reason' => $reason,
                                   'fee' => $fee
                               ]);
           
           
                               // Log::debug($drafbooking['temp_id']);
           
                               if($drafbooking['temp_id']){
                                   
                                   //if the temp_id is present
           
                                   $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
           
           
                                   $temp_id = $drafbooking['temp_id'];
                                   $message = 'Hi '.$user_data['firstname']. ', kindly proceed to make payment of ₦'.$fee. ' to secure appointment on '.$day. ' the '.$date.' by '.$time.' with '.$centername ;
           
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => $message,
                                       "msg_type" => "link",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'paymentcompleted_facility',
                                           'user_data' => $user_data,
                                           'care_type' => $care_type,
                                           'state' => $state,
                                           'location' => $location,
                                           'facility_uuid' => $facility_uuid,
                                           'reason' => $reason,
                                           'temp_id'=> $temp_id,
                                           'availability_date' => $availability_date,
               
                                       ],
                                       "quick_replies" => [
                                           
           
                                       ],
                                       "buttons" => [
                                           [
                                               "url" => "https://mw.asknello.com/servicepay/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&temp_id=".$temp_id."&cost=".$fee."&email=".$user_data['email'],
                                               "title" => "Make Payment"
                                           ]
                                       ],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
           
           
                               }
           
           
                               
                           }
           
           
           
                           //end of draft temporary appointment booking
                           ///
   
                       }
   
                       else{
   
                           if($count < 3){
                               //Error to rendered
   
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $response_available = Http::get('https://admin.asknello.com/api/checkavailability',[
                                   "specialization" => $care_type,
                                   "date" => $searched_date,
                                   "uuid" => $facility_uuid
                               ]);
               
                               $response_available = $response_available->json();
   
   
                               if($response_available["status"] == "success" && count($response_available['available']) > 0) {
   
                                  
               
                                   $available = [];
                                   $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
               
                                   foreach($response_available['available'] as $availability){
                                       $availability_date = [
                                           "date" => $availability['date'],
                                           "time" => $availability['time'],
                                           "day" => $availability['day'],
                                           "cost" => $availability['cost']
               
                                       ];
                                       $availability_date = serialize($availability_date);
               
                                       $title = $availability['day'].' - '.$availability['date'].' - '.$availability['time'] ;
                                       $available[] = [
                                           // "content_type" => "text",
                                           // "title" => $title,
                                           // "payload" => $availability_date,
                                           // "image_url" =>  null
               
                                           "title" =>$availability['day']."(".$availability['date'].")",
                                           "description" => $centername ." - ".$care_type .", - N ".$availability['cost'],
                                           "image_url" => "https://res.cloudinary.com/edifice-solutions/image/upload/v1665572286/Wavy_Med-04_Single-10-min_t1lrrz.jpg",
                                           "suggestions" => [
                                            
                                             [
                                               "title" =>  $availability['time'],
                                               "payload" => $availability_date,
                                               "type" => "postback",
                                               "url" => null
                                             ],
                                             [
                                               "title" =>  "Start Again",
                                               "payload" => "Cancel",
                                               "type" => "postback",
                                               "url" => null
                                             ]
                                           ]
                                       ];
                                   } 
               
               
                                  
               
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Kindly Choose preferred time to schedule the appointment
                                       ",
                                       "msg_type" => "carousel",
                                       "user_code" => $response->user_code,
                                           "parent_param" => [
                                           'next_step' => 'checkifavailable_facility',
                                           'user_data' => $user_data,
                                       'care_type' => $care_type,
                                       'state' => $state,
                                       'location' => $location,
                                       'facility_uuid' => $facility_uuid,
                                       'reason' => $reason,
                                       'searched_date' => $searched_date,
                                       
               
                                       ],
                                       "quick_replies" => null,
                                       "buttons" => [],
                                       "use_cache" => null,
                                       "reply_internal" => true,
                                       "label" => null,
                                       "attachments" => [],
                                       "template" => null,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id'],
               
                                       "carousels" => $available,
               
                                   ]);
                                   
                                       
               
               
               
               
                  
               
                                }
               
                                //end of when everthing checks
   
   
   
                               //////
                           }
   
                           else {
                               //Back to menu
   
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                                   ]);
                           }
                       }
   
   
   
   
                   
   
                   // $availability_date = unserialize($response->query);
   
                   // // Log::debug("Check down");
                   // // Log::debug($availability_date);
                   // // Log::debug($response->query);
   
                   // $day = $availability_date['day'];
                   // $time = $availability_date['time'];
                   // $date = $availability_date['date'];
                   // $cost = $availability_date['cost'];
   
                   
                   // //check if appointment exist already
   
                   // $dateformat= Carbon::parse($date)->format('Y-m-d');
   
                   // $checkappointment = Appointment::where('date',$dateformat)->where('time',$time)->where('center_uuid',$facility_uuid)->first();
   
                   // if($checkappointment){
   
                   //     $responsed = Http::withoutVerifying()->withHeaders([
                   //         'token'=>$token,
                           
                   //     ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                   //         "platform" => $user['platform'],
                   //         "agent_id" => $user['agent_id'],
                   //         "message" => "Seems scheduled appointment for ".$day. " the ".$date." at ".$time." has just been booked, Kindly Change time or Search Again.",
                   //         "msg_type" => "quick_reply",
                   //         "user_code" => $response->user_code,
                   //         "parent_param" => [
                   //             'next_step' => 'redate_with_time_facility',
                   //             'user_data' => $user_data,
                   //         'care_type' => $care_type,
                   //         'state' => $state,
                   //         'location' => $location,
                   //         'facility_uuid' => $facility_uuid,
                   //         'reason' => $reason,
                   //         'searched_date' => $searched_date
                           
   
                   //         ],
                          
                   //         "quick_replies" => [
   
                   //             [
                   //                 "content_type" => "text",
                   //                 "title" => "Change Time",
                   //                 "payload" => "Change Time",
                   //                 "image_url" =>  null
                   //             ],
   
                   //             [
                   //                 "content_type" => "text",
                   //                 "title" => "Search Again",
                   //                 "payload" => "Search Again",
                   //                 "image_url" =>  null
                   //             ],
                              
                   //             [
                   //                 "content_type" => "text",
                   //                 "title" => "Cancel",
                   //                 "payload" => "Cancel",
                   //                 "image_url" =>  null
                   //             ],
   
                   //             [
                   //                 "content_type" => "text",
                   //                 "title" => "Chat Support",
                   //                 "payload" => "Chat Support",
                   //                 "image_url" =>  null
                   //             ],
   
                   //         ],
                   //         "buttons" => [],
                   //         "use_cache" => true,
                   //         "reply_internal" => true,
                   //         "action" =>  $response->action,
                   //         "intent_id" => $user['intent_id']
                   //     ]);
                       
                   // }
                   // // end of check if appointment exists
   
   
                   // //When appointment doest exist Draft temporay appointment
   
                   // else{
   
                   //     //draft temporary booking 
   
                   //     // $doc_id = $doc_docss['id'];
                   //     // $date = $doc_docss['date'];
                   //     // $time = $selected_time;
                   //     // $phone = $user_data['phone'];
                   //     // $uuid = $doc_docss['uuid'];
                   //     // $fee = $doc_docss['fee'];
                   //     // $reasons = $reason;
   
                   //     $date = $date;
                   //     $time = $time;
                   //     $phone = $user_data['phone'];
                   //     $uuid = $facility_uuid;
                   //     $reason = $reason;
                   //     $fee = $cost;
   
   
                   //      //draft online api
                   //      $drafbooking = Http::withoutVerifying()->withHeaders([
                   //         'token'=>$token,
                           
                   //     ])->post('https://admin.asknello.com/api/facilitybook',[
                           
                   //         'date'=> $date,
                   //         'time' => $time,
                   //         'phone' => $phone,
                   //         'uuid' =>$uuid,
                   //         'reason' => $reason,
                   //         'fee' => $fee
                   //     ]);
   
   
                   //     // Log::debug($drafbooking['temp_id']);
   
                   //     if($drafbooking['temp_id']){
                           
                   //         //if the temp_id is present
   
                   //         $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
   
   
                   //         $temp_id = $drafbooking['temp_id'];
                   //         $message = 'Hi '.$user_data['firstname']. ', kindly proceed to make payment of ₦'.$fee. ' to secure appointment on '.$day. ' the '.$date.' by '.$time.' with '.$centername ;
   
                   //         $responsed = Http::withoutVerifying()->withHeaders([
                   //             'token'=>$token,
                               
                   //         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                   //             "platform" => $user['platform'],
                   //             "agent_id" => $user['agent_id'],
                   //             "message" => $message,
                   //             "msg_type" => "link",
                   //             "user_code" => $response->user_code,
                   //             "parent_param" => [
                   //                 'next_step' => 'paymentcompleted_facility',
                   //                 'user_data' => $user_data,
                   //                 'care_type' => $care_type,
                   //                 'state' => $state,
                   //                 'location' => $location,
                   //                 'facility_uuid' => $facility_uuid,
                   //                 'reason' => $reason,
                   //                 'temp_id'=> $temp_id,
                   //                 'availability_date' => $availability_date,
       
                   //             ],
                   //             "quick_replies" => [
                                   
   
                   //             ],
                   //             "buttons" => [
                   //                 [
                   //                     "url" => "https://mw.asknello.com/servicepay/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&temp_id=".$temp_id."&cost=".$fee."&email=".$user_data['email'],
                   //                     "title" => "Make Payment"
                   //                 ]
                   //             ],
                   //             "use_cache" => true,
                   //             "reply_internal" => true,
                   //             "action" =>  $response->action,
                   //             "intent_id" => $user['intent_id']
                   //         ]);
   
   
                   //     }
   
   
                       
                   // }
   
   
   
                   // //end of draft temporary appointment booking
   
   
                   break;
   
                   case "paymentcompleted_facility":
   
                       $input = $response->query;
   
                       $user_data = $parent_param['user_data'];
                       $care_type = $parent_param['care_type'];
                       $state = $parent_param['state'];
                       $location = $parent_param['location'];
                       $facility_uuid = $parent_param['facility_uuid'];
                       $reason = $parent_param['reason'];
                       $availability_date = $parent_param['availability_date'];
                       $temp_id = $parent_param['temp_id'];
   
                       $day = $availability_date['day'];
                       $time = $availability_date['time'];
                       $date = $availability_date['date'];
                       $cost = $availability_date['cost'];
   
   
                       $date = $date;
                       $time = $time;
                       $phone = $user_data['phone'];
                       $uuid = $facility_uuid;
                       $reason = $reason;
                       $fee = $cost;
                       $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
                       
   
   
                   //do this
   
                   $count = Count::first();
                   $count = $count->count;
   
                   $update = Count::first()->update([
                       'count' => 1
                   ]);
   
   
   if($input){
   
   if($count < 3){
   $update = Count::first()->update([
   'count' => $count + 1
   ]);
   
   
   //rendered error count
   
   $message = 'Hi '.$user_data['firstname']. ', kindly proceed to make payment of ₦'.$fee. ' to secure appointment on '.$day. ' the '.$date.' by '.$time.' with '.$centername ;
   
   $responsed = Http::withoutVerifying()->withHeaders([
       'token'=>$token,
       
   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
       "platform" => $user['platform'],
       "agent_id" => $user['agent_id'],
       "message" => $message,
       "msg_type" => "link",
       "user_code" => $response->user_code,
       "parent_param" => [
           'next_step' => 'paymentcompleted_facility',
           'user_data' => $user_data,
           'care_type' => $care_type,
           'state' => $state,
           'location' => $location,
           'facility_uuid' => $facility_uuid,
           'reason' => $reason,
           'temp_id'=> $temp_id,
           'availability_date' => $availability_date,
   
       ],
       "quick_replies" => [
           
   
       ],
       "buttons" => [
           [
               "url" => "https://mw.asknello.com/servicepay/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&temp_id=".$temp_id."&cost=".$fee."&email=".$user_data['email'],
               "title" => "Make Payment"
           ]
       ],
       "use_cache" => true,
       "reply_internal" => true,
       "action" =>  $response->action,
       "intent_id" => $user['intent_id']
   ]);
   
   
   
   
   
   }
   
   else{
   $update = Count::first()->update([
   'count' => 1
   ]);
   
   //Back to menu
   
   $responsed = Http::withoutVerifying()->withHeaders([
   'token'=> $token,
   // Back to menu
   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
   "platform" => $user['platform'],
   "agent_id" => $user['agent_id'],
   "message" => "You have exceeded you error limits of 3, kindly start again",
   "msg_type" => "quick_reply",
   "user_code" => $response->user_code,
   "parent_param" => null,
   "quick_replies" => null,
   // "buttons" => [],
   // "use_cache" => true,
   // "reply_internal" => true,
   // "action" =>  $response->action,
   // "intent_id" => $user['intent_id']
   ]);
   }
   }
   
   
   
                   break;
   
   
                  
   
   
                   case "redate_with_time_facility":
   
                       //redate with change time quick reply
   
                       $user_data = $parent_param['user_data'];
                   $care_type = $parent_param['care_type'];
                   $state = $parent_param['state'];
                   $location = $parent_param['location'];
                   $facility_uuid = $parent_param['facility_uuid'];
                   $reason = $parent_param['reason'];
                   $searched_date = $parent_param['searched_date'];
   
                   if($response->query == "Chat Support"){
                       //chat support
                       // Trigger intent to chat support
                       $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/trigger/intent',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "instantqueue",
           
                           "user_code" => $response->user_code,
                          
                          
                       ]);
                   }
                   elseif($response->query == "Search Again"){
                       //serach Again
                       $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "Please provide your preferred date for the appointment i.e 20/09/2022",
                           "msg_type" => "text",
                           "user_code" => $response->user_code,
                           "parent_param" => [
                               'next_step' => 'validate_date_facility',
                               'user_data' => $user_data,
                               'care_type' => $care_type,
                               'state' => $state,
                               'location' =>$location,
                               'facility_uuid' =>$facility_uuid,
                               'reason' => $reason,
       
                           ],
                           "quick_replies" => [],
                               
                                 
                           "buttons" => [],
                           "use_cache" => true,
                           "reply_internal" => true,
                           "action" =>  $response->action,
                           "intent_id" => $user['intent_id']
                       ]);
   
                   }
   
                   elseif($response->query == "Change Time"){
                       //change time
   
                       $response_available = Http::get('https://admin.asknello.com/api/checkavailability',[
                           "specialization" => $care_type,
                           "date" => $searched_date,
                           "uuid" => $facility_uuid
                       ]);
       
                       $response_available = $response_available->json();
       
       
                        //check if date is passed date
                       
                        if($response_available["status"] == "failed"){
                            //if error with date format or passed date
       
                            $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Invalid or past date ",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'redate_facility',
                                   'user_data' => $user_data,
                               'care_type' => $care_type,
                               'state' => $state,
                               'location' => $location,
                               'facility_uuid' => $facility_uuid,
                               'reason' => $reason,
                               
       
                               ],
                              
                               "quick_replies" => [
                                   [
                                       "content_type" => "text",
                                       "title" => "Search Again",
                                       "payload" => "Search Again",
                                       "image_url" =>  null
                                   ],
                                  
                                   [
                                       "content_type" => "text",
                                       "title" => "Cancel",
                                       "payload" => "Cancel",
                                       "image_url" =>  null
                                   ],
       
                                   [
                                       "content_type" => "text",
                                       "title" => "Chat Support",
                                       "payload" => "Chat Support",
                                       "image_url" =>  null
                                   ],
       
                               ],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
       
       
                        }
       
       
       
       
       
       
                        //end of check if date is passed date
       
       
                        //check if date is not passed but no availaility 
                        elseif($response_available["status"] == "success" && count($response_available['available'])== 0) {
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "There are no time slots that match your selection. You can search for another facility or another time, or chat with a support representative who can help you book an appointment",
                               "msg_type" => "quick_reply",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'redate_facility',
                                   'user_data' => $user_data,
                               'care_type' => $care_type,
                               'state' => $state,
                               'location' => $location,
                               'facility_uuid' => $facility_uuid,
                               'reason' => $reason,
                               
       
                               ],
                              
                               "quick_replies" => [
                                   [
                                       "content_type" => "text",
                                       "title" => "Search Again",
                                       "payload" => "Search Again",
                                       "image_url" =>  null
                                   ],
                                  
                                   [
                                       "content_type" => "text",
                                       "title" => "Cancel",
                                       "payload" => "Cancel",
                                       "image_url" =>  null
                                   ],
       
                                   [
                                       "content_type" => "text",
                                       "title" => "Chat Support",
                                       "payload" => "Chat Support",
                                       "image_url" =>  null
                                   ],
       
                               ],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
       
                        }
       
       
                        // end no availablity
       
       
       
       
                        
       
                        
       
                        //when everthing checks
       
                        elseif($response_available["status"] == "success" && count($response_available['available']) > 0) {
       
                           $available = [];
                           $centername = HealthCenter::where('uuid',$facility_uuid)->value('name');
       
                           foreach($response_available['available'] as $availability){
                               $availability_date = [
                                   "date" => $availability['date'],
                                   "time" => $availability['time'],
                                   "day" => $availability['day'],
                                   "cost" => $availability['cost']
       
                               ];
                               $availability_date = serialize($availability_date);
       
                               $title = $availability['day'].' - '.$availability['date'].' - '.$availability['time'] ;
                               $available[] = [
                                   // "content_type" => "text",
                                   // "title" => $title,
                                   // "payload" => $availability_date,
                                   // "image_url" =>  null
   
                                   "title" =>$availability['day']."(".$availability['date'].")",
                                   "description" => $centername ." - ".$care_type .", - N ".$availability['cost'],
                                   "image_url" => "https://res.cloudinary.com/edifice-solutions/image/upload/v1665572286/Wavy_Med-04_Single-10-min_t1lrrz.jpg",
                                   "suggestions" => [
                                    
                                     [
                                       "title" =>  $availability['time'],
                                       "payload" => $availability_date,
                                       "type" => "postback",
                                       "url" => null
                                     ]
                                   ]
                               ];
                           } 
       
       
                           // $responsed = Http::withoutVerifying()->withHeaders([
                           //     'token'=>$token,
                               
                           // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //     "platform" => $user['platform'],
                           //     "agent_id" => $user['agent_id'],
                           //     "message" => "Kindly Choose preferred time to schedule the appointment",
                           //     "msg_type" => "quick_reply",
                           //     "user_code" => $response->user_code,
                           //     "parent_param" => [
                           //         'next_step' => 'checkifavailable_facility',
                           //         'user_data' => $user_data,
                           //     'care_type' => $care_type,
                           //     'state' => $state,
                           //     'location' => $location,
                           //     'facility_uuid' => $facility_uuid,
                           //     'reason' => $reason,
                           //     'searched_date' => $searched_date,
                               
       
                           //     ],
                              
                           //     "quick_replies" => $available,
                           //     "buttons" => [],
                           //     "use_cache" => true,
                           //     "reply_internal" => true,
                           //     "action" =>  $response->action,
                           //     "intent_id" => $user['intent_id']
                           // ]);
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Kindly Choose preferred time to schedule the appointment
                               ",
                               "msg_type" => "carousel",
                               "user_code" => $response->user_code,
                                       "parent_param" => [
                                   'next_step' => 'checkifavailable_facility',
                                   'user_data' => $user_data,
                               'care_type' => $care_type,
                               'state' => $state,
                               'location' => $location,
                               'facility_uuid' => $facility_uuid,
                               'reason' => $reason,
                               'searched_date' => $searched_date,
                               
       
                               ],
                               "quick_replies" => null,
                               "buttons" => [],
                               "use_cache" => null,
                               "reply_internal" => true,
                               "label" => null,
                               "attachments" => [],
                               "template" => null,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id'],
       
                               "carousels" => $available,
       
                           ]);
                           
       
       
       
       
          
       
                        }
       
                        //end of when everthing checks
   
   
                   }
   
   
   
                   
   
                   break;
   
   
   
                   //facility action register
   
   
                   case "lastname_facility":
   
                       $count = Count::first();
                       $count = $count->count;
   
                        //get user last name
   
                        $firstname = $response->query;
   
   
                        //checkif value is type of string
                        if(preg_match("/^([a-zA-Z' ]+)$/",$firstname)){
   
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                        $responsed = Http::withoutVerifying()->withHeaders([
                           'token'=>$token,
                           
                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           "platform" => $user['platform'],
                           "agent_id" => $user['agent_id'],
                           "message" => "Next, Please Input your last name",
                           "msg_type" => "text",
                           "user_code" => $response->user_code,
                           "parent_param" => [
                               'next_step' => 'email_facility',
                               'firstname' => $firstname,
                               
   
                           ],
                           "quick_replies" => [],
                           "buttons" => [],
                           "use_cache" => true,
                           "reply_internal" => true,
                           "action" =>  $response->action,
                           "intent_id" => $user['intent_id']
                       ]);
                       
   
   
                        }
   
                        else{
   
                           if($count < 3){
   
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => " Invalid name format, please provide a valid firstname ",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       "next_step" => "lastname_facility"
                                   ],
                                   "quick_replies" => [],
                                       
                                       
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                           }
   
   
                           else{
   
                               $update = Count::first()->update([
                                   'count' =>  1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
       
   
                               
                           }
   
   
   
                        }
   
                        ////////
   
   
                   
                   break;
   
                   case "email_facility":
                       $lastname = $response->query;
                           $firstname = $parent_param['firstname'];
   
                           $count = Count::first();
                           $count = $count->count;
   
   
   
                           if(preg_match("/^([a-zA-Z' ]+)$/",$lastname)){
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Please input your email",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_email_facility',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                               
                               // Log::debug($responsed);
   
   
                           }
   
                           else {
   
                               if($count < 3){
   
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid input, Please input a valid lastname",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'email_facility',
                                           'firstname' => $firstname,
                                           
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
   
                               }
   
                               else{
                                   $update = Count::first()->update([
                                       'count' =>  1
                                   ]);
   
                                   //Back to menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
           
                               }
                           }
   
                           //Log::debug($firstname);
   
                           
                           
   
                       break;
                       
   
                       case "validate_email_facility":
                       
                           // input email
   
                           $email = $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
   
                           $count = Count::first();
                           $count = $count->count;
   
   
                           if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                               // Email Is Validated
   
                               $useremail = User::where('email',$email)->first();
   
                               if($useremail){
   
                                   //Log::debug('Email Exists');
                                   //if the email already exist
   
                                   if($count < 3){
                                       $update = Count::first()->update([
                                           'count' => $count + 1
                                       ]);
                                       
   
                                       $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=>$token,
                                           
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "Email already registered in our database, Try another email.",
                                           "msg_type" => "text",
                                           "user_code" => $response->user_code,
                                           "parent_param" => [
                                               'next_step' => 'validate_email_facility',
                                              'firstname' => $firstname,
                                              'lastname' => $lastname,
                   
                                           ],
                                           "quick_replies" => [],
                                           "buttons" => [],
                                           "use_cache" => true,
                                           "reply_internal" => true,
                                           "action" =>  $response->action,
                                           "intent_id" => $user['intent_id']
                                       ]);
   
                                   }
   
                                   else{
   
                                       $update = Count::first()->update([
                                           'count' => 1
                                       ]);
   
                                       //Back to menu
   
                                       $responsed = Http::withoutVerifying()->withHeaders([
                                           'token'=> $token,
                                           // Back to menu
                                       ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                           "platform" => $user['platform'],
                                           "agent_id" => $user['agent_id'],
                                           "message" => "You have exceeded you error limits of 3, kindly start again",
                                           "msg_type" => "quick_reply",
                                           "user_code" => $response->user_code,
                                           "parent_param" => null,
                                           "quick_replies" => null,
                                           // "buttons" => [],
                                           // "use_cache" => true,
                                           // "reply_internal" => true,
                                           // "action" =>  $response->action,
                                           // "intent_id" => $user['intent_id']
                                       ]);
               
                                   }
   
                                  
                                   
   
                               }
   
                               elseif(!$useremail){
   
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                  // Log::debug('Good');
                                     $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Enter your phone Number",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_phone_facility',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               }
                             
                             } else {
                               // Email Not Valida
   
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid Email Format, input a valid email address",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'validate_email_facility',
                                          'firstname' => $firstname,
                                          'lastname' => $lastname,
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
                                   
       
                               }
   
                               else{
   
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                   //Back to menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
           
                               }
   
                              
   
                             }
                           
     
   
   
                   break;
   
   
                   case "validate_phone_facility":
   
                         //validate the phone number length
                         $phone = $response->query;
                         $firstname = $parent_param['firstname'];
                         $lastname = $parent_param['lastname'];
                         $email = $parent_param['email'];
   
                         $existed = User::where('phone',$phone)->exists();
   
                         $count = Count::first();
                         $count = $count->count;
   
                         if($existed){
   
                           if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "User with phone number already exists, try another number",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_phone_facility',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                           }
   
                           else {
                               //Back to menu
   
                               $update = Count::first()->update([
                                   'count' =>  1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
   
   
                           }
   
                          
                         }
   
                         elseif(preg_match('/^[0-9]{11}+$/', $phone)){
   
   
                           $update = Count::first()->update([
                               'count' => 1
                           ]);
   
                            // Log::debug('Valid Phone');
                            $responsed = Http::withoutVerifying()->withHeaders([
                             'token'=>$token,
                             
                         ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                             "platform" => $user['platform'],
                             "agent_id" => $user['agent_id'],
                             "message" => "Select your gender",
                             "msg_type" => "quick_reply",
                             "user_code" => $response->user_code,
                             "parent_param" => [
                                 'next_step' => 'date_of_birth_facility',
                                'firstname' => $firstname,
                                'lastname' => $lastname,
                                'email' => $email,
                                'phone' => $phone,
     
                             ],
                             "quick_replies" => [
                                 [
                                     "content_type" => "text",
                                 "title" => "Male",
                                 "payload" =>"Male",
                                 "image_url" =>  null
                                 ],
   
                                 [
                                     "content_type" => "text",
                                 "title" => "Female",
                                 "payload" =>"Female",
                                 "image_url" =>  null
                                 ],
                                 [
                                   "content_type" => "text",
                               "title" => "Cancel",
                               "payload" =>"Cancel",
                               "image_url" =>  null
                               ],
   
                             ],
                             "buttons" => [],
                             "use_cache" => true,
                             "reply_internal" => true,
                             "action" =>  $response->action,
                             "intent_id" => $user['intent_id']
                         ]);
   
                         }
   
                        
   
                         else {
                            // Log::debug('Invalid Phone');
   
                            if($count < 3){
                                $update = Count::first()->update([
                                    'count' => $count + 1
                                ]);
   
                                $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid phone number, Kindly enter a valid phone number",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_phone_facility',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                            }
   
                            else {
                               $update = Count::first()->update([
                                   'count' =>  1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                            }
   
                            
                         }
   
   
                   break;
   
   
                   case "date_of_birth_facility":
   
   
                        
   
                       $options = json_decode($user['options_temp'], true);
   
                      
                          
                       $count = Count::first();
                           $count = $count->count;
   
   
   
                       $gender = $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
                           $email = $parent_param['email'];
                           $phone = $parent_param['phone'];
   
   
   
                           if(in_array($gender,array_column($options,'value'))){
                              
   
                               //update count back to one
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                                 $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Kindly enter date of birth, i.e 30-06-1996. (dd/mm/yyyy)",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_dob_facility',
                                  'firstname' => $firstname,
                                  'lastname' => $lastname,
                                  'email' => $email,
                                  'phone' => $phone,
                                  'gender' => $gender,
       
                               ],
                               "quick_replies" => [],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
   
   
                               
                          }  
                          else{
                              
   
                              //if count is lesser than 3
                              if($count < 3){
                               $update = Count::first()->update([
                                   'count' => $count + 1
                               ]);
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid input, you are expected to select from below options",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'date_of_birth_facility',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
                                      'phone' => $phone,
           
                                   ],
                                   "quick_replies" => [
                                       [
                                           "content_type" => "text",
                                       "title" => "Male",
                                       "payload" =>"Male",
                                       "image_url" =>  null
                                       ],
         
                                       [
                                           "content_type" => "text",
                                       "title" => "Female",
                                       "payload" =>"Female",
                                       "image_url" =>  null
                                       ],
                                       [
                                         "content_type" => "text",
                                     "title" => "Cancel",
                                     "payload" =>"Cancel",
                                     "image_url" =>  null
                                     ],
         
                                   ],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
         
       
   
                               //send message to select from below button
   
                            }
   
                            else {
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               //Back To Menu
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
                            }
                          }  
   
   
   
   
                           // $responsed = Http::withoutVerifying()->withHeaders([
                           //     'token'=>$token,
                               
                           // ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                           //     "platform" => $user['platform'],
                           //     "agent_id" => $user['agent_id'],
                           //     "message" => "Kindly enter date of birth, i.e 30-06-1996. (dd-mm-yyyy)",
                           //     "msg_type" => "text",
                           //     "user_code" => $response->user_code,
                           //     "parent_param" => [
                           //         'next_step' => 'validate_dob_facility',
                           //        'firstname' => $firstname,
                           //        'lastname' => $lastname,
                           //        'email' => $email,
                           //        'phone' => $phone,
                           //        'gender' => $gender,
       
                           //     ],
                           //     "quick_replies" => [],
                           //     "buttons" => [],
                           //     "use_cache" => true,
                           //     "reply_internal" => true,
                           //     "action" =>  $response->action,
                           //     "intent_id" => $user['intent_id']
                           // ]);
   
                           
   
   
                   break;
   
                   case "validate_dob_facility":
   
                       $dob= $response->query;
                           $firstname = $parent_param['firstname'];
                           $lastname = $parent_param['lastname'];
                           $email = $parent_param['email'];
                           $phone = $parent_param['phone'];
                           $gender = $parent_param['gender'];
   
                           $count = Count::first();
                           $count = $count->count;
   
   
   
                           if(preg_match("/^([a-zA-Z' ]+)$/",$dob) || preg_match('/-/', $dob) || ctype_alnum($dob)){
                               $updated = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               ///
   
                               if($count < 3){
                                   $updated = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid date input, kindly input a valid input with format (dd/mm/yyyy)",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'validate_dob_facility',
                                          'firstname' => $firstname,
                                          'lastname' => $lastname,
                                          'email' => $email,
                                          'phone' => $phone,
                                          'gender' => $gender,
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
   
                               }
   
                               else{
                                   //Back to Menu
   
                                   $updated = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
                               }
   
                           }
   
   
   
                          // $mydate = Carbon::createFromFormat('d-m-y')
                          $dob = Carbon::createFromFormat('d/m/Y', $dob)->format('d-m-Y');
                          Log::debug($dob);
   
   
                          $dateformat= Carbon::parse($dob)->format('Y-m-d');
                           $result = Carbon::parse($dateformat)->lte(Carbon::now());
   
                           if($dateformat == Carbon::now()->format('Y-m-d')){
   
   
                               if($count < 3){
                                   $update = Count::first()->update([
                                       'count' => $count + 1
                                   ]);
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=>$token,
                                       
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "Invalid date, date must be a past date",
                                       "msg_type" => "text",
                                       "user_code" => $response->user_code,
                                       "parent_param" => [
                                           'next_step' => 'validate_dob_facility',
                                          'firstname' => $firstname,
                                          'lastname' => $lastname,
                                          'email' => $email,
                                          'phone' => $phone,
                                          'gender' => $gender,
               
                                       ],
                                       "quick_replies" => [],
                                       "buttons" => [],
                                       "use_cache" => true,
                                       "reply_internal" => true,
                                       "action" =>  $response->action,
                                       "intent_id" => $user['intent_id']
                                   ]);
   
                               }
   
                               else{
                                   $update = Count::first()->update([
                                       'count' => 1
                                   ]);
   
                                   //Back to menu
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                       'token'=> $token,
                                       // Back to menu
                                   ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                       "platform" => $user['platform'],
                                       "agent_id" => $user['agent_id'],
                                       "message" => "You have exceeded you error limits of 3, kindly start again",
                                       "msg_type" => "quick_reply",
                                       "user_code" => $response->user_code,
                                       "parent_param" => null,
                                       "quick_replies" => null,
                                       // "buttons" => [],
                                       // "use_cache" => true,
                                       // "reply_internal" => true,
                                       // "action" =>  $response->action,
                                       // "intent_id" => $user['intent_id']
                                   ]);
                               }
                               
                               
                           }
   
                           elseif($result){
                              //passed date 
   
                              $update = Count::first()->update([
                               'count' => 1
                           ]);
                           
   
                              $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Click on the button below, to create password",
                               "msg_type" => "link",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_password_facility',
                                  'firstname' => $firstname,
                                  'lastname' => $lastname,
                                  'email' => $email,
                                  'phone' => $phone,
                                  'gender' => $gender,
                                  'dob' => $dob,
       
                               ],
                               "quick_replies" => [],
                               "buttons" => [
                                   [
                                       "url" => "https://admin.asknello.com/embanqo/password/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&firstname=".$firstname."&lastname=".$lastname."&email=".$email."&phone=".$phone."&gender=".$gender."&dob=".$dob,
                                       "title" => "Create Password"
                                   ]
                               ],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                              
                           }
   
                           
   
                           elseif(!$result){
                               //Log::debug('Correct');
                              // when date is future date back to error
   
                              //go to password 
   
                              if($count < 3){
                                  $update = Count::first()->update([
                                      'count' => $count + 1
                                  ]);
   
                                  $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Invalid date, date must be a past date",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'validate_dob_facility',
                                      'firstname' => $firstname,
                                      'lastname' => $lastname,
                                      'email' => $email,
                                      'phone' => $phone,
                                      'gender' => $gender,
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
                                 
       
   
   
                              }
   
                              else{
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
                               //Back to menu
   
                               $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=> $token,
                                   // Back to menu
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have exceeded you error limits of 3, kindly start again",
                                   "msg_type" => "quick_reply",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => null,
                                   // "buttons" => [],
                                   // "use_cache" => true,
                                   // "reply_internal" => true,
                                   // "action" =>  $response->action,
                                   // "intent_id" => $user['intent_id']
                               ]);
   
                              }
   
                             
                        
                           }
   
   
   
                   break;
   
   
                   case "validate_password_facility":
   
                       $password = $response->query;
                       $firstname = $parent_param['firstname'];
                       $lastname = $parent_param['lastname'];
                       $email = $parent_param['email'];
                       $phone = $parent_param['phone'];
                       $gender = $parent_param['gender'];
                       $dob = $parent_param['dob'];
   
   
   
                       $count = Count::first();
                               $count = $count->count;
   
                               $update = Count::first()->update([
                                   'count' => 1
                               ]);
   
   
   if($password){
   
       if($count < 3){
           $update = Count::first()->update([
               'count' => $count + 1
           ]);
   
           //render button again
   
           $responsed = Http::withoutVerifying()->withHeaders([
               'token'=>$token,
               
           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
               "platform" => $user['platform'],
               "agent_id" => $user['agent_id'],
               "message" => "You are expected to click on the button below, to create password",
               "msg_type" => "link",
               "user_code" => $response->user_code,
               "parent_param" => [
                   'next_step' => 'validate_password_facility',
                  'firstname' => $firstname,
                  'lastname' => $lastname,
                  'email' => $email,
                  'phone' => $phone,
                  'gender' => $gender,
                  'dob' => $dob,
   
               ],
               "quick_replies" => [],
               "buttons" => [
                   [
                       "url" => "https://admin.asknello.com/embanqo/password/?platform=".$user['platform']."&agent_id=".$user['agent_id']."&user_code=".$response->user_code."&action=".$response->action."&firstname=".$firstname."&lastname=".$lastname."&email=".$email."&phone=".$phone."&gender=".$gender."&dob=".$dob,
                       "title" => "Create Password"
                   ]
               ],
               "use_cache" => true,
               "reply_internal" => true,
               "action" =>  $response->action,
               "intent_id" => $user['intent_id']
           ]);
   
           
       }
   
       else{
           $update = Count::first()->update([
               'count' => 1
           ]);
   
           //Back to menu
   
           $responsed = Http::withoutVerifying()->withHeaders([
               'token'=> $token,
               // Back to menu
           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
               "platform" => $user['platform'],
               "agent_id" => $user['agent_id'],
               "message" => "You have exceeded you error limits of 3, kindly start again",
               "msg_type" => "quick_reply",
               "user_code" => $response->user_code,
               "parent_param" => null,
               "quick_replies" => null,
               // "buttons" => [],
               // "use_cache" => true,
               // "reply_internal" => true,
               // "action" =>  $response->action,
               // "intent_id" => $user['intent_id']
           ]);
       }
   }
   
                       
   
   
   
                   
               break;
   
               case "password_confirm_facility":
   
                   $password_confirmation = $response->query;
                       $firstname = $parent_param['firstname'];
                       $lastname = $parent_param['lastname'];
                       $email = $parent_param['email'];
                       $phone = $parent_param['phone'];
                       $gender = $parent_param['gender'];
                       $dob = $parent_param['dob'];
                       $password = $parent_param['password'];
   
   
                       if($password_confirmation == $password){
                           //if the two passwords are simipler
   
                           //Log::debug('Similar');
   
                           $register = Http::withoutVerifying()->post('https://mw.asknello.com/api/auth/register',[
                              "firstname" => $firstname,
                              "lastname" => $lastname,
                              "email" => $email,
                              "phone" => $phone,
                              "gender" => $gender,
                              "password" => $password,
                              "password_confirmation" => $password_confirmation,
                              "dob" => $dob,
                           ]);
   
                           if($register['token']){
   
                               //if the User is Created
                               //display welcome message
   
                                $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "You have successfully registered on Nello, your personal healthcare assistance!!! ",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => null,
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               if($responsed["status"] == "success"){
                                   //if when message sent,, ask user to provide their phone number
   
                                   $responsed = Http::withoutVerifying()->withHeaders([
                                   'token'=>$token,
                                   
                               ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                                   "platform" => $user['platform'],
                                   "agent_id" => $user['agent_id'],
                                   "message" => "Kindly Provide your phone Number registered on Nello ",
                                   "msg_type" => "text",
                                   "user_code" => $response->user_code,
                                   "parent_param" => [
                                       'next_step' => 'checkauth_facility',
                                      
           
                                   ],
                                   "quick_replies" => [],
                                   "buttons" => [],
                                   "use_cache" => true,
                                   "reply_internal" => true,
                                   "action" =>  $response->action,
                                   "intent_id" => $user['intent_id']
                               ]);
   
                               }
   
                               
   
   
                               
   
                               
   
                           }
                       }
                       elseif($password_confirmation != $password) {
                           //If the Two Password are not similar
   
                           $responsed = Http::withoutVerifying()->withHeaders([
                               'token'=>$token,
                               
                           ])->post('https://jl8moajhh2.execute-api.us-east-2.amazonaws.com/prod/ebqmsg/message/reply/',[
                               "platform" => $user['platform'],
                               "agent_id" => $user['agent_id'],
                               "message" => "Passwords not matched, Try again.",
                               "msg_type" => "text",
                               "user_code" => $response->user_code,
                               "parent_param" => [
                                   'next_step' => 'validate_password_facility',
                                  'firstname' => $firstname,
                                  'lastname' => $lastname,
                                  'email' => $email,
                                  'phone' => $phone,
                                  'gender' => $gender,
                                  'dob' => $dob,
       
                               ],
                               "quick_replies" => [],
                               "buttons" => [],
                               "use_cache" => true,
                               "reply_internal" => true,
                               "action" =>  $response->action,
                               "intent_id" => $user['intent_id']
                           ]);
                           
                       }
   
   
   
                       //confirm password 
   
   
               break;
   
   
   
   
                   default:
                     echo "Your favorite color is neither red, blue, nor green!";
   
               } //end of switch
   
   
               
   
   
           //end of when parent param is not null
           }
   
   
   
       }
   
       
   }
   
   