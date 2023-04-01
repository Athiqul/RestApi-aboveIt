<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Review extends Migration
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
          
            "customer_name"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
           
            "image"=>[
                "type"=>"VARCHAR",
                "constraint"=>255
            ],
            "company"=>[
                "type"=>"VARCHAR",
                "constraint"=>255
            ],
            "quote"=>[
                "type"=>"TEXT",
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
        $this->forge->createTable('customer_review');
    }

    public function down()
    {
        $this->forge->dropTable('customer_review');
    }
}
