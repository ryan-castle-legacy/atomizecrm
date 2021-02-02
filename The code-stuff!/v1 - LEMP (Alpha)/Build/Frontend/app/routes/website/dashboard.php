<?php

// * File: 				/routes/website/dashboard.php
// * Description:		This file holds the routes for the public-facing pages on the AtomizeCRM Frontend.
// * Created:			17th December 2020 at 12:03
// * Updated:			17th December 2020 at 23:22
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\APIRequest;


// ----------

// @route               GET /dashboard
// @description         The dashboard's home page
// @access              Private
Route::get('/dashboard', function() {
	// Check if the user has not been added to an organisation
	if (Session::get('users_organisations') == null || @sizeof(Session::get('users_organisations')) == 0) {
		// Redirect the user to the organisation set-up/join
		return Redirect::route('organisations-userSetupOrJoin');
	}
	// Check if the user has switched to an organisation
	if (Session::has('currentOrg')) {
		// Set which organisation the dashboard is focussed on
		Session::put('dashboard-currentOrg', Session::get('currentOrg'));
	// Check if the user has not been assigned to their organisation
	} else {
		// Set which organisation the dashboard is focussed on
		Session::put('dashboard-currentOrg', Session::get('users_organisations')[0]->organisation_unique_id);
	}
    // Return view for this page
    return view('website/dashboard/home');
})->middleware('auth', 'singleSession')->name('dashboard-home');


// @route               GET /dashboard/contacts/all
// @description         The dashboard's home page
// @access              Private
Route::get('/dashboard/contacts/all', function() {

	echo Session::get('dashboard-currentOrg');

	// Get the contacts that the organisation has
	$contacts = APIRequest::performRequest('GET', '/organisation/contacts/all/'.Session::get('dashboard-currentOrg'));

	// echo '<pre>';
	// var_dump($contacts);
	// return;

    // Return view for this page
    return view('website/dashboard/contacts/all', ['navigationCurrent' => 'contacts.all', 'pageTitle' => 'All Contacts', 'pageFilterToggle' => 'contact-filters', 'pageFilterToggleLabels' => array('on' => 'Remove Filters', 'off' => 'Apply Filters'), 'contacts' => $contacts]);
})->middleware('auth', 'singleSession')->name('dashboard-contacts-all');


// @route               GET /dashboard/chat-inbox
// @description         The dashboard's chat inbox page
// @access              Private
Route::get('/dashboard/chat-inbox', function() {
    // Return view for this page
    return view('website/dashboard/chatInbox/inbox', ['navigationCurrent' => 'chatInbox', 'pageTitle' => 'Chat Inbox']);
})->middleware('auth', 'singleSession')->name('dashboard-chatInbox');
