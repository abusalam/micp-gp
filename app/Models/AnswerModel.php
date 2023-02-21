<?php namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Answer;

/**
 * Models provide a way to interact with a specific table in your database.
 * They come out of the box with helper methods for much of the standard ways
 * you would need to interact with a database table, including finding records,
 * updating records, deleting records, and more.
 *
 * @package ePathshala
 */
class AnswerModel extends Model
{
	/**
	 * Specifies the database table that this model primarily works with. This
	 * only applies to the built-in CRUD methods. You are not restricted to using
	 * only this table in your own queries.
	 *
	 * @var string
	 */
	protected $table = 'answers';

	/**
	 * The Model’s CRUD methods will take a step of work away from you and
	 * automatically return the resulting data, instead of the Result object.
	 * This setting allows you to define the type of data that is returned.
	 * Valid values are ‘array’, ‘object’, or the fully qualified name of a class
	 * that can be used with the Result object’s getCustomResultObject() method.
	 *
	 * @var string
	 */
	protected $returnType = 'App\Entities\Answer';

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
	 * potential mass Answer vulnerabilities.
	 *
	 * @var array
	 */
	protected $allowedFields = [
		'assignment_id',
		'marks',
		'assessment',
		'user_id',
		'status',
	];

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
	 * Adds a single file to this Answer and clear cache AnswerId#_answer_files
	 *
	 * @param integer $fileID   The newly saved file id
	 * @param integer $answerID The Answer id for which this file is uploaded
	 *
	 * @return object
	 */
	public function addFile(int $fileID, int $answerID)
	{
		cache()->delete($answerID . '_answer_files');
		$data = [
			'file_id'   => $fileID,
			'answer_id' => $answerID,
			'user_id'   => user_id(),
		];
		return $this->db->table('answer_files')->insert($data);
	}

	/**
	 * Save a single checked file to this Answer
	 *
	 * @param array $data Contains file_id, answer_id, and assessment data
	 *
	 * @return object
	 */
	public function saveCheckedFile(array $data)
	{
		return $this->db->table('answer_files')
				->where('file_id', $data['file_id'])
				->where('answer_id', $data['answer_id'])
				->update($data);
	}

	/**
	 * Load a single checked file of this Answer which shall be used for rendering
	 * the pen markings and should contain valid JSON array in assessment property
	 *
	 * Sample Object:
	 * object(stdClass)#86 (6) {
	 *   ["answer_id"] => string(1) "1"
	 *   ["file_id"]   => string(1) "3"
	 *   ["page_no"]   => string(1) "0"
	 *   ["assessment"]=> string(2) "[]"
	 *   ["user_id"]   => string(1) "3"
	 *   ["status"]    => string(7) "pending"
	 * }
	 *
	 * @param integer $fileID The newly saved file id
	 *
	 * @return object
	 */
	public function getCheckedFile(int $fileID)
	{
		$answerID = model('FileModel')->find($fileID)->getAnswer()->id;
		$row      = $this->db->table('answer_files')
				->where('file_id', $fileID)
				->where('answer_id', $answerID)
				->get()->getRow();
		if (! $row->assessment)
		{
			$row->assessment = '[]';
		}
		return $row;
	}

	/**
	 * Returns the list of Files as Array and cache as AnswerId#_answer_files
	 *
	 * @param integer $id Answer ID for which files are required
	 *
	 * @return array
	 */
	public function getFiles(int $id)
	{
		if (! $found = cache($id . '_answer_files'))
		{
			$found = $this->builder()
				->select('files.id,files.file,files.size')
				->join('answer_files', 'answers.id = answer_files.answer_id', 'right')
				->join('files', 'answer_files.file_id = files.id', 'left')
				->where('answer_files.answer_id', $id)
				->where('files.deleted_at', null)
				// ->getCompiledSelect();
				->get()->getResultArray();
			cache()->save($id . '_answer_files', $found, env('app.cacheTimeout', 300));
		}
		return $found;
	}

	/**
	 * Returns the list of Answers created for the assignment as Array
	 * and cache as AssignmentId#_answers which shall be used to get all the
	 * submitted answers for the assessment
	 *
	 * @param integer $assignmentID Assignment ID for which files are required
	 *
	 * @return array
	 */
	public function getAnswersToCheck(int $assignmentID)
	{
		$found = $this->asArray()
			->select('answers.id, full_name, answers.id as files, answers.status, answers.updated_at')
			->join('assignments', 'assignments.id=answers.assignment_id')
			->join('users', 'users.id=answers.user_id')
			->where('assignment_id', $assignmentID)
			->where('assignments.user_id', user_id())
			->where('answers.status', 'sent')
			->paginate();
			// ->getCompiledSelect();
		return $found;
	}

	/**
	 * Returns the Answer ID created by the current user which shall be used
	 * to get the submitted answer for the assignment
	 *
	 * @param integer $assignmentID Assignment ID for which files are required
	 *
	 * @return object
	 */
	public function getMyAnswerByAssignmentID(int $assignmentID)
	{
		return $this->where('assignment_id', $assignmentID)
				->where('user_id', user_id())
				// ->getCompiledSelect();
				->first();
	}

	public function fake(&$faker)
	{
		$users = model('UserModel')->findColumn('id');

		// Get list of allowed assignments
		$forUser    = $faker->randomElement($users);
		$assignment = model('AssignmentModel')->findAll();

		return [
			'assignment_id' => $faker->randomElement($assignment)->id,
			'user_id'       => $faker->randomElement($users),
			'marks'         => $faker->numberBetween(1, 5) * 20,
			'status'        => 'sent',
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
		'assignment_id' => 'required|integer',
		'marks'         => 'permit_empty|numeric',
		'user_id'       => 'required|integer',
	];

}
