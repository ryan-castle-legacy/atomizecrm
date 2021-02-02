<?php

// * File: 				/routes/api/organisations-contacts.php
// * Description:		This file holds the routes that interact with the OrganisationContacts model.
// * Created:			28th December 2020 at 20:17
// * Updated:			28th December 2020 at 20:24
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\Database;
use App\Http\Controllers\TimeElapsed;
use App\Models\User;
use App\Models\Organisations;
use App\Models\OrganisationContacts;
use App\Models\MessengerInstances;
use Illuminate\Support\Facades\Cache;


// ----------

// @route               GET /organisation/contacts/all/:organisation_unique_id
// @description         Returns the data about the organisation's contacts
// @access              Public
Route::get('/organisation/contacts/all/{organisation_unique_id}', function(String $organisation_unique_id) {
    // Attempt running code
    try {


		// Change this to CACHE based!!!!!!



		// Get organisation's ID from their unique ID
		$organisation = DB::table('organisations')->where('organisation_unique_id', '=', $organisation_unique_id)->first();
		// Get the organisation contacts
		$organisationContacts = DB::table('organisation_contacts')->where('organisation_id', '=', $organisation->id)->orderBy('created_at', 'DESC')->get();
		// Check if results were found
		if ($organisationContacts) {
			// Loop through the results
			for ($i = 0; $i < sizeof($organisationContacts); $i++) {
				// Check if they're part of the organisation that owns the record
				if ($organisationContacts[$i]->is_organisation_agent) {
					// Get the details from the organisation's records
					$agentDetails = DB::table('users')->where('id', '=', $organisationContacts[$i]->organisation_agent_user_id)->first();
					// Set the contact's details
					$organisationContacts[$i]->firstname 	= $agentDetails->firstname;
					$organisationContacts[$i]->lastname 	= $agentDetails->lastname;
					$organisationContacts[$i]->email 		= $agentDetails->email;
				}
				// Get the domain that the contact was added on
				$domain = DB::table('organisation_websites')->where('domain_unique_id', '=', $organisationContacts[$i]->created_from_domain_unique_id)->first();
				// Check if they were added by the messenger widget
				if ($organisationContacts[$i]->created_by_messenger_instance) {
					// Website that the contact was added from on the Messenger Widget
					$organisationContacts[$i]->source_added = 'On '.$domain->domain_name.'\'s Messenger Widget';
				} else {
					if ($organisationContacts[$i]->is_organisation_agent) {
						// Organisation that the contact was added from
						$organisationContacts[$i]->source_added = 'Your Organisation';
					} else {
						// Blank that the contact was added from
						$organisationContacts[$i]->source_added = '-';
					}
				}
				// Create 'time_since_created' item and add it to the result's object
				$organisationContacts[$i]->time_since_created = TimeElapsed::howLongSince($organisationContacts[$i]->created_at, false);
			}
		}
        // Send the result
        return json_encode($organisationContacts);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getMessage());
    }
})->middleware('apiAuthorisedOnly');



// $contacts = APIRequest::performRequest('GET', '/organisation/contacts/all/'.Session::get('dashboard-currentOrg'));
