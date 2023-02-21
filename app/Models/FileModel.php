<?php namespace App\Models;

use CodeIgniter\Model;
use App\Entities\File;
use App\Models\UserModel;
use App\Models\AssignmentModel;

/**
 * Models provide a way to interact with a specific table in your database.
 * They come out of the box with helper methods for much of the standard ways
 * you would need to interact with a database table, including finding records,
 * updating records, deleting records, and more.
 *
 * @package ePathshala
 */
class FileModel extends Model
{
	/**
	 * Specifies the database table that this model primarily works with. This
	 * only applies to the built-in CRUD methods. You are not restricted to using
	 * only this table in your own queries.
	 *
	 * @var string
	 */
	protected $table = 'files';

	/**
	 * The Model’s CRUD methods will take a step of work away from you and
	 * automatically return the resulting data, instead of the Result object.
	 * This setting allows you to define the type of data that is returned.
	 * Valid values are ‘array’, ‘object’, or the fully qualified name of a class
	 * that can be used with the Result object’s getCustomResultObject() method.
	 *
	 * @var string
	 */
	protected $returnType = 'App\Entities\File';

	/**
	 * This boolean value determines whether the current date is automatically
	 * added to all inserts and updates. If true, will set the current time in the
	 * format specified by $dateFormat. This requires that the table have columns
	 * named ‘created_at’ and ‘updated_at’ in the appropriate data type.
	 *
	 * @var boolean
	 */
	protected $useTimestamps = true;

	/**
	 * If true, then any delete* method calls will set deleted_at in the database,
	 * instead of actually deleting the row. This can preserve data when it might
	 * be referenced elsewhere, or can maintain a “recycle bin” of objects that
	 * can be restored, or even simply preserve it as part of a security trail.
	 * If true, the find* methods will only return non-deleted rows, unless the
	 * withDeleted() method is called prior to calling the find* method.
	 *
	 * This requires either a DATETIME or INTEGER field in the database as per
	 * the model’s $dateFormat setting. The default field name is deleted_at
	 * however this name can be configured to any name of your choice by using
	 * $deletedField property.
	 *
	 * @var boolean
	 */
	protected $useSoftDeletes = true;

	/**
	 * This array should be updated with the field names that can be set
	 * during save, insert, or update methods. Any field names other than
	 * these will be discarded. This helps to protect against just taking
	 * input from a form and throwing it all at the model, resulting in
	 * potential mass assignment vulnerabilities.
	 *
	 * @var array
	 */
	protected $allowedFields = [
		'file',
		'size',
		'user_id',
	];

	/**
	 * Attach File to Assignment/Answer after uploading
	 *
	 * @var array
	 */
	protected $afterInsert = ['addTo'];

	/**
	 * At many points in your career, you will run into situations where the use
	 * of an application has changed and the original column names in the database
	 * no longer make sense. Or you find that your coding style prefers camelCase
	 * class properties, but your database schema required snake_case names. These
	 * situations can be easily handled with the Entity class’ data mapping features.
	 *
	 * @var array
	 */
	protected $datamap = [];

	/**
	 * The id of the material to which this file is to be attached
	 * set internally by attachTo($id)
	 *
	 * @var integer
	 */
	protected $attachID;

	/**
	 * The id of the material to which this file is to be attached
	 * set internally by attachTo($id)
	 *
	 * @var integer
	 */
	protected $attachClass;

	public function fake(&$faker)
	{
		$dir       = WRITEPATH . env('app.fileDirectory', 'uploads') . '/' . date('Ymd');
		$width     = 620;
		$height    = 832;
		$userModel = new UserModel();
		$users     = $userModel->findColumn('id');
		//$assignments = model('AssignmentModel')->findColumn('id');

		//$this->attachWith(intval($faker->randomElement($assignments)));

		// If Folder Doesn't Exists Create it
		if (! file_exists($dir))
		{
			mkdir($dir, 0755, true);
		}

		$fullPath = $faker->image($dir, $width, $height, 'cats', true, true, 'Faker');

		return [
			// Return file path starting from  YYYYMMDD/*.jpg
			'file'    => substr($fullPath, strlen($dir) - 8),
			'size'    => $faker->numberBetween($min = 10000, $max = 9000000),
			'user_id' => $faker->randomElement($users),
		];
	}

	/**
	 * Contains either an[] of validation rules as described in How to save your
	 * rules(https://codeigniter.com/user_guide/libraries/validation.html#validation-array)
	 * a string containing the name of a validation group, as described in the same section.
	 *
	 * The following is an example:
	 *
	 * <input type='file' name='avatar'>
	 *
	 * $this->validate([
	 *    'avatar' => 'uploaded[avatar]|is_image[avatar]|max_size[avatar,1024]',
	 * ]);
	 *
	 * @var array
	 */
	protected $validationRules = [
		'file'    => 'required', //'uploaded[file]|is_image[file]',
		'size'    => 'required|integer',
		'user_id' => 'required|integer',
	];

	/**
	 * Sets the material for the file must be called before save() or insert()
	 *
	 * @param integer $id            The id of the material (Assignment/Answer)
	 * @param string  $materialClass The Class Name of the material
	 *
	 * @return object
	 */
	public function attachWith(int $id, string $materialClass = AssignmentModel::class)
	{
		$this->attachID    = $id ;
		$this->attachClass = $materialClass;
		// echo 'Attachment ID:' . $id . "\n";
		return $this;
	}

	/**
	 * Gets the material to which this file is attached to this function assumes
	 * that if this file exists then it must be attached to
	 *
	 * @param integer $fileID File id for which assignment to be found
	 *
	 * @return object
	 */
	public function getAssignmentByFileID(int $fileID)
	{
		// Check if it is already cached
		if (! $found = cache($fileID . '_file_assignment'))
		{
			// Attached material is already available
			if (is_numeric($this->attachID))
			{
				if ($this->attachClass === AssignmentModel::class)
				{
					$assignment = new $this->attachClass;
					$found      = $assignment->find($this->attachID);
				}
				else
				{
					// We have the answer just find the assignment
					$found = model('AnswerModel')->find($this->attachID)->getAssignment();
				}
			}
			else
			{
				// The attachID is not set lets check it from database
				// Check if this file belongs to any assignment directly
				$found = $this->builder()
				->select('assignments.id')
				->join('assignment_files', 'assignment_files.file_id = files.id', 'left')
				->join('assignments', 'assignments.id = assignment_files.assignment_id', 'left')
				->where('files.id', $fileID)
				->get()->getResultArray();
				// ->getCompiledSelect();
				if (is_numeric($found[0]['id']))
				{
					$found = model('AssignmentModel')->find($found[0]['id']);
				}
				else
				{
					// Now the file must belong to the answer of an assignment
					$found = $this->builder()
					->select('assignments.*')
					->join('answer_files', 'answer_files.file_id = files.id', 'left')
					->join('answers', 'answers.id = answer_files.answer_id', 'left')
					->join('assignments', 'answers.assignment_id = assignments.id', 'left')
					->where('files.id', $fileID)
					->get()->getResultArray();
					// ->getCompiledSelect();
					if (is_numeric($found[0]['id']))
					{
						$found = model('AssignmentModel')->find($found[0]['id']);
					}
				}
			}
			cache()->save($fileID . '_file_assignment', $found, env('app.cacheTimeout', 300));
		}
		return $found;
	}

	/**
	 * Gets the answer to which this file is attached to if not the returns null
	 *
	 * @param integer $fileID File id for which answer to be found
	 *
	 * @return object
	 */
	public function getAnswerByFileID(int $fileID)
	{
		// Check if it is already cached
		if (! $found = cache($fileID . '_file_answer'))
		{
			// Attached answer is already available
			if (is_numeric($this->attachID))
			{
				if ($this->attachClass === AnswerModel::class)
				{
					$answer = new $this->attachClass;
					$found  = $answer->find($this->attachID);
				}
			}
			else
			{
				// Now the file may belong to an answer
				$found = $this->builder()
				->select('answers.*')
				->join('answer_files', 'answer_files.file_id = files.id', 'left')
				->join('answers', 'answers.id = answer_files.answer_id', 'left')
				->where('files.id', $fileID)
				->get()->getResultArray();
				// ->getCompiledSelect();
				if (is_numeric($found[0]['id']))
				{
					$found = model('AnswerModel')->find($found[0]['id']);
				}
			}

			cache()->save($fileID . '_file_answer', $found, env('app.cacheTimeout', 300));
		}
		return $found;
	}

	/**
	 * Add File IDs to assignment_files/answer_files table both the materials
	 * must define an **addFile($id, $class)** method
	 *
	 * @param array $data To be called by afterInsert with array of file data
	 *
	 * @return array
	 */
	protected function addTo(array $data)
	{
		// Update the InsertID
		$this->id = $data['id'];

		if (is_numeric($this->attachID))
		{
				$newModel = new $this->attachClass;
				$newModel->addFile($data['id'], $this->attachID);
		}
		return $data;
	}
}
