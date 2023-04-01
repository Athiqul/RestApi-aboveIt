<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Contest extends Migration
{
    public function up()
    {
        $this->forge->addField([

            "id"=>[
                "type"=>'INT',
                "constraint"=>10,
                "unsigned"=>true,
                "auto_increment"=>true
            ],
           
            "participant_name"=>[
                "type"=>"VARCHAR",
                "constraint"=>255
            ],
            "mobile"=>[
                "type"=>"VARCHAR",
                "constraint"=>11,
                "unique"=>true
            ],
            "email"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
                "unique"=>true
            ],
            "address"=>[
                "type"=>"TEXT",
            ],
            "institute"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],

            "yob"=>[
                "type"=>"VARCHAR",
                "constraint"=>10,
            ],

            "contest_type"=>[
                "type"=>"ENUM",
                "constraint"=>['1','0'],
            ],

            "referrer"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
            "opinion"=>[
                "type"=>"VARCHAR",
                "constraint"=>255,
            ],
            "joining"=>[
                "type"=>"ENUM",
                "constraint"=>['1','0'],
            ],
            "mail_receive"=>[
                "type"=>"ENUM",
                "constraint"=>['1','0'],
                "default"=>"1"
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
        $this->forge->createTable('contest');
    }

    public function down()
    {
        $this->forge->dropTable('contest');
    }
}
