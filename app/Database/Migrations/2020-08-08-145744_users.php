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
		$this->forge->addField([
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'udise'      => [
				'type'       => 'varchar',
				'constraint' => 11,
				'null'       => true,
			],
			'school'     => [
				'type'       => 'varchar',
				'constraint' => 255,
				'null'       => true,
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
		]);
		$this->forge->addKey('id', true);
		$this->forge->addUniqueKey('udise');
		$this->forge->createTable('schools', true);

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
			'school_id'   => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'after'      => 'id',
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
		$schoolsTable  = $this->db->prefixTable('schools');
		$addForeignKey = 'ALTER TABLE `' . $usersTable . '` '
		. ' ADD CONSTRAINT `' . $usersTable . '_school_id_foreign` FOREIGN KEY (`school_id`) '
		. ' REFERENCES `' . $schoolsTable . '`(`id`) '
		. ' ON DELETE RESTRICT ON UPDATE CASCADE; ';

		$this->db->query($addForeignKey);
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

		$this->forge->dropForeignKey('users', 'users_school_id_foreign');

		$this->forge->dropColumn('users', 'mobile');
		$this->forge->dropColumn('users', 'full_name');
		$this->forge->dropColumn('users', 'school_id');
		$this->forge->dropColumn('users', 'description');

		$this->forge->dropTable('schools', true);
	}
}
