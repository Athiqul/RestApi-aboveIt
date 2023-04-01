<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use \App\Models\ContestModel;
use \App\Entities\ContestEntity;
use Exception;

class Contest extends ResourceController
{
    private $contestModel;

    public function __construct()
    {
        $this->contestModel= new ContestModel();
    }
    public function create()
    {
        //Validation

        $validate=[
            "key"=>[
             "rules"=>"required",
             "errors"=>"Key is required"
         ],
            "participant_name"=>[
             "rules"=>"required|alpha_space|min_length[3]",
             "errors"=>[
                 "required"=>"Name Required!",
                 "alpha_space"=>"Use only alpha space!!!",
                 "min_length"=>"Minimum 3 characters",
             ]
         ],
            "email"=>[
             "rules"=>"required|valid_email|is_unique[contest.email]",
             "errors"=>[
                 "required"=>"Provide Email!",
                 "valid_email"=>"Provide Valid Email",
                 "is_unique"=>"Already exist this email with another account",
             ]
         ],
            "mobile"=>[
             "rules"=>"required|regex_match[017+[0-9]{8}|018+[0-9]{8}|013+[0-9]{8}|014+[0-9]{8}|019+[0-9]{8}|015+[0-9]{8}|016+[0-9]{8}]|is_unique[contest.mobile]",
             "errors"=>[
                 "required"=>"Mobile number needed",
                  "regex_match"=>"Give the correct number like 017XXXXXXX1",
                  "is_unique"=>"Already exist this mobile number with another account",
             ],
         ],
 
         "address"=>[
             "rules"=>"required",
             "errors"=>[
                 "required"=>"Adress info missing",
             ],
         ],
 
         "institute"=>[
             "rules"=>"required",
             "errors"=>[
                 "required"=>"If you are student write your varsity/college name Or Job holder you can write your company name !",
             ]
         ],

         "yob"=>[
            "rules"=>"required",
            "errors"=>[
                "required"=>"Please provide your Year of Birth",
            ]
        ],
        "contest_type"=>[
            "rules"=>"required",
            "errors"=>[
                "required"=>"Choose contest UI/UX or Front End",
            ]
        ],
         
        "referrer"=>[
            "rules"=>"required",
            "errors"=>[
                "required"=>"From where did you know about this contest?",
            ]
        ],
        "opinion"=>[
            "rules"=>"required|min_length[20]|max_length[150]",
            "errors"=>[
                "required"=>"What do you know about ABOVE IT?",
                "min_length"=>"Minimum 20 characters to write about Above IT",
                "max_length"=>"Maximum 150 characters you can write on Above IT",
            ]
        ],

        "joining"=>[
            "rules"=>"required",
            "errors"=>[
                "required"=>"Are you interest to work with Above IT or not?",
            ]
        ],
 
         ];
 
         if(!$this->validate($validate))
 
         {
              
            return $this->setResponse(403,true,$this->validator->getErrors());
             
         }
 
 
        if(getenv('API_SECRET')!=$this->request->getVar('key'))
 
        {
           return $this->setResponse(403,true,"Access denied");
        }

       $entity=new ContestEntity();
       $entity->fill($this->request->getVar());
       try{
         $this->contestModel->insert($entity);
         $lastId=$this->contestModel->getInsertID();
         $email=$this->request->getVar('email');
         if($this->sentEmail($email,$entity))
         {
            return $this->setResponse(200,false,"Congratulations Check Your Email Please");      
         }else{
            $data=[
                "mail_receive"=>"0",
            ];
            $this->contestModel->update($lastId,$data);
            return $this->setResponse(200,true,"Email did not sent but application stored");
         }
         
         

       }catch(Exception $ex){
           return $this->setResponse(403,true,$ex->getMessage());
       }
 


    }

    //show all application list

    public function showAll()
    {
        if($this->request->getVar('page'))
        {
            $page=$this->request->getVar('page');
        }
        else{
            $page=1;
        }
        
        if($this->request->getVar('limit'))
        {
            $limit=$this->request->getVar('limit');
        }
        else{
            $limit=10;
        }
        
    
        $total=count($this->contestModel->findAll());
        $today= date('Y-m-d');
        $builder=count($this->contestModel->where('date(created_at)',$today)->findAll());
        if($total==null||$total==0)
        {
            return $this->setResponse(407,true,"No data found");    
        }  
        $totalPage=ceil($total/$limit);

        if($totalPage<$page)
        {
            return $this->setResponse(403,true,"No More records");  
        }
        $offset=($page-1)*$limit;

        $row=$this->contestModel->orderBy('id','desc')->findAll($limit,$offset);  
        
        
        $res = [
            'code'=>200,
            "errors" => true,
            "msg" => $row,
            "totalPage"=>$totalPage,
            "currentPage"=>$page,
            "todayApplied"=>$builder,
            "totalApplied"=>$total,
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }

    //Email did not receive list

    public function emailFailedList()
    {
        if($this->request->getVar('page'))
        {
            $page=$this->request->getVar('page');
        }
        else{
            $page=1;
        }
        
        if($this->request->getVar('limit'))
        {
            $limit=$this->request->getVar('limit');
        }
        else{
            $limit=10;
        }
        
    
        $total=count($this->contestModel->where('mail_receive',"0")->findAll());
        if($total==null||$total==0)
        {
            return $this->setResponse(407,true,"No data found");    
        }  
        $totalPage=ceil($total/$limit);

        if($totalPage<$page)
        {
            return $this->setResponse(403,true,"No More records");  
        }
        $offset=($page-1)*$limit;

        $row=$this->contestModel->where('mail_receive',"0")->orderBy('id','desc')->findAll($limit,$offset);  
        
        
        $res = [
            'code'=>200,
            "errors" => true,
            "msg" => $row,
            "totalPage"=>$totalPage,
            "currentPage"=>$page
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }

    //email resent

    public function emailResent($id)
    {
        $row=$this->contestModel->find($id);
        if($row==null)
        {
            return $this->setResponse(403,true,'Invalid candidate');
        }
          
      try{
        if($this->sentEmail($row->email,$row))
        {
            $data=[
                "mail_receive"=>"1",
            ];
            $this->contestModel->update($row->id,$data);
            return $this->setResponse(200,false,"Email sent to ".$row->participant_name);
        }
      }catch(Exception $ex){

        return $this->setResponse(200,true,$ex->getMessage());
      }
        
       
        

    }

    //report datewise

    private function sentEmail($to, $candidate)
    {
        $email = service('email');
        $email->setTo($to);
        $email->setSubject('Confirmation of Your Participation in the Above IT '.($candidate->contest_type)? 'UI UX Design Contest' :  'Frontend Developer Contest');
        $message = view('Email/contest', ['candidate' => $candidate]);
        $email->setMessage($message);
        if ($email->send()) {
            return true;
        } else return false;
    }

    private function setResponse($code,$error,$msg)
    {
        $res = [
            'code'=>$code,
            "errors" => $error,
            "msg" => $msg,
        ];

        $this->response->setStatusCode($code);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }
}
