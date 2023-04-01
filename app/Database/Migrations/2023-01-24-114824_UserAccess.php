<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserAccess extends Migration
{
    public function up()
    {
        $this->forge->addField([

            "id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
                "auto_increment"=>true
            ],
            "user_name"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
                "null"=>false
            ],
            "email"=>[
                "type"=>"VARCHAR",
                "constraint"=>100,
                "unique"=> true,

            ],
            "mobile"=>[
                "type"=>"VARCHAR",
                "constraint"=>12,
                "unique"=> true,
            ],
            "password"=>[
                "type"=>"VARCHAR",
                "constraint"=>128,
            ],
            "role"=>[
                  "type"=>"INT",
                  "constraint"=>5,
            ],

            "status"=>[
                "type"=>"ENUM",
                "constraint"=>['1','0'],
                "default"=>'1'
            ],

            "address"=>[
                "type"=>"TEXT",
                "null"=>true
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
        $this->forge->createTable('user_access');

        
    }

    public function down()
    {
        $this->forge->dropTable('user_access');
    }
}
