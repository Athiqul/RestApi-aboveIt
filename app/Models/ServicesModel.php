<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicesModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = \App\Entities\ServicesEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ["user_id","title","image","desc","meta_desc","meta_tag","status"];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        "user_id"=>"required|numeric|is_not_unique[user_access.id]",
        "title"=>"required|alpha_numeric_space",
        "desc"=>"required"
    ];
    protected $validationMessages   = [
        "user_id"=>[
            "required"=>"Please Login Again",
            "numeric"=>"Please Login Again",
            "is_not_unique"=>"Please Login Again"
        ],
        "title"=>[
            "required"=>"Service title missing",
            "alpha_numeric_space"=>"Only Alphabet, Numeric and spaces are allowed"
        ],
        "desc"=>[
            "required"=>"Description missing",           
        ]

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
