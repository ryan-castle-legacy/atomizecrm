// Get existing container object (or create one if null)
window.Magic = window.Magic || {};


// Auto-calling of widget's methods
(function() {


	// Get existing container object for widgets (or create one if null)
	window.Magic.Widgets = window.Magic.Widgets || {};




	// Messenger widget object
	function MagicMessenger() {

		// Set request URL
		this.requestBaseURL 		= 'http://18.130.253.250';
		// Set whether the first load of the widget has happened
		this.hasLoaded 				= false;
		// Storage of widget DOM object
		this.widget					= null;
		// Storage of active XMLHttpRequest objects
		this.openRequest 			= null;
		// Store cookie data
		this.cookies				= null;
		// Initialise widget
		this.init 					= function() {
			console.log('init');
			// Check if wrapper object was create
			if (typeof window.Magic !== 'undefined') {
				// Get cookie data
				this.getCookies();
				// Fetch widget DOM
				return this.fetchWidget();
			}
		};
		// Get session storage data
		this.getSession				= function() {
			// Data storage
			data = {
				instanceToken: this.getCookie('instanceToken'),
			};
			// Return data
			return data;
		};
		// Get cookie data
		this.getCookie				= function(key) {
			// Check if defined
			if (this.cookies[key] !== undefined) {
				// Return value
				return this.cookies[key];
			}
			return null;
		};
		// Set cookie data
		this.setCookie				= function(key, value) {
			// Set widget object for cookies storage
			window.Magic.Widgets.Messenger.cookies[key] = value;
			// Get date now
			var nowPlus9 = new Date();
			// Add date in 9 months
			nowPlus9.setDate(nowPlus9.getDate() + 365); // Add a year
			// Set cookie
			document.cookie = key + '=' + value + ';expires=' + nowPlus9;
		};
		// Turn cookie object to string
		this.stringifyCookies		= function() {
			// Container for stringified cookies
			var str = '';
			// Loop through cookies
			Object.keys(window.Magic.Widgets.Messenger.cookies).forEach(function (key) {
				console.log(key);
				str += key + '=' + window.Magic.Widgets.Messenger.cookies[key] + ';';
			});
			console.log(str + 'path=/');
			// Set cookies
			document.cookie = str + 'path=/';
		}
		// Get cookies data
		this.getCookies				= function() {
			// Get cookies and split into array
			var cookies = document.cookie.split(';');
			// Storage for data
			var data = {};
			// Check if cookie exists
			for (var i = 0; i < cookies.length; i++) {
				// Split cookie into data object
				data[cookies[i].substr(0, cookies[i].indexOf('='))] = cookies[i].substr(cookies[i].indexOf('=') + 1);
			}
			// Return data
			this.cookies = data;
		};
		// Get widget DOM
		this.fetchWidget 			= function() {
			// Create the request
			this.request('/widget/messenger', this.getSession(), this.buildWidget);
		};
		// XMLHttpRequest handler
		this.request 				= function(target, data, callback) {
			// Create XML HTTP Request
			var request = new XMLHttpRequest();
			// Switch type of request
			switch (target) {
				case '/widget/messenger':
					// Open PUT request
					request.open('POST', this.requestBaseURL + target);
				break;
			}
			// Log the active request
			this.openRequest = target;
			// Set request header for how the request was sent
			request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			// Set request header for sending data
			request.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
			// Send request to target
			request.send(this.encodeRequestBody(data));
			// Listen for response
			request.onreadystatechange = function() {
				// Check if readyState is 4 (done - finished processing)
				if (request.readyState === 4) {
					// Check if the request was successful
					if (request.status === 200) {

						console.log(request.responseText);

						// Check if response data is a JSON object
						if (JSON.parse(request.responseText)) {
							console.log('callback');
							// Parse response text through callback
							return callback(JSON.parse(request.responseText));
						}
					}
					// Print error
					return console.error(JSON.parse(request.responseText));
				}
			}
		};
		// Build widget with DOM
		this.buildWidget			= function(data) {
			// Create Messenger container
			var container = document.createElement('div');
			// Add data to container
			container.setAttribute('class', 'magicMain');
			// Add widget from server
			container.innerHTML = data.widget;
			// Add widget to body
			document.body.appendChild(container);
			// Set widget in object
			window.Magic.Widgets.Messenger.widget = document.querySelector('#MagicMessenger');
			// Set event listeners
			window.Magic.Widgets.Messenger.setEventListeners();



			// Update instanceToken cookie
			window.Magic.Widgets.Messenger.setCookie('instanceToken', data.data.instanceToken);



			setTimeout(function() {

				// Set header height
				document.querySelector('.MagicMessenger_board_main_header').style.height = document.querySelector('.MagicMessenger_board_main_header_details').offsetHeight + 'px';

				document.querySelector('.MagicMessenger_board_main_body[frame="home"]').style.paddingTop = (document.querySelector('.MagicMessenger_board_main_header_details').offsetHeight - 74) + 'px';

				// Set frame to 'home'
				window.Magic.Widgets.Messenger.setFrame(false);

			}, data.data.widgetLoadDelay + 1000);
		};
		// Set the frame of the widget
		this.setFrame 				= function(target) {
			// Check if target is not set
			if (target == false) {
				// Get existing frame
				var target = window.Magic.Widgets.Messenger.widget.getAttribute('frame');
				// Switch frame
				switch (target) {
					case 'loading':
						// Check if initial loading
						if (window.Magic.Widgets.Messenger.hasLoaded == false) {
							// Set 'hasLoaded' as true
							window.Magic.Widgets.Messenger.hasLoaded = true;
							// Set widget as loaded
							window.Magic.Widgets.Messenger.widget.setAttribute('loaded', 'true');
							// Set frame
							target = 'home';


							// TESTING
							// TESTING
							target = 'conversation';
							window.Magic.Widgets.Messenger.resetMessageArea();
							// TESTING
							// TESTING
						} else {

						}
					break;

				}
			}
			// Set frame
			window.Magic.Widgets.Messenger.widget.setAttribute('frame', target);
		};
		// Set event listeners
		this.setEventListeners 		= function() {

			// Get items
			var items = document.querySelector('#MagicMessenger .MagicMessenger_trigger_button');
			// Set event listener
			items.addEventListener('click', window.Magic.Widgets.Messenger.toggleWidget);


			// Get items
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_header_close');
			// Set event listener
			items.addEventListener('click', window.Magic.Widgets.Messenger.toggleWidget);


			// Get home frame scrolling
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body[frame="home"]');
			// Set event listener
			items.addEventListener('scroll', window.Magic.Widgets.Messenger.homeFrameScrolling);


			// Start new conversation
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_card_conversations_start');
			// Set event listener
			items.addEventListener('click', window.Magic.Widgets.Messenger.openNewConversation);


			// Go back a stage
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_header_back');
			// Set event listener
			items.addEventListener('click', window.Magic.Widgets.Messenger.goBackFrame);


			// Go back a stage
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_input textarea');
			// Set event listener
			items.addEventListener('input', window.Magic.Widgets.Messenger.expandMessageArea);


			// Go back a stage
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_send');
			// Set event listener
			items.addEventListener('click', window.Magic.Widgets.Messenger.sendMessage);


			// Go back a stage
			var items = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_input textarea');
			// Set event listener
			items.addEventListener('keydown', window.Magic.Widgets.Messenger.handleMessageComposer);


		};
		this.debounceSend			= false;
		this.handleMessageComposer	= function(e) {
			// Check if enter key pressed
			if (e.which === 13) {
				// Prevent adding line
				e.preventDefault();
				// Set debounce
				window.Magic.Widgets.Messenger.debounceSend = true;
				// Send message
				window.Magic.Widgets.Messenger.sendMessage();
			}
		};
		this.sendMessage			= function(e) {
			// Get value of message
			var message = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_input textarea').value;
			// Check if message is not empty
			if (message.trim().length > 0) {
				// Reset message area
				window.Magic.Widgets.Messenger.resetMessageArea();
				// Add message to thread
				window.Magic.Widgets.Messenger.addMessageToThread(message, new Date(), false);
			}
		};
		this.addMessageToThread		= function(message, dateSent, fromAgent) {
			// Get template
			var temp = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages_messageTemplate').cloneNode(true);
			// Remove temp message class
			temp.classList.remove('MagicMessenger_board_main_body_main_messages_messageTemplate');
			// Add real message class
			temp.classList.add('MagicMessenger_board_main_body_main_messages_message');
			// Set from agent value
			temp.setAttribute('fromAgent', fromAgent);
			// Set message date
			temp.querySelector('.MagicMessenger_board_main_body_main_messages_message_content_date').innerHTML = 'TIME HERE';
			// Set message text
			temp.querySelector('.MagicMessenger_board_main_body_main_messages_message_content p').innerHTML = message;
			// Add animation to fade in
			temp.classList.add('MagicMessenger_board_main_body_main_messages_message_new');
			// Append new message
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').appendChild(temp);
			// Auto scroll to the newest message
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollTop = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollHeight;
		};
		this.expandMessageArea		= function(e) {
			// Check if debounce
			if (window.Magic.Widgets.Messenger.debounceSend === false) {
				// Set to 1px height so that scroll height can be calculated
				e.target.style.height = '1px';
				// Set new height
				e.target.style.height = (e.target.scrollHeight) + 'px';
			} else {
				// Reset message area
				window.Magic.Widgets.Messenger.resetMessageArea();
			}
		};
		this.resetMessageArea		= function(e) {
			// Clear input
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_input textarea').value = '';
			// Set reset height
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_input textarea').style.height = '62px';
			// Focus on input
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_input textarea').focus();
			// Auto scroll to the newest message
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollTop = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollHeight;
			// Set debounce
			window.Magic.Widgets.Messenger.debounceSend = false;
		};
		this.goBackFrame 			= function() {
			// Get existing frame
			var target = window.Magic.Widgets.Messenger.widget.getAttribute('frame');
			// Switch frame
			switch (target) {
				case 'conversation':
					// Set target frame
					target = 'home';
				break;
			}
			// Set frame
			window.Magic.Widgets.Messenger.widget.setAttribute('frame', target);
		};
		this.openNewConversation 	= function(e) {

			// Do stuff

			// Set frame
			window.Magic.Widgets.Messenger.setFrame('conversation');
			// Auto scroll to the newest message
			document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollTop = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollHeight;
		};
		this.homeFrameScrolling 	= function(e) {
			// Scroll opacity height range
			var range = 100;
			// Calculate range progress (and flip to be negative)
			var progress = ((e.target.scrollTop / range) - 1) * -1;
			// Check if scroll is past the range
			if (e.target.scrollTop >= range) {
				// Set opacity to 0
				document.querySelector('.MagicMessenger_board_main_header_details').style.opacity = '0';
				// Set close button background colour
				document.querySelector('.MagicMessenger_board_main_header_close').setAttribute('semi-transparent', 'true');
			} else {
				// Set opacity to the percentage of range taken
				document.querySelector('.MagicMessenger_board_main_header_details').style.opacity = progress;
				// Set close button background colour
				document.querySelector('.MagicMessenger_board_main_header_close').setAttribute('semi-transparent', 'false');
			}
		};
		// Toggle messenger widget
		this.toggleWidget 			= function() {
			// Check toggle status
			if (window.Magic.Widgets.Messenger.widget.getAttribute('open') === 'true') {
				// Close widget
				window.Magic.Widgets.Messenger.closeWidget();
			} else {
				// Open widget
				window.Magic.Widgets.Messenger.openWidget();
			}
		};
		// Close messenger widget
		this.closeWidget 			= function() {
			// Close widget
			window.Magic.Widgets.Messenger.widget.setAttribute('open', false);
		};
		// Open messenger widget
		this.openWidget 			= function() {
			// Close widget
			window.Magic.Widgets.Messenger.widget.setAttribute('open', true);
		};
		// Prepare request body
		this.encodeRequestBody			= function(body) {
			// Encode for data
			return JSON.stringify(body);
		};








		// Initialise widget
		this.init();
	}




	// Delay loading until all DOM content is loaded
	document.addEventListener('DOMContentLoaded', function() {
		console.log('DOMContentLoaded');
		// Initialise widget object
		window.Magic.Widgets.Messenger = new MagicMessenger();
	});


})();
