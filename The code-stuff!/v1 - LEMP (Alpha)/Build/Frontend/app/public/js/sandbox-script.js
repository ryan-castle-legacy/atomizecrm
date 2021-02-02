// * File: 				/js/sandbox-script.js
// * Description:		This file holds the JavaScript for the sandbox.
// * Created:			21st December 2020 at 17:04
// * Updated:			21st December 2020 at 17:04
// * Author:			Ryan Castle
// * Notes:

// ----------

// Debounce
var debounce = false;
window.waitingForWidgetLoad = true;


// Code to execute once this script has been read
var initialise = function() {

    // Await DOM to load for DOM-based code to execute
    document.addEventListener('DOMContentLoaded', DOMBasedFunctionality);

};


// DOM-based code
var DOMBasedFunctionality = function() {

	// Action for when the Messenger has loaded
	window.waitingForWidgetLoad = setInterval(function() {
		// Check if loaded
		if (window.AtomizeCRM.Messenger.hasLoaded == true) {
			// Clear interval
			window.clearInterval(window.waitingForWidgetLoad);
			// Delay opening the portal
			setTimeout(function() {
				// Open the portal
				document.getElementById('AtomizeCRM-messenger-launcher-button').click();
				// Delay opening the chat frame
				setTimeout(function() {
					// Click to open the chat frame
					document.querySelector('button[process-frame="chat"]').click();
				}, 250);
				// Delay clicking the data-gathering logic
				setTimeout(function() {
					// Click to option that leads to data-gathering
					document.querySelector('.AtomizeCRM-messenger-messages-choice[logic-next="1"]').click();
				}, 250);
			}, 250);
		};
	}, 250);

};


// ----------

// Execute this script
initialise();
