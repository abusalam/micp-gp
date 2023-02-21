<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use Myth\Auth\Authorization\GroupModel;
use App\Models\UserModel;

/**
 * Database seeds are simple classes that must have a run() method, and extend
 * CodeIgniterDatabaseSeeder. Within the run() the class can create any form of
 * data that it needs to. It has access to the database connection and the forge
 * through $this->db and $this->forge, respectively. Seed files must be stored
 * within the app/Database/Seeds directory. The name of the file must match the
 * name of the class
 *
 * @package ePathshala
 */
class UserSeeder extends Seeder
{
	public function run()
	{
		$fabricator   = new Fabricator(UserModel::class);
		$newUserModel = new UserModel();
		$groupModel   = new GroupModel();

		$newUser           = $fabricator->make();
		$newUser->username = 'admin';
		//$newUser->setSchoolId(1);
		$newUserModel->withGroup('admins')->save($newUser);
		// $groupModel->addUserToGroup(1, 2);
		// $groupModel->addUserToGroup(1, 3);

		$newUser           = $fabricator->make();
		$newUser->username = 'passenger';
		//$newUser->setSchoolId(1);
		$newUserModel->withGroup('passengers')->save($newUser);

		$newUser           = $fabricator->make();
		$newUser->username = 'operator';
		//$newUser->setSchoolId(1);
		$newUserModel->withGroup('operators')->save($newUser);

		//$newUsers = $fabricator->make(20);

		// $groups = $groupModel->asArray()->findColumn('name');

		// $newUserModel->skipValidation();
		// foreach ($newUsers as $user)
		// {
		// 	// Use Random Groups for new users
		// 	shuffle($groups);
		// 	$newUserModel->withGroup($groups[0])->save($user);
		// }
	}
}
