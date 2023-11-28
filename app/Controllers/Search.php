<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpParser\Node\Expr\Cast\Object_;

class Search extends BaseController
{

    private $key;
  
    //constructor
    public function __construct()
    {
        $this->key=getenv('API_SECRET');
       
    }

    //All search From Service Product and Blog
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


        $keyword=$this->request->getVar('search');

        if(!isset($keyword))
        {
            return $this->setResponse('200',true,'No Searching Value');
        }
        //Searching
        //Getting Products
        $db=db_connect();
        $builder=$db->table('products');
        $builder->select('products.id,products.title,products.image,products.desc,products.brand,products.stock_status,user_access.user_name');
        
        $builder->Like('products.title',$keyword,'both');
        $builder->orLike('products.meta_tag',$keyword);
        $builder->orLike('products.meta_desc',$keyword);
         
        $builder->join('user_access','user_access.id=products.user_id');
        $productResult=$builder->get()->getResultArray(); 

        if($productResult!==null)
        {
            $items=[];
            foreach($productResult as $item)
            {
                 
                   $temp=[
                    "type"=>"products",
                   ];
                   $item+=$temp;
                   $items[]=$item;

            }

            $productResult=$items;
        }


        //Now Work With services
        $builder=$db->table('services');
        $builder->select('services.id,services.title,services.sub_title,services.desc,user_access.user_name');
        
        $builder->Like('services.title',$keyword,'both');
        $builder->orLike('services.sub_title',$keyword);
        $builder->orLike('services.meta_tag',$keyword);
        $builder->orLike('services.meta_desc',$keyword);
         
        $builder->join('user_access','user_access.id=services.user_id');
        $serviceResult=$builder->get()->getResultArray(); 

        if($serviceResult!==null)
        {
            $items=[];
            foreach($serviceResult as $item)
            {
                 
                   $temp=[
                    "type"=>"services",
                   ];
                   $item+=$temp;
                   $items[]=$item;

            }

            $serviceResult=$items;
        }


        //Work With Blogs
        $builder=$db->table('blog');
        $builder->select('blog.id,blog.title,blog.image,blog.desc,blog.publish_at,user_access.user_name');
        
        $builder->Like('blog.title',$keyword,'both');
        $builder->orLike('blog.meta_tag',$keyword);
        $builder->orLike('blog.meta_desc',$keyword);
         
        $builder->join('user_access','user_access.id=blog.user_id');
        $blogResult=$builder->get()->getResultArray(); 

        if($blogResult!==null)
        {
            $items=[];
            foreach($blogResult as $item)
            {
                 
                   $temp=[
                    "type"=>"blog",
                   ];
                   $item+=$temp;
                   $items[]=$item;

            }

            $blogResult=$items;
        }

    
        // $total=count($this->servicesModel->where('status',1)->findAll());
        // if($total==null||$total==0)
        // {
        //     return $this->setResponse(200,true,"No data found");    
        // }  
        // $totalPage=ceil($total/$limit);

        // if($totalPage<$page)
        // {
        //     return $this->setResponse(200,true,"No More records");  
        // }
        // $offset=($page-1)*$limit;

        // $row=$this->servicesModel->where('status',1)->orderBy('id','desc')->findAll($limit,$offset);  
        
        $merge=array_merge($productResult,$serviceResult,$blogResult);
        shuffle($merge);
        
        
        $res = [
            'code'=>200,
            "errors" => false,
            "msg" => $merge,
            "total"=>count($merge),
            // "totalPage"=>$totalPage,
            // "currentPage"=>$page
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }

    //Service Search

    //Product Search

    //Blog Search


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
