<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use \App\Models\ClientReviewModel;
use \App\Entities\ClientReviewEntity;
use Exception;

class ClientReview extends ResourceController
{
    private $key;
    private $reviewModel;

    public function __construct()
    {
        $this->key=getenv('API_SECRET');
        $this->reviewModel=new ClientReviewModel();
    }
    //for front page show all active review for client maximum 5 review
    public function index()
    {
        $reviews=$this->reviewModel->where('status',"1")->orderby('id','desc')->findAll(5);
        if($reviews==null)
        {
            return $this->setResponse(403,true,"No data found");
        }        
        $res = [
            'code'=>200,
            "errors" => true,
            "msg" => $reviews,
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }
    //Show inactive review for admin panel
    public function showAllReview()
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
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(402,true,"Invalid Access");
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
        
    
        $total=count($this->reviewModel->findAll());
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

        $row=$this->reviewModel->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    //to create a customer review
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
            "image" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Image Link missing",
                ],
            ],
            "company" => [
                "rules" => "min_length[3]",
                "errors" => [
                    "min_length" => "Description Should be minimum 3 characters",
                ],
            ],

            "quote" => [
                "rules" => "min_length[10]",
                "errors" => [
                    "min_length" => "Quote or opinion Should be minimum 10 characters",
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

        $reviewEntity= new ClientReviewEntity();
        $reviewInfo=$this->request->getVar();
        
        $reviewEntity->fill($reviewInfo);
        unset($reviewEntity->key);
        unset($reviewEntity->user_id);
        try{
            if($this->reviewModel->insert($reviewEntity))
            {
                return $this->setResponse(200,false,"Customer Review Added");
            }
        }catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }

    //Update customer review
    public function updateReview($id)
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
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }


        //checking key 
        if ($this->key !== $this->request->getVar('key')) {
            return $this->setResponse(402,true,"Invalid Access");
        }


        //find that blog exist or not exist
        $review=$this->reviewModel->find($id);
        if($review==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        $review->fill($userRequest);
        unset($review->key);
        

        if(!$review->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        try{

            $this->reviewModel->save($review);
           return $this->setResponse(200,false,'Customer Review Updated');

        }catch(Exception $ex)
        {
            return $this->setResponse(403,true,$ex->getMessage());
        }

    }
    //show particular review for admin panel
    public function showReview($id)
    {
        $review=$this->reviewModel->where('id',$id)->first();
        if($review==null)
        {
            return $this->setResponse(403,true,'Product does not exist');
        }

        return $this->setResponse(200,false,$review);
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
