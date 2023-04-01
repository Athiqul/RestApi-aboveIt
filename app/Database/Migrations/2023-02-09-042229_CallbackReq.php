<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CallbackReq extends Migration
{
    public function up()
    {
        $this->forge->addField([

            "id"=>[
                "type"=>'INT',
                "constraint"=>8,
                "unsigned"=>true,
                "auto_increment"=>true
            ],
          
            "customer_name"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
           
            "mobile"=>[
                "type"=>"VARCHAR",
                "constraint"=>255
            ],
            "email"=>[
                "type"=>"VARCHAR",
                "constraint"=>255
            ],

            "status"=>[
                "type"=>"ENUM",
                "constraint"=>['1','0'],
                "default"=>'1'
            ],

            "created_at"=>[
                "type"=>"DATETIME",
                "null"=>true
            ],

            "updated_at"=>[
                "type"=>"DATETIME",
                "null"=>true
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('callback_req');
    }

    public function down()
    {
        $this->forge->dropTable('callback_req');
    }
}
