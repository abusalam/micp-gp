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
	public function getBase64ImageData($attr_name)
	{
		list($format, $base64Data) = explode(';', $this->attributes[$attr_name]);
		list(, $base64Data) = explode(',', $base64Data);
		return $base64Data;
	}

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

	public function setPassValidity()
	{
		$this->attributes['issued_on'] = date('Y-m-d', time());
		$this->attributes['valid_till'] = date('Y-m-d', strtotime(getenv("PASS_VALIDITY","+30 days")));
	}

}
