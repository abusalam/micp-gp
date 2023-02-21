<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Database seeds are simple classes that must have a run() method, && extend
 * CodeIgniterDatabaseSeeder. Within the run() the class can create any form of
 * data that it needs to. It has access to the database connection && the
 * forge through $this->db && $this->forge, respectively. Seed files must be
 * stored within the app / Database / Seeds directory. The name of the file
 * must match the name of the class.
 *
 * @package ePathshala
 */
class SubjectSeeder extends Seeder
{
	public function run()
	{
		$subjects = [
			[
				'subject' => 'Bengali',
			],
			[
				'subject' => 'English',
			],
			[
				'subject' => 'Computer Science',
			],
			[
				'subject' => 'Mathematics',
			],
			[
				'subject' => 'Physics',
			],
			[
				'subject' => 'Chemistry',
			],
			[
				'subject' => 'Biology',
			],
			[
				'subject' => 'History',
			],
			[
				'subject' => 'Geography',
			],
			[
				'subject' => 'Economics',
			],
			[
				'subject' => 'Political Science',
			],
			[
				'subject' => 'Psychology',
			],
			[
				'subject' => 'Statistics',
			],
			[
				'subject' => 'Nutrition',
			],
			[
				'subject' => 'Sanskrit',
			],
		];
		foreach ($subjects as $subject)
		{
			model('SubjectModel')->save($subject);
			// $this->db->table('subjects')
			// 	->insert($subject);
		}
	}
}
