<?php


use Illuminate\Support\Facades\Request;


// Handle CORS preflight request
Route::options('/widget/chat/refreshLiveItems/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// Get most recent live updates
Route::post('/widget/chat/refreshLiveItems/', function(Request $request) {
	try {
		// Get request data
		$data = Request::all();

		// Get host of widget
		$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
		// Check if website is allowed
		$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
		// Check if website is not allowed
		if (!$site) {
			// Return error
			return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
		}
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
		// Get settings for widget
		$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();
		// Get widget instance
		$instance = DB::table('widget_instances')->where(['token' => $data['widgetInstance']])->first();
		// Get client
		$client = DB::table('organisations_clients')->where(['id' => $instance->client_id])->first();
		// Chat data holder
		$chatData = array();
		// Check if lastRefreshed is not null
		if (@is_null($data['lastRefreshed'])) {
			// Get all new chat data
			$chatData = DB::table('chat_instances')->where(['client_id' => $client->id])->orderBy('date_updated', 'DESC')->get();
			// Loop through chats (to get new messages)
			for ($i = 0; $i < sizeof($chatData); $i++) {
				// Get messages in chat
				$chatData[$i]->messages = DB::table('chat_messages')->where(['instance_token' => $chatData[$i]->token])->orderBy('date_updated', 'DESC')->get();
				// Go through all messages
				for ($j = 0; $j < sizeof($chatData[$i]->messages); $j++) {
					// Parse time stamp
					$chatData[$i]->messages[$j]->timesent = strtotime($chatData[$i]->messages[$j]->date_updated);
				}
				// Check if chat has agent assigned to it
				if ($chatData[$i]->assigned_agent != null) {
					// Get agent
					$agent = DB::table('organisations_agents')->where(['org_id' => $org->id, 'user_id' => $chatData[$i]->assigned_agent])->first();
					// Get agent details
					$chatData[$i]->agent = array(
						'name'					=> $agent->name,
						'role'					=> $agent->role,
						'iconSRC'				=> $agent->iconSRC,
						'iconAlt'				=> $agent->name[0],
					);
				} else {
					// Get agent details
					$chatData[$i]->agent = array(
						'name'					=> $settings->widget_teamName,
						'role'					=> 'Customer Service Team',
						'iconSRC'				=> $settings->widget_iconSRC,
						'iconAlt'				=> $settings->widget_iconAlt,
					);
				}
			}
		} else {
			// Get all new chat data
			$chatData = DB::table('chat_instances')
				->where(['client_id' => $client->id])
				->where('date_updated', '>=', $data['lastRefreshed'])
				->orderBy('date_updated', 'DESC')->get();
			// Loop through chats (to get new messages)
			for ($i = 0; $i < sizeof($chatData); $i++) {
				// Get messages in chat
				$chatData[$i]->messages = DB::table('chat_messages')
					->where(['instance_token' => $chatData[$i]->token])
					->where('date_updated', '>=', $data['lastRefreshed'])
					->orderBy('date_updated', 'DESC')->get();
				// Go through all messages
				for ($j = 0; $j < sizeof($chatData[$i]->messages); $j++) {
					// Parse time stamp
					$chatData[$i]->messages[$j]->timesent = strtotime($chatData[$i]->messages[$j]->date_updated);
				}
				// Check if chat has agent assigned to it
				if ($chatData[$i]->assigned_agent != null) {
					// Get agent
					$agent = DB::table('organisations_agents')->where(['org_id' => $org->id, 'user_id' => $chatData[$i]->assigned_agent])->first();
					// Get agent details
					$chatData[$i]->agent = array(
						'name'					=> $agent->name,
						'role'					=> $agent->role,
						'iconSRC'				=> $agent->iconSRC,
						'iconAlt'				=> $agent->name[0],
					);
				} else {
					// Get agent details
					$chatData[$i]->agent = array(
						'name'					=> $settings->widget_teamName,
						'role'					=> 'Customer Service Team',
						'iconSRC'				=> $settings->widget_iconSRC,
						'iconAlt'				=> $settings->widget_iconAlt,
					);
				}
			}
		}


		// Get all chats
		$previousChats	= DB::table('chat_instances')->where(['client_id' => $instance->client_id])->orderBy('date_updated', 'DESC')->get();
		// Check if not empty
		if ($previousChats != null) {
			// Go through chats
			for ($i = 0; $i < sizeof($previousChats); $i++) {
				// Get most recent message
				$mostRecentMessage = DB::table('chat_messages')->where(['instance_token' => $previousChats[$i]->token])->orderBy('date_updated', 'DESC')->first();
				// Check if chat has agent assigned to it
				if ($previousChats[$i]->assigned_agent != null) {
					// Get agent
					$agent = DB::table('organisations_agents')->where(['org_id' => $org->id, 'user_id' => $previousChats[$i]->assigned_agent])->first();
					// Get agent details
					$previousChats[$i]->agentDetails = array(
						'name'					=> $agent->name,
						'role'					=> $agent->role,
						'mostRecentMessage'		=> $mostRecentMessage,
						'iconSRC'				=> $agent->iconSRC,
						'iconAlt'				=> $agent->name[0],
					);
				} else {
					// Get agent details
					$previousChats[$i]->agentDetails = array(
						'name'					=> $settings->widget_teamName,
						'role'					=> 'Customer Service Team',
						'mostRecentMessage'		=> $mostRecentMessage,
						'iconSRC'				=> $settings->widget_iconSRC,
						'iconAlt'				=> $settings->widget_iconAlt,
					);
				}
				// Check if client last message
				if ($mostRecentMessage->from == 'client') {
					// Set prefix
					$previousChats[$i]->agentDetails['mostRecentMessage']->message = 'You: '.$previousChats[$i]->agentDetails['mostRecentMessage']->message;
				} else {
					// Set prefix
					$previousChats[$i]->agentDetails['mostRecentMessage']->message = 'Agent: '.$previousChats[$i]->agentDetails['mostRecentMessage']->message;
				}
				// Parse time sent for message
				$previousChats[$i]->agentDetails['mostRecentMessage']->timesent = strtotime($previousChats[$i]->agentDetails['mostRecentMessage']->date_updated);
			}
		}


		// Return data
		return json_encode(['chatData' => $chatData, 'allChats' => $previousChats, 'lastRefreshed' => date('Y-m-d H:i:s', time())]);
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');



























// Handle CORS preflight request
Route::options('/widget/chat/getConvo/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');





// Send email to business
Route::post('/widget/chat/getConvo/', function(Request $request) {
	try {
		// Get request data
		$data = Request::all();

		// Get host of widget
		$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
		// Check if website is allowed
		$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
		// Check if website is not allowed
		if (!$site) {
			// Return error
			return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
		}
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
		// Get settings for widget
		$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();
		// Get conversation
		$convo = DB::table('chat_instances')->where(['token' => Request::get('chatInstance')])->first();
		// Check if convo exists
		if ($convo) {
			// Get messages in chat
			$messages = DB::table('chat_messages')->where(['instance_token' => $convo->token])->orderBy('date_updated', 'DESC')->get();
			// Go through all messages
			for ($i = 0; $i < sizeof($messages); $i++) {
				// Parse time stamp
				$messages[$i]->timesent = strtotime($messages[$i]->date_updated);
			}
			// Check if chat has agent assigned to it
			if ($convo->assigned_agent != null) {
				// Get agent
				$agent = DB::table('organisations_agents')->where(['org_id' => $org->id, 'user_id' => $convo->assigned_agent])->first();
				// Get agent details
				$agentDetails = array(
					'name'					=> $agent->name,
					'role'					=> $agent->role,
					'mostRecentMessage'		=> $messages[0],
					'iconSRC'				=> $agent->iconSRC,
					'iconAlt'				=> $agent->name[0],
				);
			} else {
				// Get agent details
				$agentDetails = array(
					'name'					=> $settings->widget_teamName,
					'role'					=> 'Customer Service Team',
					'mostRecentMessage'		=> $messages[0],
					'iconSRC'				=> $settings->widget_iconSRC,
					'iconAlt'				=> $settings->widget_iconAlt,
				);
			}
			// Return data
			return json_encode(['messages' => $messages, 'agent' => $agentDetails, 'convo' => $convo]);
		}
		return json_encode(false);
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');
























// Handle CORS preflight request
Route::options('/widget/chat/new-client-message/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// Send email to business
Route::post('/widget/chat/new-client-message/', function(Request $request) {
	try {
		// Get request data
		$data = Request::all();

		// Get host of widget
		$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
		// Check if website is allowed
		$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
		// Check if website is not allowed
		if (!$site) {
			// Return error
			return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
		}
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
		// Get settings for widget
		$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();
		// Get widget instance
		$instance = DB::table('widget_instances')->where(['token' => $data['widgetInstance']])->first();
		// Get client
		$client = DB::table('organisations_clients')->where(['id' => $instance->client_id])->first();
		// Get chatInstance by token
		if (!is_null($data['chatInstance'])) {
			// Get instance
			$chat = DB::table('chat_instances')->where(['token'	=> $data['chatInstance']])->first();
		} else {
			// Create chat token
			$chatToken = sha1('chat-'.sha1('chatInstance'.sha1(time().rand())));
			// Create encryption token
			$encryptionToken = sha1('chat-'.sha1('chatInstance'.sha1(time().rand())));
			// Create record
			$chat = DB::table('chat_instances')->insertGetId([
				'token'				=> $chatToken,
				'encrypt'			=> $encryptionToken,
				'client_id'			=> $client->id,
				'org_id'			=> $org->id,
				'date_created'		=> date('Y-m-d H:i:s', time()),
				'date_updated'		=> date('Y-m-d H:i:s', time()),
			]);
			// Get instance
			$chat = DB::table('chat_instances')->where(['id' => $chat])->first();
			// Add first message (Agent AI opening message)
			$message = DB::table('chat_messages')->insertGetId([
				'instance_token'	=> $chat->token,
				'from'				=> 'agent',
				'message'			=> $settings->startChatWithAI_message,
				'date_created'		=> date('Y-m-d H:i:s', time() - 30),
				'date_updated'		=> date('Y-m-d H:i:s', time() - 30),
			]);
		}
		// Add message to DB
		$message = DB::table('chat_messages')->insertGetId([
			'instance_token'	=> $chat->token,
			'from'				=> 'client',
			'message'			=> $data['message'],
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
		// Update chat instance
		DB::table('chat_instances')->where(['token' => $chat->token])->update(['date_updated' => date('Y-m-d H:i:s', time())]);
		// Send response back
		return json_encode(array(
			'chatInstance'	=> $chat->token,
			'message'		=> $message,
			'messageText'	=> 'You: '.$data['message'],
			'name'			=> $settings->widget_teamName,
			'avatar'		=> $settings->widget_iconSRC,
			'avatarIcon'	=> $settings->widget_iconAlt,
			'date'			=> date('H:ia', time()),
		));
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');










// Handle CORS preflight request
Route::options('/widget/chat/new/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// Send email to business
Route::post('/widget/chat/new/', function(Request $request) {
	try {
		// Get request data
		$data = Request::all();


		// Get host of widget
		$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
		// Check if website is allowed
		$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
		// Check if website is not allowed
		if (!$site) {
			// Return error
			return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
		}
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
		// Get settings for widget
		$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();


		// Get widget instance
		$instance = DB::table('widget_instances')->where(['token' => $data['instance']])->first();
		// Get client
		$client = DB::table('organisations_clients')->where(['id' => $instance->client_id])->first();


		// Create chat token
		$chatToken = sha1('chat-'.sha1('chatInstance'.sha1(time().rand())));
		// Create encryption token
		$encryptionToken = sha1('chat-'.sha1('chatInstance'.sha1(time().rand())));


		// Create record
		$save = DB::table('chat_instances')->insertGetId([
			'token'				=> $chatToken,
			'encrypt'			=> $encryptionToken,
			'client_id'			=> $client->id,
			'org_id'			=> $org->id,
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);


		// image, date updated, name, first message


		// Send response back
		return json_encode($save);
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');















// Handle CORS preflight request
Route::options('/widget/chat/sendEmail/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// Send email to business
Route::post('/widget/chat/sendEmail/', function(Request $request) {
	try {
		// Get request data
		$data = Request::all();


		// Get host of widget
		$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
		// Check if website is allowed
		$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
		// Check if website is not allowed
		if (!$site) {
			// Return error
			return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
		}
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
		// Get settings for widget
		$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();


		// Email info
		$email = array(
			'recipient'			=> $settings->supportEmailAddress,
			'message'			=> $data['message'],
			'customer'			=> $data['clientEmail'],
		);
		// Send message
		Mail::send([], ['email' => $email], function ($m) use ($email) {
			$m->from('noreply@atomizecrm.com', 'AtomizeCRM');
			$m->to($email['recipient']);
			$m->replyTo($email['customer']);
			$m->subject('AtomizeCRM - New enquiry notification.');
			$m->setBody('<h1>New AtomizeCRM Enquiry</h1><p>You have received a new enquiry via the AtomizeCRM help widget.</p><br/><p>Email: '.$email['customer'].'</p><p>Message: '.$email['message'].'</p>', 'text/html');
		});
		// Errors
		if (Mail::failures()) {
			// Return response to sending
			return json_encode(false);
		}
		// Send response back
		return json_encode($data);
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');





// Handle CORS preflight request
Route::options('/widget/chat/sendFeedback/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// Send email to business
Route::post('/widget/chat/sendFeedback/', function(Request $request) {
	try {
		// Get request data
		$data = Request::all();


		// Get host of widget
		$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
		// Check if website is allowed
		$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
		// Check if website is not allowed
		if (!$site) {
			// Return error
			return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
		}
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
		// Get settings for widget
		$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();

		// Save record
		$save = DB::table('feedback_submissions')->insertGetId([
			'feedback'			=> $data['feedback'],
			'type'				=> $data['context'],
			'client_id'			=> null,
			'org_id'			=> $org->id,
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);

		// Send response back
		return json_encode($save);
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');










// Handle CORS preflight request
Route::options('/widget/chat/', function(Request $request) {
	// Send response back
	return json_encode(true);
})->middleware('widgetCORS');


// Send chat widget DOM
Route::post('/widget/chat/', function(Request $request) {
	try {
		// Check if AJAX
		if (Request::ajax()) {
			// Get request data
			$data = Request::all();

			// Get host of widget
			$hostWebsite = parse_url(Request::server('HTTP_REFERER'), PHP_URL_HOST);
			// Check if website is allowed
			$site = DB::table('permitted_websites')->where(['web_host' => $hostWebsite])->first();
			// Check if website is not allowed
			if (!$site) {
				// Return error
				return json_encode(['notallowed' => true, 'host' => Request::server('HTTP_REFERER')]);
			}
			// Get organisation
			$org = DB::table('organisations')->where(['id' => $site->org_id])->first();
			// Get settings for widget
			$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();
			// Check if new instance or not
			if (!isset($data['instanceToken'])) {
				// Set that instance is new
				$newInstance = true;
				// Create instance token
				$data['instanceToken'] = 'atomize-'.sha1('messengerInstance-'.sha1('instance'.sha1(time().rand())));
				// Create client token
				$data['clientId'] = sha1('client-'.sha1('instance'.sha1(time().rand())));
				// Create encryption token
				$encryptionToken = sha1(uniqid().rand().sha1(time().$data['instanceToken']));
				// Add new client into table
				$clientId = DB::table('organisations_clients')->insertGetId([
					'token'				=> $data['clientId'],
					'org_id'			=> $org->id,
					'date_created'		=> date('Y-m-d H:i:s', time()),
					'date_updated'		=> date('Y-m-d H:i:s', time()),
		        ]);
				// Save instance token
				$instance = DB::table('widget_instances')->insertGetId([
					'token'				=> $data['instanceToken'],
					'encrypt'			=> $encryptionToken,
					'client_id'			=> $clientId,
					'org_id'			=> $org->id,
					'date_created'		=> date('Y-m-d H:i:s', time()),
					'date_updated'		=> date('Y-m-d H:i:s', time()),
				]);
				// Set client ID
				$data['clientId'] = $clientId;
			} else {
				// Set that instance is not new
				$newInstance = false;
				// Get instance
				$instance = DB::table('widget_instances')->where([
					'token'				=> $data['instanceToken'],
					'org_id'			=> $org->id,
				])->first();
				// Set client ID
				$data['clientId'] = $instance->client_id;
			}


			// Add fake loading delay
			$data['widgetLoadDelay']		= 250;//1000;

			// Get previous chats
			$previousChats	= DB::table('chat_instances')->where(['client_id' => $data['clientId']])->orderBy('date_updated', 'DESC')->get();
			// Check if not empty
			if ($previousChats != null) {
				// Go through chats
				for ($i = 0; $i < sizeof($previousChats); $i++) {

					// Test
					// Test
					// Test
					$previousChats[$i]->assigned_agent = null;
					// Test
					// Test
					// Test

					// Check if an agent is assigned
					if ($previousChats[$i]->assigned_agent != null) {
						// Get agent details
					} else {
						// Get most recent message
						$mostRecentMessage = DB::table('chat_messages')->where(['instance_token' => $previousChats[$i]->token])->orderBy('date_updated', 'DESC')->first();

						// Get team details details
						$previousChats[$i]->agent_details = array(
							'name'					=> $settings->widget_teamName,
							'mostRecentMessage'		=> $mostRecentMessage,
							'iconSRC'				=> $settings->widget_iconSRC,
							'iconAlt'				=> $settings->widget_iconAlt,
						);
						// Check if agent sent last message
						if ($mostRecentMessage->agent_id != null) {
							// Set prefix
							$previousChats[$i]->agent_details['mostRecentMessage']->message = 'Agent: '.$previousChats[$i]->agent_details['mostRecentMessage']->message;
						} else {
							// Set prefix
							$previousChats[$i]->agent_details['mostRecentMessage']->message = 'You: '.$previousChats[$i]->agent_details['mostRecentMessage']->message;
						}
					}
				}
			}




			// $data['freshChat'] = true;
			$data['freshChat'] = false;
			$data['aiFirstMessage'] = $settings->startChatWithAI_message;
			// $data['outOfOffice'] = true;
			$data['outOfOffice'] = false;
			$data['outOfOfficeMessage'] = $settings->outOfOffice_message;
			$data['outOfOfficeEmailRequest'] = $settings->outOfOffice_requestEmail;
			$data['outOfOfficeDetailsRequest'] = $settings->outOfOffice_requestDetails;
			$data['iconSRC'] = $settings->widget_iconSRC;
			$data['iconAlt'] = $settings->widget_iconAlt;


			// Live refresh rate (milliseconds)
			$data['liveRefreshRate'] = 15000;

			if ($org->token != 'atomize') {
				$settings->widget_feedbackWidget = false;
			}

			// Get widget for rendering to the user
			$widget = view('widgets/chat', array(
				'newInstance'			=> $newInstance,
				'instanceToken'			=> $data['instanceToken'],
				'content'				=> array(
					'orgName'				=> $org->name,
					'primaryColor'			=> $settings->widget_primaryColour,
					'primaryColorAlt'		=> $settings->widget_primaryAlt,
					'logo'					=> $settings->widget_logoSRC,
					'iconSRC'				=> $settings->widget_iconSRC,
					'iconAlt'				=> $settings->widget_iconAlt,
					'teamName'				=> $settings->widget_teamName,
					'teamDescription'		=> $settings->widget_teamDescription,
					'greeting'				=> $settings->widget_greeting,
					'description'			=> $settings->widget_description,
					'outOfOffice'			=> $data['outOfOffice'],
					'previousChats'			=> $previousChats,
				),
				'blocks'				=> array(
					'feedback'				=> @$settings->widget_feedbackWidget,
				),
			))->render();

			// Send widget data as JSON object
			return json_encode(['widget' => $widget, 'data' => $data]);
		} else {
			// Send to website
			return Redirect::to(env('APP_URL'));
		}
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');
