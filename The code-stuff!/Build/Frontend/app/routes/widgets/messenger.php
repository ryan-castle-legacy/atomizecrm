<?php


use Illuminate\Support\Facades\Request;




// Send messenger widget DOM
Route::post('/widget/messenger/', function(Request $request) {
	try {
		// Check that the request was from AJAX
		if (Request::ajax()) {
			// Get request data
			$data = Request::all();


			// TEST
			$data['instanceToken'] = null;
			// TEST


			// Check if new instance or not
			if ($data['instanceToken'] == null) {
				// Set that instance is new
				$newInstance = true;
				// Create instance token
				$data['instanceToken'] = 'messengerInstance-'.sha1('instance'.sha1(time().rand()));
			} else {
				// Set that instance is not new
				$newInstance = false;
			}


			// Get widget for rendering to the user
			$widget = view('widgets/messenger', array(
				'newInstance'			=> $newInstance,
				'instanceToken'			=> $data['instanceToken'],
				'content'				=> array(
					'logo'					=> 'http://18.130.253.250/widgets/messenger/img/apple-logo.svg',
					'greeting'				=> 'Hi  👋',
					'description'			=> 'We help your business grow by connecting you to your customers.',
				),
			))->render();


			// Add fake loading delay
			$data['widgetLoadDelay']		= 250;//1000;


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
});
