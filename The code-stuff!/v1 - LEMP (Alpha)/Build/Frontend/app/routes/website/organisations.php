<?php

// * File: 				/routes/website/organisations.php
// * Description:		This file holds the routes for the pages regarding organisations (creating new ones, users joining, etc.) on the AtomizeCRM Frontend.
// * Created:			24th December 2020 at 14:10
// * Updated:			24th December 2020 at 14:16
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\APIRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


// ----------

// @route               GET /dashboard/organisations
// @description         This page will allow the user to set-up or join an organisation
// @access              Private
Route::get('/dashboard/organisations', function() {
	// Return view for this page
    return view('website/organisations/joinOrganisation');
})->middleware('auth')->name('organisations-userSetupOrJoin');


// @route               POST /dashboard/organisations/new
// @description         This page handles submissions from the new organisation form.
// @access              Private
Route::post('/dashboard/organisations/new', function() {
	// Capture data from the request
    $data = Request::all();
	// Validate data that has been sent through
    $validation = Validator::make($data, array(
        'name'         		=> ['string', 'max:128', 'required'],
    ));
    // Check if the validation has failed
    if ($validation->fails()) {
        // Return redirect with errors
        return Redirect::back()->withErrors($validation)->withInput();
    }
    // Create unique ID for this organisation password
    $uniqueID = sha1('orgID-'.sha1($data['name'].sha1(time().rand())));
    // Send data to API to create new organisation
    $organisation = APIRequest::performRequest('POST', '/organisation/create', array(
		'owner_id'					=> Auth::user()->id,
        'name'         				=> $data['name'],
        'organisation_unique_id'    => $uniqueID,
    ));
	// Check that the organisation has been created
	if (isset($organisation['organisation']) && isset($organisation['organisationContact'])) {
		// Send user to the organisation's dashboard
		return Redirect::route('dashboard-home')->with(['currentOrg' => $organisation['organisation']->organisation_unique_id]);
	}
	// Return redirect with errors
	return Redirect::back()->with(['message' => 'Something went wrong when creating a new oranisation - please try again.'])->withInput();
})->middleware('auth')->name('organisations-userCreate');


// @route               GET /dashboard/organisations/connected-websites
// @description         This page will allow the organisation to connect websites (for collecting data, displaying widgets, etc).
// @access              Private
Route::get('/dashboard/organisations/connected-websites', function() {

	// Change this to CACHE based!!!!!!

	// Get the connected websites that the organisation has added
	$connectedWebsites = APIRequest::performRequest('GET', '/organisation/connected-websites/'.Session::get('dashboard-currentOrg'));
	// Return view for this page
	return view('website/organisations/connectedWebsites', ['pageTitle' => 'Connected Websites', 'connectedWebsites' => $connectedWebsites]);
})->middleware('auth')->name('organisations-connectedWebsites');


// @route               POST /dashboard/organisations/connected-websites
// @description         This handles submission of domains that will allow the organisation for collecting data, displaying widgets, etc.
// @access              Private
Route::post('/dashboard/organisations/connected-websites', function() {
	try {
		// Check that the request was from AJAX
		if (Request::ajax()) {
			// Get request data
			$data = Request::all();
			// Check if the domain was received in the request
			if ($data['domain']) {
				// Send the new domain to the API so that it is stored in the database
			    $data = APIRequest::performRequest('POST', '/organisation/domains/create', array(
					'domain'					=> $data['domain'],
					'organisation_unique_id'    => $data['org'],
			        'author_id'    				=> Auth::user()->id,
			    ));
			} else {
				// Return error as data that was received was wrong
				return json_encode(['error' => 'No domain was received.']);
			}


			return json_encode($data);


		} else {
			// Send to home page
			return Redirect::to(env('APP_URL'));
		}
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS', 'auth');


//
// @route               POST /dashboard/organisations/connected-websites
// @description         This handles the CORS preflight request for the AJAX submission form
// @access              Public
Route::options('/dashboard/organisations/connected-websites', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');
