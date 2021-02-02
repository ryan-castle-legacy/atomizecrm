<?php

// * File: 				/routes/api/messenger-instance.php
// * Description:		This file holds the routes that interact with the MessengerInstances model.
// * Created:			24th December 2020 at 21:40
// * Updated:			08th January 2021 at 22:13
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\Database;
use App\Http\Controllers\ColourConverter;
use App\Models\User;
use App\Models\Organisations;
use App\Models\OrganisationContacts;
use App\Models\MessengerInstances;
use App\Models\ContactMessages;
use Illuminate\Support\Facades\Cache;


// ----------


// @route               GET /messenger-instance/:websiteToken/:instanceToken
// @description         Returns the data about approved websites (which organisations it belongs to, etc)
// @access              Public
Route::get('/messenger-instance/{websiteToken}/{instanceToken?}', function($websiteToken, $instanceToken = null) {
    // Attempt running code
    try {

		// Store defaults of the request and responses
		$data = array(
			'request' 			=> [
				'websiteToken'		=> $websiteToken,
				'instanceToken'		=> $instanceToken,
			],
			'response' 			=> [
				'status'			=> true,
				'data'				=> [],
				'props'				=> [
					'chatFeature'			=> true,
					'chatStartWithBot' 		=> true,
					'chatStartWithAgent' 	=> false,
					'chatStartWithComposer' => false,
					'newVisitor'			=> false,
				],
				'triggers'			=> [],
				'styles'			=> [],
				'widget'			=> null,
				'error'				=> null,
				'errorMessage'		=> null,
			],
		);

		Cache::flush();

		// Check if the messenger's instance has been cached already
		if (Cache::has('messenger-instance-'.$instanceToken)) {
			// Returns cached information to the '$data' array
			$data['response']['data'] = Cache::get('messenger-instance-'.$instanceToken);
		} else {
			// Search the database to see if this website has been approved for the organisation to serve the Messenger widget
	        $approvedWebsite = DB::table('organisation_websites')->where('domain_unique_id', '=', $websiteToken)->first();
			// Check if the website was approved
			if ($approvedWebsite) {
				// Get the organisation that owns this approved website (remembered forever - unless deleted by cancelling org's billing)
				$data['response']['data']['organisation'] = Cache::rememberForever('organisation-that-serves-'.$websiteToken, function() use ($approvedWebsite) {
					// Get organisation's data from the $approvedWebsite object's data
					$organisation = DB::table('organisations')->where('id', '=', $approvedWebsite->organisation_id)->first();
					// Check that the $organisation was found
					if ($organisation) {
						// Store data about the organisation in the Cache
						return $organisation;
					}
					// Store null as no valid record was found
					return null;
				});



				// Check if the Messenger has not already been instanciated and needs an instanceToken created
				if ($data['request']['instanceToken'] == null || $data['request']['instanceToken'] == 'undefined') {
					// Create a unique token for the instance
					$data['request']['instanceToken'] = 'atomizecrm-'.sha1('MessengerInstance'.sha1(time().rand()));
					// Set property of the instance as the first visit from the user
					$data['response']['props']['newVisitor'] = true;
					// Create unique ID for the contact
					$contactUniqueId = sha1('contactID-newAnonContact'.sha1($data['response']['data']['organisation']->id.sha1(time().rand())));
					// Create a new OrganisationContacts from the model (adds a new contact for the organisation)
					$organisationContact = OrganisationContacts::create([
			            'contact_unique_id'    			=> $contactUniqueId,
			            'organisation_id'      			=> $data['response']['data']['organisation']->id,
						'messenger_instances_token'		=> $data['request']['instanceToken'],
						'created_by_messenger_instance'	=> true,
						'is_anonymouse'					=> true,
						'created_from_domain_unique_id'	=> $websiteToken,
			        ]);
					// Get the organisationContact data
					$data['response']['data']['organisationContact'] = Cache::rememberForever('contact-with-unique-id-'.$contactUniqueId, function() use ($contactUniqueId) {
						// Get the organisation contact's details
						$organisationContact = DB::table('organisation_contacts')->where('contact_unique_id', '=', $contactUniqueId)->first();
						// Check that the $organisationContact was found
						if ($organisationContact) {
							// Store data about the organisation's contact in the Cache
							return $organisationContact;
						}
						// Store null as no valid record was found
						return null;
					});

					// Create a new MessengerInstance from the model
			        $messengerInstance = MessengerInstances::create([
			            'instance_token'    			=> $data['request']['instanceToken'],
						'contact_id'      				=> $data['response']['data']['organisationContact']->id,
			            'organisation_id'      			=> $data['response']['data']['organisation']->id,
			            'assigned_agent_id'         	=> 0,
			        ]);
					// Get the messengerInstance
					$data['response']['data']['messengerInstance'] = Cache::rememberForever('messenger-instance-'.$data['request']['instanceToken'], function() use ($data) {
						// Get the messenger instance's details
				        $messengerInstance = DB::table('messenger_instances')->where('instance_token', '=', $data['request']['instanceToken'])->first();
						// Check that the $messengerInstance was found
						if ($messengerInstance) {
							// Store data about the messenger's instances in the Cache
							return $messengerInstance;
						}
						// Store null as no valid record was found
						return null;
					});

				} else {

					// Get contact data from this instance
					$contactData = DB::table('organisation_contacts')->where('messenger_instances_token', '=', $data['request']['instanceToken'])->first();



					// Extract the unique ID for the contact
					$contactUniqueId = $contactData->contact_unique_id;
					// Get the organisationContact data
					Cache::forever('contact-with-unique-id-'.$contactUniqueId, $contactData);
					// Save the data
					$data['response']['data']['organisationContact'] = Cache::get('contact-with-unique-id-'.$contactUniqueId);
					// Get the messengerInstance's details
					$messengerInstance = DB::table('messenger_instances')->where('instance_token', '=', $data['request']['instanceToken'])->first();
					// Get the messengerInstance data
					Cache::forever('messenger-instance-'.$data['request']['instanceToken'], $messengerInstance);
					// Save the data
					$data['response']['data']['messengerInstance'] = Cache::get('messenger-instance-'.$data['request']['instanceToken']);
				}



				// // Get the messengerInstance
				// $data['response']['data']['messengerInstance'] = Cache::rememberForever('messenger-instance-'.$data['request']['instanceToken'], function() use ($data) {
				// 	// Get the messenger instance's details
			    //     $messengerInstance = DB::table('messenger_instances')->where('instance_token', '=', $data['request']['instanceToken'])->first();
				// 	// Check that the $messengerInstance was found
				// 	if ($messengerInstance) {
				// 		// Store data about the messenger's instances in the Cache
				// 		return $messengerInstance;
				// 	}
				// 	// Store null as no valid record was found
				// 	return null;
				// });






				// Save the database data to the cache to save cost make time-to-load faster
				Cache::forever('messenger-instance-'.$data['request']['instanceToken'], $data['response']['data']);
			} else {
				// Set the response as an error
				$data['response']['status'] 		= false;
				$data['response']['error'] 			= 'unapproved_domain';
				$data['response']['errorMessage']	= 'You are trying to serve an AtomizeCRM widget on an unauthorised website. If this is your website, please add it to your organisation\'s connected website list - https://atomizecrm.com/dashboard/organisations/connected-websites';
			}
		}

		$data['response']['props']['displayWhiteLabelCredit'] 	= true;

		// Add the non-cache-stored items to the response
		$data['response']['props']['widgetLoadDelay'] 			= 250; 			// Just a fake loading delay
		$data['response']['props']['launcherText'] 				= 'Need Help?  🖐'; 			// The text that would be displayed prompting the visitor to click the launcher
		$data['response']['props']['portalLogo'] 				= 'http://xxx/img/icon-dark.svg';

		$data['response']['props']['portalHeroBannerHeader'] 	= 'Hi 👋';
		$data['response']['props']['portalHeroBannerDesc'] 		= 'We help your business grow by connecting you to your customers.';


		$data['response']['props']['portalCardGetInTouch_header'] 	= 'Want to get in touch?';
		$data['response']['props']['portalCardGetInTouch_text'] 	= 'We\'re here to help, chat to us to get answers or discuss anything with our team.';
		$data['response']['props']['portalCardGetInTouch_button'] 	= 'Send us a message';




		$data['response']['styles']['launcherType'] 			= 'text-button-right-center'; 	// Launcher type set by the organisation (text-button-right-center, circle-button-right-bottom)
		$data['response']['styles']['launcherButtonBGColour'] 	= '#735fd7'; 	// Theme colours set by the organisation
		$data['response']['styles']['portalBGColour'] 			= '#ffffff'; 	// Theme colours set by the organisation
		$data['response']['styles']['portalHeroBGColour'] 		= '#735fd7';
		$data['response']['styles']['portalHeroTextColour'] 	= '#ffffff';
		$data['response']['styles']['messageOptionText'] 		= ColourConverter::hexToRGB($data['response']['styles']['portalHeroBGColour']);
		$data['response']['styles']['messageOptionBG'] 			= ColourConverter::hexToRGB($data['response']['styles']['portalHeroBGColour']);





		// THESE VALUES HAVE BEEN ADDED FOR BUILDING
		// THESE VALUES HAVE BEEN ADDED FOR BUILDING
		// THESE VALUES HAVE BEEN ADDED FOR BUILDING



		// Set the bot's options of sending to agent etc
		$data['response']['triggers']['new-chat']['bot-start'] = array(
			'open-messenger-portal'		=> false,
			'send-prompt-from-agent'	=> array(
				'agent'						=> array(
					'is_bot'					=> true,
				),
				'thread-logic'				=> array(
					'step-0'					=> array(
						'type'						=> 'message',
						'message-from'				=> false,
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> '👋 Hi there! What brings you here today?',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> array(
							'type'						=> 'choice',
							'choices'					=> array(
								array(
									'next-step' 			=> 'step-1',
									'message' 				=> 'Just browsing',
								),
								array(
									'next-step' 			=> 'step-2',
									'message' 				=> 'I\'d like to learn more about AtomizeCRM',
								),
							),
						),
						'reveal-composer'			=> false,
					),
					'step-1'					=> array(
						'type'						=> 'message',
						'message-from'				=> 'bot', // Either: false, bot, user, or agent (with data about who it is)
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'No problem.<br/><br/>Just chat in below if you need us. We\'re always here to help 👋',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> false,
						'if-already-gathered'		=> false,
						'reveal-composer'			=> true,
					),
					'step-2'					=> array(
						'type'						=> 'gather-data',
						'message-from'				=> 'bot',
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'Great! tell us more about you:',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> array(
							'type'						=> 'gather-data',
							'field'						=> array(
								'label'						=> 'Company name',
								'type'						=> 'text',
								'name'						=> 'company-name',
								'placeholder'				=> 'Acme Ltd',
							),
							'next-step'					=> 'step-3',
						),
						'if-already-gathered'		=> array(
							'step'						=> 'step-3',
							'message'					=> 'Great!',
						),
						'reveal-composer'			=> false,
					),
					'step-3'					=> array(
						'type'						=> 'gather-data',
						'message-from'				=> 'bot',
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'What is your email address?',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> array(
							'type'						=> 'gather-data',
							'field'						=> array(
								'label'						=> 'Email',
								'type'						=> 'email',
								'name'						=> 'email-address',
								'placeholder'				=> 'email@example.com',
							),
							'next-step'						=> 'step-4',
						),
						'if-already-gathered'		=> array(
							'step'						=> 'step-4',
							'message'					=> false,
						),
						'reveal-composer'			=> false,
					),
					'step-4'					=> array(
						'type'						=> 'message',
						'message-from'				=> 'bot', // Either: false, bot, user, or agent (with data about who it is)
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'To help you get started, <a href="https://www.google.com" target="_blank">here\'s a quick article</a> on how AtomizeCRM works.',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> array(
							'type'						=> 'progress',
							'next-step'					=> 'step-5',
						),
						'if-already-gathered'		=> array(
							'step'						=> 'step-5',
							'message'					=> 'To help you get started, <a href="https://www.google.com" target="_blank">here\'s a quick article</a> on how AtomizeCRM works.',
						),
						'reveal-composer'			=> false,
					),
					'step-5'					=> array(
						'type'						=> 'message',
						'message-from'				=> 'bot',
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'What would you like to do next?',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> array(
							'type'						=> 'choice',
							'choices'					=> array(
								array(
									'next-step' 			=> 'step-6',
									'message' 				=> 'Chat with the team',
								),
								array(
									'next-step' 			=> 'step-7',
									'message' 				=> 'I\'m good for now, thanks',
								),
							),
						),
						'if-already-gathered'		=> false,
						'reveal-composer'			=> false,
					),
					'step-6'					=> array(
						'type'						=> 'message',
						'message-from'				=> 'bot', // Either: false, bot, user, or agent (with data about who it is)
						'route-to-team'				=> true,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'Great 😊 Our team will reach out as soon as possible, via this message or by email.',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> false,
						'if-already-gathered'		=> false,
						'reveal-composer'			=> true,
					),
					'step-7'					=> array(
						'type'						=> 'message',
						'message-from'				=> 'bot',
						'route-to-team'				=> null,
						'route-to-page'				=> null,
						'route-to-action'			=> null,
						'message'					=> 'Ok! Have a nice day 👋',
						'deliver-outside-portal'	=> false,
						'actions-for-next'			=> false,
						'if-already-gathered'		=> false,
						'reveal-composer'			=> true,
					),
				),
			),
		);




		// // Whether the visitor is new and is first-page of visit
		// $data['response']['triggers']['new-visitor']['first-page'] = array(
		// 	'open-messenger-portal'		=> false,
		// 	'send-prompt-from-agent'	=> array(
		// 		'agent'						=> array(
		// 			'is_bot'					=> true,
		// 		),
		// 		'thread-logic'				=> array(
		// 			'step-0'					=> array(
		// 				'type'						=> 'message',
		// 				'route-to-team'				=> null,
		// 				'route-to-page'				=> null,
		// 				'route-to-action'			=> null,
		// 				'message'					=> '👋 Hi there! What brings you here today?',
		// 				'deliver-outside-portal'	=> true,
		// 				'actions-for-next'			=> array(
		// 					'type'						=> 'choice',
		// 					'choices'					=> array(
		// 						array(
		// 							'next-step' 			=> 'step-1',
		// 							'message' 				=> 'Looking to buy',
		// 						),
		// 						array(
		// 							'next-step' 			=> 'step-2',
		// 							'message' 				=> 'I\'m a customer with a question',
		// 						),
		// 					),
		// 				),
		// 			),
		// 			'step-1'					=> array(
		// 				'type'						=> 'message',
		// 				'route-to-team'				=> null,
		// 				'route-to-page'				=> null,
		// 				'route-to-action'			=> null,
		// 				'message'					=> 'That\'s great! How many people are on your team?',
		// 				'deliver-outside-portal'	=> false,
		// 				'actions-for-next'			=> array(
		// 					'type'						=> 'choice',
		// 					'choices'					=> array(
		// 						array(
		// 							'next-step' 			=> 'step-3',
		// 							'message' 				=> '1-50',
		// 						),
		// 						array(
		// 							'next-step' 			=> 'step-4',
		// 							'message' 				=> '50+',
		// 						),
		// 					),
		// 				),
		// 			),
		// 			'step-2'					=> array(
		// 				'type'						=> 'route',
		// 				'route-to-team'				=> 'support-team',
		// 				'route-to-page'				=> null,
		// 				'route-to-action'			=> null,
		// 				'message'					=> null,
		// 				'deliver-outside-portal'	=> false,
		// 				'actions-for-next'			=> null,
		// 			),
		// 			'step-3'					=> array(
		// 				'type'						=> 'route',
		// 				'route-to-team'				=> null,
		// 				'route-to-page'				=> 'start-a-trial',
		// 				'route-to-action'			=> null,
		// 				'message'					=> null,
		// 				'deliver-outside-portal'	=> false,
		// 				'actions-for-next'			=> null,
		// 			),
		// 			'step-4'					=> array(
		// 				'type'						=> 'route',
		// 				'route-to-team'				=> null,
		// 				'route-to-page'				=> null,
		// 				'route-to-action'			=> 'book-a-meeting',
		// 				'message'					=> 'Sound like we should connect you to our enterprise accounts team. When would be a good time to chat?',
		// 				'deliver-outside-portal'	=> false,
		// 				'actions-for-next'			=> null,
		// 			),
		// 		),
		// 	),
		// );


		// THESE VALUES HAVE BEEN ADDED FOR BUILDING
		// THESE VALUES HAVE BEEN ADDED FOR BUILDING
		// THESE VALUES HAVE BEEN ADDED FOR BUILDING




		// Send data as JSON object
		return json_encode($data['response']);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getMessage());
    }
})->middleware('apiAuthorisedOnly');




// @route               POST /messenger-instance/add-contact-information
// @description         Recieves data to update a contact record via the messenger widget
// @access              Public
Route::post('/messenger-instance/add-contact-information', function() {
    // Attempt running code
    try {
		// Collect data from the request
		$data = Request::all();
		// Get the instance data from the cache
		$instanceData = Cache::get('messenger-instance-'.$data['instanceToken']);
		// Create copy of the data in the cache to update
		$instanceDataChangeable = $instanceData;
		// Variable that checks whether cache has changed
		$cacheNeedsChanged = false;


		// Check whether company name was received
		if (@$data['dataGathered']['company-name']) {
			// Get the current value in the contact data
			$currentValue = @$instanceData['organisationContact']->company_name;
			// See if the gathered value is different to the stored data
			if ($currentValue == null || $data['dataGathered']['company-name'] != $currentValue) {
				// Update the database's record
				DB::table('organisation_contacts')->where('messenger_instances_token', '=', $instanceData['organisationContact']->messenger_instances_token)->update([
					'company_name' => $data['dataGathered']['company-name'],
				]);
				// Change the data
				$instanceDataChangeable['organisationContact']->company_name = $data['dataGathered']['company-name'];
				// Mark as change needed in the cache
				$cacheNeedsChanged = true;
			}
		}


		// Check whether email address was received
		if (@$data['dataGathered']['email-address']) {
			// Get the current value in the contact data
			$currentValue = @$instanceData['organisationContact']->email;
			// See if the gathered value is different to the stored data
			if ($currentValue == null || $data['dataGathered']['email-address'] != $currentValue) {
				// Update the database's record
				DB::table('organisation_contacts')->where('messenger_instances_token', '=', $instanceData['organisationContact']->messenger_instances_token)->update([
					'email' => $data['dataGathered']['email-address'],
				]);
				// Change the data
				$instanceDataChangeable['organisationContact']->email = $data['dataGathered']['email-address'];
				// Mark as change needed in the cache
				$cacheNeedsChanged = true;
			}
		}


		// Check if the cache needs changes
		if ($cacheNeedsChanged == true) {
			// Clear the old data from the cache
			Cache::forget('messenger-instance-'.$data['instanceToken']);
			// Store the newly updated data to the cache
			Cache::forever('messenger-instance-'.$data['instanceToken'], $instanceDataChangeable);
			// Get the contact unique ID
			$contactUniqueId = $instanceData['organisationContact']->contact_unique_id;
			// Remove the current cached contact data
			Cache::forget('contact-with-unique-id-'.$contactUniqueId);
			// Store the newly updated data to the cache
			$contactData = Cache::rememberForever('contact-with-unique-id-'.$contactUniqueId, function() use ($contactUniqueId) {
				// Get the organisation contact's details
				$organisationContact = DB::table('organisation_contacts')->where('contact_unique_id', '=', $contactUniqueId)->first();
				// Check that the $organisationContact was found
				if ($organisationContact) {
					// Store data about the organisation's contact in the Cache
					return $organisationContact;
				}
				// Store null as no valid record was found
				return null;
			});
		}

		// Return as no errors
		return json_encode(['status' => 'updated']);
	} catch (Exception $error) {
		// Set the headers for the return data
		header('Content-Type: application/json');
		// Return the error
		return array('message' => $error->getMessage(), 'line' => $error->getLine());
	}
})->middleware('apiAuthorisedOnly');




// @route               POST /messenger-instance/add-message-from-contact
// @description         Recieves data to add a message from a contact via the messenger widget
// @access              Public
Route::post('/messenger-instance/add-message-from-contact', function() {
    // Attempt running code
    try {
		// Collect data from the request
		$data = Request::all();
		// Get the instance data from the cache
		$instanceData = Cache::get('messenger-instance-'.$data['sessionData']['instanceToken']);
		// Create copy of the data in the cache to update
		$instanceDataChangeable = $instanceData;
		// Create unique ID for the message
		$messageUniqueId = sha1('messageID-'.$data['sessionData']['instanceToken'].sha1($instanceData['organisation']->organisation_unique_id.sha1(time().rand())));
		// Create a new ContactMessages from the model
		$contactMessage = ContactMessages::create([
			'message_unique_id'    			=> $messageUniqueId,
			'messenger_instances_token'    	=> $data['sessionData']['instanceToken'],
			'organisation_id'    			=> $instanceData['organisation']->id,
			'sender_contact_id'    			=> $instanceData['organisationContact']->id,
			'message_text'    				=> $data['message'],
		]);




		// Clear the old data from the cache
		Cache::forget('messenger-instance-'.$data['sessionData']['instanceToken']);
		// See if the message array needs to be created
		if (!isset($instanceDataChangeable['messages'])) {
			// Create new array for message storage
			$instanceDataChangeable['messages'] = array();
			// Get all messages sent from the contact
			$contactMessages = DB::table('contact_messages')->where('sender_contact_id', '=', $instanceData['organisationContact']->id)->get();
			// Loop through messages sent by the contact
			for ($i = 0; $i < sizeof($contactMessages); $i++) {
				// Add them to the array
				$instanceDataChangeable['messages'][$contactMessages[$i]->message_unique_id] = $contactMessages[$i];
			}
			// Get all messages received by the contact
			$contactMessages = DB::table('contact_messages')->where('receiver_contact_id', '=', $instanceData['organisationContact']->id)->get();
			// Loop through messages received by the contact
			for ($i = 0; $i < sizeof($contactMessages); $i++) {
				// Add them to the array
				$instanceDataChangeable['messages'][$contactMessages[$i]->message_unique_id] = $contactMessages[$i];
			}
		} else {
			// Get the contact's message details
			$contactMessage = DB::table('contact_messages')->where('message_unique_id', '=', $messageUniqueId)->first();
			// Add message to the data
			$instanceDataChangeable['messages'][$contactMessage->message_unique_id] = $contactMessage;
		}
		// Store the newly updated data to the cache
		Cache::forever('messenger-instance-'.$data['sessionData']['instanceToken'], $instanceDataChangeable);
		// Return as no errors
		return json_encode('saved');
	} catch (Exception $error) {
		// Set the headers for the return data
		header('Content-Type: application/json');
		// Return the error
		return array('message' => $error->getMessage(), 'line' => $error->getLine());
	}
})->middleware('apiAuthorisedOnly');
