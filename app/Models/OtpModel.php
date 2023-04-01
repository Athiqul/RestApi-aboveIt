<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'otp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = \App\Entities\OtpEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id','otp_code','status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        "user_id"=>"required",
        "otp_code"=>"required|min_length[6]"
    ];
    protected $validationMessages   = [
        "user_id"=>[
            "required"=>"No user Id provided",
        ],
        "otp_code"=>[
            "required"=>"Otp code missing",
            "min_length"=>"OTP pattern not matched"
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
