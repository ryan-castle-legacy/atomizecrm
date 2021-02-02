<?php

// * File: 				/routes/api/user.php
// * Description:		This file holds the routes that interact with the User model.
// * Created:			17th December 2020 at 18:33
// * Updated:			17th December 2020 at 18:37
// * Author:			Ryan Castle
// * Notes:

// ----------

// Import external packages and scripts
use App\Http\Controllers\Database;
use App\Http\Controllers\EmailHandler;
use App\Models\User;
use App\Models\Organisations;


// ----------

// @route               GET /user/exists/:email
// @description         Return a Boolean whether the supplied email address is registed with a user already
// @access              Public
Route::get('/user/exists/{email}', function(String $email) {
    // Attempt running code
    try {
        // Find the user record
        $user = DB::table('users')->where('email', '=', $email)->first();
        // Check if user was found
        if ($user) {
            // Send the result
            return true;
        }
        // Return the data
        return false;
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               POST /user/create
// @description         Creation of a new user account
// @access              Public
Route::post('/user/create', function() {
    // Attempt running code
    try {
        // Collect data from the request
        $data = Request::all();
        // Search for an existing user record
        $user = DB::table('users')->where('email', '=', $data['email'])->first();
        // Check if user was found
        if ($user) {
            // Send an error
            return json_encode(['error' => 'This email address is already in use.']);
        }
        // Create a new User from the model
        $user = User::create([
            'firstname'     => $data['firstname'],
            'lastname'      => $data['lastname'],
            'email'         => $data['email'],
            'password'      => $data['password']
        ]);
        // Get the user's details
        $user = DB::table('users')->where('email', '=', $data['email'])->first();
        // Return the User Model's data
        return json_encode(['user' => $user]);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getLine().' - '.$error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               POST /user/check-login
// @description         Checking if the user's login details are correct
// @access              Public
Route::post('/user/check-login', function() {
    // Attempt running code
    try {
        // Collect data from the request
        $data = Request::all();
        // Search for a user record matching these credentials
        $user = DB::table('users')->where([
            ['email',       '=', $data['email']],
        ])->first();
        // Check if a user was not found with this email address
        if (!$user) {
            // Send an error
            return json_encode(['error' => 'This account does not exist.']);
        }
        // Check if password matched
        if (Hash::check($data['password'], $user->password)) {
            // Return the user's data
            return json_encode(['user' => $user]);
        }
        // Return error
        return json_encode(['error' => 'This password was incorrect.']);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getLine().' - '.$error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               GET /user/organisations/:user_id
// @description         Return a Boolean whether the supplied email address is registed with a user already
// @access              Public
Route::get('/user/organisations/{user_id}', function(String $user_id) {
    // Attempt running code
    try {
		// Set the '$cacheTestData' cache-expiration seconds (1 year)
		$cacheExpirySeconds = 60*60*365;
		// Save the data in the cache if not already done
		$cachedData = Cache::remember('user-'.$user_id.'-organisations', $cacheExpirySeconds, function() use ($user_id) {
			// Find the user's organisations
	        $organisations = DB::table('organisation_contacts')
				->join('organisations', 'organisation_contacts.organisation_id', '=', 'organisations.id')
				->where([
					['organisation_agent_user_id', '=', $user_id],
					['is_organisation_agent', '=', true]
				])->get();
			// Return the data
	        return $organisations;
		});

		// Return the data
		return json_encode($cachedData);

    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               POST /user/reset-password
// @description         Allows user to reset their password via an email
// @access              Public
Route::post('/user/reset-password', function() {
    // Attempt running code
    try {
        // Collect data from the request
        $data = Request::all();
        // Search for an existing user record
        $user = DB::table('users')->where('email', '=', $data['email'])->first();
        // Check if user was found
        if ($user) {
			// Create a token for resetting the password
			$resetPasswordToken = sha1('resetPassword-'.$data['email'].sha1(time().rand()));
			// Save the reset password token
			$update = DB::table('users')->where('id', '=', $user->id)->update([
				'password_reset_token' 		=> $resetPasswordToken,
				'password_reset_token_date'	=> DB::raw('CURRENT_TIMESTAMP')
			]);
			// Send email to the user
			$emailSent = EmailHandler::sendEmail(
				'accounts.UserResetPassword',
				$data['email'],
				'Complete your password reset request',
				array(
					'name'					=> $user->firstname,
					'resetPasswordToken'	=> $resetPasswordToken,
					'previewMessage'		=> 'Preview message!',
				),
				array(
					'email'		=> env('SUPPORT_EMAIL'),
					'name'		=> env('SUPPORT_EMAIL_NAME'),
				),
				array(
					'email'		=> env('NOREPLY_EMAIL'),
					'name'		=> env('NOREPLY_EMAIL_NAME'),
				),
			);
			// Return that the email has been sent a reset email
			return json_encode('sent_email');
		}
		// Return that the email has not been registered
		return json_encode('no_email_registered');
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getLine().' - '.$error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               GET /user/reset-password/:resetPasswordToken
// @description         Return whether the token is valid for the password to be changed
// @access              Public
Route::get('/user/reset-password/{resetPasswordToken}', function($resetPasswordToken) {
    // Attempt running code
    try {
		// Search for an existing user record
        $user = DB::table('users')->where('password_reset_token', '=', $resetPasswordToken)->first();
        // Check if user was found
        if ($user) {
			// Check the date of the token and make sure it has been created within the last 1 hour
			if (strtotime($user->password_reset_token_date) >= strtotime('-1 hours')) {
				// Return with the user data as the token is valid
				return json_encode(['status' => 'token_valid', 'user' => $user]);
			} else {
				// Return that the token was not valid anymore as it expired
				return json_encode(['status' => 'token_expired']);
			}
		}
		// Return that the token was not valid
		return json_encode(['status' => 'not_valid']);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getMessage());
    }
})->middleware('apiAuthorisedOnly');


// @route               POST /user/update-password
// @description         Updating the user's password
// @access              Public
Route::post('/user/update-password', function() {
    // Attempt running code
    try {
        // Collect data from the request
        $data = Request::all();
		// Search for an existing user record
        $user = DB::table('users')->where('password_reset_token', '=', $data['resetPasswordToken'])->first();
        // Check if user was found
        if ($user) {
			// Check the date of the token and make sure it has been created within the last 1 hour
			if (strtotime($user->password_reset_token_date) >= strtotime('-1 hours')) {
				// Update the user's password
				$update = DB::table('users')->where('id', '=', $user->id)->update([
					'password' 					=> $data['password'],
					'password_reset_token' 		=> null,
					'password_reset_token_date'	=> null,
				]);
		        // Return the user's data
		        return json_encode(['status' => 'successful', 'user' => $user]);
			}
		}
		// Return that the token was not valid anymore as it expired
		return json_encode(['status' => 'token_expired']);
    } catch (Exception $error) {
        // Set the headers for the return data
        header('Content-Type: application/json');
        // Return the error
        return array('message' => $error->getLine().' - '.$error->getMessage());
    }
})->middleware('apiAuthorisedOnly');





//
// '/user/update-password', array(
// 	'email'             => $tokenValidity['user']->email,
// 	'password'          => $password
// ));
