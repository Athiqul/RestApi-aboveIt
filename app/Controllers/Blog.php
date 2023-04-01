<?php

namespace App\Controllers;


use CodeIgniter\RESTful\ResourceController;

//model
use \App\Models\BlogModel;
use \App\Models\BlogUnderCatModel;
use \App\Models\BlogCategoryModel;

use \App\Entities\BlogEntity;

//
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;
use Exception;

class Blog extends ResourceController
{
    use ResponseTrait;
    private $key;
    protected $blogModel;
    protected $blogUnCat;

    public function __construct()
    {
        $this->blogModel= new BlogModel();
        $this->blogUnCat= new BlogUnderCatModel();
        $this->key=getenv('API_SECRET');
    }


    //Get method Show All the active blog list with paginate
    public function index()
    {
        //Request verify
        //Need page number
        //Need limit
        //check 

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
        
    
        $total=count($this->blogModel->where('status',1)->findAll());
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

        $row=$this->blogModel->where('status',1)->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    

    //inactive blog get method 
    public function allBlogList()
    {
        //Request verify
       
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
        //Need page number
        //Need limit
        //check 

        if($this->request->getVar('page'))
        {
            $page=$this->request->getVar('page');
        }
        else{
            $page=1;
        }

        $limit=10;
    
        $total=count($this->blogModel->where('status',0)->findAll());
        if($total==null||$total==0)
        {
            return $this->setResponse(407,true,"No data found");    
        }  
        $totalPage=ceil($total/$limit);

        if($totalPage<$page)
        {
            return $this->setResponse(200,false,"No More records");  
        }
        $offset=($page-1)*$limit;

        
        $query=$this->blogModel;
        $query->select( 'blog.id, user_access.user_name, blog.title ,blog.status,blog.created_at');
        $query->join('user_access','blog.user_id= user_access.id',);
        $query->limit($limit,$offset);
        $row=$query->get()->getResult(); 
      
        
        
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

    //Get Method show particular blog
    public function showBlog($id)
    {
        $row=$this->blogModel->find($id);

        
        if($row==null)
        {
            return $this->setResponse(403,true,"Blog not found");
        }
       $getCategories=$this->showCatListUnderBlog($id);
       $selectCat=[];

       foreach($getCategories as $item){
           $selectCat[]=$item->id;
       }

       $getCatModel=new BlogCategoryModel();

        $data=[
         "blog"=>$row,
         "categories"=>$getCategories,
         "noselectcat"=>$getCatModel->whereNotIn('id',$selectCat),
        ];
       // return $this->response->setJSON(['data'=>$this->showCatListUnderBlog($id)]);
        return $this->setResponse(200,false,$data);
    }

    //find category list
    private function showCatListUnderBlog($blogId)
    {
        $getBlogCatId=$this->blogUnCat->where('blog_id',$blogId)->findAll();
        //dd($getBlogCatId);
        $data=[];
        helper('categoryName');
        foreach($getBlogCatId as $item)
        {
            $data[]=getCategoryName($item->cat_id);
        }
       return $data;
    }
    private function NotSelectedCatListUnderBlog($blogId)
    {
        $getBlogCatId=$this->blogUnCat->whereNotIn('blog_id',$blogId)->findAll();
        //dd($getBlogCatId);
        $data=[];
        helper('categoryName');
        foreach($getBlogCatId as $item)
        {
            $data[]=getCategoryName($item->cat_id);
        }
       return $data;
    }
    //get method show blog list in terms category 
    public function categoryBlog($cat_id)
    {
        $catModel=new BlogCategoryModel();
        if($catModel->where(['status'=>1,'id'=>$cat_id])->first())
        {
            $catUnderBlog=new BlogUnderCatModel();
            //get all blog id;
            $findBlog=$catUnderBlog->where('cat_id',$cat_id)->findAll();
            $data=[];
            foreach($findBlog as $blog)
            {
                $data[]=$this->blogDetail($blog->blog_id);
            }
            
            return $this->setResponse(200,false,$data);
        }

        return $this->setResponse(402,true,"This category does not exist");
    }


    private function blogDetail($blog)
    {
       return $this->blogModel->where('id',$blog)->findAll();

    }
  


    //post method create blog
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
                "rules" => "required|min_length[3]|alpha_space",
                "errors" => [
                    "required" => "Blog Title Missing",
                    "min_length[3]" => "Category Name Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet and space are Allow"
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

            "cat_id"=>[
                "rules" => "required",
                "errors" => [
                    "required" => "Select Categories!",
                ],
            ],

            "publish_at" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Provide Valid date month and Year",
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

        //checking cat_id valid or not
        $catList=$this->request->getVar('cat_id');
        $blogCat=new BlogCategoryModel();
        $exist_cat= $blogCat->whereIn('id',$catList)->findAll();
        if(count($catList)!==count($exist_cat))
        {
            return $this->setResponse(402,true,"Category does not exist");
        }


        $blogEntity= new BlogEntity();
        $blogInfo=$this->request->getVar();
        
        $blogEntity->fill($blogInfo);
        unset($blogEntity->key);
        unset($blogEntity->cat_id);
        
        
        
       

        try{
            if($this->blogModel->insert($blogEntity))
            {
                $blog_id=$this->blogModel->getInsertID();
                foreach($catList as $item)
                {
                     $data=[
                       "cat_id"=>$item,
                       "blog_id"=>$blog_id,
                     ];
                     $this->blogUnCat->insert($data);
                }
    
                return $this->setResponse(200,false,"Blog Created");
            }
        }catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
        

    }
    
    //post method to update particular blog
    public function updateBlogInfo($id)
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
        $blog=$this->blogModel->find($id);
        if($blog==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        $blog->fill($userRequest);
        unset($blog->key);
        

        if(!$blog->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        if($this->blogModel->save($blog)){
            return $this->setResponse(200,false,'Blog Updated');
        }

        else{
            return $this->setResponse(403,true,$this->blogModel->errors());
        }


    }
    //Blog Category Update and Remove;


    public function blogCategoryAdded($blog_id)
    {
        $validate = [
            "key" => [
                "rules" => "required",
                "errors" => [
                    "required" => "key is missing"
                ],
            ],
            "cat_id" => [
                "rules" => "required",
                "errors" => [
                    "required" => "cat_id is missing"
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

        //checking blog
        $blog=$this->blogModel->where('id',$blog_id)->first();
        //dd($blog);
        if($blog==null)
        {
           return $this->setResponse(403,true,' this blog does not exist!'); 
        }

        $getCat_id=$this->request->getVar('cat_id');
       
        $blogCat=new BlogCategoryModel();
        $exist_cat= $blogCat->whereIn('id',$getCat_id)->findAll();

        if(count($getCat_id)!==count($exist_cat))
        {
            return $this->setResponse(402,true,"Category does not exist");
        }
        //check those cat_id already in blog or not
        foreach($getCat_id as $cat_id)
        {
            $result= $this->checkCategory($cat_id,$blog_id);

            if($result!=null)
            {
                //dd(getCategoryName($cat_id)->cat_name);
                  return $this->setResponse(403,true,getCategoryName($cat_id)->cat_name." already exist for this blog!");
            }

            $data=[
                "cat_id"=>$cat_id,
                "blog_id"=>$blog_id,
              ];
              $this->blogUnCat->insert($data);
        }
      
       return $this->setResponse(200,false,"Updated category");


    }

    public function removeCategoryFromBlog ($blog_id)
    {
        $validate = [
            "key" => [
                "rules" => "required",
                "errors" => [
                    "required" => "key is missing"
                ],
            ],
            "cat_id" => [
                "rules" => "required",
                "errors" => [
                    "required" => "cat_id is missing"
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

        //checking blog
        $blog=$this->blogModel->where('id',$blog_id)->first();
        //dd($blog);
        if($blog==null)
        {
           return $this->setResponse(403,true,' this blog does not exist!'); 
        }

        $getCat_id=$this->request->getVar('cat_id');
       

        //check those cat_id already in blog or not
        foreach($getCat_id as $cat_id)
        {
            $result= $this->checkCategory($cat_id,$blog_id);

            if($result==null)
            {
                //dd(getCategoryName($cat_id)->cat_name);
                  return $this->setResponse(403,true,getCategoryName($cat_id)->cat_name." category does not exist in this blog!");
            }

            $data=[
                "cat_id"=>$cat_id,
                "blog_id"=>$blog_id,
              ];
              $this->blogUnCat->where($data)->delete();
        }
      
       return $this->setResponse(200,false,"Category deleted from ".$blog->title);

    }

    private function checkCategory($cat_id,$blog_id)
    {
       
        
        $result=$this->blogUnCat->where(['blog_id'=>$blog_id,'cat_id'=>$cat_id])->first();
        
        return $result;

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
//challenge facing on category