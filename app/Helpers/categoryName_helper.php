<?php 

if(!function_exists('getCategoryName'))
{
    function getCategoryName($id)
    {
        $model_info=new \App\Models\BlogCategoryModel();
        return $model_info->where('id',$id)->first();
    }
}


?>