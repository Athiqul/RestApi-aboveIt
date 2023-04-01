<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;

use \App\Models\CallbackRequestModel;
use \App\Entities\CallbackRequestEntity;

class CallbackRequest extends ResourceController
{
    private $key;
    private $callModel;

    public function __construct()
    {
        $this->key=getenv('API_SECRET');
        $this->callModel=new CallbackRequestModel();
    }
    //Show All  solved callback request
    public function index()
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
        
    
        $total=count($this->callModel->where('status',"0")->findAll());
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

        $row=$this->callModel->where('status',"0")->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    //Show pending call request for admin panel
    public function showAllPendingRequest()
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
        
    
        $total=count($this->callModel->where('status',"1")->findAll());
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

        $row=$this->callModel->where('status','1')->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    //to create a Call back request
    public function create()
    {
        $validate = [
            "key" => [
                "rules" => "required",
                "errors" => [
                    "required" => "key is missing"
                ],
            ],
            "user_id" => [
                "rules" => "required|numeric|is_not_unique[user_access.id]",
                "errors" => [
                    "required" => "User is not Authentic",
                    "is_not_unique"=>"Please Login Again",
                ],
            ],

            "customer_name" => [
                "rules" => "required|min_length[3]|alpha_space",
                "errors" => [
                    "required" => "Service Title Missing",
                    "min_length[3]" => "Service title Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet and space are Allowed"
                ],
            ],
           
            "email"=>[
                "rules"=>"required|valid_email",
                "errors"=>[
                    "required"=>"Provide Email!",
                    "valid_email"=>"Provide Valid Email",
                ]
            ],
               "mobile"=>[
                "rules"=>"required|regex_match[017+[0-9]{8}|018+[0-9]{8}|013+[0-9]{8}|014+[0-9]{8}|019+[0-9]{8}|015+[0-9]{8}|016+[0-9]{8}]",
                "errors"=>[
                    "required"=>"Mobile number needed",
                     "regex_match"=>"Give the correct number like 017XXXXXXX1"
                ],
            ],

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(402,true,"Invalid Access");
        }

        $callEntity= new CallbackRequestEntity();
        $callInfo=$this->request->getVar();
        
        $callEntity->fill($callInfo);
        unset($callEntity->key);
        unset($callEntity->user_id);
        try{
            if($this->callModel->insert($callEntity))
            {
                return $this->setResponse(200,false,"Thank you for your interest ,We will contact you soon");
            }
        }catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }

    //Update callback request status
    public function statusUpdate($id)
    {
        $validate = [
            "key" => [
                "rules" => "required",
                "errors" => [
                    "required" => "key is missing"
                ],
            ],
            "status"=>[
                "rules"=>"required|numeric",
                "errors"=>[
                    "required"=>"Status Missing",
                    "numeric"=>"Only 0 and 1 accepted",
                ],
            ]
           

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(402,true,"Invalid Access");
        }


        //find that blog exist or not exist
        $call=$this->callModel->find($id);
        if($call==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        $call->fill($userRequest);
        unset($call->key);
        

        if(!$call->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        try{

            $this->callModel->save($call);
           return $this->setResponse(200,false,'Call Request updated');

        }catch(Exception $ex)
        {
            return $this->setResponse(403,true,$ex->getMessage());
        }

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
