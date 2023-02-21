<?php namespace App\Entities;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

/**
 * Handles CRUD operation of Topics
 *
 * @package ePathshala
 */
class Topic extends Entity
{

	public function getTitle()
	{
		return model('ClassModel')->find($this->getClassId())['class']
			. ' - ' . model('SubjectModel')->find($this->getSubjectId())['subject']
			. ' - ' . $this->attributes['topic'];
	}
	public function getDetail()
	{
		return $this->attributes['detail'] ?? '';
	}
	public function getClass()
	{
		return model('ClassModel')->find($this->getClassId())->class ?? '';
	}
	public function getClassId()
	{
		return $this->attributes['class_id'] ?? '';
	}

	public function getSubjectName()
	{
		return model('SubjectModel')->find($this->attributes['subject_id'])->subject ?? '';
	}
	public function getSubjectId()
	{
		return $this->attributes['subject_id'] ?? '';
	}
	public function setCreatedBy()
	{
		$this->attributes['user_id'] = user_id();
		return $this;
	}

	public function getCreatedBy()
	{
		return $this->attributes['user_id'];
	}
}
