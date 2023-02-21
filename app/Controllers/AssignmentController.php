<?php namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Models\AssignmentModel;
use App\Entities\Assignment;
use App\Models\FileModel;
use App\Entities\File;

/**
 * Assignment Controller the following functions
 *
 * 1. Show a list of eligible assignments for the current user
 * 2. Displays a single assignment if the user is eligible
 * 3. Show a form to create new assignment with the current users topics
 * 4. Saves a single assignment
 * 5. Show a form to upload file to be attached with it
 * 6. Saves the file and attaches to itself
 *
 * @package ePathshala
 */
class AssignmentController extends BaseController
{
	/**
	 * Displays the list of assignments for the logged in user
	 *
	 * Route: /assignment/list as get:view-assignments
	 * Uses view assignment/list-form.php
	 *
	 * @return view
	 */
	public function index()
	{
		helper('inflector');
		$assignmentModel = model('AssignmentModel');
		if (in_groups('teachers'))
		{
			$assignments = $assignmentModel->asArray()
					->select('id,topic_id,title,marks,status')
					->where('user_id', user_id())
					->orderBy('updated_at', 'DESC')
					->paginate();
		}
		else
		{
			// To Display Assignments it is ensured that Assignment and Topic both
			// should be created by the teacher of that school only and
			// the assignment status must be sent
			$assignments = $assignmentModel->findAssignmentsToSolve(user_id());
		}
		//dd($assignments);
		// Define the Table Heading
		$_SESSION['heads'] = [
			'id'       => 'ID# | Files',
			'topic_id' => 'Topic',
			'title'    => 'Assignment',
			'marks'    => 'Marks',
			'status'   => 'Status',
		];

		$rows = [];

		$callback = function (&$value, $key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		// Double Check for Access Rights and Locked Status
		foreach ($assignments as $assignment)
		{
			$found = model('AssignmentModel')->find($assignment['id']);
			if ($found->isAllowed())
			{
				if (! in_groups('teachers'))
				{
					if ($found->isLocked())
					{
						array_push($rows, array_filter($assignment, $callback, ARRAY_FILTER_USE_BOTH));
					}
				}
				else
				{
					array_push($rows, array_filter($assignment, $callback, ARRAY_FILTER_USE_BOTH));
				}
			}
		}

		$updateArray = function (&$value, $key) {
			switch(true){

				case ($key === 'topic_id'):
					$value = model('TopicModel')->find($value)->getTitle();
				break;

				case ($key === 'id'):
					$files = model('AssignmentModel')->getFiles($value);
					// From Route: assignment/list => assignment/#id
					$value = $value . ' | ' . '<a href="' . base_url(route_to('view-assignment-files', $value)) . '">' . counted(count($files ?? []), 'Page') . '</a>';
				break;
			}
		};

		array_walk_recursive($rows, $updateArray);

		$data = [
			'heads' => $_SESSION['heads'],
			'rows'  => $rows,
			'pager' => $assignmentModel->pager,
		];

		unset($_SESSION['heads']);

		$data['config'] = $this->config;

		return view('Assignment/list-form', $data);
	}

	/**
	 * Displays Assignment Files with Topic Details
	 *
	 * Route: /assignment/#assignmentId as get:view-assignment-files
	 * Uses view assignment/file-form.php
	 *
	 * @param integer $id Assignment to be displayed
	 *
	 * @return view
	 */
	public function view(int $id = null)
	{
		$found = model('AssignmentModel')->find($id);
		if (! $found)
		{
			return redirect()->to(base_url(route_to('view-assignments')))
					->with('error', lang('app.assignment.notFound'));
		}
		if (! $found->isAllowed())
		{
			return redirect()->to(base_url(route_to('view-assignments')))
					->with('error', lang('app.assignment.unAuthorized'));
		}
		$data['id']     = $id;
		$data['title']  = $found->title;
		$data['topic']  = $found->getTopic()->getTitle();
		$data['detail'] = $found->getTopic()->getDetail();
		$data['files']  = $found->getFiles();
		$data['locked'] = $found->isLocked();
		$data['config'] = $this->config;
		return view('Assignment/view-form', $data);
	}

	public function create()
	{
		$data['config']   = $this->config;
		$data['subjects'] = model('SubjectModel')->asArray()->findAll();
		$data['topics']   = model('TopicModel')->asArray()->where('user_id', user_id())->findAll();
		$db               = db_connect();
		$data['classes']  = $db->table('classes')->select('id, class')->get()->getResultArray();
		$db->close();
		$data['assignment'] = (ENVIRONMENT !== 'production') ?
													(new Fabricator(AssignmentModel::class))->make() :
													new Assignment();

		// Display message if Update is Required
		model('UserModel')->find(user_id())->isProfileUpdateRequired();

		return view('Assignment/create-form', $data);
	}

	public function tryToCreate()
	{
		$assignmentModel = new AssignmentModel();
		$newAssignment   = new Assignment();
		$newAssignment->setCreatedBy()->fill($this->request->getPost());

		if (! $assignmentModel->save($newAssignment))
		{
			return redirect()->back()->withInput()->with('errors', $assignmentModel->errors());
		}
		$parser = \Config\Services::parser();
		$data   = [
			'id'    => $assignmentModel->getInsertID(),
			'title' => $newAssignment->title,
		];
		return redirect()->to(base_url(route_to('add-assignment-file', $data['id'])))
							->with(
								'message',
								$parser->setData($data)
								->renderString(lang('app.assignment.createSuccess'))
							);
	}

	/**
	 * Shows the form to attach file to assignment
	 * Route: /assignment/file as get:add-assignment-file
	 * Uses view File/file-form.php
	 *
	 * @param integer $id The id of the Assignment to which file to be attached
	 *
	 * @return view
	 */
	public function attachFile(int $id)
	{
		$data['id']         = $id;
		$data['config']     = $this->config;
		$data['assignment'] = model('AssignmentModel')->find($id);
		$data['targetDims'] = \Config\Services::parser()
														->setData([
															'width'  => env('app.imageWidth', 595),
															'height' => env('app.imageHeight', 842),
														])
														->renderString(lang('app.file.createHelp'));
		if ($data['assignment'])
		{
			if ($data['assignment']->isLocked())
			{
				return redirect()->to(base_url(route_to('view-assignment-files', $id)))
						->with('message', lang('app.assignment.msgLocked'));
			}
			$data['files'] = model('AssignmentModel')->getFiles($id);
			// This view contains forms pointing to the tryToAttachFile/$1 route
			// of this controller itself
			return view('Assignment/file-form', $data);
		}
		else
		{
			return redirect()->to(base_url(route_to('view-assignments')))
						->with('error', lang('app.assignment.notFound'));
		}
	}

	/**
	 * Uploads files to configured directory with the following env settings
	 * by default it updates the existing file in place
	 *
	 * Value of fileDirectory is relative to WRITEPATH without any slashes
	 * Should not be changed when files already uploaded to another location
	 * without renaming that location
	 * app.fileDirectory = 'uploads'
	 *
	 * Image Compression Options
	 *
	 * app.imageQuality = 90
	 * app.imageWidth   = 620
	 * app.imageHeight  = 832
	 *
	 * Route: /assignment/file as post:add-assignment-file
	 * Uses view File/file-form.php
	 *
	 * @param integer $id The id of the Assignment to which file to be attached
	 *
	 * @return view
	 */
	public function tryToAttachFile(int $id)
	{
		$newFile   = new File();
		$savedFile = $newFile->save(
										$this->request->getFile('imageFile'),
										AssignmentModel::class,
										$id
									);

		// The File Object is returned by the save function if not
		// then there must be some error
		if (! $savedFile instanceof File)
		{
			return redirect()->to(base_url(route_to('add-assignment-file', $id)))
							->with(is_array($savedFile) ? 'errors' : 'error', $savedFile);
		}

		$parser    = \Config\Services::parser();
		$savedFile = $savedFile->toArray();

		return redirect()->to(base_url(route_to('add-assignment-file', $id)))
							->with(
									'message',
									$parser->setData($savedFile)
											->renderString(lang('app.file.createSuccess'))
								);
	}

	/**
	 * Locks the Assignment to send it to students to prepare answer and submit
	 *
	 * @param integer $id Assignment ID to be sent
	 *
	 * @return view
	 */
	public function lock(int $id)
	{
		// Check If the id in integer
		if (is_numeric($id))
		{
			$assignmentModel = new AssignmentModel();
			$newAssignment   = model('AssignmentModel')->find($id);
			if ($newAssignment === null)
			{
				redirect()->to(base_url(route_to('view-assignments')))
						->with('error', lang('app.assignment.notFound'));
			}
			else
			{
				$newAssignment->lock();
				// Save the assignment
				if (! $assignmentModel->save($newAssignment))
				{
					return redirect()->to(base_url(route_to('add-assignment-file', $id)))
										->withInput()
										->with('errors', $assignmentModel->errors());
				}
				else
				{
					return redirect()->to(base_url(route_to('view-assignments')))->with(
						'message',
						lang('app.assignment.lockSuccess')
					);
				}
			}
			//dd($newAssignment);
		}
		else
		{
			redirect()->to(base_url(route_to('view-assignments')))
						->with('error', lang('app.assignment.notFound'));
		}
	}

	/**
	 * Display a list of Answers for an assignment
	 *
	 * Route: /assignment/#assignmentId/answers as get:check-answers
	 * Uses view Evaluate/list-form.php
	 *
	 * @param integer $id Assignment for which answers to be displayed
	 *
	 * @return view
	 */
	public function answers(int $id = null)
	{
		helper('inflector');
		// Check if the answer actually exists
		$assignment = model('AssignmentModel')->find($id);

		if (! $assignment)
		{
			return redirect()->to(base_url(route_to('view-assignments')))->with('error', lang('app.assignment.notFound'));
		}

		if (! $assignment->isAllowed())
		{
			return redirect()->to(base_url(route_to('view-assignments')))->with('error', lang('app.assignment.unAuthorized'));
		}

		$answerModel = model('AnswerModel');
		$found       = $answerModel->getAnswersToCheck($id);

		//dd($found);
		// If no answer submitted then getAnswersToCheck() function returns false
		if (! is_array($found))
		{
			return redirect()->to(base_url(route_to('view-assignment-files', $id)))
					->with('error', lang('app.answer.notFound'));
		}

		// Define the Table Heading
		$_SESSION['heads'] = [
			'id'         => 'ID#',
			'full_name'  => 'Given By',
			'files'      => 'Answer',
			'status'     => 'Status',
			'updated_at' => 'Submitted On',
		];

		$rows = [];

		$callback = function (&$value, $key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		foreach ($found as $answer)
		{
			//dd($answer);
			array_push($rows, array_filter($answer, $callback, ARRAY_FILTER_USE_BOTH));
		}

		$updateArray = function (&$value, $key) {
			switch(true){

				case ($key === 'topic_id'):
					$value = model('TopicModel')->find($value)->getTitle();
				break;

				case ($key === 'files'):
					$files = model('AnswerModel')->getFiles($value);
					// From Route: assignment/list => assignment/#id
					$value = '<a href="' . base_url(route_to('view-answer-files', $value)) . '">' . counted(count($files ?? []), 'Page') . '</a>';
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

		$data['config']     = $this->config;
		$data['id']         = $id;
		$data['assignment'] = $assignment;
		$data['title']      = $assignment->title;

		return view('Evaluate/list-form', $data, ['cache' => env('app.cacheTimeout', 300)]);
	}
}
