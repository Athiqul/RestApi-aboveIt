<?php 

if(!function_exists('getUserName'))
{
    function getUserName($id)
    {
        $model_info=new \App\Models\UserModel();
        return $model_info->where('id',$id)->first()->user_name;
    }
}


?>