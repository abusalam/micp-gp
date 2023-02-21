<?php namespace App\Entities;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

/**
 * Handles CRUD operation of Answers
 *
 * @package ePathshala
 */
class Answer extends Entity
{
	/**
	 * This is to provide access to the student details of this answer
	 *
	 * @var object
	 */
	protected $studentDetails;

	/**
	 * Provides Student Details at listing for evaluation
	 *
	 * @return object
	 */
	public function getDetail()
	{
		$this->studentDetails = model('UserModel')->find(($this->attributes['user_id']));
		return $this->studentDetails;
	}

	public function getCreatedBy()
	{
		return $this->attributes['user_id'] ?? '';
	}

	public function setCreatedBy()
	{
		$this->attributes['user_id'] = user_id();
		return $this;
	}

	public function getAssignmentID()
	{
		return $this->attributes['assignment_id'] ?? '';
	}

	public function getStatus()
	{
		return $this->attributes['status'] ?? '';
	}

	public function isLocked()
	{
		return $this->attributes['status'] !== 'draft';
	}

	public function lock()
	{
		/**
		 * ENUM 1-draft, 2-sent, 3-checked
		 */
		$this->attributes['status'] = 'sent';
		return $this;
	}

	public function getAssignment()
	{
		return model('AssignmentModel')->find($this->getAssignmentID());
	}

	public function getFiles()
	{
		return model('AnswerModel')->getFiles($this->id);
	}

	/**
	 * Check if the current answer is allowed for the current user
	 * considering both the roles teachers and students
	 *
	 * This function must be called after proper initialization from it's model
	 *
	 * @return boolean
	 */
	public function isAllowed()
	{
		if (in_groups(env('auth.defaultUserGroup', 'students')))
		{
			// Check if the user has created the answer
			return $this->getCreatedBy() === user_id();
		}
		if (in_groups('teachers'))
		{
			if ($this->status !== 'sent')
			{
				return false;
			}
			// Check for answer > assignment > user
			// matches current user
			return $this->getAssignment()->isAllowed();
		}
		return true;
	}
}
