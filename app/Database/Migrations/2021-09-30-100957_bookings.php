<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bookings extends Migration
{
	public function up()
	{
		$bookings = [
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'vehicle_no'     => [
				'type'       => 'varchar',
				'constraint' => 10,
			],
			'purpose'    => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'license_no'  => [
				'type'       => 'varchar',
				'constraint' => 50,
			],
			'driver_name'  => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'driver_mobile'     => [
				'type'       => 'varchar',
				'constraint' => 10,
			],
			'driver_address'    => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'driver_photo'    => [
				'type'       => 'TEXT',
				'null'       => true,
			],
			'issued_on'  => [
				'type' => 'date',
				'null' => true,
			],
			'valid_till'  => [
				'type' => 'date',
				'null' => true,
			],
			'crew_name'  => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'crew_mobile'     => [
				'type'       => 'varchar',
				'constraint' => 10,
			],
			'crew_id_type'  => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'crew_id_no'     => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'crew_address'    => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'crew_photo'    => [
				'type'       => 'TEXT',
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

		];
		$this->forge->addField($bookings);
		$this->forge->addKey('id', true);
		//$this->forge->addUniqueKey('ticket');
		$this->forge->createTable('bookings', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		// drop constraints first to prevent errors
		if ($this->db->DBDriver !== 'SQLite3')
		{
			// $this->forge->dropForeignKey('classes_users', 'classes_users_class_id_foreign');
			// $this->forge->dropForeignKey('classes_users', 'classes_users_user_id_foreign');
			// $this->forge->dropForeignKey('topics', 'topics_class_id_foreign');
			// $this->forge->dropForeignKey('topics', 'topics_subject_id_foreign');
			// $this->forge->dropForeignKey('files', 'files_user_id_foreign');
			// $this->forge->dropForeignKey('assignment_files', 'assignment_files_assignment_id_foreign');
			// $this->forge->dropForeignKey('assignment_files', 'assignment_files_file_id_foreign');
			// $this->forge->dropForeignKey('assignments', 'assignments_topic_id_foreign');
			// $this->forge->dropForeignKey('assignments', 'assignments_user_id_foreign');
			// $this->forge->dropForeignKey('answer_files', 'answer_files_answer_id_foreign');
			// $this->forge->dropForeignKey('answer_files', 'answer_files_file_id_foreign');
			// $this->forge->dropForeignKey('answer_files', 'answer_files_user_id_foreign');
			// $this->forge->dropForeignKey('answers', 'answers_assignment_id_foreign');
			// $this->forge->dropForeignKey('answers', 'answers_user_id_foreign');
		}

		$this->forge->dropTable('bookings', true);
	}
}
