<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index',[
	'as' => 'home'
]);

// Bookings Route Group
$routes->group(
	'booking',
	['namespace' => 'App\Controllers'],
	function($routes) {
		
		// List of All Bookings
		$routes->get('', 'BookingController::index', [
			'as' => 'view-bookings',
			'filter' => 'role:admin'
			]);

		// Shows the Booking form
		$routes->get('new', 'BookingController::createBooking', [
			'as' => 'create-booking',
			]);
		$routes->post('new', 'BookingController::tryToCreateBooking', [
			'as' => 'create-booking'
			]);

		// Make Payment for the booking
		$routes->get('(:num)/pay', 'BookingController::makePayment/$1', [
			'as' => 'make-payment',
			]);
		$routes->post('(:num)/pay', 'BookingController::tryToMakePayment/$1', [
			'as' => 'make-payment',
			]);
		$routes->get('(:num)/status', 'BookingController::setStatus/$1', [
			'as' => 'status',
			]);
		$routes->post('(:num)/webhook', 'BookingController::webhook/$1', [
			'as' => 'webhook',
			]);
		$routes->get('(:num)/print', 'BookingController::printReceipt/$1', [
			'as' => 'print',
			]);
		$routes->get('check', 'BookingController::getBookingSlot', [
			'as' => 'check',
			]);
		$routes->post('check', 'BookingController::getBookingsByDate', [
			'as' => 'check',
			]);
		$routes->get('ticket', 'BookingController::showTicketSearch', [
			'as' => 'search',
			]);
		$routes->post('ticket', 'BookingController::getBookingsByRef', [
			'as' => 'search',
			]);
	}
);