<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\OtpModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;
use App\Entities\OtpEntity;
use Firebase\JWT\JWT;

class OtpVerify extends ResourceController
{
   use ResponseTrait;
   
  
   //checking otp
   public function otpVerify()
   {
    # code...
    //validation process
    $validate = [
        "key" => [
            "rules" => "required",
            "errors" => [
                "required" => "key is missing"
            ],
        ],
        "otp_code" => [
            "rules" => "required|min_length[6]",
            "errors" => [
                "required" => "OTP code Missing",
                "valid_email" => "Provide valid OTP Code",
            ],

        ],

        "user_id"=>[
            "rules"=>"required|is_not_unique[user_access.id]",
            "errors"=>[
                "required"=>"Unatuhorized access",
                "is_not_unique"=>"Failed to access"
            ]
        ]

     
    ];


    //check 
    if (!$this->validate($validate)) {
        
        $res = [
            'code'=>1,
            "errors" => true,
            "msg" => $this->validator->getErrors(),
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }

    //checking key 
    if (getenv('API_SECRET')!== $this->request->getVar('key')) {
        $res = [
            'code'=>2,
            "errors" => true,
            "msg" => "Invalid Access",
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }

    //check session
    if($this->request->getVar('user_id'))
    {
         $user_id=$this->request->getVar('user_id');
         //firstly check userid exist or not
         $model=new OtpModel();
         $user_data=$model->where('user_id',$user_id)->orderBy('id','desc')->first();
        
         if($user_data){

            $otp=md5($this->request->getVar('otp_code'));
            //dd($otp);
            
            if($otp==$user_data->otp_code){ 
              //checking otp expiration

              $currentTime=new Time('now');  
              $otpDateTime= new Time($user_data->created_at);
              $otptime=$otpDateTime->addMinutes(5);
             ///working with time
            // dd(date('Y-m-d H-i-s',strtotime($otptime)));
             if($currentTime<=$otptime && $user_data->status=="0")
             {
                $data=[
                    "status"=>"1"
                ];
                $user_data->fill($data);

                //Otp match update
               if( $model->save($user_data))
               {
                
                $userModel=new \App\Models\UserModel();
                $user_info=$userModel->find($user_data->user_id);
                $iat=time();
                $exp=$iat+86400;

                $payload=[
                    'iat'=>$iat,
                    'exp'=> $exp,
                    'user_data'=> $user_info
                ];

                $token=JWT::encode($payload,getenv('JWT_SECRET'),'HS256'); 
                $res = [
                    'code'=>200,
                    "errors" => null,
                    "msg" => $token,
                ];
        
                $this->response->setStatusCode(200);
                $this->response->setContentType('application/json');
                return $this->response->setJSON($res);
               }

                
                $res = [
                    'code'=>5,
                    "errors" => true,
                    "msg" => $model->errors(),
                ];
        
                $this->response->setStatusCode(200);
                $this->response->setContentType('application/json');
                return $this->response->setJSON($res);
             }
             else{
                $res = [
                    'code'=>7,
                    "errors" => true,
                    "msg" => "OTP code time expired",
                ];
        
                $this->response->setStatusCode(200);
                $this->response->setContentType('application/json');
                return $this->response->setJSON($res);
             } 
            
            }
            else{  
             $res = [
                'code'=>7,
                "errors" => true,
                "msg" => "OTP code does not match",
            ];
    
            $this->response->setStatusCode(200);
            $this->response->setContentType('application/json');
            return $this->response->setJSON($res);
        }
         }
         else{
            $res = [
                'code'=>7,
                "errors" => true,
                "msg" => "User does not exist",
            ];
    
            $this->response->setStatusCode(200);
            $this->response->setContentType('application/json');
            return $this->response->setJSON($res);
         }
    }
    else{
        $res = [
            'code'=>2,
            "errors" => true,
            "msg" => "Invalid Access session expired",
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }

   }

}
