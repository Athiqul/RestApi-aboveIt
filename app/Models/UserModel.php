<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'user_access';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = \App\Entities\UserAccessEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_name','email','mobile','role','status','password','address'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // Validation
    protected $validationRules      = [
        "user_name"=>"required|alpha_numeric_space|min_length[3]",
        "email"=>"required|valid_email|is_unique[user_access.email]",
        "mobile"=>"required|is_unique[user_access.mobile]"
    ];
    protected $validationMessages   = [
        "user_name"=>[
            "required"=>"Name Required!",
            "alpha_numeric_space"=>"Use only alpha numeric space!!!",
            "min_length"=>"Minimum choose 3 characters"
        ],
        "email"=>[
            "required"=>"Provide Email!",
            "valid_email"=>"Provide Valid Email",
            "is_unique"=>"Already exist this email with another account"
        ],
        "mobile"=>[
            "required"=>"Mobile number needed",
            "is_unique"=>"Already exist this mobile number with an another account"
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
