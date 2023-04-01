<?php

namespace App\Models;

use CodeIgniter\Model;

class Jwt extends Model
{
    private $token;

    public function setToken($token)
    {
        $this->token=$token;
    }
    public function getToken()
    {
        
        return $this->token;
    }
}
