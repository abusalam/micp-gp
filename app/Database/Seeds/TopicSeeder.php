<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\TopicModel;

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
class TopicSeeder extends Seeder
{
	public function run()
	{
		$fabricator    = new Fabricator(TopicModel::class);
		$newTopics     = $fabricator->make(10);
		$newTopicModel = model('TopicModel');
		foreach ($newTopics as $topic)
		{
			$newTopicModel->save($topic);
		}
	}
}
