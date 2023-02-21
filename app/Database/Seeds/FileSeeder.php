<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\FileModel;

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
class FileSeeder extends Seeder
{
	public function run()
	{
		$fabricator   = new Fabricator(FileModel::class);
		$newFileModel = new FileModel();

		// Must use count parameter like make(1) for foreach to work
		$newFiles = $fabricator->make(10);

		$assignments = model('AssignmentModel')->asArray()->findColumn('id');
		foreach ($newFiles as $file)
		{
			$attachID = $fabricator->getFaker()->randomElement($assignments);
			$newFileModel->attachWith($attachID)->save($file);
		}
	}
}
