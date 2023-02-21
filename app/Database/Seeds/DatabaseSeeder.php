<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;

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
class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$seeders = [
			'ClassSeeder',
			'SubjectSeeder',
			'SchoolSeeder',
			'RoleSeeder',
			'UserSeeder',
			'TopicSeeder',
			'AssignmentSeeder',
			'FileSeeder',
			'AnswerSeeder',
			'BookingSeeder',
		];

		foreach ($seeders as $seeder)
		{
			if (CLI::prompt('Execute  ' . $seeder, ['y', 'n'] ) === 'y')
			{
				$this->call($seeder);
			}
		}
	}
}
