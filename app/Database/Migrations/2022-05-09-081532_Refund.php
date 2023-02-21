<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Refund extends Migration
{
    public function up()
    {
        $refunds = [
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'booking_id'     => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
			],
			'reason'    => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'cancellation_date'  => [
				'type' => 'date',
				'null' => true,
			],
			'cancellation_time'  => [
				'type'       => 'varchar',
				'constraint' => 25,
			],
			'hours_before'       => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 2,
			],
			'amount'     => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'status'     => [
				'type'       => 'ENUM',
				'constraint' => [
					'pending',
					'FAILED',
					'SUCCESS',
				],
				'default'    => 'pending',
			],
			'pg_resp' => [
				'type' => 'TEXT',
				'null' => true,
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
		$this->forge->addField($refunds);
		$this->forge->addKey('id', true);
		//$this->forge->addUniqueKey('ticket');
		$this->forge->createTable('refunds', true);
    }

    public function down()
    {
        $this->forge->dropTable('refunds', true);
    }
}
