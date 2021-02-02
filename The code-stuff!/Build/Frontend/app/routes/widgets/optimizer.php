<?php


use Illuminate\Support\Facades\Request;



// Optimise the JS
Route::get('/chat-widget/main.min.js', function(Request $request) {

	// Set file as JS
	header('Content-Type: application/javascript');

	// Toggle logs
	$show_logs = true;

	// Get widget JS code
	$code = file_get_contents('../public/widgets/chat/main.js');

	// Check if dev not server
	if (env('APP_URL') != 'http://18.130.253.250') {
		// Replace dev URL with live URL
		$code = str_replace('http://18.130.253.250', 'https://atomizecrm.com', $code);
		// Replace dev to live
		$code = str_replace('window.AtomizeTestMode = true;', 'window.AtomizeTestMode = false;', $code);
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
