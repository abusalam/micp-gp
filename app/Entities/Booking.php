<?php namespace App\Entities;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

/**
 * Handles CRUD operation of Assignments
 *
 * @package ePathshala
 */
class Booking extends Entity
{

	public function getPassNo()
	{
		return $this->attributes['id'] ?? '';
	}

	public function getIssueDate()
	{
		return $this->attributes['issued_on'] ?? '';
	}

	public function getValidTill()
	{
		return $this->attributes['valid_till'] ?? '';
	}

	public function getVehicleNo()
	{
		return $this->attributes['vehicle_no'] ?? '';
	}

	public function getDriverName()
	{
		return $this->attributes['driver_name'] ?? '';
	}

	public function getDriverAddress()
	{
		return $this->attributes['driver_address'] ?? '';
	}

	public function getLicenseNo()
	{
		return $this->attributes['license_no'] ?? '';
	}

	public function getDOB()
	{
		return $this->attributes['deleted_at'] ?? '';
	}

	public function getCrewName()
	{
		return $this->attributes['crew_name'] ?? '';
	}

	public function getCrewAddress()
	{
		return $this->attributes['crew_address'] ?? '';
	}

	public function getCrewID()
	{
		return $this->attributes['crew_id_no'] ?? '';
	}

	public function getCrewIdType()
	{
		return $this->attributes['crew_id_type'] ?? '';
	}

}
