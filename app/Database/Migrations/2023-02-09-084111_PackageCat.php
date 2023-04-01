<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PackageCat extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([

            "id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
                "auto_increment"=>true
            ],
          
            "title"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
           "meta_desc"=>[
              "type"=>"TEXT"
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
        $this->forge->createTable('package_cat');
    }

    public function down()
    {
        $this->forge->dropTable('package_cat');
    }
}
