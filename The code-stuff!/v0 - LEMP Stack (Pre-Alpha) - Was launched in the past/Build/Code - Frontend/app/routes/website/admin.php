<?php


//	******
// 	Admin Routes		- 	User list
//	******

Route::get('/admin/users', function() {

	// DB::table('organisations_clients')->truncate();
	// DB::table('chat_messages')->truncate();
	// DB::table('chat_instances')->truncate();
	// DB::table('widget_instances')->truncate();

	// Get users
	$users = DB::table('users')->orderBy('id', 'ASC')->get();

	return view('website/admin/users', ['users' => $users]);

})->middleware('auth', 'isAdmin')->name('admin-users');


Route::post('/admin/users', function() {

	// Create user
	$user = DB::table('users')->insert([
		'firstname'			=> Request::get('firstname'),
		'lastname'			=> Request::get('lastname'),
		'email'				=> Request::get('email'),
		'password'			=> Hash::make(Request::get('password')),
		'date_created'		=> date('Y-m-d H:i:s', time()),
		'date_updated'		=> date('Y-m-d H:i:s', time()),
	]);

	return Redirect::back();

})->middleware('auth', 'isAdmin')->name('admin-users');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******




//	******
// 	Admin Routes		- 	User list
//	******

Route::get('/admin/organisations', function() {

	// Get users
	$users = DB::table('users')->orderBy('id', 'ASC')->get();
	// Get organisations
	$orgs = DB::table('organisations')->orderBy('id', 'ASC')->get();
	// Loop through organisations
	for ($i = 0; $i < sizeof($orgs); $i++) {
		// Get organisation agents
		$orgs[$i]->agents = DB::table('organisations_agents')->where(['org_id' => $orgs[$i]->id])->orderBy('date_created', 'ASC')->get();
		// Loop through agents
		for ($j = 0; $j < sizeof($orgs[$i]->agents); $j++) {
			// Get user details for agents
			$orgs[$i]->agents[$j]->details = DB::table('users')->where(['id' => $orgs[$i]->agents[$j]->user_id])->first();
		}
	}

	return view('website/admin/organisations', ['users' => $users, 'orgs' => $orgs]);

})->middleware('auth', 'isAdmin')->name('admin-organisations');


Route::post('/admin/organisations', function() {

	$org = DB::table('organisations')->insertGetId([
		'name'				=> Request::get('org_name'),
		'token'				=> sha1(Request::get('org_name').time()),
		'date_created'		=> date('Y-m-d H:i:s', time()),
		'date_updated'		=> date('Y-m-d H:i:s', time()),
	]);

	return Redirect::back();

})->middleware('auth', 'isAdmin')->name('admin-organisations');


Route::post('/admin/organisations-agents', function() {

	// Get duplicate (if there is one)
	$duplicate = DB::table('organisations_agents')->where(['user_id' => Request::get('user_id'), 'org_id' => Request::get('org_id')])->first();
	// Check if not duplicate
	if (!$duplicate) {

		$isAdmin = false;
		// Check if admin
		if (Request::get('is_admin') == 'on') {
			$isAdmin = true;
		}

		DB::table('organisations_agents')->insert([
			'user_id'			=> Request::get('user_id'),
			'org_id'			=> Request::get('org_id'),
			'is_admin'			=> $isAdmin,
			'date_created'		=> date('Y-m-d H:i:s', time()),
			'date_updated'		=> date('Y-m-d H:i:s', time()),
		]);
	}

	return Redirect::back();

})->middleware('auth', 'isAdmin')->name('admin-organisations-agents');


Route::post('/admin/organisations-agents-update', function() {

	$isAdmin = false;
	// Check if admin
	if (Request::get('is_admin') == 'on') {
		$isAdmin = true;
	}

	DB::table('organisations_agents')->where([
		'user_id'			=> Request::get('user_id'),
		'org_id'			=> Request::get('org_id'),
	])->update([
		'name'				=> Request::get('agent_name'),
		'role'				=> Request::get('agent_role'),
		'iconSRC'			=> Request::get('agent_iconSRC'),
		'is_admin'			=> $isAdmin,
		'date_updated'		=> date('Y-m-d H:i:s', time()),
	]);

	return Redirect::back();

})->middleware('auth', 'isAdmin')->name('admin-organisations-agents-update');


Route::get('/admin/widgets', function() {

	// Get widgets
	$widgets = DB::table('widget_instances')->get();

	return view('website/admin/widgets', ['widgets' => $widgets]);

})->middleware('auth', 'isAdmin')->name('admin-widgets');


Route::get('/admin/chats', function() {

	// Get chats
	$chats = DB::table('chat_instances')->get();

	return view('website/admin/chats', ['chats' => $chats]);

})->middleware('auth', 'isAdmin')->name('admin-chats');


Route::get('/admin/audit', function() {

	// Get logs
	$audit = DB::table('audit_log')->get();

	return view('website/admin/audit', ['audit' => $audit]);

})->middleware('auth', 'isAdmin')->name('admin-audit');

//	******	******	******	******
//	******	******	******	******
//	******	******	******	******
