<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = \App\Entities\ProductEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id','title','image','desc','meta_desc','meta_tag','brand','stock_status','status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        "user_id"=>"required",
        "title"=>"required|alpha_numeric_space|min_length[3]",
        "desc"=>"required|min_length[10]"
    ];
    protected $validationMessages   = [
        "user_id"=>[
            "required"=>"User ID Needed"
        ],
        "title"=>[
           "required"=>"Need product title",
           "alpha_numeric_space"=>"Please Use Only Alpha Numeric Space",
           "min_length[3]"=>"Title should be at least 3 characters"    
        ],
        "desc"=>[
            "required"=>"Need product description ",
            "min_length[10]"=>"Title should be at least 10 characters"    
         ],

    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
