<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Create Database Structure for ePathshala Assignments
 *
 * @package ePathshala
 */
class Assignments extends Migration
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
			'class'      => [
				'type'       => 'varchar',
				'constraint' => 25,
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
		$this->forge->addUniqueKey('class');
		$this->forge->createTable('classes', true);

		$fields = [
			'class_id' => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'user_id'  => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
		];

		$this->forge->addField($fields);
		$this->forge->addKey(['class_id', 'user_id']);
		$this->forge->addForeignKey('class_id', 'classes', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		$this->forge->createTable('classes_users', true);

		$this->forge->addField([
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'subject'    => [
				'type'       => 'varchar',
				'constraint' => 25,
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
		$this->forge->addUniqueKey('subject');
		$this->forge->createTable('subjects', true);

		$this->forge->addField([
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'class_id'   => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'subject_id' => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'topic'      => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'detail'     => [
				'type' => 'text',
				'null' => true,
			],
			'user_id'    => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
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
		$this->forge->addUniqueKey('topic');
		$this->forge->addKey(['class_id', 'subject_id']);
		$this->forge->addKey(['user_id']);
		$this->forge->addForeignKey('class_id', 'classes', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('subject_id', 'subjects', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		$this->forge->createTable('topics', true);

		$this->forge->addField([
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'file'       => [
				'type'       => 'varchar',
				'constraint' => 255,
			],
			'size'       => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'user_id'    => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
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
		$this->forge->addUniqueKey('file');
		$this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		$this->forge->createTable('files', true);

		$this->forge->addField([
			'id'         => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'topic_id'   => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'title'      => [
				'type'       => 'varchar',
				'constraint' => 100,
			],
			'questions'  => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'marks'      => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'user_id'    => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'status'     => [
				'type'       => 'ENUM',
				'constraint' => [
					'draft',
					'sent',
					'complete',
				],
				'default'    => 'draft',
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
		$this->forge->addUniqueKey('files');
		$this->forge->addKey(['topic_id', 'user_id']);
		$this->forge->addForeignKey('topic_id', 'topics', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		$this->forge->createTable('assignments', true);

		$fields = [
			'assignment_id' => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'file_id'       => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
		];

		$this->forge->addField($fields);
		$this->forge->addKey(['assignment_id', 'file_id']);
		$this->forge->addUniqueKey(['file_id']);
		$this->forge->addForeignKey('assignment_id', 'assignments', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('file_id', 'files', 'id', false, 'CASCADE');
		$this->forge->createTable('assignment_files', true);

		$this->forge->addField([
			'id'            => [
				'type'           => 'int',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'assignment_id' => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
			],
			'marks'         => [
				'type'       => 'decimal',
				'constraint' => '6,2',
				'unsigned'   => true,
				'null'       => true,
			],
			'assessment'    => [
				'type' => 'TEXT',
				'null' => true,
			],
			'user_id'       => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'status'        => [
				'type'       => 'ENUM',
				'constraint' => [
					'draft',
					'sent',
					'checked',
				],
				'default'    => 'draft',
			],
			'created_at'    => [
				'type' => 'datetime',
				'null' => true,
			],
			'updated_at'    => [
				'type' => 'datetime',
				'null' => true,
			],
			'deleted_at'    => [
				'type' => 'datetime',
				'null' => true,
			],
		]);
		$this->forge->addKey('id', true);
		$this->forge->addUniqueKey('files');
		$this->forge->addForeignKey('assignment_id', 'assignments', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		$this->forge->createTable('answers', true);

		$fields = [
			'answer_id'  => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'file_id'    => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'page_no'    => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'assessment' => [
				'type' => 'LONGTEXT',
				'null' => true,
			],
			'user_id'    => [
				'type'       => 'int',
				'constraint' => 11,
				'unsigned'   => true,
				'default'    => 0,
			],
			'status'     => [
				'type'       => 'ENUM',
				'constraint' => [
					'pending',
					'checking',
					'checked',
				],
				'default'    => 'pending',
			],
		];

		$this->forge->addField($fields);
		$this->forge->addKey(['answer_id', 'file_id']);
		$this->forge->addUniqueKey(['file_id']);
		$this->forge->addForeignKey('answer_id', 'answers', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('file_id', 'files', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		$this->forge->createTable('answer_files', true);
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
		if ($this->db->DBDriver !== 'SQLite3')
		{
			$this->forge->dropForeignKey('classes_users', 'classes_users_class_id_foreign');
			$this->forge->dropForeignKey('classes_users', 'classes_users_user_id_foreign');
			$this->forge->dropForeignKey('topics', 'topics_class_id_foreign');
			$this->forge->dropForeignKey('topics', 'topics_subject_id_foreign');
			$this->forge->dropForeignKey('files', 'files_user_id_foreign');
			$this->forge->dropForeignKey('assignment_files', 'assignment_files_assignment_id_foreign');
			$this->forge->dropForeignKey('assignment_files', 'assignment_files_file_id_foreign');
			$this->forge->dropForeignKey('assignments', 'assignments_topic_id_foreign');
			$this->forge->dropForeignKey('assignments', 'assignments_user_id_foreign');
			$this->forge->dropForeignKey('answer_files', 'answer_files_answer_id_foreign');
			$this->forge->dropForeignKey('answer_files', 'answer_files_file_id_foreign');
			$this->forge->dropForeignKey('answer_files', 'answer_files_user_id_foreign');
			$this->forge->dropForeignKey('answers', 'answers_assignment_id_foreign');
			$this->forge->dropForeignKey('answers', 'answers_user_id_foreign');
		}

		$this->forge->dropTable('classes_users', true);
		$this->forge->dropTable('classes', true);
		$this->forge->dropTable('subjects', true);
		$this->forge->dropTable('topics', true);
		$this->forge->dropTable('files', true);
		$this->forge->dropTable('assignment_files', true);
		$this->forge->dropTable('assignments', true);
		$this->forge->dropTable('answer_files', true);
		$this->forge->dropTable('answers', true);
	}
}
