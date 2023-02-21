<?php namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Assignment;

/**
 * Models provide a way to interact with a specific table in your database.
 * They come out of the box with helper methods for much of the standard ways
 * you would need to interact with a database table, including finding records,
 * updating records, deleting records, and more.
 *
 * @package ePathshala
 */
class BookingModel extends Model
{
	/**
	 * Specifies the database table that this model primarily works with. This
	 * only applies to the built-in CRUD methods. You are not restricted to using
	 * only this table in your own queries.
	 *
	 * @var string
	 */
	protected $table = 'bookings';

	/**
	 * The Model’s CRUD methods will take a step of work away from you and
	 * automatically return the resulting data, instead of the Result object.
	 * This setting allows you to define the type of data that is returned.
	 * Valid values are ‘array’, ‘object’, or the fully qualified name of a class
	 * that can be used with the Result object’s getCustomResultObject() method.
	 *
	 * @var string
	 */
	protected $returnType = 'App\Entities\Booking';

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
		'ticket',
		'passenger',
		'address',
		'mobile',
		'purpose',
		'booking_date',
		'start_time',
		'hours',
		'amount',
		'pg_resp',
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
	 * Adds a single file to this Assignment and clear cache assignmentId#_assignment_files
	 *
	 * @param integer $fileID       The newly saved file id
	 * @param integer $assignmentID The assignment id for which this file is uploaded
	 *
	 * @return object
	 */
	public function addFile(int $fileID, int $assignmentID)
	{
		cache()->delete($assignmentID . '_assignment_files');
		$data = [
			'file_id'       => $fileID,
			'assignment_id' => $assignmentID,
		];
		return $this->db->table('assignment_files')->insert($data);
	}

	/**
	 * Returns the list of Files as Array and cache as assignmentId#_assignment_files
	 *
	 * @param integer $assignmentID Assignment ID for which files are required
	 *
	 * @return array
	 */
	public function getFiles(int $assignmentID)
	{
		if (! $found = cache($assignmentID . '_assignment_files'))
		{
			$found = $this->builder()
				->select('files.id,files.file,files.size')
				->join('assignment_files', 'assignments.id = assignment_files.assignment_id', 'right')
				->join('files', 'assignment_files.file_id = files.id', 'left')
				->where('assignment_files.assignment_id', $assignmentID)
				->where('files.deleted_at', null)
				//->getCompiledSelect();
				->get()->getResultArray();
			cache()->save($assignmentID . '_assignment_files', $found, env('app.cacheTimeout', 300));
		}
		return $found;
	}


	public function fake(&$faker)
	{
		return [
			'ticket'    => $faker->ean8,
			'passenger' => $faker->name,
			'mobile'    => $faker->numberBetween(
				                    $min = 6000000000,
				                    $max = 9999999999
			               ),
			'address'   => $faker->address,
			'purpose'   => $faker->sentence($nbWords = 4, $variableNbWords = true),
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
		'passenger' => 'required|string|max_length[100]',
		'mobile'    => 'required|integer|is_natural_no_zero|exact_length[10]',
		'address'   => 'required|string|max_length[255]',
		'purpose'   => 'required|string|max_length[255]',
	];

	//protected $beforeInsert = ['setBookingDate'];
	//protected $beforeUpdate = ['setBookingDate'];

	protected function setBookingDate(array $data)
	{
		if (! isset($data['data']['booking_date'])) {
				return $data;
		}

		$data['data']['booking_date'] = \DateTime::createFromFormat("Y-m-d H:i:s", $data['data']['booking_date'])->format("Y-m-d");
		unset($data['data']['booking_date']);

		return $data;
	}
}
