<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use Exception;

use \App\Models\PackageCategoryModel;
use \App\Models\PackagesModel;
use \App\Models\PackageServicesModel;


use \App\Entities\PackageCategoryEntity;
use \App\Entities\PackageServicesEntity;
use \App\Entities\PackagesEntity;

class Packages extends ResourceController
{
    private $key;
    private $packagesModel;
    private $categoryModel;
    private $servicesModel;

    public function __construct()
    {
        $this->key=getenv('API_SECRET');
        $this->packagesModel= new PackagesModel();
        $this->categoryModel=new PackageCategoryModel();
        $this->servicesModel=new PackageServicesModel();
    }
    //Show All Active Package Category list
    public function showActiveCategoryList()
    {
        $list=$this->categoryModel->where('status','1')->orderBy('id','desc')->findAll(20);
        if($list==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        return $this->setResponse(200,false,$list);
    }
    //Show Inactive Package Category List
    public function showDeactiveCategoryList()
    {
        $list=$this->categoryModel->where('status','0')->orderBy('id','desc')->findAll(20);
        if($list==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

       return $this->setResponse(200,false,$list);
    }
    //Create Package Category
    public function createCategory()
    {
        $validate = [
        
            "title" => [
                "rules" => "required|min_length[3]|alpha_space",
                "errors" => [
                    "required" => "Category Title Missing",
                    "min_length[3]" => "Category Name Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet and space are Allow"
                ],
            ],
        

            "meta_desc" => [
                "rules" => "min_length[3]",
                "errors" => [
                    "min_length[3]" => "Meta Desc Should be minimum 3 characters",
                ],
            ],

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }

        $catEntity= new PackageCategoryEntity();
        $catInfo=$this->request->getVar();
        
        $catEntity->fill($catInfo);
        try{
            $this->categoryModel->insert($catEntity);    
            return $this->setResponse(200,false,"Package Category Added");
            }
        catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }
    //Update Status of package Category
    
    public function categoryStatusUpdate($id)
    {
    
        //find that blog exist or not exist
        $call=$this->categoryModel->find($id);
        if($call==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
        //check for category 
        $call->fill($userRequest);
        if(!$call->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        try{

            $this->categoryModel->save($call);
           return $this->setResponse(200,false,'Category Status updated');

        }catch(Exception $ex)
        {
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }
    //Show All Active Package list
    public function activePackages()
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
        
    
        $total=count($this->packagesModel->where('status',1)->findAll());
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
        
        $query=$this->packagesModel;
        $query->select( 'packages.id, packages.title, packages.package_cat_id ,packages.status,package_cat.title as category, package_cat.meta_desc');
        $query->join('package_cat','packages.package_cat_id= package_cat.id',);
        $query->where('packages.status','1');
        $query->limit($limit,$offset);
        $row=$query->get()->getResult(); 
        $data=[];
         foreach($row as $item)
         {
            $query=$this->servicesModel;
            $query->select('package_services.services');
            $query->join('packages','package_services.package_id=packages.id', );
            $query->where('package_services.package_id',$item->id);
            $query->where('package_services.status','1');
            $services=$query->get()->getResult();
            $data[]=[
                "packages"=>$item,
                "services"=>$services,
            ];
         }       
        $res = [
            'code'=>200,
            "errors" => true,
            "msg" => $data,
            "totalPage"=>$totalPage,
            "currentPage"=>$page,
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }
    //show all packages active and inactive
    public function allPackages()
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
        
    
        $total=count($this->packagesModel->findAll());
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
        
        $query=$this->packagesModel;
        $query->select( 'packages.id, packages.title, packages.package_cat_id ,packages.status,package_cat.title as category, package_cat.meta_desc');
        $query->join('package_cat','packages.package_cat_id= package_cat.id',);
        $query->limit($limit,$offset);
        $row=$query->get()->getResult(); 
        $data=[];
         foreach($row as $item)
         {
            $query=$this->servicesModel;
            $query->select('package_services.services');
            $query->join('packages','package_services.package_id=packages.id', );
            $query->where('package_services.package_id',$item->id);
            $query->where('package_services.status','1');
            $services=$query->get()->getResult();
            $data[]=[
                "packages"=>$item,
                "services"=>$services,
            ];
         }       
        $res = [
            'code'=>200,
            "errors" => true,
            "msg" => $data,
            "totalPage"=>$totalPage,
            "currentPage"=>$page,
        ];

        $this->response->setStatusCode(200);
        $this->response->setContentType('application/json');
        return $this->response->setJSON($res);
    }
    //Show Particular Package Detail for admin

    public function showPackages($id)
    {
        $query=$this->packagesModel;
        $query->select('packages.id, packages.title, packages.package_cat_id ,packages.status,package_cat.title as category, package_cat.meta_desc');
        $query->join('package_cat','packages.package_cat_id= package_cat.id',);
        $query->where('packages.id',$id);
        $row=$query->get()->getResult();
        if($row==null)
        {
            return $this->setResponse(403,true,"Packages not found");
        }
       
        // get services
        $query=$this->servicesModel;
        $query->select('package_services.id, package_services.services,package_services.status');
        $query->join('packages','package_services.package_id=packages.id', );
        $query->where('package_services.package_id',$id);
        $query->where('package_services.status','1');
        $services=$query->get()->getResult();

        $data=[
         "package"=>$row,
         "services"=>$services,
        ];
       // return $this->response->setJSON(['data'=>$this->showCatListUnderBlog($id)]);
        return $this->setResponse(200,false,$data);
    }


    //Show Inactive Package List
    //Create Package Category
    public function createPackage()
    {
        $validate = [
        
            "title" => [
                "rules" => "required|min_length[3]|alpha_space",
                "errors" => [
                    "required" => "Category Title Missing",
                    "min_length[3]" => "Category Name Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet and space are Allow"
                ],
            ],
            
            "package_cat_id"=>[
               "rules"=>"required|numeric|is_not_unique[package_cat.id]",
               "errors"=>[
                "required"=>"Package ID missing",
                "numeric"=>"Package ID Should Be numeric",
                "is_not_unique"=>"Invalid Package ID",
               ]
            ],

            "services" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Please Provide Services list",
                ],
            ],

        ];


        //check 
        if (!$this->validate($validate)) {
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }

        $catEntity= new PackagesEntity();
        $catInfo=$this->request->getVar();
        $getServices=$this->request->getVar('services');
        $catEntity->fill($catInfo);
        unset($catEntity->services);
        try{
            if( $this->packagesModel->insert($catEntity))
            {
                $package_id=$this->packagesModel->getInsertID();  
                foreach($getServices as $item)
                {
                     $data=[
                       "package_id"=>$package_id,
                       "services"=>$item,
                     ];
                     $this->servicesModel->insert($data);
                }

                return $this->setResponse(200,false,"Package Added");
            }

            return $this->setResponse(403,false,$this->packagesModel->errors());           
            }
        catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }

    //Update package info
    public function updatePackage($id)
    {
    
         //check package exist or not
         $package=$this->packagesModel->find($id);
         if($package==null)
         {
            return $this->setResponse(403,true,"Package does not exist");
         }

         $package->fill($this->request->getVar());

         if(!$package->hasChanged())
         {
            return $this->setResponse(403,true,"Nothing to update");
         }

         try{
            $this->packagesModel->save($package);
            return $this->setResponse(200,false,"Information Updated");
         }catch(Exception $ex){
           return $this->setResponse(403,true,$ex->getMessage());
         }


    }

    //Remove Service from package
    public function removeService($id)
    {
        if($this->servicesModel->find($id)==null)
        {
            return $this->setResponse(403,true,'This service is invalid');
        }
        try{
           if( $this->servicesModel->delete($id))
           {
            return $this->setResponse(200,false,"Service Deleted");
           }
           
        }catch(Exception $ex){
           return $this->setResponse(403,true,$ex->getMessage());
        }
    }
    //add Service into packages
    public function addServices($package_id)
    {
        $validate = [  
            "services" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Please Provide Service description",
                ],
            ],

        ];
        if (!$this->validate($validate)) {
           
            return $this->setResponse(401,true,$this->validator->getErrors());
        }
         $package=$this->packagesModel->find($package_id);
         if($package==null)
         {
            return $this->setResponse(403,true,"Package ID does not exist");
         }

         $data=[
            "services"=>$this->request->getVar('services'),
            "package_id"=>$package_id
         ];

        try{
            $this->servicesModel->insert($data);
            return $this->setResponse(200,false,"Package Service included");
        }catch(Exception $ex){
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
