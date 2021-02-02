<?php

// * File: 				/routes/messenger/widget.php
// * Description:		This file holds the routes for the Messenger Widget.
// * Created:			06th January 2021 at 22:28
// * Updated:			21st December 2020 at 22:54
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\APIRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// ----------

// @route               GET /messenger-widget-building
// @description         The Messenger Widget's building sandbox
// @access              Public
Route::get('/messenger-widget-building', function() {
    // Return view for this page
    return view('messenger/sandbox');
});


// @route               GET /widgets/messenger.min.js
// @description         The JavaScript that builds the Messenger Widget
// @access              Public
Route::get('/widgets/messenger.min.js', function() {

	// Set file as a JavaScript file
	header('Content-Type: application/javascript');


	// Toggle logs
	$show_logs = true;
	// DEV mode APP_URL
	$devAppURL = 'http://xxx';


	// Get widget JS code
	$code = file_get_contents('../public/widgets/messenger.js');


	// Check if dev not server
	if (env('APP_URL') != $devAppURL) {
		// Replace dev URL with live URL
		$code = str_replace($devAppURL, 'https://atomizecrm.com', $code);
		// Replace dev to live
		// $code = str_replace('window.AtomizeTestMode = true;', 'window.AtomizeTestMode = false;', $code);
	}


	// // Check if dev not server
	// if (env('APP_ENV') != 'dev') {
	// 	// Pattern for comments
	// 	$pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
	// 	// Replace comments
	// 	$code = preg_replace($pattern, '', $code);
	//
	// 	// Check if dev not server
	// 	if (!$show_logs && env('APP_ENV') != 'dev') {
	// 		// Remove console logs
	// 		$code = str_replace("console.log(response);", 						'', 		$code);
	// 		$code = str_replace("console.log(data);", 							'', 		$code);
	// 		$code = str_replace("console.clear();", 							'', 		$code);
	// 	}
	//
	// 	// Replace non-functional items
	// 	$code = str_replace("\n", 		'', 		$code);
	// 	$code = str_replace('	', 		'', 		$code);
	// 	$code = str_replace(': \'', 	':\'', 		$code);
	// 	$code = str_replace(': {', 		':{', 		$code);
	// 	$code = str_replace(') {', 		'){', 		$code);
	// 	$code = str_replace('= ', 		'=', 		$code);
	// 	$code = str_replace(' =', 		'=', 		$code);
	// 	$code = str_replace('} else', 	'}else', 	$code);
	// 	$code = str_replace('else {', 	'else{', 	$code);
	// 	$code = str_replace('; }', 		';}', 		$code);
	// 	$code = str_replace('if (', 	'if(', 		$code);
	// 	$code = str_replace('{ ', 		'{', 		$code);
	// 	$code = str_replace('} ', 		'}', 		$code);
	// 	$code = str_replace(' !=', 		'!=', 		$code);
	// 	$code = str_replace(', ', 		',', 		$code);
	// 	$code = str_replace('+ ', 		'+', 		$code);
	// 	$code = str_replace(' +', 		'+', 		$code);
	// 	$code = str_replace(' &&', 		'&&', 		$code);
	// 	$code = str_replace('&& ', 		'&&', 		$code);
	// 	$code = str_replace(' (', 		'(', 		$code);
	// 	$code = str_replace(') ', 		')', 		$code);
	// 	$code = str_replace('; ', 		';', 		$code);
	// 	$code = str_replace(' <', 		'<', 		$code);
	// 	$code = str_replace('< ', 		'<', 		$code);
	//
	// }

	// Output code
	echo $code;

});


// @route               POST /widgets/messenger
// @description         This sends the JavaScript file the DOM to render the messenger widget
// @access              Public
Route::post('/widgets/messenger', function() {
	try {
		// Check that the request was from AJAX
		if (Request::ajax()) {
			// Get request data
			$requestData = Request::all();
			// Initialise the Messenger - sending and gathering all data needed supplied in the request
			$data = APIRequest::performRequest('GET', '/messenger-instance/'.$requestData['websiteToken'].'/'.$requestData['instanceToken']);


			// return json_encode($data);


			// Get widget DOM for rendering to the user
			$widget = view('messenger/widget', ['data' => $data])->render();
			// Send widget data as JSON object
			return json_encode(['widget' => $widget, 'data' => $data]);
		} else {
			// Send to home page
			return Redirect::to(env('APP_URL'));
		}
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');


// @route               POST /widgets/messenger
// @description         This handles the CORS preflight request for the Messenger widget
// @access              Public
Route::options('/widgets/messenger', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// @route               POST /widgets/submit-contact-data
// @description         This receives data that updates the contact information about the website visitor
// @access              Public
Route::post('/widgets/submit-contact-data', function() {
	try {
		// Check that the request was from AJAX
		if (Request::ajax()) {
			// Get request data
			$requestData = Request::all();
			// Send data to the API to update the contact information about the website visitor
			$data = APIRequest::performRequest('POST', '/messenger-instance/add-contact-information', json_encode($requestData), false, true);
			// Send response back to the JavaScript
			return json_encode(['status' => $data]);
		} else {
			// Send to home page
			return Redirect::to(env('APP_URL'));
		}
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');


// @route               POST /widgets/submit-contact-data
// @description         This handles the CORS preflight request for the Messenger widget
// @access              Public
Route::options('/widgets/submit-contact-data', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');





// @route               POST /widgets/send-message-from-contact
// @description         This receives data that the contact has sent a message to the database
// @access              Public
Route::post('/widgets/send-message-from-contact', function() {
	try {
		// Check that the request was from AJAX
		if (Request::ajax()) {
			// Get request data
			$requestData = Request::all();
			// Send data for the message to the API
			$data = APIRequest::performRequest('POST', '/messenger-instance/add-message-from-contact', json_encode($requestData), false, true);
			// Send response back to the JavaScript
			return json_encode(['status' => $data, 'DOMTracker' => $requestData['DOMTracker']]);
		} else {
			// Send to home page
			return Redirect::to(env('APP_URL'));
		}
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');


// @route               POST /widgets/send-message-from-contact
// @description         This handles the CORS preflight request for the Messenger widget
// @access              Public
Route::options('/widgets/send-message-from-contact', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');

















// // Handle CORS preflight request
// Route::options('/widget/chat/', function(Request $request) {
// 	// Send response back
// 	return json_encode(true);
// })->middleware('widgetCORS');
//
//
// // Send chat widget DOM
// Route::post('/widget/chat/', function(Request $request) {
// 	try {
// 		// Check if AJAX
// 		if (Request::ajax()) {
// 			// Get request data
// 			$data = Request::all();
//
// 			// Get host of widget
// 			$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
// 			// Check if website is allowed
// 			$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
// 			// Check if website is not allowed
// 			if (!$site) {
// 				// Return error
// 				return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
// 			}
// 			// Get organisation
// 			$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
// 			// Get settings for widget
// 			$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();
// 			// Check if new instance or not
// 			if (!isset($data['instanceToken'])) {
// 				// Set that instance is new
// 				$newInstance = true;
// 				// Create instance token
// 				$data['instanceToken'] = 'atomize-'.sha1('messengerInstance-'.sha1('instance'.sha1(time().rand())));
// 				// Create client token
// 				$data['clientId'] = sha1('client-'.sha1('instance'.sha1(time().rand())));
// 				// Create encryption token
// 				$encryptionToken = sha1(uniqid().rand().sha1(time().$data['instanceToken']));
// 				// Add new client into table
// 				$clientId = DB::table('organisations_clients')->insertGetId([
// 					'token'				=> $data['clientId'],
// 					'org_id'			=> $org->id,
// 					'date_created'		=> date('Y-m-d H:i:s', time()),
// 					'date_updated'		=> date('Y-m-d H:i:s', time()),
// 		        ]);
// 				// Save instance token
// 				$instance = DB::table('widget_instances')->insertGetId([
// 					'token'				=> $data['instanceToken'],
// 					'encrypt'			=> $encryptionToken,
// 					'client_id'			=> $clientId,
// 					'org_id'			=> $org->id,
// 					'date_created'		=> date('Y-m-d H:i:s', time()),
// 					'date_updated'		=> date('Y-m-d H:i:s', time()),
// 				]);
// 				// Set client ID
// 				$data['clientId'] = $clientId;
// 			} else {
// 				// Set that instance is not new
// 				$newInstance = false;
// 				// Get instance
// 				$instance = DB::table('widget_instances')->where([
// 					'token'				=> $data['instanceToken'],
// 					'org_id'			=> $org->id,
// 				])->first();
// 				// Set client ID
// 				$data['clientId'] = $instance->client_id;
// 			}
//
//
// 			// Add fake loading delay
// 			$data['widgetLoadDelay']		= 250;//1000;
//
// 			// Get previous chats
// 			$previousChats	= DB::table('chat_instances')->where(['client_id' => $data['clientId']])->orderBy('date_updated', 'DESC')->get();
// 			// Check if not empty
// 			if ($previousChats != null) {
// 				// Go through chats
// 				for ($i = 0; $i < sizeof($previousChats); $i++) {
//
// 					// Test
// 					// Test
// 					// Test
// 					$previousChats[$i]->assigned_agent = null;
// 					// Test
// 					// Test
// 					// Test
//
// 					// Check if an agent is assigned
// 					if ($previousChats[$i]->assigned_agent != null) {
// 						// Get agent details
// 					} else {
// 						// Get most recent message
// 						$mostRecentMessage = DB::table('chat_messages')->where(['instance_token' => $previousChats[$i]->token])->orderBy('date_updated', 'DESC')->first();
//
// 						// Get team details details
// 						$previousChats[$i]->agent_details = array(
// 							'name'					=> $settings->widget_teamName,
// 							'mostRecentMessage'		=> $mostRecentMessage,
// 							'iconSRC'				=> $settings->widget_iconSRC,
// 							'iconAlt'				=> $settings->widget_iconAlt,
// 						);
// 						// Check if agent sent last message
// 						if ($mostRecentMessage->agent_id != null) {
// 							// Set prefix
// 							$previousChats[$i]->agent_details['mostRecentMessage']->message = 'Agent: '.$previousChats[$i]->agent_details['mostRecentMessage']->message;
// 						} else {
// 							// Set prefix
// 							$previousChats[$i]->agent_details['mostRecentMessage']->message = 'You: '.$previousChats[$i]->agent_details['mostRecentMessage']->message;
// 						}
// 					}
// 				}
// 			}
//
//
//
//
// 			// $data['freshChat'] = true;
// 			$data['freshChat'] = false;
// 			$data['aiFirstMessage'] = $settings->startChatWithAI_message;
// 			// $data['outOfOffice'] = true;
// 			$data['outOfOffice'] = false;
// 			$data['outOfOfficeMessage'] = $settings->outOfOffice_message;
// 			$data['outOfOfficeEmailRequest'] = $settings->outOfOffice_requestEmail;
// 			$data['outOfOfficeDetailsRequest'] = $settings->outOfOffice_requestDetails;
// 			$data['iconSRC'] = $settings->widget_iconSRC;
// 			$data['iconAlt'] = $settings->widget_iconAlt;
//
//
// 			// Live refresh rate (milliseconds)
// 			$data['liveRefreshRate'] = 15000;
//
// 			if ($org->token != 'atomize') {
// 				$settings->widget_feedbackWidget = false;
// 			}
//
// 			// Get widget for rendering to the user
// 			$widget = view('widgets/chat', array(
// 				'newInstance'			=> $newInstance,
// 				'instanceToken'			=> $data['instanceToken'],
// 				'content'				=> array(
// 					'orgName'				=> $org->name,
// 					'primaryColor'			=> $settings->widget_primaryColour,
// 					'primaryColorAlt'		=> $settings->widget_primaryAlt,
// 					'logo'					=> $settings->widget_logoSRC,
// 					'iconSRC'				=> $settings->widget_iconSRC,
// 					'iconAlt'				=> $settings->widget_iconAlt,
// 					'teamName'				=> $settings->widget_teamName,
// 					'teamDescription'		=> $settings->widget_teamDescription,
// 					'greeting'				=> $settings->widget_greeting,
// 					'description'			=> $settings->widget_description,
// 					'outOfOffice'			=> $data['outOfOffice'],
// 					'previousChats'			=> $previousChats,
// 				),
// 				'blocks'				=> array(
// 					'feedback'				=> @$settings->widget_feedbackWidget,
// 				),
// 			))->render();
//
// 			// Send widget data as JSON object
// 			return json_encode(['widget' => $widget, 'data' => $data]);
// 		} else {
// 			// Send to website
// 			return Redirect::to(env('APP_URL'));
// 		}
// 	} catch (Exception $e) {
// 		// Return error
// 		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
// 	}
// })->middleware('widgetCORS');
