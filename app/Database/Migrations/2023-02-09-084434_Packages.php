<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Packages extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([

            "id"=>[
                "type"=>'INT',
                "constraint"=>8,
                "unsigned"=>true,
                "auto_increment"=>true
            ],
          
            "package_cat_id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
                
            ],
           
            "title"=>[
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
        $this->forge->addForeignKey('package_cat_id','package_cat','id','CASCADE','CASCADE');
        $this->forge->createTable('packages');
    }

    public function down()
    {
        $this->forge->dropTable('packages');
    }
}
