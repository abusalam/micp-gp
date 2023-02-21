<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\AssignmentModel;

class AssignmentSeeder extends Seeder
{
	public function run()
	{
		$fabricator         = new Fabricator(AssignmentModel::class);
		$newAssignmentModel = model('AssignmentModel');
		$newAssignments     = $fabricator->make(10);
		foreach ($newAssignments as $assignment)
		{
			$newAssignmentModel->save($assignment);
		}
	}
}
