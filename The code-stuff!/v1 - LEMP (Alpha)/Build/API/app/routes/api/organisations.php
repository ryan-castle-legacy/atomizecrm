<?php

// * File: 				/routes/api/organisations.php
// * Description:		This file holds the routes that interact with the Organisations model.
// * Created:			24th December 2020 at 13:54
// * Updated:			24th December 2020 at 14:08
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\Database;
use App\Http\Controllers\TimeElapsed;
use App\Models\User;
use App\Models\Organisations;
use App\Models\OrganisationContacts;
use App\Models\OrganisationWebsites;


// ----------

// @route               POST /organisation/create
// @description         Creation of a new organisation
// @access              Public
Route::post('/organisation/create', function() {
    // Attempt running code
    try {
        // Collect data from the request
        $data = Request::all();
		// Create a new Organisation from the model
        $organisation = Organisations::create([
            'organisation_unique_id'    	=> $data['organisation_unique_id'],
            'name'      					=> $data['name'],
            'owner_user_id'         		=> $data['owner_id'],
        ]);
        // Get the organisation's details
        $organisation = DB::table('organisations')->where('organisation_unique_id', '=', $data['organisation_unique_id'])->first();
		// Create unique ID for the contact
		$contactUniqueId = sha1('contactID-'.$data['owner_id'].sha1($organisation->id.sha1(time().rand())));
		// Create a new OrganisationContacts from the model (adds the owner as a contact/agent of the organisation)
		$organisationContact = OrganisationContacts::create([
            'contact_unique_id'    			=> $contactUniqueId,
            'organisation_id'      			=> $organisation->id,
			'organisation_agent_user_id'	=> $data['owner_id'],
            'is_organisation_agent'     	=> true,
        ]);
		// Get the organisation contact's details
        $organisationContact = DB::table('organisation_contacts')->where('contact_unique_id', '=', $contactUniqueId)->first();
        // Return the User Organisation's data
        return json_encode(['organisation' => $organisation, 'organisationContact' => $organisationContact]);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getLine().' - '.$error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               POST /organisation/domains/create
// @description         Creation of a new authorised domain for the organisation to host widgets on
// @access              Public
Route::post('/organisation/domains/create', function() {
    // Attempt running code
    try {
        // Collect data from the request
        $data = Request::all();
		// Get the organisation's details
        $organisation = DB::table('organisations')->where('organisation_unique_id', '=', $data['organisation_unique_id'])->first();
		// Check if the author is an admin for the organisation
		if ($organisation->owner_user_id == $data['author_id']) {
			// Check if there is not already a record for this domain
			$duplicate = DB::table('organisation_websites')->where([
				['domain_name', '=', $data['domain']],
				['organisation_id', '=', $organisation->id],
			])->first();
			// Add a record if there isn't one already
			if (!$duplicate) {
				// Create a unique token for this domain
				$domainUniqueId = sha1('domainID-newWebsiteDomain'.sha1($organisation->id.sha1(time().rand())));
				// Create a new OrganisationWebsites from the model
				$organisationWebsite = OrganisationWebsites::create([
					'domain_unique_id'      		=> $domainUniqueId,
		            'organisation_id'      			=> $organisation->id,
					'domain_name'					=> $data['domain'],
		            'owner_user_id'     			=> $organisation->owner_user_id,
		        ]);
			}
			// Get the organisation website's details
	        $organisationWebsite = DB::table('organisation_websites')->where([
				['domain_name', '=', $data['domain']],
				['organisation_id', '=', $organisation->id],
			])->first();
	        // Return the  data
	        return json_encode(['organisation' => $organisation, 'organisationWebsite' => $organisationWebsite]);
		}
		// Return error that the user is not an admin of the organisation
		return json_encode(['error' => 'not-administrator-for-this-organisation']);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getLine().' - '.$error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               GET /organisation/connected-websites/$organisation_unique_id
// @description         Returns the list of connected websites for this organisation
// @access              Public
Route::get('/organisation/connected-websites/{organisation_unique_id}', function(String $organisation_unique_id) {
    // Attempt running code
    try {
		// Get the organisation's details
        $organisation = DB::table('organisations')->where('organisation_unique_id', '=', $organisation_unique_id)->first();
        // Get websites for this organisation
        $websites = DB::table('organisation_websites')->where('organisation_id', '=', $organisation->id)->orderBy('domain_name', 'ASC')->get();
		// Check if results were found
		if ($websites) {
			// Loop through the results
			for ($i = 0; $i < sizeof($websites); $i++) {
				// Create 'time_since_created' item and add it to the result's object
				$websites[$i]->time_since_created = TimeElapsed::howLongSince($websites[$i]->created_at, false);
			}
		}
        // Send the result
        return json_encode($websites);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getMessage());
    }
})->middleware('apiAuthorisedOnly');
