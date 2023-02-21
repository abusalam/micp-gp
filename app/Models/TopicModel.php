<?php namespace App\Models;

use CodeIgniter\Model;

/**
 * Models provide a way to interact with a specific table in your database.
 * They come out of the box with helper methods for much of the standard ways
 * you would need to interact with a database table, including finding records,
 * updating records, deleting records, and more.
 *
 * @package ePathshala
 */
class TopicModel extends Model
{
	/**
	 * Specifies the database table that this model primarily works with. This
	 * only applies to the built-in CRUD methods. You are not restricted to using
	 * only this table in your own queries.
	 *
	 * @var string
	 */
	protected $table = 'topics';

	/**
	 * The Model’s CRUD methods will take a step of work away from you and
	 * automatically return the resulting data, instead of the Result object.
	 * This setting allows you to define the type of data that is returned.
	 * Valid values are ‘array’, ‘object’, or the fully qualified name of a class
	 * that can be used with the Result object’s getCustomResultObject() method.
	 *
	 * @var string
	 */
	protected $returnType = 'App\Entities\Topic';

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
		'topic',
		'detail',
		'class_id',
		'subject_id',
		'user_id',
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

	public function fake(&$faker)
	{
		$classes  = model('ClassModel')->findColumn('id');
		$subjects = model('SubjectModel')->findColumn('id');
		$users    = model('UserModel')->findColumn('id');
		return [
			'topic'      => $faker->catchPhrase,
			'detail'     => $faker->realText($maxNbChars = 4096, $indexSize = 2),
			'class_id'   => $faker->randomElement($classes),
			'subject_id' => $faker->randomElement($subjects),
			'user_id'    => $faker->randomElement($users),
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
		'topic'      => 'required|string|max_length[100]|is_unique[topics.topic]',
		'detail'     => 'required|string',
		'class_id'   => 'required|integer',
		'subject_id' => 'required|integer',
		'user_id'    => 'required|integer',
	];
}
