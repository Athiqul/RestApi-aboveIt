<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Product extends Migration
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
            "user_id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
            ],
            "title"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
            "image"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
            "desc"=>[
                "type"=>"TEXT",
            ],
            "meta_desc"=>[
                 "type"=>"TEXT",
                 "null"=>true
            ],

            "meta_tag"=>[
                "type"=>"TEXT",
                "null"=>true
           ],
           "brand"=>[
            "type"=>"VARCHAR",
            "constraint"=>255,
       ],

       "stock_status"=>[
        "type"=>"ENUM",
        "constraint"=>['1','0'],
        "default"=>'1'
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
        $this->forge->addForeignKey('user_id','user_access','id','CASCADE','CASCADE');

        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
