<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Team extends Migration
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
            "name"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
            "designation"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
            "image"=>[
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
        $this->forge->addForeignKey('user_id','user_access','id','CASCADE','CASCADE');

        $this->forge->createTable('team_members');
    }

    public function down()
    {
        $this->forge->dropTable('team_members');
    }
}
