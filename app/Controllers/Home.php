<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;


class Home extends BaseController
{
    use ResponseTrait;
    public function index()
    {
       echo "404 ERROR";
    }
}
