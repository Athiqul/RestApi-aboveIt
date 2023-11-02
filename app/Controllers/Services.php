<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;
use \App\Models\ServicesModel;
use \App\Entities\ServicesEntity;

class Services extends ResourceController
{
    private $key;
    private $servicesModel;
    //constructor
    public function __construct()
    {
        $this->key=getenv('API_SECRET');
        $this->servicesModel=new ServicesModel();
    }
    //Active product show
     //get routes name products
     public function index()
     {
        // Take pages and limit from the client site
           
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
        
    
        $total=count($this->servicesModel->where('status',1)->findAll());
        if($total==null||$total==0)
        {
            return $this->setResponse(200,true,"No data found");    
        }  
        $totalPage=ceil($total/$limit);

        if($totalPage<$page)
        {
            return $this->setResponse(200,true,"No More records");  
        }
        $offset=($page-1)*$limit;

        $row=$this->servicesModel->where('status',1)->orderBy('id','desc')->findAll($limit,$offset);  
        
        
        $res = [
            'code'=>200,
            "errors" => false,
            "msg" => $row,
            "totalPage"=>$totalPage,
            "currentPage"=>$page
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
     }
    //Product Create post method
    public function create()
    {
         //validation process

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

            "title" => [
                "rules" => "required|min_length[3]",
                "errors" => [
                    "required" => "Service Title Missing",
                    "min_length[3]" => "Service title Should be minimum 3 characters",
                  
                ],
            ],

            "sub_title" => [
                "rules" => "required|min_length[3]",
                "errors" => [
                    "required" => "Service Sub Title Missing",
                    "min_length[3]" => "Service title Should be minimum 3 characters",
                   
                ],
            ],
            "image" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Image Link missing",
                ],
            ],
            "desc" => [
                "rules" => "min_length[10]",
                "errors" => [
                    "min_length[10]" => "Description Should be minimum 10 characters",
                ],
            ],

            "meta_desc" => [
                "rules" => "min_length[3]",
                "errors" => [
                    "min_length[3]" => "Meta Desc Should be minimum 3 characters",
                    
                ],
            ],

            "meta_tag" => [
                "rules" => "min_length[3]",
                "errors" => [
                    "min_length[3]" => "Meta Tag Should be minimum 3 characters",
                
                ],
            ],

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(200,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(200,true,"Invalid Access");
        }

        $servicesEntity= new ServicesEntity();
        $serviceInfo=$this->request->getVar();
        
        $servicesEntity->fill($serviceInfo);
        unset($servicesEntity->key);
        try{
            if($this->servicesModel->insert($servicesEntity))
            {
                return $this->setResponse(200,false,"Service Created");
            }
            else{
                return $this->setResponse(200,true,$this->servicesModel->errors());
            }
        }catch(Exception $ex){
            return $this->setResponse(200,true,$ex->getMessage());
        }
    }
    //Product show by id get method
    public function showService($service_id)
    {
            $service=$this->servicesModel->where('id',$service_id)->first();
            if($service==null)
            {
                return $this->setResponse(200,true,'Service does not exist');
            }

            return $this->setResponse(200,false,$service);
    }
    //deactive product list show by get method
    public function deactivatedServices()
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

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(200,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(200,true,"Invalid Access");
        }

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
        
    
        $total=count($this->servicesModel->where('status',"0")->findAll());
        if($total==null||$total==0)
        {
            return $this->setResponse(200,true,"No data found");    
        }  
        $totalPage=ceil($total/$limit);

        if($totalPage<$page)
        {
            return $this->setResponse(200,true,"No More records");  
        }
        $offset=($page-1)*$limit;

        $row=$this->servicesModel->where('status',"0")->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    //update product information
    public function updateService($service_id)
    {
        $validate = [
            "key" => [
                "rules" => "required",
                "errors" => [
                    "required" => "key is missing"
                ],
            ],
           

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(200,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(200,true,"Invalid Access");
        }


        //find that blog exist or not exist
        $service=$this->servicesModel->find($service_id);
        if($service==null)
        {
            return $this->setResponse(200,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        $service->fill($userRequest);
        unset($service->key);
        

        if(!$service->hasChanged())
        {
            return $this->setResponse(200,true,'Nothing to update');
        }

        try{
            $this->servicesModel->save($service);
            return $this->setResponse(200,false,'Service Updated');
        }catch(Exception $ex){
            return $this->setResponse(200,true,$ex->getMessage());
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
