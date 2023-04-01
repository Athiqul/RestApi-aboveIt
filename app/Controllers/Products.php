<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;
use Exception;
use \App\Models\ProductModel;
use \App\Entities\ProductEntity;

class Products extends ResourceController
{
    private $key;
    private $productModel;
    //constructor
    public function __construct()
    {
        $this->key=getenv('API_SECRET');
        $this->productModel=new ProductModel();
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
        
    
        $total=count($this->productModel->where('status',1)->findAll());
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

        $row=$this->productModel->where('status',1)->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
                "rules" => "required|min_length[3]|alpha_numeric_space",
                "errors" => [
                    "required" => "Product Title Missing",
                    "min_length[3]" => "Product title Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet,Numeric and space are Allowed"
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
                "rules" => "min_length[3]|alpha_numeric_space",
                "errors" => [
                    "min_length[3]" => "Meta Desc Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet,Numeric and space are Allow"
                ],
            ],

            "meta_tag" => [
                "rules" => "min_length[3]|alpha_numeric_space",
                "errors" => [
                    "min_length[3]" => "Meta Tag Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet,Numeric and space are Allow"
                ],
            ],

            "brand"=>[
                "rules" => "required",
                "errors" => [
                    "required" => "Provide Product Brand name!",
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

        $productEntity= new ProductEntity();
        $productInfo=$this->request->getVar();
        
        $productEntity->fill($productInfo);
        unset($productEntity->key);
        try{
            if($this->productModel->insert($productEntity))
            {
                return $this->setResponse(200,false,"Product Created");
            }
        }catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }
    //Product show by id get method
    public function showProduct($product_id)
    {
            $product=$this->productModel->where('id',$product_id)->first();
            if($product==null)
            {
                return $this->setResponse(403,true,'Product does not exist');
            }

            return $this->setResponse(200,false,$product);
    }
    //deactive product list show by get method
    public function allProducts()
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
        
    
        $total=count($this->productModel->orderBy('id','desc')->findAll());
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

        $row=$this->productModel->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    //update product information
    public function updateProduct($product_id)
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
        $product=$this->productModel->find($product_id);
        if($product==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        $product->fill($userRequest);
        unset($product->key);
        

        if(!$product->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        if($this->productModel->save($product)){
            return $this->setResponse(200,false,'Blog Updated');
        }

        else{
            return $this->setResponse(403,true,$this->productModel->errors());
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
