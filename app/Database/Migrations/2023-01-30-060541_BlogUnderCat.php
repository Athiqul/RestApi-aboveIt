<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BlogUnderCat extends Migration
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
            "blog_id"=>[
                "type"=>'INT',
                "constraint"=>8,
                "unsigned"=>true,
            ],
            "cat_id"=>[
                "type"=>'INT',
                "constraint"=>5,
                "unsigned"=>true,
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
        $this->forge->addForeignKey('blog_id','blog','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('cat_id','blog_cat','id','CASCADE','CASCADE');
        $this->forge->createTable('blog_under_cat');
    }

    public function down()
    {
        $this->forge->dropTable('blog_under_cat');
    }
}
