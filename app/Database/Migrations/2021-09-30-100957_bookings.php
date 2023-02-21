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
			'ticket'     => [
				'type'       => 'varchar',
				'constraint' => 25,
			],
			'passenger'  => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'mobile'     => [
				'type'       => 'varchar',
				'constraint' => 10,
			],
			'address'    => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'purpose'    => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'booking_date'  => [
				'type' => 'date',
				'null' => true,
			],
			'start_time'  => [
				'type'       => 'varchar',
				'constraint' => 25,
			],
			'hours'       => [
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
