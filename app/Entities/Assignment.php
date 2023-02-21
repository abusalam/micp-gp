<?php namespace App\Entities;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

/**
 * Handles CRUD operation of Assignments
 *
 * @package ePathshala
 */
class Assignment extends Entity
{
	/**
	 * This is to provide access to the topic of this assignment
	 *
	 * @var object
	 */
	protected $topic;

	public function getTopic()
	{
		$this->topic = model('TopicModel')->find(($this->attributes['topic_id'])) ?? new Topic();
		return $this->topic;
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
		 * ENUM 1-draft, 2-sent, 3-complete
		 */
		$this->attributes['status'] = 'sent';
		return $this;
	}

	/**
	 * Check if the current assignment is allowed for the current user
	 * considering both the roles teachers and students
	 *
	 * This function must be called after proper initialization from it's model
	 *
	 * @return boolean
	 */
	public function isAllowed()
	{
		if (in_groups('teachers'))
		{
			// Check if the user has created the assignment
			return $this->getUserId() === user_id();
		}
		if (in_groups(env('auth.defaultUserGroup', 'students')))
		{
			if ($this->status !== 'sent')
			{
				return false;
			}

			// Check for assignment > topic > school and class
			// matches current user > school and class
			$currUser = model('UserModel')->find(user_id());
			return ($this->getSchoolId() === $currUser->getSchoolId())
			&& ($this->getClassId() === $currUser->getClassId());
		}
		return true;
	}

	public function getFiles()
	{
		return model('AssignmentModel')->getFiles($this->id);
	}
	public function getUserId()
	{
		return $this->attributes['user_id'] ?? '';
	}

	public function getTopicId()
	{
		return $this->attributes['topic_id'] ?? '';
	}

	public function getSchoolId()
	{
		return model('UserModel')->getSchool($this->attributes['user_id'])[0]['id'];
	}

	public function getClass()
	{
		return $this->getTopic()->getClass();
	}

	public function getClassId()
	{
		return $this->getTopic()->getClassId();
	}

	public function setCreatedBy()
	{
		$this->attributes['user_id'] = user_id();
		return $this;
	}
}
