<?php namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Models\TopicModel;
use App\Entities\Topic;

/**
 * Home Page Controller
 *
 * @package ePathshala
 */
class TopicController extends BaseController
{

	public function create()
	{
		$data['config']   = $this->config;
		$data['subjects'] = model('SubjectModel')->asArray()->findAll();
		$db               = db_connect();
		$data['classes']  = $db->table('classes')->select('id, class')->get()->getResultArray();
		$db->close();
		$data['topic'] = (ENVIRONMENT !== 'production') ? (new Fabricator(TopicModel::class))->make() : new Topic();

		return view('Topic/create-form', $data);
	}

	public function tryToCreate()
	{
		$topicModel = new TopicModel();
		$newTopic   = new Topic();
		$newTopic->setCreatedBy()->fill($this->request->getPost());

		// Try and Catch Database Exception
		try
		{
			if (! $topicModel->insert($newTopic))
			{
				return redirect()->back()->withInput()->with('errors', $topicModel->errors());
			}
		}
		catch (\mysqli_sql_exception $e)
		{
			return redirect()->back()->withInput()->with('error', $e->getMessage());
		}

		$parser = \Config\Services::parser();
		$topic  = [
			'id'    => $topicModel->getInsertID(),
			'topic' => $newTopic->topic,
		];
		return redirect('create-assignment')->with(
			'message',
			$parser->setData($topic)->renderString(lang('app.topic.createSuccess')));
	}
}
