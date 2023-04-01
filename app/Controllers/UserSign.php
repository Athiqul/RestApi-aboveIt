<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Entities\UserAccessEntity;



class UserSign extends ResourceController
{
     use ResponseTrait;

    

     //Post request
    public function userCreate()
    {
        //Validation process
        $validate=[
           "key"=>[
            "rules"=>"required",
            "errors"=>"Key is required"
        ],
           "user_name"=>[
            "rules"=>"required|alpha_numeric_space|min_length[3]",
            "errors"=>[
                "required"=>"Name Required!",
                "alpha_numeric_space"=>"Use only alpha numeric space!!!",
                "min_length"=>"Minimum choose 3 characters",
            ]
        ],
           "email"=>[
            "rules"=>"required|valid_email|is_unique",
            "errors"=>[
                "required"=>"Provide Email!",
                "valid_email"=>"Provide Valid Email",
                "is_unique"=>"Already exist this email with another account",
            ]
        ],
           "mobile"=>[
            "rules"=>"required|regex_match[017+[0-9]{8}|018+[0-9]{8}|013+[0-9]{8}|014+[0-9]{8}|019+[0-9]{8}|015+[0-9]{8}|016+[0-9]{8}]",
            "errors"=>[
                "required"=>"Mobile number needed",
                 "regex_match"=>"Give the correct number like 017XXXXXXX1"
            ],
        ],

        "password"=>[
            "rules"=>"required|min_length[8]",
            "errors"=>[
                "required"=>"Password needed",
                "min_length"=>"Password Should be 8 characters",
            ],
        ],

        "conpass"=>[
            "rules"=>"required|min_length[8]|matches[password]",
            "errors"=>[
                "required"=>"Confirm password needed !",
                "min_length[8]"=>"Confirm password should be minimum 8 characters also",
                "matches"=>"Password should be match with confirm password",
            ]
        ],

        ];

        if(!$this->validate($validate))

        {
              $errors=[
                'code'=>401,
                "errors"=>true,
                "msg"=>$this->validator->getErrors(),
            ]; 
              $this->response->setStatusCode(401);
              $this->response->setContentType('application/json');
              return $this->response->setJSON($errors);

              //return $this->response->send();
            
        }


       if(getenv('API_SECRET')!=$this->request->getVar('key'))

       {
           $errors=[
            'code'=>402,
            "errors"=>true,

            "msg"=>[
                "error"=>"Access denied"
            ]
           ];
           $this->response->setStatusCode(402);
           $this->response->setContentType('application/json');
          return $this->response->setJSON($errors);
           
       }


      $model=new UserModel();
      $user= new UserAccessEntity();

     
        $user->user_name=$this->request->getVar('user_name');
        $user->email=$this->request->getVar('email');
        $user->mobile=$this->request->getVar('mobile');
        $user->password=md5($this->request->getVar('password'));
        $user->address=$this->request->getVar('address');
        $user->role=1;
     

      if($model->insert($user))
      {
           $res=[
            'code'=>200,
            "errors"=>null,
            "msg"=>[
                "success"=>"Congratulations! User Account Created"
            ]
           ];
           $this->response->setStatusCode(200);
           $this->response->setContentType('application/json');
          return $this->response->setJSON($res);
           
      }

      else{

        $res=[
            'code'=>403,
            "errors"=>true,
            "msg"=>[
                "error"=>$model->errors(),
            ]
           ];
           $this->response->setStatusCode(403);
           $this->response->setContentType('application/json');
           return $this->response->setJSON($res);
           
      }




    }

    public function userlist()
    {
        $userModel=new UserModel();
        $users=$userModel->where(['role'=>'1'])->findAll();

        if($users==null)
        {
            return $this->setResponse(200,true,"No account found");
        }

        return $this->setResponse(200,false,$users);
    }


    public function userProfile($id)
    {
      //all information
      // user personal info
      $userModel=new UserModel();
      $user=$userModel->where(['id'=>$id,'role'=>'1'])->find();
      if($user==null)
      {
          return $this->setResponse(200,true,"No account found");
      }
      
      $otpModel=new \App\Models\OtpModel();
      $lastLogin=$otpModel->where(['user_id'=>$id,'status'=>'1'])->orderBy('id','desc')->first();

      if($lastLogin==null)
      {
        $lastLogin="";
    }


      $blogModel=new \App\Models\BlogModel();

      $blogs=$blogModel->where('user_id',$id)->orderBy('id','desc')->paginate(10);
    
      $data=[
        "info"=>$user,
        "last_login"=>$lastLogin,
        "latest_blog"=>$blogs,
        'pager'=>$blogModel->pager,
      ];

      return $this->setResponse(200,false,$data);
      //user last login
      //user last 10 blog list
      //user last login time
    }



    //Update profile info

    public function updateUserProfile($id)
    {
    
        //find that blog exist or not exist
        $userModel=new UserModel();
        $user=$userModel->find($id);
        if($user==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        
        if($this->request->getVar('password'))
        {
            $pass=md5($this->request->getVar('password'));
           if( $userModel->update($id,['password'=>$pass]))
           {
            return $this->setResponse(200,false,'Password Updated');
           }
           else{
            return $this->setResponse(403,true,$userModel->errors());
           }
            
        }
        $user->fill($userRequest);
        

        if(!$user->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        if($userModel->save($user)){
            return $this->setResponse(200,false,'Profile Updated');
        }

        else{
            return $this->setResponse(403,true,$userModel->errors());
        }


    }

    private function setResponse($code,$error,$msg)
    {
        $res = [
            'code'=>$code,
            "errors" => $error,
            "msg" => $msg,
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }
}
