<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use CodeIgniter\CLI\CLI;
use App\Models\AnswerModel;

class AnswerSeeder extends Seeder
{
	public function run()
	{
		$fabricator     = new Fabricator(AnswerModel::class);
		$newAnswerModel = model('AnswerModel');
		$newAnswers     = $fabricator->make(10);
		//dd($newAnswers);
		$i = 0;
		foreach ($newAnswers as $answer)
		{
			$i++;
			$newAnswerModel->save($answer);
		}
		CLI::write('Total answers created: ' . $i, 'green');
	}
}
