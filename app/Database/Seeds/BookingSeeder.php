<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Test\Fabricator;
use App\Models\BookingModel;

class BookingSeeder extends Seeder
{
	public function run()
	{
		$fabricator         = new Fabricator(BookingModel::class);
		$newBookingModel = model('BookingModel');
		$newBookings     = $fabricator->make(10);
		foreach ($newBookings as $Booking)
		{
			$newBookingModel->save($Booking);
		}
	}
}
