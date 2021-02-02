<?php


//	******
// 	AJAX Routes
//	******

Route::get('/ajax/getMessengerConvos/{inbox}/{org_token}', function(Request $request, $inbox, $org_token) {
	try {
		// Get organisation
		$org = DB::table('organisations')->where(['token' => $org_token])->first();
		// Switch inboxes
		switch ($inbox) {
			case 'assigned':
				// Get convos
				$chatData = DB::table('chat_instances')->where(['org_id' => $org->id, 'assigned_agent' => Auth::user()->id])->orderBy('date_updated', 'DESC')->get();
				// Get alt inbox count
				$altInboxCounter = DB::table('chat_instances')->where(['org_id' => $org->id, 'assigned_agent' => null])->count();
			break;
			case 'queue':
				// Get convos
				$chatData = DB::table('chat_instances')->where(['org_id' => $org->id, 'assigned_agent' => null])->orderBy('date_updated', 'DESC')->get();
				// Get alt inbox count
				$altInboxCounter = DB::table('chat_instances')->where(['org_id' => $org->id])->whereNotNull('assigned_agent')->count();
			break;
		}
		// Loop through chats (to get new messages)
		for ($i = 0; $i < sizeof($chatData); $i++) {
			// Get messages in chat
			$chatData[$i]->mostRecentMessage = DB::table('chat_messages')->where(['instance_token' => $chatData[$i]->token])->orderBy('date_updated', 'DESC')->first();
			// Check if there was a message
			if ($chatData[$i]->mostRecentMessage) {
				// Parse time stamp
				$chatData[$i]->mostRecentMessage->timesent = strtotime($chatData[$i]->mostRecentMessage->date_updated);
			}
			// Get the client info
			$chatData[$i]->client = DB::table('organisations_clients')->where(['id' => $chatData[$i]->client_id])->first();
		}
		// Return data
		return json_encode(['currentInbox' => $chatData, 'altInboxCounter' => $altInboxCounter]);
	} catch (Exception $e) {
		return json_encode($e->getMessage());
	}
})->middleware('ajax', 'auth');






Route::get('/ajax/getMessengerMessages/{instance}', function(Request $request, $instance) {
	try {
		// Get conversation
		$convo = DB::table('chat_instances')->where(['token' => $instance])->first();
		// Check if convo exists
		if ($convo) {
			// Get time started
			$convo->timestarted = strtotime($convo->date_created);
			// Get messages in chat
			$messages = DB::table('chat_messages')->where(['instance_token' => $convo->token])->orderBy('date_updated', 'DESC')->get();
			// Go through all messages
			for ($i = 0; $i < sizeof($messages); $i++) {
				// Parse time stamp
				$messages[$i]->timesent = strtotime($messages[$i]->date_updated);
			}
			// Get the client info
			$clientDetails = DB::table('organisations_clients')->where(['id' => $convo->client_id])->first();
			// Return data
			return json_encode(['messages' => $messages, 'client' => $clientDetails, 'convo' => $convo]);
		}
		return json_encode(false);
	} catch (Exception $e) {
		return json_encode($e->getMessage());
	}
})->middleware('ajax', 'auth');






Route::post('/ajax/assignLocalAgent/', function(Request $request) {
	try {
		// Get post data
		$data = Request::all();
		// Get convo
		$convo = DB::table('chat_instances')->where(['token' => Request::get('convo')])->first();
		// Assign user convo
		DB::table('chat_instances')->where(['id' => $convo->id])->update([
			'assigned_agent'	=> Auth::user()->id,
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
		// Create record to audit chat agent assignments
		DB::table('audit_log')->insert([
			'actioner_user_id'		=> Auth::user()->id,
			'alt_user_id'			=> Auth::user()->id,
			'type'					=> 'assign_agent',
			'meta'					=> json_encode(array(
				'convo_token'			=> $convo->token,
				'org_id'				=> $convo->org_id,
			)),
			'date_created'			=> date('Y-m-d H:i:s', time()),
			'date_updated'			=> date('Y-m-d H:i:s', time()),
		]);
		// Return success
		return json_encode($convo->token);
	} catch (Exception $e) {
		return json_encode($e->getMessage());
	}
})->middleware('ajax', 'auth');






Route::post('/ajax/sendAgentMessage/', function(Request $request) {
	try {
		// Get post data
		$data = Request::all();
		// Get convo
		$convo = DB::table('chat_instances')->where(['token' => Request::get('convo')])->first();
		// Get client info
		$client = DB::table('organisations_clients')->where(['id' => $convo->client_id])->first();
		// Get organisation
		$org = DB::table('organisations')->where(['id' => $convo->org_id])->first();
		// Add message to DB
		$message = DB::table('chat_messages')->insertGetId([
			'instance_token'	=> $convo->token,
			'from'				=> 'agent',
			'message'			=> $data['message'],
			'agent_id'			=> Auth::user()->id,
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
		// Update chat instance
		DB::table('chat_instances')->where(['token' => $convo->token])->update(['date_updated' => date('Y-m-d H:i:s', time())]);
		// Send response back
		return json_encode(array(
			'chatInstance'	=> $convo->token,
			'message'		=> $message,
			'messageText'	=> 'You: '.$data['message'],
			'client'		=> $client,
			'date'			=> date('H:ia', time()),
		));
	} catch (Exception $e) {
		return json_encode($e->getMessage());
	}
})->middleware('ajax', 'auth');






// Get most recent live updates
Route::post('/ajax/refreshLiveChatItems/', function(Request $request) {
	try {
		// Get post data
		$data = Request::all();
		// Get organisation
		$org = DB::table('organisations')->where(['token' => Request::get('org_token')])->first();
		// Assigned Chat data holder
		$assignedChatData = array();
		// Chat queue data holder
		$queueChatData = array();
		// Check if lastRefreshed is not null
		if (@is_null($data['lastRefreshed'])) {
			// Get user convos
			$assignedChatData = DB::table('chat_instances')->where(['org_id' => $org->id, 'assigned_agent' => Auth::user()->id])->orderBy('date_updated', 'DESC')->get();
			// Loop through assigned chats (to get new messages)
			for ($i = 0; $i < sizeof($assignedChatData); $i++) {
				// Get time started
				$assignedChatData[$i]->timestarted = strtotime($assignedChatData[$i]->date_created);
				// Get messages in chat
				$assignedChatData[$i]->messages = DB::table('chat_messages')->where(['instance_token' => $assignedChatData[$i]->token])->orderBy('date_updated', 'DESC')->get();
				// Go through all messages
				for ($j = 0; $j < sizeof($assignedChatData[$i]->messages); $j++) {
					// Parse time stamp
					$assignedChatData[$i]->messages[$j]->timesent = strtotime($assignedChatData[$i]->messages[$j]->date_updated);
				}
				// Get the client info
				$assignedChatData[$i]->client = DB::table('organisations_clients')->where(['id' => $assignedChatData[$i]->client_id])->first();
			}


			// Get queue convos
			$queueChatData = DB::table('chat_instances')->where(['org_id' => $org->id, 'assigned_agent' => null])->orderBy('date_updated', 'DESC')->get();
			// Loop through assigned chats (to get new messages)
			for ($i = 0; $i < sizeof($queueChatData); $i++) {
				// Get time started
				$queueChatData[$i]->timestarted = strtotime($queueChatData[$i]->date_created);
				// Get messages in chat
				$queueChatData[$i]->messages = DB::table('chat_messages')->where(['instance_token' => $queueChatData[$i]->token])->orderBy('date_updated', 'DESC')->get();
				// Go through all messages
				for ($j = 0; $j < sizeof($queueChatData[$i]->messages); $j++) {
					// Parse time stamp
					$queueChatData[$i]->messages[$j]->timesent = strtotime($queueChatData[$i]->messages[$j]->date_updated);
				}
				// Get the client info
				$queueChatData[$i]->client = DB::table('organisations_clients')->where(['id' => $queueChatData[$i]->client_id])->first();
			}
		} else {
			// Get user convos
			$assignedChatData = DB::table('chat_instances')
				->where(['org_id' => $org->id, 'assigned_agent' => Auth::user()->id])
				->where('date_updated', '>=', $data['lastRefreshed'])
				->orderBy('date_updated', 'DESC')->get();
			// Loop through assigned chats (to get new messages)
			for ($i = 0; $i < sizeof($assignedChatData); $i++) {
				// Get time started
				$assignedChatData[$i]->timestarted = strtotime($assignedChatData[$i]->date_created);
				// Get messages in chat
				$assignedChatData[$i]->messages = DB::table('chat_messages')
					->where(['instance_token' => $assignedChatData[$i]->token])
					->where('date_updated', '>=', $data['lastRefreshed'])
					->orderBy('date_updated', 'DESC')->get();
				// Go through all messages
				for ($j = 0; $j < sizeof($assignedChatData[$i]->messages); $j++) {
					// Parse time stamp
					$assignedChatData[$i]->messages[$j]->timesent = strtotime($assignedChatData[$i]->messages[$j]->date_updated);
				}
				// Get the client info
				$assignedChatData[$i]->client = DB::table('organisations_clients')->where(['id' => $assignedChatData[$i]->client_id])->first();
			}


			// Get user convos
			$queueChatData = DB::table('chat_instances')
				->where(['org_id' => $org->id, 'assigned_agent' => null])
				->where('date_updated', '>=', $data['lastRefreshed'])
				->orderBy('date_updated', 'DESC')->get();
			// Loop through assigned chats (to get new messages)
			for ($i = 0; $i < sizeof($queueChatData); $i++) {
				// Get time started
				$queueChatData[$i]->timestarted = strtotime($queueChatData[$i]->date_created);
				// Get messages in chat
				$queueChatData[$i]->messages = DB::table('chat_messages')
					->where(['instance_token' => $queueChatData[$i]->token])
					->where('date_updated', '>=', $data['lastRefreshed'])
					->orderBy('date_updated', 'DESC')->get();
				// Go through all messages
				for ($j = 0; $j < sizeof($queueChatData[$i]->messages); $j++) {
					// Parse time stamp
					$queueChatData[$i]->messages[$j]->timesent = strtotime($queueChatData[$i]->messages[$j]->date_updated);
				}
				// Get the client info
				$queueChatData[$i]->client = DB::table('organisations_clients')->where(['id' => $queueChatData[$i]->client_id])->first();
			}
		}


		// Get all chats
		// $previousChats	= DB::table('chat_instances')->where(['client_id' => $instance->client_id])->orderBy('date_updated', 'DESC')->get();
		// // Check if not empty
		// if ($previousChats != null) {
		// 	// Go through chats
		// 	for ($i = 0; $i < sizeof($previousChats); $i++) {
		// 		// Get most recent message
		// 		$mostRecentMessage = DB::table('chat_messages')->where(['instance_token' => $previousChats[$i]->token])->orderBy('date_updated', 'DESC')->first();
		// 		// Check if chat has agent assigned to it
		// 		if ($previousChats[$i]->assigned_agent != null) {
		// 			// Get agent
		// 			$agent = DB::table('organisations_agents')->where(['org_id' => $org->id, 'user_id' => $previousChats[$i]->assigned_agent])->first();
		// 			// Get agent details
		// 			$previousChats[$i]->agentDetails = array(
		// 				'name'					=> $agent->name,
		// 				'role'					=> $agent->role,
		// 				'mostRecentMessage'		=> $mostRecentMessage,
		// 				'iconSRC'				=> $agent->iconSRC,
		// 				'iconAlt'				=> $agent->name[0],
		// 			);
		// 		} else {
		// 			// Get agent details
		// 			$previousChats[$i]->agentDetails = array(
		// 				'name'					=> $settings->widget_teamName,
		// 				'role'					=> 'Customer Service Team',
		// 				'mostRecentMessage'		=> $mostRecentMessage,
		// 				'iconSRC'				=> $settings->widget_iconSRC,
		// 				'iconAlt'				=> $settings->widget_iconAlt,
		// 			);
		// 		}
		// 		// Check if client last message
		// 		if ($mostRecentMessage->from == 'client') {
		// 			// Set prefix
		// 			$previousChats[$i]->agentDetails['mostRecentMessage']->message = 'You: '.$previousChats[$i]->agentDetails['mostRecentMessage']->message;
		// 		} else {
		// 			// Set prefix
		// 			$previousChats[$i]->agentDetails['mostRecentMessage']->message = 'Agent: '.$previousChats[$i]->agentDetails['mostRecentMessage']->message;
		// 		}
		// 		// Parse time sent for message
		// 		$previousChats[$i]->agentDetails['mostRecentMessage']->timesent = strtotime($previousChats[$i]->agentDetails['mostRecentMessage']->date_updated);
		// 	}
		// }


		// Return data
		return json_encode(['queueChatData' => $queueChatData, 'assignedChatData' => $assignedChatData, 'lastRefreshed' => date('Y-m-d H:i:s', time())]);
	} catch (Exception $e) {
		// Return error
		return json_encode(['message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => json_encode($e->getTrace())]);
	}
})->middleware('widgetCORS');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******
