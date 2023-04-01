<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OtpAgain extends Migration
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
            "user_id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
            ],
            "otp_code"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
                "null"=>false
            ],
          
            "status"=>[
                "type"=>"ENUM",
                "constraint"=>['1','0'],
                "default"=>'0'
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
        $this->forge->createTable('otp');
    }

    public function down()
    {
        $this->forge->dropTable('otp');
    }
}
