<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PackageServices extends Migration
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
          
            "package_id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
                
            ],
           
            "services"=>[
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
        $this->forge->addForeignKey('package_id','packages','id','CASCADE','CASCADE');
        $this->forge->createTable('package_services');
    }

    public function down()
    {
        $this->forge->dropTable('package_services');
    }
}
