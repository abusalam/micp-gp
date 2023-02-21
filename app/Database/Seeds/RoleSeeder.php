<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Authorization\GroupModel;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

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
class RoleSeeder extends Seeder
{
	public function run()
	{
		$groups = [
			'admins'   => [
				'name'        => 'admins',
				'description' => 'Administrator: Manages user roles and permissions.',
			],
			'passengers' => [
				'name'        => 'passengers',
				'description' => 'passenger: books boat tickets.',
			],
			'operators' => [
				'name'        => 'operators',
				'description' => 'operator: Modify or Cancel booked tickets.',
			],
		];

		$groupModel = new GroupModel();

		if (is_cli())
		{
			$this->setSilent(false);
			$groupNames = [
				'default-groups',
				'new',
			];

			$groupName = CLI::prompt('Group: ', $groupNames );

			if ($groupName === 'new')
			{
				$groupName = CLI::prompt('Name', null, 'required|alpha|is_unique[auth_groups.name,name,{name}]');
				$groupDesc = CLI::prompt('Description');
				$groupModel->save([
					'name'        => $groupName,
					'description' => $groupName . ': ' . $groupDesc,
				]);
			}
			else
			{
				$groupModel->setValidationRule('name', 'required|alpha|is_unique[auth_groups.name,name,{name}]');
				foreach ($groups as $group)
				{
					$groupModel->save($group);
				}
			}

			CLI::write('Available Groups', 'blue');
			CLI::write(CLI::getOptionString(), 'white');
			$groups = $groupModel->findAll();
			$data   = [];
			foreach ($groups as $group)
			{
				$data[] = [
					CLI::color($group->name, 'green'),
					CLI::color($group->description, 'white'),
				];
			}
			CLI::table($data, ['Group', 'Description']);
		}
		else
		{
			// For non-cli Calls Only
			foreach ($groups as $group)
			{
				$groupModel->save($group);
			}
		}
	}
}
