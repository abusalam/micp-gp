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
class ClassSeeder extends Seeder
{
	public function run()
	{
		$classes = [
			[
				'class' => 'Class IX',
			],
			[
				'class' => 'Class X',
			],
			[
				'class' => 'Class XI',
			],
			[
				'class' => 'Class XII',
			],
		];
		foreach ($classes as $class)
		{
			model('ClassModel')->save($class);
		}
	}
}
