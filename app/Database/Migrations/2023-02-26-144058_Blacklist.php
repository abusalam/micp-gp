<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Blacklist extends Migration
{
    public function up()
    {
        $blacklists = [
      'id'         => [
        'type'           => 'int',
        'constraint'     => 11,
        'unsigned'       => true,
        'auto_increment' => true,
      ],
      'blacklist_no'  => [
        'type'       => 'varchar',
        'constraint' => 50,
      ],
      'reason'    => [
        'type'       => 'varchar',
        'constraint' => 255,
      ],
      'status' => [
          'type'       => 'ENUM',
          'constraint' => ['enabled', 'disabled'],
          'default'    => 'enabled',
      ],
      'created_at' => [
        'type' => 'datetime',
        'null' => true,
      ],
      'updated_at' => [
        'type' => 'datetime',
        'null' => true,
      ],
      'deleted_at' => [
        'type' => 'datetime',
        'null' => true,
      ],

    ];
    $this->forge->addField($blacklists);
    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey('blacklist_no');
    $this->forge->createTable('blacklists', true);
    }

    public function down()
    {
        $this->forge->dropTable('blacklists', true);
    }
}
