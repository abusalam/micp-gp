<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create Database Structure for ePathshala Users
 *
 * @package ePathshala
 */
class Users extends Migration
{
	public function up()
	{
		$fields = [
			'mobile'      => [
				'type'       => 'VARCHAR',
				'constraint' => '10',
				'after'      => 'id',
			],
			'full_name'   => [
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'after'      => 'mobile',
			],
			'description' => [
				'type'  => 'TEXT',
				'null'  => true,
				'after' => 'full_name',
			],
		];

		$this->forge->addColumn('users', $fields);

		// TODO: Not Supported till CI 4.0.4
		//$this->forge->addForeignKey('school_id', 'schools', 'id', false, 'CASCADE');
		$usersTable    = $this->db->prefixTable('users');
	}

	/**
	 * Rollback Structure Changes
	 *
	 * CLI Command: php spark migrate:rollback
	 *
	 * @return void
	 */
	public function down()
	{
		// drop constraints first to prevent errors
		// if ($this->db->DBDriver !== 'SQLite3')
		// {
		// 	$this->forge->dropForeignKey('schools', 'schools_school_id_foreign');
		// }

		$this->forge->dropColumn('users', 'mobile');
		$this->forge->dropColumn('users', 'full_name');
		$this->forge->dropColumn('users', 'description');
	}
}
