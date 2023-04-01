<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use \App\Models\TeamModel;
use \App\Entities\TeamEntity;

use Exception;

class Team extends ResourceController
{
    private $key;
    private $teamModel;
    //constructor
    public function __construct()
    {
        $this->key=getenv('API_SECRET');
        $this->teamModel=new TeamModel();
    }
    //Active Team Members show
     //get routes Team members
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
        
    
        $total=count($this->teamModel->where('status',1)->findAll());
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

        $row=$this->teamModel->where('status',1)->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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

     //
    //New Team member Create post method
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

            "name" => [
                "rules" => "required|min_length[3]|alpha_space",
                "errors" => [
                    "required" => "Team Member name Missing",
                    "min_length[3]" => "Name Should be minimum 3 characters",
                    "alpha_space"=>"Only Alphabet and space are Allowed"
                ],
            ],
            "image" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Image Link missing",
                ],
            ],
            "designation" => [
                "rules" => "min_length[3]",
                "errors" => [
                    "min_length" => "Designation Should be minimum 3 characters",
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

        $teamEntity= new TeamEntity();
        $teamInfo=$this->request->getVar();
        
        $teamEntity->fill($teamInfo);
        unset($teamEntity->key);
        try{
            if($this->teamModel->insert($teamEntity))
            {
                return $this->setResponse(200,false,"Team member Added");
            }
        }catch(Exception $ex){
            return $this->setResponse(403,true,$ex->getMessage());
        }
    }
    // show team member by id get method
    public function showTeamMember($member_id)
    {
            $team_member=$this->teamModel->where('id',$member_id)->first();
            if($team_member==null)
            {
                return $this->setResponse(403,true,'Team member does not exist');
            }

            return $this->setResponse(200,false,$team_member);
    }

    //deactive team members list show by post method
    public function allTeamMembers()
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
        
    
        $total=count($this->teamModel->findAll());
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

        $row=$this->teamModel->orderBy('id','desc')->findAll($limit,$offset);  
        
        
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
    //update Team Member information
    public function updateTeamMemberInfo($member_id)
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
        $team_member=$this->teamModel->find($member_id);
        if($team_member==null)
        {
            return $this->setResponse(403,true,"No data found");
        }

        $userRequest=$this->request->getVar();
    
        $team_member->fill($userRequest);
        unset($team_member->key);
        

        if(!$team_member->hasChanged())
        {
            return $this->setResponse(402,true,'Nothing to update');
        }

        if($this->teamModel->save($team_member)){
            return $this->setResponse(200,false,'Information Updated');
        }

        else{
            return $this->setResponse(403,true,$this->teamModel->errors());
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
