<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use App\Entities\OtpEntity;
use App\Models\OtpModel;
use CodeIgniter\I18n\Time;

class Authen extends ResourceController
{
    use ResponseTrait;
    
    //Authentication Process
    public function authUser()
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
            "email" => [
                "rules" => "required|valid_email",
                "errors" => [
                    "required" => "Email missing",
                    "valid_email" => "Provide an valid Email",
                ],
            ],

            "password" => [
                "rules" => "required|min_length[8]",
                "errors" => [
                    "required" => "Password Empty",
                    "min_length[8]" => "Provide Password Correctly",
                ],
            ],

        ];


        //check 
        if (!$this->validate($validate)) {
            $res = [
                'code'=>1, //1 means validate problem
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
                'code'=>2,//2 means key problem
                "errors" => true,
                "msg" => "Invalid Access",
            ];

            $this->response->setStatusCode(200);
            $this->response->setContentType('application/json');
            return $this->response->setJSON($res);
        }

        //check email,password,status & role

        $model = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $row = $model->where('email', $email)->first();
        // dd($row);

        if ($row) {
            //valid email which exist in database
            //now check password

            if ($row->password == md5($password)) {
                //Now check account is active or not
                if ($row->status == "1") {

                    //Checking previous otp time;
                    $otpModel = new OtpModel();
                    //check userotp request exist or not
                     $otpTime=$otpModel->where('user_id',$row->id)->orderBy('id','desc')->first();
                     if($otpTime)
                     {
                         $getCurrentTime= new Time('now');
                         $lastOtpTime= new Time($otpTime->created_at);
                         $lastotp=$lastOtpTime->addMinutes(1);
                          
                        if($getCurrentTime<$lastotp)
                        {
                            $endTime=$lastOtpTime->addMinutes(1);
                            $diff=$endTime->diff($getCurrentTime);
                            $minutes= $diff->format('%i');
                            $sec= $diff->format('%S');
                            $res = [
                                'code'=>3,//times error
                                "errors" => true,
                                "msg" => "Your otp already sent! wait for ".$minutes.' Minutes '. $sec ." Seconds to try again",
                            ];
                            $this->response->setStatusCode(200);
                            $this->response->setContentType('application/json');
                            return $this->response->setJSON($res);
                        }
                     }
                    //Now checking roles

                    //user logged in as a content writer

                    //Generate OTP and Sent to email
                    $getOtp = $this->otpGenerate();

                    $otpEn = new OtpEntity();
                    $otpEn->user_id = $row->id;
                    $otpEn->otp_code = md5($getOtp);
                    
                    //Store otp into database
                    if ($otpModel->insert($otpEn)) {
                        $checkEmail = $this->sentEmail($row->email, $getOtp);

                        if ($checkEmail) {
                            $res = [
                                'code'=>200,
                                "errors" => null,
                                "msg" => "Now check your email for an OTP code",
                                "user_id"=>$row->id,
                                "user_name"=>$row->user_name
                            ];
                           


                            $this->response->setStatusCode(200);
                            $this->response->setContentType('application/json');
                            return $this->response->setJSON($res);
                        } else {
                            $res = [
                                'code'=>4,//Email Failed
                                "errors" => true,
                                "msg" => "Your otp is not sent please try again",
                            ];
                            $this->response->setStatusCode(200);
                            $this->response->setContentType('application/json');
                            return $this->response->setJSON($res);
                        }
                    } else {
                        $res = [
                            'code'=>5, //Insertation operation failed
                            "errors" => true,
                            "msg" => $otpModel->errors(),
                        ];
                        $this->response->setStatusCode(200);
                        $this->response->setContentType('application/json');
                        return $this->response->setJSON($res);
                    }
                } else {
                    $res = [
                        'code'=>6,//Access denied
                        "errors" => true,
                        "msg" => "Your login access is Inactive",
                    ];

                    $this->response->setStatusCode(200);
                    $this->response->setContentType('application/json');
                    return $this->response->setJSON($res);
                }
            } else {
                $res = [
                    'code'=>7,//Authentication problem
                    "errors" => true,
                    "msg" => "Wrong Password given",
                ];

                $this->response->setStatusCode(200);
                $this->response->setContentType('application/json');
                return $this->response->setJSON($res);
            }
        } else {

            $res = [
                'code'=>7,//authentication problem
                "errors" => true,
                "msg" => "No account found with this email",
            ];

            $this->response->setStatusCode(200);
            $this->response->setContentType('application/json');
            return $this->response->setJSON($res);
        }
    }


    private  function otpGenerate()
    {
        $len = 6;
        $otpRange = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $otp = "";
        for ($i = 0; $i < $len; $i++) {
            $index = rand(0, strlen($otpRange) - 1);
            $otp .= $otpRange[$index];
        }

        return $otp;
    }


    private function sentEmail($to, $otp)
    {
        $email = service('email');
        $email->setTo($to);
        $email->setSubject('Verify your access');
        $message = view('Email/otpsent.php', ['otp' => $otp]);
        $email->setMessage($message);
        if ($email->send()) {
            return true;
        } else false;
    }
}
