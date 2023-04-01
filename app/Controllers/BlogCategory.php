<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BlogCategoryModel;
use App\Entities\BlogCategoryEntity;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;

class BlogCategory extends ResourceController
{


    use ResponseTrait;
  
    protected $model;

    public function __construct()
  {
    $this->model=new BlogCategoryModel();
  }
  
    //Show All Category List get request
    public function index()
    {
        //

        $model= new BlogCategoryModel();
         
        $data= $model->findAll();
        if($data==null)
        {
            return $this->setResponse(200,true,"No data found");
        }
        return $this->setResponse(200,false,$data);

    }

      //active category List
      public function activeCategory()
    {
        //

        $model= new BlogCategoryModel();
         
        $data= $model->where('status','1')->findAll();
        if($data==null)
        {
            return $this->setResponse(200,true,"No active Category found");
        }
        return $this->setResponse(200,false,$data);

    }
    //Create Category 
    public function create()
    {
        //dd(session()->get('loggedId'));
        //Validation Process
        $validate = [
            "key" => [
                "rules" => "required",
                "errors" => [
                    "required" => "key is missing"
                ],
            ],
            "user_id" => [
                "rules" => "required",
                "errors" => [
                    "required" => "User is not Authentic",
                ],
            ],

            "cat_name" => [
                "rules" => "required|min_length[3]|alpha_space",
                "errors" => [
                    "required" => "Category Title Missing",
                    "min_length[3]" => "Category Name Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet and space are Allow"
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

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }


        //checking key 
        if (getenv('API_SECRET') !== $this->request->getVar('key')) {
            return $this->setResponse(402,true,"Invalid Access");
        }

       //Get User from session
       
       $categoryData=new BlogCategoryEntity();
       $categoryData->fill($this->request->getVar());
       $model= new BlogCategoryModel();

       if($model->save($categoryData))
       {
            return $this->setResponse(200,false,"Blog Category Added");
       }
       else{
           return $this->setResponse(403,true,$model->errors());
       }

    }

    // Edit Category get method
    public function editCategory($cat_id)
    {
      
        $model=new BlogCategoryModel();        
        $data= $model->where('id',$cat_id)->first();        
        if($data)
        {
          return $this->setResponse(200,false,$data);
        }

       return $this->setResponse('407',true,"Not found");
    }

    //Update category info
    
    public function updateCategory($cat_id)
    {
        //
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
        if (getenv('API_SECRET') !== $this->request->getVar('key')) {
            return $this->setResponse(402,true,"Invalid Access");

        }

        $data= $this->model->where('id',$cat_id)->first();
              
        if($data==null)
        {
            return $this->setResponse('407',true,"Not found");
                   
        }
        $userRequest= $this->request->getVar();

        $data->fill($userRequest);
        unset($data->key);

        //dd(var_dump($data)); 
           //difference check
           
           if(!$data->hasChanged())
           {
            return $this->setResponse(400,true,"Nothing to Update");
           }
          if( $this->model->save($data))
          {
            return $this->setResponse(200,false,"Category Updated");
          }

          return $this->setResponse(403,true,$this->model->errors());

      
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
