<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\SchoolModel;

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
class SchoolSeeder extends Seeder
{
	public function run()
	{
		$fabricator     = new Fabricator(SchoolModel::class);
		$newSchools     = $fabricator->make(2);
		$newSchoolModel = new SchoolModel();

		foreach ($newSchools as $school)
		{
			$newSchoolModel->save($school);
		}
	}
}
