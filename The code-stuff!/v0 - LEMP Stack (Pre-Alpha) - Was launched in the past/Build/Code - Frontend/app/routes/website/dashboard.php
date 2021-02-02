<?php


//	******
// 	Auth Routes			- 	Login
//	******

Route::get('/login', function() {
	return view('website/dashboard/login');
})->middleware('guest')->name('dashboard-login');


Route::post('/login', 'Auth\LoginController@login')->middleware('guest')->name('dashboard-login');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Auth Routes			- 	Register Page
//	******

// Route::get('/register', function() {
// 	return view('website/dashboard/register');
// })->middleware('guest')->name('dashboard-register');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******












//	******
// 	Dashboard Routes		- 	Homepage
//	******

Route::get('/home/{org_token?}', function($org_token = null) {

	// Check if org token was set
	if ($org_token != null) {
		// Send user to chats
		return Redirect::route('dashboard-messenger-chats', ['org_token' => $org_token]);
	} else {
		// Get organisations that the user is connected to
		$orgs = DB::table('organisations_agents')
			->join('organisations', 'organisations.id', '=', 'organisations_agents.org_id')
			->where('user_id', Auth::user()->id)
			->orderBy('organisations.name', 'ASC')->get();
		// Redirect user to home with first alphabetical name
		return Redirect::route('dashboard-home', ['org_token' => $orgs[0]->token]);
	}

})->middleware('auth')->name('dashboard-home');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Dashboard Routes		- 	Logout
//	******

Route::get('/logout', 'Auth\LoginController@logout')->middleware('auth')->name('dashboard-logout');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Dashboard Routes		- 	Chat widget settings
//	******

Route::get('/messenger/settings/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();
	// Get chat widget settings
	$settings = DB::table('widget_chat_settings')
		->join('organisations', 'widget_chat_settings.org_id', '=', 'organisations.id')
		->where('organisations.token', $org_token)->first();

	return view('website/dashboard/business/messenger', ['navGroup' => 'businessSettings_messenger', 'org_token' => $org_token, 'org' => $org, 'settings' => $settings]);

})->middleware('auth', 'isOrgAdmin')->name('dashboard-messenger-settings');


Route::post('/messenger/settings/{org_token}', function($org_token) {

	$data = Request::all();

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();
	// Check if record exists
	$settings = DB::table('widget_chat_settings')->where(['org_id' => $org->id])->first();
	// Check if record needs created
	if (!$settings) {
		// Create basic template for chat settings
		$settings = DB::table('widget_chat_settings')->insertGetId([
			'org_id'					=> $org->id,
			'date_created'				=> date('Y-m-d H:i:s', time()),
			'date_updated'				=> date('Y-m-d H:i:s', time()),
		]);
	} else {
		// Get ID of settings record
		$settings = $settings->id;
	}

	// Feedback block toggle
	if (@Request::get('widget_feedbackWidget') == 'on') {
		$feedbackBlock = true;
	} else {
		$feedbackBlock = false;
	}

	// Update settings
	$data = DB::table('widget_chat_settings')->where(['id' => $settings])->update([
		'date_updated'						=> date('Y-m-d H:i:s', time()),
		'outOfOffice_message'				=> Request::get('outOfOffice_message'),
		'outOfOffice_requestEmail'			=> Request::get('outOfOffice_requestEmail'),
		'outOfOffice_requestDetails'		=> Request::get('outOfOffice_requestDetails'),
		'startChatWithAI_message'			=> Request::get('startChatWithAI_message'),
		'widget_primaryColour'				=> Request::get('widget_primaryColour'),
		'widget_primaryAlt'					=> Request::get('widget_primaryAlt'),
		'supportEmailAddress'				=> Request::get('supportEmailAddress'),
		'widget_logoSRC'					=> Request::get('widget_logoSRC'),
		'widget_iconSRC'					=> Request::get('widget_iconSRC'),
		'widget_iconAlt'					=> Request::get('widget_iconAlt'),
		'widget_teamName'					=> Request::get('widget_teamName'),
		'widget_teamDescription'			=> Request::get('widget_teamDescription'),
		'widget_greeting'					=> Request::get('widget_greeting'),
		'widget_description'				=> Request::get('widget_description'),
		'widget_feedbackWidget'				=> $feedbackBlock,
	]);

	return Redirect::back();

})->middleware('auth', 'isOrgAdmin')->name('dashboard-messenger-settings');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Dashboard Routes		- 	Chat permitted websites
//	******

Route::get('/messenger/websites/{org_token}', function($org_token) {

	// Get websites that the widget can be displayed on settings
	$websites = DB::table('permitted_websites')
		->join('organisations', 'permitted_websites.org_id', '=', 'organisations.id')
		->where('organisations.token', $org_token)->get();

	return view('website/dashboard/widgets/chat/websites', ['org_token' => $org_token, 'websites' => $websites]);

})->middleware('auth', 'isOrgAdmin')->name('dashboard-messenger-websites');


Route::post('/messenger/websites/{org_token}', function($org_token) {

	$url = parse_url('https://'.Request::get('web_url'), PHP_URL_HOST);

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	// Check if record exists
	$site = DB::table('permitted_websites')->where(['web_host' => $url, 'org_id' => $org->id])->first();
	// Check if record needs created
	if (!$site) {
		// Create record
		$site = DB::table('permitted_websites')->insertGetId([
			'org_id'					=> $org->id,
			'web_host'					=> $url,
			'date_created'				=> date('Y-m-d H:i:s', time()),
			'date_updated'				=> date('Y-m-d H:i:s', time()),
		]);
	}

	return Redirect::back();

})->middleware('auth', 'isOrgAdmin')->name('dashboard-messenger-websites');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Dashboard Routes		- 	Client list
//	******

Route::get('/clients/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();
	// Get clients for this organisation
	$clients = DB::table('organisations_clients')->where(['org_id' => $org->id])->orderBy('date_updated', 'DESC')->get();

	return view('website/dashboard/clients/list', ['org_token' => $org_token, 'org' => $org, 'clients' => $clients]);

})->middleware('auth', 'isOrgStaff')->name('dashboard-messenger-clients');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Dashboard Routes		- 	Chat list
//	******

Route::get('/chats/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	return view('website/dashboard/chats/list', ['navGroup' => 'chats', 'noSupportWidget' => true, 'org_token' => $org_token, 'org' => $org]);

})->middleware('auth', 'isOrgStaff')->name('dashboard-messenger-chats');




Route::post('/chats/{convo_token}/{org_token}/assign-agent', function($convo_token, $org_token) {

	// Debounce
	$assign = false;

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	// Check if not null
	if (Request::get('assigned_agent') == 'not_assigned') {
		// Clear agent
		$assign = DB::table('chat_instances')->where(['token' => $convo_token])->update([
			'assigned_agent'	=> null,
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
	} else {
		// Get agent in org
		$agent = DB::table('organisations_agents')->where(['org_id' => $org->id, 'user_id' => Request::get('assigned_agent')])->first();
		// Check if agent is in organisation
		if ($agent) {
			// Assign agent
			$assign = DB::table('chat_instances')->where(['token' => $convo_token])->update([
				'assigned_agent'	=> $agent->user_id,
				'date_updated'		=> date('Y-m-d H:i:s', time()),
			]);
		}
	}

	// Check if successful
	if ($assign) {
		// Check if assigned
		if (Request::get('assigned_agent') == 'not_assigned') {
			// Set alt user ID
			$alt_user_id = null;
		} else {
			// Set alt user ID
			$alt_user_id = Request::get('assigned_agent');
		}
		// Create record to audit chat agent assignments
		DB::table('audit_log')->insert([
			'actioner_user_id'		=> Auth::user()->id,
			'alt_user_id'			=> $alt_user_id,
			'type'					=> 'assign_agent',
			'meta'					=> json_encode(array(
				'convo_token'			=> $convo_token,
				'org_id'				=> $org->id,
			)),
			'date_created'			=> date('Y-m-d H:i:s', time()),
			'date_updated'			=> date('Y-m-d H:i:s', time()),
		]);
	}

	return Redirect::back();

})->middleware('auth', 'isOrgStaff')->name('dashboard-messenger-chats-chat-assignAgent');





Route::post('/chats/{convo_token}/{org_token}/send-message', function($convo_token, $org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();
	// Get agent for this chat
	$agent = DB::table('chat_instances')->where(['token' => $convo_token, 'assigned_agent' => Auth::user()->id])->first();
	// Check if agent exists
	if ($agent) {
		// Add message to DB
		$message = DB::table('chat_messages')->insertGetId([
			'instance_token'	=> $convo_token,
			'from'				=> 'agent',
			'message'			=> Request::get('message'),
			'agent_id'			=> Auth::user()->id,
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
		// Update chat instance
		DB::table('chat_instances')->where(['token' => $convo_token])->update(['date_updated' => date('Y-m-d H:i:s', time())]);
	}

	return Redirect::back();

})->middleware('auth', 'isOrgStaff')->name('dashboard-messenger-chats-chat-sendMessage');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Dashboard Routes		- 	Feedback list
//	******

Route::get('/feedback/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();
	// Get feedback for this organisation
	$feedback = DB::table('feedback_submissions')->where(['org_id' => $org->id])->orderBy('date_created', 'DESC')->get();

	return view('website/dashboard/feedback/list', ['org_token' => $org_token, 'org' => $org, 'feedback' => $feedback]);

})->middleware('auth', 'isOrgStaff')->name('dashboard-feedback');
































































//	******
// 	Dashboard Routes		- 	Agent list
//	******

Route::get('/settings/business/agents/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	// Get organisation agents
	$org->agents = DB::table('organisations_agents')->where(['org_id' => $org->id])->orderBy('date_created', 'ASC')->get();
	// Loop through agents
	for ($i = 0; $i < sizeof($org->agents); $i++) {
		// Get user details for agents
		$org->agents[$i]->details = DB::table('users')->where(['id' => $org->agents[$i]->user_id])->first();
	}

	return view('website/dashboard/business/agents', ['navGroup' => 'businessSettings_agents', 'org_token' => $org_token, 'org' => $org]);

})->middleware('auth', 'isOrgAdmin')->name('dashboard-settings-agents');





//	******
// 	Dashboard Routes		- 	Billing manager
//	******

Route::get('/settings/business/billing/{org_token}', function($org_token) {

	echo 'Redirecting...<br/><a href="'.route('dashboard-home').'">Go back</a>';

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	// Start Stripe session
	$Stripe = new \Stripe\StripeClient(env('STRIPE_SK'));
	\Stripe\Stripe::setApiKey(env('STRIPE_SK'));

	// Check whether the org does not have a customer record in Stripe
	if ($org->stripe_customerId == null) {
		// Create Stripe customer for the organisation
		$stripe_customer = $Stripe->customers->create([
			'email'			=> Auth::user()->email,
			'name'			=> $org->name,
		]);
		// Update the org's record
		DB::table('organisations')->where(['id' => $org->id])->update(['stripe_customerId' => $stripe_customer->id]);
		// Update object
		$org->stripe_customerId = $stripe_customer;
	}

	// Create portal session
	$portal = \Stripe\BillingPortal\Session::create([
		'customer' 		=> $org->stripe_customerId,
		'return_url' 	=> env('APP_URL').'/home',
	]);

	return Redirect::to($portal->url);

})->middleware('auth', 'isOrgAdmin')->name('dashboard-settings-billing');




















//	******
// 	Dashboard Routes		- 	User account settings
//	******

Route::get('/settings/account/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	return view('website/dashboard/settings/user', ['navGroup' => 'accountSettings_main', 'org_token' => $org_token, 'org' => $org]);

})->middleware('auth')->name('dashboard-settings-account');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******







//	******
// 	Dashboard Routes		- 	Agent profile settings
//	******

Route::get('/settings/business/profile/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	return view('website/dashboard/business/profile', ['navGroup' => 'businessSettings_profile', 'org_token' => $org_token, 'org' => $org]);

})->middleware('auth')->name('dashboard-business-profile');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******






//	******
// 	Dashboard Routes		- 	Business settings
//	******

Route::get('/settings/business/general/{org_token}', function($org_token) {

	// Get organisation
	$org = DB::table('organisations')->where(['token' => $org_token])->first();

	$org->inOffice_timezone = 'UTC';
	$org->inOffice_times = array(
		'Mon_0900_1200', 'Mon_1300_1700',
		'Tue_0900_1200', 'Tue_1300_1700',
		'Wed_0900_1200', 'Wed_1300_1700',
		'Thu_0900_1200', 'Thu_1300_1700',
		'Fri_0900_1200', 'Fri_1300_1630',
	);

	return view('website/dashboard/business/general', ['navGroup' => 'businessSettings_general', 'org_token' => $org_token, 'org' => $org]);

})->middleware('auth')->name('dashboard-business-settings');


Route::post('/settings/business/general/{org_token}', function($org_token) {

	// Get data
	$data = Request::all();





	$inOffice_timezone = $data['timezone'];
	// Create array of in-office times
	$inOffice_times = array();

	// Get


	echo '<pre>';
	var_dump($data);
	return;

})->middleware('auth')->name('dashboard-business-settings');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******
