<?php namespace App\Models;

use CodeIgniter\Model;
use App\Entities\School;

/**
 * Models provide a way to interact with a specific table in your database.
 * They come out of the box with helper methods for much of the standard ways
 * you would need to interact with a database table, including finding records,
 * updating records, deleting records, and more.
 *
 * @package ePathshala
 */
class ClassModel extends Model
{
	/**
	 * Specifies the database table that this model primarily works with. This
	 * only applies to the built-in CRUD methods. You are not restricted to using
	 * only this table in your own queries.
	 *
	 * @var string
	 */
	protected $table = 'classes';

	/**
	 * The Model’s CRUD methods will take a step of work away from you and
	 * automatically return the resulting data, instead of the Result object.
	 * This setting allows you to define the type of data that is returned.
	 * Valid values are ‘array’, ‘object’, or the fully qualified name of a class
	 * that can be used with the Result object’s getCustomResultObject() method.
	 *
	 * @var string
	 */
	protected $returnType = 'array';

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
		'class',
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
		'class' => 'required|alpha_space|max_length[25]|is_unique[classes.class,class,{class}]',
	];

	/**
	 * Adds a single user to a single class.
	 *
	 * @param integer $userId
	 * @param integer $classId
	 *
	 * @return object
	 */
	public function addUserToClass(int $userId, int $classId)
	{
		cache()->delete($userId . '_classes');

		$data = [
			'user_id'  => (int)$userId,
			'class_id' => (int)$classId,
		];

		return $this->db->table('classes_users')->insert($data);
	}

	/**
	 * Removes a single user from a single class.
	 *
	 * @param integer $userId
	 * @param integer $classId
	 *
	 * @return boolean
	 */
	public function removeUserFromClass(int $userId, int $classId)
	{
		cache()->delete($userId . '_classes');

		return $this->db->table('classes_users')
			->where([
				'user_id'  => (int)$userId,
				'class_id' => (int)$classId,
			])->delete();
	}

		/**
		 * Returns an array of all users that are members of a class.
		 *
		 * @param integer $classId ClassID to get students for
		 *
		 * @return array
		 */
	public function getUsersForClass(int $classId)
	{
		if (! $found = cache($classId . '_users'))
		{
			$found = $this->builder()
				->select('classes_users.*, users.*')
				->join('classes_users', 'classes_users.class_id = classes.id', 'left')
				->join('users', 'classes_users.user_id = users.id', 'left')
				->where('classes_users.class_id', $classId)
				->get()->getResultArray();

			cache()->save($classId . '_users', $found, env('app.cacheTimeout', 300));
		}

		return $found;
	}

	/**
	 * Removes a single user from all classes.
	 *
	 * @param integer $userId UserID to be removed
	 *
	 * @return mixed
	 */
	public function removeUserFromAllClasses(int $userId)
	{
		cache()->delete($userId . '_classes');

		return $this->db->table('classes_users')
			->where('user_id', (int)$userId)
			->delete();
	}
}
