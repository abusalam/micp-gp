<?php namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Models\AnswerModel;
use App\Models\FileModel;
use App\Entities\Answer;
use App\Entities\File;

/**
 * Home Page Controller
 *
 * @package ePathshala
 */
class AnswerController extends BaseController
{
	/**
	 * Display the list of answers for the logged in user
	 *
	 * Route: /answer as get:list-answers
	 * Uses view answer/list-form.php
	 *
	 * @return view
	 */
	public function index()
	{
		helper('inflector');
		$answerModel = model('AnswerModel');

		if (in_groups(env('auth.defaultUserGroup', 'students')))
		{
			// files should contain the assignment id so that
			// student can create answers for this assignment
			$answers = $answerModel->asArray()
					->select(
						'answers.id,assignments.id as files,full_name,topic_id,title,assignments.marks'
						. ',answers.status'
					)
					->join('assignments', 'assignments.id = answers.assignment_id')
					->join('users', 'assignments.user_id = users.id')
					->where('answers.user_id', user_id())
					->orderBy('answers.updated_at', 'DESC')
					->paginate();
		}
		else
		{
			// files should contain the answer id so that
			// teachers can check answers for this assignment
			$answers = $answerModel->asArray()
			->select(
				'answers.id,answers.id as files,full_name,topic_id,title'
				. ',assignments.marks,answers.status'
			)
			->join('assignments', 'assignments.id = answers.assignment_id')
			->join('users', 'users.id = answers.user_id')
			->where('answers.status', 'sent')
			->where('assignments.user_id', user_id())
			->orderBy('answers.updated_at', 'DESC')
			->paginate();
		}

		// Define the Table Heading
		$_SESSION['heads'] = [
			'id'        => 'ID#',
			'files'     => 'Answers',
			'full_name' => 'Given By',
			'topic_id'  => 'Class - Subject - Topic',
			'title'     => 'Assignment',
			'marks'     => 'Marks',
			'status'    => 'Status',
		];

		$rows = [];

		$callback = function (&$value, $key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		foreach ($answers as $answer)
		{
			array_push($rows, array_filter($answer, $callback, ARRAY_FILTER_USE_BOTH));
		}

		$updateArray = function (&$value, $key) {
			switch(true){

				case ($key === 'topic_id'):
					$value = model('TopicModel')->find($value)->getTitle();
				break;

				case ($key === 'files'):
					// From Route: answer/list => answer/#id
					if (in_groups(env('auth.defaultUserGroup', 'students')))
					{
						// $value contains AssignmentID
						$files = model('AnswerModel')->getMyAnswerByAssignmentID($value)->getFiles();
						$value = '<a href="' . base_url(route_to('create-answer', $value)) . '">'
								. counted(count($files ?? []), 'Page') . '</a>';
					}
					else
					{
						// $value contains AnswerID
						$files = model('AnswerModel')->getFiles($value);
						$value = '<a href="' . base_url(route_to('view-answer-files', $value)) . '">'
								. counted(count($files ?? []), 'Page') . '</a>';
					}
				break;
			}
		};

		array_walk_recursive($rows, $updateArray);

		$data = [
			'heads' => $_SESSION['heads'],
			'rows'  => $rows,
			'pager' => $answerModel->pager,
		];

		unset($_SESSION['heads']);

		$data['config'] = $this->config;

		return view('Answer/list-form', $data, ['cache' => env('app.cacheTimeout', 300)]);
	}

	/**
	 * Displays one Answer File for assessment
	 *
	 * Route: /answer/#answerId/check/#fileId as get:check-answer-file
	 * Uses view answer/check-form.php
	 *
	 * @param integer $answerID Answer ID to be displayed
	 * @param integer $fileID   File ID to be checked
	 *
	 * @return view
	 */
	public function check(int $answerID, int $fileID)
	{
		// Check if the answer actually exists
		$answerModel = model('AnswerModel');
		$found       = $answerModel->find($answerID);
		if (! $found)
		{
			return redirect()->to(base_url(route_to('view-answer-files', $answerID)))
					->with('error', lang('app.answer.notFound'));
		}

		// Check if the user is allowed to access
		if (! $found->isAllowed())
		{
			return redirect()->to(base_url(route_to('view-answer-files', $answerID)))
					->with('error', lang('app.answer.unAuthorized'));
		}
		$found              = $found->getAssignment();
		$data['id']         = $answerID;
		$data['file']       = model('FileModel')->find($fileID)->loadFile();
		$data['image']      = $data['file']->getImageInfo();
		$data['title']      = $found->title;
		$data['assnId']     = $found->id;
		$data['isMobile']   = $this->request->getUserAgent()->isMobile();
		$data['topic']      = $found->getTopic()->getTitle();
		$data['detail']     = $found->getTopic()->getDetail();
		$data['files']      = $answerModel->getFiles($answerID);
		$data['assessment'] = $answerModel->getCheckedFile($fileID);
		$data['config']     = $this->config;
		return view('Answer/check-form', $data);
	}

	/**
	 * Save one checked Answer File for assessment
	 *
	 * Route: /answer/#answerId/check/#fileId/save as get:save-answer-file
	 * Uses view answer/check-form.php
	 *
	 * @param integer $answerID Answer ID to be displayed
	 * @param integer $fileID   File ID to be checked
	 *
	 * @return view
	 */
	public function saveChecked(int $answerID, int $fileID)
	{
		// Check if the answer actually exists
		$answerModel = model('AnswerModel');
		$found       = $answerModel->find($answerID);
		if (! $found)
		{
			return redirect()->to(base_url(route_to('view-answer-files', $answerID)))
					->with('error', lang('app.answer.notFound'));
		}

		// Check if the user is allowed to access
		if (! $found->isAllowed())
		{
			return redirect()->to(base_url(route_to('view-answer-files', $answerID)))
					->with('error', lang('app.answer.unAuthorized'));
		}

		// Save the checked answer file
		$fileData = [
			'file_id'    => $fileID,
			'answer_id'  => $answerID,
			'assessment' => $this->request->getPost('assessment'),
		];

		if (! $answerModel->saveCheckedFile($fileData))
		{
			return redirect()->to(base_url(route_to('check-answer-file', $answerID, $fileID)))
			->withInput()
			->with('errors', $answerModel->errors());
		}

		return redirect()->to(base_url(route_to('view-answer-files', $answerID)))
		->withInput()
		->with('message', lang('app.answer.checkSaveDone'));
	}

	/**
	 * Create one Answer File for assessment
	 *
	 * Route: /assignment/#assignmentId/answer as get:create-answer
	 * Uses view answer/create-form.php
	 *
	 * @param integer $id Assignment ID for which answer to be uploaded
	 *
	 * @return view
	 */
	public function create(int $id)
	{
		$data['id']         = $id;
		$data['config']     = $this->config;
		$data['title']      = lang('app.answer.createTitle');
		$data['assignment'] = model('AssignmentModel')->find($id);
		$data['files']      = [];
		$data['answer']     = null;

		// Check if the assignment id is valid
		if (! $data['assignment'])
		{
			return redirect()->to(base_url(route_to('view-assignments')))
			->withInput()
			->with('error', lang('app.answer.notFound'));
		}
		elseif (! $data['assignment']->isAllowed())
		{
			return redirect()->to(base_url(route_to('view-assignments')))
			->withInput()
			->with('error', lang('app.assignment.unAuthorized'));
		}
		else
		{
			// AssignmentID is valid now check if answer exists for this assignment
			$answer = model('AnswerModel')->getMyAnswerByAssignmentID($id);
		}

		if ($answer)
		{
			if ($answer->isLocked())
			{
				return redirect()->to(base_url(route_to('view-answer-files', $answer->id)))
						->withInput()
						->with('message', lang('app.answer.msgLocked'));
			}
			$data['answer'] = $answer;
			$data['files']  = $answer->getFiles();
		}

		// This view contains forms pointing to the tryToCreate/$1 route of this controller
		return view('Answer/create-form', $data);
	}

	public function tryToCreate(int $id)
	{
		$answer = model('AnswerModel')->getMyAnswerByAssignmentID($id);
								// ->where('assignment_id', $id)
								// ->findColumn('id')->first();
		// If the Answer already exists then just add the file only
		if (! $answer)
		{
			$answerModel = new AnswerModel();
			$answer      = new Answer();
			$answer->setAttributes(['assignment_id' => $id]);
			$answer->setCreatedBy()
					->fill($this->request->getPost());
			// Save the new answer
			if (! $answerModel->save($answer))
			{
				return redirect()->back()
									->withInput()
									->with('errors', $answerModel->errors());
			}
			$answer->id = $answerModel->getInsertID();
		}

		$newFile   = new File();
		$savedFile = $newFile->save(
										$this->request->getFile('imageFile'),
										AnswerModel::class,
										$answer->id
									);

		// The File Object is returned by the save function if not
		// then there must be some error
		if (! $savedFile instanceof File)
		{
			return redirect()->to(base_url(route_to('create-answer', $id)))
							->with(is_array($savedFile) ? 'errors' : 'error', $savedFile);
		}
		$parser    = \Config\Services::parser();
		$savedFile = $savedFile->toArray();

		return redirect()->to(base_url(route_to('create-answer', $id)))
				->with(
					'message',
					$parser->setData($savedFile)
							->renderString(lang('app.file.createSuccess'))
				);
	}

	/**
	 * Locks the Answer for evaluation
	 *
	 * @param integer $id Answer ID
	 *
	 * @return view
	 */
	public function lock(int $id)
	{
		// If the Answer already exists then just add the file only
		if (is_numeric($id))
		{
			$answerModel = new AnswerModel();
			$newAnswer   = model('AnswerModel')->find($id);
			$newAnswer->lock();
			// Save the answer
			if (! $answerModel->save($newAnswer))
			{
				return redirect()->to(base_url(route_to('create-answer', $id)))
									->withInput()
									->with('errors', $answerModel->errors());
			}
			//dd($newAnswer);
		}

		return redirect()->to(base_url(route_to('list-answers')))->with(
									'message',
									lang('app.answer.lockSuccess')
								);
	}

	/**
	 * Displays Answer Files with Topic Details
	 *
	 * Route: /answer/#answerId as get:view-answer-files
	 * Uses view answer/view-form.php
	 *
	 * @param integer $id Answer to be displayed
	 *
	 * @return view
	 */
	public function view(int $id = null)
	{
		// Check if the answer actually exists
		$answer = model('AnswerModel')->find($id);
		if (! $answer)
		{
			return redirect()->to(base_url(route_to('list-answers')))->with('error', lang('app.answer.notFound'));
		}

		// Check if the user is allowed to access
		if (! $answer->isAllowed())
		{
			return redirect()->to(base_url(route_to('list-answers')))->with('error', lang('app.answer.unAuthorized'));
		}
		if ($answer->marks === '0')
		{
			$answer->marks = null;
		}
		$assignment        = $answer->getAssignment();
		$data['id']        = $id;
		$data['title']     = $assignment->title;
		$data['assnId']    = $assignment->id;
		$data['marks']     = $assignment->marks;
		$data['answer']    = $answer;
		$data['questions'] = $assignment->questions;
		$data['topic']     = $assignment->getTopic()->getTitle();
		$data['detail']    = $assignment->getTopic()->getDetail();
		$data['files']     = model('AnswerModel')->getFiles($id);
		$data['config']    = $this->config;
		return view('Answer/view-form', $data, ['cache' => env('app.cacheTimeout', 300)]);
	}

	/**
	 * Displays Answer Files with Topic Details
	 *
	 * Route: /answer/#answerId as get:view-answer-files
	 * Uses view answer/view-form.php
	 *
	 * @param integer $id Answer to be displayed
	 *
	 * @return view
	 */
	public function saveMarks(int $id = null)
	{
		// Check if the answer actually exists
		$answerModel = model('AnswerModel');
		$found       = $answerModel->find($id);
		if (! $found)
		{
			return redirect()->to(base_url(route_to('list-answers')))->with('error', lang('app.answer.notFound'));
		}

		// Check if the user is allowed to access
		if (! $found->isAllowed())
		{
			return redirect()->to(base_url(route_to('list-answers')))->with('error', lang('app.answer.unAuthorized'));
		}

		$assignment = $found->getAssignment();

		// Check if marks obtained is not greater than total marks
		$marksObtained = $this->request->getPost('marks');
		if ($marksObtained > $assignment->marks)
		{
			return redirect()->to(base_url(route_to('view-answer-files', $id)))
					->withInput()->with('error', lang('app.answer.tooMuchMarks'));
		}
		// Save marks for only the number of questions in assignment
		$assessment = $this->request->getPost('q');

		// Check if there is no questions
		if (! is_array($assessment))
		{
			return redirect()->to(base_url(route_to('view-answer-files', $id)))
					->withInput()->with('error', lang('app.answer.invalidQuestions'));
		}

		// Check if the number of questions matches assignment
		if (count($assessment) !== intval($assignment->questions) || ! is_array($assessment))
		{
			return redirect()->to(base_url(route_to('view-answer-files', $id)))
					->withInput()->with('error', lang('app.answer.tooManyQuestions'));
		}

		// Save Total Marks with Answer
		$found->marks      = $marksObtained;
		$found->assessment = serialize($assessment);

		// If something has changed only then we have to save
		if (! $found->hasChanged())
		{
			// Redirect to view answer
			return redirect()->to(base_url(route_to('view-answer-files', $id)))
					->withInput()
					->with('message', lang('app.answer.marksSaveSuccess'));
		}

		if (! $answerModel->save($found))
		{
			return redirect()->to(base_url(route_to('view-answer-files', $id)))
			->withInput()
			->with('errors', $answerModel->errors());
		}
		// Redirect to view answer
		return redirect()->to(base_url(route_to('view-answer-files', $id)))
					->withInput()
					->with('message', lang('app.answer.marksSaveSuccess'));
	}
}
