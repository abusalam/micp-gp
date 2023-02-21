<?php namespace App\Entities;

use CodeIgniter\I18n\Time;
use Myth\Auth\Entities\User as Entity;
use Myth\Auth\Config\Services;
use App\Models\UserModel;

/**
 * Handles CRUD operation of Users
 *
 * @package ePathshala
 */
class User extends Entity
{

	public function getFullName()
	{
		return $this->attributes['full_name'] ?? '';
	}

	public function isProfileUpdateRequired()
	{

		$authorize = Services::authorization();
		if ($authorize->inGroup(env('auth.defaultUserGroup', 'students'), $this->id))
		{
			// if ($this->getClassId() === '')
			// {
			// 	session()->set('has_no_profile', true);
			// 	session()->set('message', lang('app.profile.hasNoClass'));
			// 	return true;
			// }
		}
		// Required to update session to render routes
		session()->set('has_no_profile', false);
		return false;
	}
}
