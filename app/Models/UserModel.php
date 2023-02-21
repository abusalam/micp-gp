<?php namespace App\Models;

use Myth\Auth\Models\UserModel as Model;

/**
 * Models provide a way to interact with a specific table in your database.
 * They come out of the box with helper methods for much of the standard ways
 * you would need to interact with a database table, including finding records,
 * updating records, deleting records, and more.
 *
 * @package ePathshala
 */
class UserModel extends Model
{
	/**
	 * Specifies the database table that this model primarily works with. This
	 * only applies to the built-in CRUD methods. You are not restricted to using
	 * only this table in your own queries.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The Model’s CRUD methods will take a step of work away from you and
	 * automatically return the resulting data, instead of the Result object.
	 * This setting allows you to define the type of data that is returned.
	 * Valid values are ‘array’, ‘object’, or the fully qualified name of a class
	 * that can be used with the Result object’s getCustomResultObject() method.
	 *
	 * @var string
	 */
	protected $returnType = 'App\Entities\User';

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
		'email',
		'username',
		'password_hash',
		'reset_hash',
		'reset_at',
		'reset_expires',
		'activate_hash',
		'status',
		'status_message',
		'active',
		'force_pass_reset',
		'permissions',
		'school_id',
		'mobile',
		'full_name',
		'description',
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
	protected $datamap = [
		'password' => 'password_hash',
		'name'     => 'full_name',
	];

	public function fake(&$faker)
	{
		$schoolModel = new SchoolModel();
		$schools     = $schoolModel->findColumn('id');

		return [
			'school_id'   => $faker->randomElement($schools),
			'mobile'      => $faker->numberBetween(
													$min = 6000000000,
													$max = 9999999999
												),
			'full_name'   => $faker->name,
			'username'    => $faker->firstname,
			'email'       => $faker->email,
			'password'    => getenv('auth.defaultPassword'),
			'active'      => 1,
			'description' => $faker->text($maxNbChars = 200),
		];
	}

	/**
	 * Returns an array of all classes that the user belongs to.
	 *
	 * @param integer $userId
	 *
	 * @return array
	 */
	public function getClasses(int $userId)
	{
		if (! $found = cache($userId . '_classes'))
		{
			$found = $this->builder()
				->select('classes.*')
				->join('classes_users', 'classes_users.user_id = users.id', 'left')
				->join('classes', 'classes_users.class_id = classes.id', 'left')
				->where('users.id', $userId)
				->get()->getResultArray();

			cache()->save($userId . '_classes', $found, env('app.cacheTimeout', 300));
		}

		return $found;
	}

	/**
	 * Returns an array of all schools that the user belongs to.
	 *
	 * @param integer $userId
	 *
	 * @return array
	 */
	public function getSchool(int $userId)
	{
		if (! $found = cache($userId . '_schools'))
		{
			$found = $this->builder()
				->select('schools.*')
				->join('schools', 'schools.id = users.school_id', 'left')
				->where('users.id', $userId)
				//->getCompiledSelect();
				->get()->getResultArray();

			cache()->save($userId . '_schools', $found, env('app.cacheTimeout', 300));
		}

		return $found;
	}
}
