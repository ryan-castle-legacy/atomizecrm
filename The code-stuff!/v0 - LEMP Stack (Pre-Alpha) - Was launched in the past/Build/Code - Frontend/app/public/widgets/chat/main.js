// Get existing container object (or create one if null)
window.Atomize = window.Atomize || {};


// Test mode
window.AtomizeTestMode = false;


// // Store object to localStorage
// Storage.prototype.setObj = function(key, obj) {
// 	return this.setItem(key, JSON.stringify(obj));
// }
// // Retrieve item from localStorage
// Storage.prototype.getObj = function(key) {
// 	return JSON.parse(this.getItem(key));
// }


// Auto-calling of widget's methods
(function() {


	// Get existing container object for widgets (or create one if null)
	window.Atomize.Widgets = window.Atomize.Widgets || {};




	// Chat widget object
	function AtomizeChat() {

		// Set request URL
		this.requestBaseURL 		= 'http://18.130.253.250';
		// Storage of widget DOM object
		this.widget					= null;
		// Storage of active XMLHttpRequest objects
		this.openRequest 			= null;
		// Store cookie data
		this.cookies				= null;
		// Initialise widget
		this.init 					= function() {
			// Check if wrapper object was create
			if (typeof window.Atomize !== 'undefined') {
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
			} else {
				if (this.cookies[' ' + key] !== undefined) {
					// Return value
					return this.cookies[' ' + key];
				}
			}
			return null;
		};
		// Set cookie data
		this.setCookie				= function(key, value) {
			// Set widget object for cookies storage
			window.Atomize.Widgets.AtomizeChat.cookies[key] = value;
			// Get date now
			var nowPlusYear = new Date();
			// Add date in 1 year
			nowPlusYear.setDate(nowPlusYear.getDate() + 365); // Add a year
			// Set cookie
			document.cookie = key + '=' + value + ';expires=' + nowPlusYear;
		};
		// Turn cookie object to string
		this.stringifyCookies		= function() {
			// Container for stringified cookies
			var str = '';
			// Loop through cookies
			Object.keys(window.Atomize.Widgets.AtomizeChat.cookies).forEach(function (key) {
				console.log(key);
				str += key + '=' + window.Atomize.Widgets.AtomizeChat.cookies[key] + ';';
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
		// Storage for chat data
		this.chatData				= {};
		// Get widget DOM
		this.fetchWidget 			= function() {
			// Create the request
			this.request('/widget/chat/', this.getSession(), this.loadWidget);
		};
		// XMLHttpRequest handler
		this.request 				= function(target, data, callback) {
			// Create XML HTTP Request
			var request = new XMLHttpRequest();
			// Switch type of request
			switch (target) {
				case '/widget/chat/':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				case '/widget/chat/sendEmail/':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				case '/widget/chat/sendFeedback/':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				case '/widget/chat/new-client-message/':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				case '/widget/chat/getConvo/':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				default:
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
			}
			// Log the active request
			this.openRequest = target;
			// Set request header for sending data
			request.setRequestHeader('Content-Type', 'application/json');
			// Set as XML request header
			request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			// Send request to target
			request.send(this.encodeRequestBody(data));
			// Listen for response
			request.onreadystatechange = function() {
				// Check if readyState is 4 (done - finished processing)
				if (request.readyState === 4) {
					// Check if the request was successful
					if (request.status === 200) {
						// Check if response data is a JSON object
						if (JSON.parse(request.responseText)) {
							// Parse response text through callback
							return callback(JSON.parse(request.responseText));
						}
					}
					// Check if JSON
					if (window.Atomize.Widgets.AtomizeChat.isJsonString(request.responseText)) {
						// Print error
						return console.error(JSON.parse(request.responseText));
					} else {
						// Print error
						return console.error(request.responseText);
					}
				}
			}
		};
		// Check if JSON
		this.isJsonString			= function(str) {
			try {
				JSON.parse(str);
			} catch (e) {
				return false;
			}
			return true;
		};
		// Store widget code
		this.holdWidget				= null;
		// Build widget CSS
		this.loadWidget				= function(data) {
			if (window.AtomizeTestMode == true) {
				console.log(data);
			}
			// Store widget code in holder
			window.Atomize.Widgets.AtomizeChat.holdWidget = data;
			// Create link tag for the CSS
			var link = document.createElement('link');
			// Add attribute for rel
			link.setAttribute('rel', 'stylesheet');
			// Add attribute for type
			link.setAttribute('type', 'text/css');
			// Add onLoad function
			link.onload = function() {
				// Build widget
				window.Atomize.Widgets.AtomizeChat.buildWidget(window.Atomize.Widgets.AtomizeChat.holdWidget);
			};
			// Set href attribute
			link.setAttribute('href', window.Atomize.Widgets.AtomizeChat.requestBaseURL + '/widgets/chat/main.css');
			// Add tag to head
			document.getElementsByTagName('head')[0].appendChild(link);
		};
		// Storage
		this.widgetData				= null;
		// Build widget with DOM
		this.buildWidget			= function(data) {
			// Check if not allowed
			if (data.notallowed == true) {
				// Return error
				console.error('Your website is not permitted to display the AtomizeCRM chat widget. Please configure your chat settings if you wish you use AtomizeCRM at this URL.');
				return;
			}
			// Store data
			window.Atomize.Widgets.AtomizeChat.widgetData = data;
			// Create Messenger container
			var container = document.createElement('div');
			// Add data to container
			container.setAttribute('id', 'AtomizeChat');
			// Add widget from server
			container.innerHTML = data.widget;
			// Add widget to body
			document.body.appendChild(container);
			// Set widget in object
			window.Atomize.Widgets.AtomizeChat.widget = document.querySelector('#AtomizeChat');
			// Set event listeners
			window.Atomize.Widgets.AtomizeChat.setEventListeners();
			// Display widget
			window.Atomize.Widgets.AtomizeChat.displayWidget(1000);
			// Set data
			window.Atomize.Widgets.AtomizeChat.setChatData(data);
			// Update instanceToken cookie
			window.Atomize.Widgets.AtomizeChat.setCookie('instanceToken', data.data.instanceToken);
			// Set live refresh
			window.Atomize.Widgets.AtomizeChat.setMessageLoadInterval(data.data.liveRefreshRate, data.data.instanceToken);
			// Update live items
			window.Atomize.Widgets.AtomizeChat.updateLiveItems(false);
			// Contain alert sound
			window.Atomize.Widgets.AtomizeChat.alertSound = new Audio(window.Atomize.Widgets.AtomizeChat.requestBaseURL + '/audio/pop.mp3');
		};
		// Set event listeners
		this.setEventListeners 		= function() {


			// Check if there are any trigger links for new chats
			if (document.querySelector('[href="[openatomizenewchat]"]')) {
				// Go to report frame
				var items = document.querySelectorAll('[href="[openatomizenewchat]"]');
				// Loop through triggers
				for (var i = 0; i < items.length; i++) {
					// Add event listener
					items[i].addEventListener('click', function(e) {
						// Prevent default
						e.preventDefault();
						// Open widget
						window.Atomize.Widgets.AtomizeChat.openWidget(e, function(e) {
							// Delay
							setTimeout(function() {
								// Create new conversation
								window.Atomize.Widgets.AtomizeChat.startNewConversation();
								// Delay
								setTimeout(function() {
									// Set placeholder message
									document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea').value = e.target.getAttribute('data');
									// Expand message area
									window.Atomize.Widgets.AtomizeChat.defaultExpandMessageArea();
								}, 1000);
							}, 500);
						});
					});
				}
			}


			// Get items
			var items = document.querySelector('#AtomizeChat .AtomizeChat_toggle');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.toggleWidget);


			// Get items
			var items = document.querySelector('#AtomizeChat .AtomizeChat_close');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.toggleWidget);


			// Go back a stage
			var items = document.querySelector('#AtomizeChat .AtomizeChat_back');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.goBackFrame);


			// Get home frame scrolling
			var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame[frame="home"]');
			// Set event listener
			items.addEventListener('scroll', window.Atomize.Widgets.AtomizeChat.homeFrameScrolling);


			if (document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card[intent="chat"]')) {
				// Go to chat frame
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card[intent="chat"]');
				// Set event listener
				items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.startNewConversation);
			}


			// Go to chat frame
			var items = document.querySelector('#AtomizeChat .AtomizeChat_start_chat');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.startNewConversation);


			// Send email address
			var items = document.querySelector('#AtomizeChat .AtomizeChat_email_send');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.sendEmailAddress);


			// Send message
			var items = document.querySelector('#AtomizeChat .AtomizeChat_send');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.sendMessageInput);


			// Expand message area
			var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea');
			// Set event listener
			items.addEventListener('input', window.Atomize.Widgets.AtomizeChat.expandMessageArea);


			// Handle message composer
			var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea');
			// Set event listener
			items.addEventListener('keydown', window.Atomize.Widgets.AtomizeChat.handleMessageComposer);


			// Handle message composer
			var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer_email input');
			// Set event listener
			items.addEventListener('keydown', window.Atomize.Widgets.AtomizeChat.handleMessageEmail);


			// Check if feedback widget added
			if (document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card[intent="feedback"]')) {
				// Go to report frame
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackOptions_option[type="issue"]');
				// Set event listener
				items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.goToReportIssueScreen);


				// Go to idea frame
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackOptions_option[type="idea"]');
				// Set event listener
				items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.goToShareIdeaScreen);


				// Go to idea frame
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_back');
				// Set event listener
				items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.goBackToFeedbackHome);


				// Go to idea frame
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackSuccess_reset');
				// Set event listener
				items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.goBackToFeedbackHome);


				// Check if feedback is empty
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_input');
				// Set event listener
				items.addEventListener('input', window.Atomize.Widgets.AtomizeChat.checkIfValidFeedback);


				// Submit feedback
				var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_submit');
				// Set event listener
				items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.submitFeedback);
			}


			// Check if there are any trigger links
			if (document.querySelector('[href="[openatomize]"]')) {
				// Go to report frame
				var items = document.querySelectorAll('[href="[openatomize]"]');
				// Loop through triggers
				for (var i = 0; i < items.length; i++) {
					// Add event listener
					items[i].addEventListener('click', function(e) {
						// Prevent default
						e.preventDefault();
						// Open widget
						window.Atomize.Widgets.AtomizeChat.openWidget(false, false);
					});
				}
			}


			// Check if there are any previously created chats
			if (document.querySelector('.AtomizeChat_board_frame_card[intent="conversation"]')) {
				// Get convos
				var items = document.querySelectorAll('.AtomizeChat_board_frame_card[intent="conversation"]');
				// Loop through convos
				for (var i = 0; i < items.length; i++) {
					// Add event listener
					items[i].addEventListener('click', window.Atomize.Widgets.AtomizeChat.openExistingChat);
				}
			}


			// Go to Atomize credit
			var items = document.querySelector('#AtomizeChat .AtomizeChat_board_home_credit');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.sendToCreditPage);


			// Go to Atomize credit
			var items = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_convo_title a');
			// Set event listener
			items.addEventListener('click', window.Atomize.Widgets.AtomizeChat.goToAllConvos);

		};
		// Get all conversations
		this.goToAllConvos			= function() {
			// Send to loading
			document.querySelector('#AtomizeChat .AtomizeChat_board').setAttribute('frame', 'loading');
			// Set opacity of header to 0
			document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = '0';
			// Get latest live items
			window.Atomize.Widgets.AtomizeChat.updateLiveItems(window.Atomize.Widgets.AtomizeChat.displayAllConvos);
		};
		// Display all convos
		this.displayAllConvos		= function(data) {
			// Update all convo list
			window.Atomize.Widgets.AtomizeChat.updateAllConvosList(data);
			// Send to loading
			document.querySelector('#AtomizeChat .AtomizeChat_board').setAttribute('frame', 'allConvos');
		};
		// Update all convo list
		this.updateAllConvosList	= function(data) {
			// Get template
			var template = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chats_message_temp');
			var chat;
			// Clear items
			window.Atomize.Widgets.AtomizeChat.clearElementsBySelector('.AtomizeChat_board_frame_chats_message');
			// Loop through chats
			for (var i = 0; i < data.allChats.length; i++) {
				// Create element for chat
				chat = template.cloneNode(true);
				// Remove temp chat class
				chat.classList.remove('AtomizeChat_board_frame_chats_message_temp');
				// Add real chat class
				chat.classList.add('AtomizeChat_board_frame_chats_message');
				// Set the chat instance
				chat.setAttribute('instance', data.allChats[i].token);
				// Set name text
				chat.querySelector('.AtomizeChat_board_frame_chats_messages_name').innerHTML = data.allChats[i].agentDetails.name;
				// Set preview text
				chat.querySelector('.AtomizeChat_board_frame_chats_messages_preview').innerHTML = data.allChats[i].agentDetails.mostRecentMessage.message;
				// Get date now
				var date = new Date(data.allChats[i].agentDetails.mostRecentMessage.timesent * 1000);
				// Set time text
				chat.querySelector('.AtomizeChat_board_frame_chats_messages_time').innerHTML = window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getDate()) + '/' + window.Atomize.Widgets.AtomizeChat.addLeadingZero((parseInt(date.getMonth()) + 1)) + '/' + date.getFullYear() + ' at ' + date.getHours() + ':' + window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getMinutes());
				// Check if user has logo or icon
				if (data.allChats[i].agentDetails.iconSRC == '') {
					// Clear image
					chat.querySelector('.AtomizeChat_board_frame_chats_messages_avatar span').style.backgroundImage = 'none';
					// Set text
					chat.querySelector('.AtomizeChat_board_frame_chats_messages_avatar span').innerHTML = data.allChats[i].agentDetails.iconAlt
				} else {
					// Set image
					chat.querySelector('.AtomizeChat_board_frame_chats_messages_avatar span').style.backgroundImage = 'url("' + data.allChats[i].agentDetails.iconSRC + '")';
					// Clear text
					chat.querySelector('.AtomizeChat_board_frame_chats_messages_avatar span').innerHTML = '';
				}
				// Append new chat
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chats_messages_list').appendChild(chat);
				// Add event listener
				chat.addEventListener('click', window.Atomize.Widgets.AtomizeChat.openExistingChat);
			}
		};
		// Clear elements by class
		this.clearElementsBySelector = function(selector) {
			// Get elements
			var elements = document.querySelectorAll(selector);
			// Check if elements exist
			if (elements) {
				// Loop through elements
				for (var i = 0; i < elements.length; i++) {
					// Delete element
					elements[i].remove();
				}
			}
		},
		// Open existing chat
		this.openExistingChat		= function(e) {
			// Check if convo list
			if (e.target.closest('.AtomizeChat_board_frame_chats_message')) {
				// Get convo
				var convo = e.target.closest('.AtomizeChat_board_frame_chats_message');
			} else {
				// Get convo
				var convo = e.target.closest('.AtomizeChat_board_frame_card[intent="conversation"]');
			}
			// Open convo
			window.Atomize.Widgets.AtomizeChat.openConversation(window.Atomize.Widgets.AtomizeChat.chatData, false, convo.getAttribute('instance'));
		};
		// Check if debounce
		this.debounceSendFeedback 	= false;
		// Submit feedback
		this.submitFeedback			= function() {
			// Check if debounce
			if (!window.Atomize.Widgets.AtomizeChat.debounceSendFeedback) {
				// Disable button
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_submit').setAttribute('disabled', true);
				// Disable input
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_input').setAttribute('disabled', true);
				// Disable form
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm').setAttribute('submitted', true);
				// Get context
				var context = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm').getAttribute('type');
				// Get feedback
				var feedback = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_input').value;
				// Submit feedback via AJAX
				window.Atomize.Widgets.AtomizeChat.submitFeedbackEmail(context, feedback);
			}
		},
		// Submit feedback
		this.submitFeedbackEmail	= function(context, feedback) {
			// Check if debounce
			if (window.Atomize.Widgets.AtomizeChat.debounceSendFeedback == false) {
				// Set debounceSendFeedback
				window.Atomize.Widgets.AtomizeChat.debounceSendFeedback = true;
				// Create data object
				var data = {
					context:	context,
					feedback:	feedback,
				};
				// Create the request
				this.request('/widget/chat/sendFeedback/', data, function(data) {
					// Reset debounce
					window.Atomize.Widgets.AtomizeChat.debounceSendEmail = false;
					// Block
					var block = document.querySelector('.AtomizeChat_board_frame_card[intent="feedback"]');
					// Transition screen
					block.setAttribute('status', 'changingback');
					// Delay transition screen
					setTimeout(function() {
						// Transition screen
						block.setAttribute('status', 'success');
						// Clear input
						document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_input').value = '';
					}, 500);
				});
			}
		};
		// Check if feedback is empty
		this.checkIfValidFeedback	= function() {
			// Get input
			var input = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_input');
			// Check if empty
			if (input.value.trim() == '') {
				// Set submit as invalid
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_submit').setAttribute('disabled', true);
			} else {
				// Set submit as valid
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_submit').removeAttribute('disabled');
			}
		},
		// Go to feedback home
		this.goBackToFeedbackHome	= function() {
			// Block
			var block = document.querySelector('.AtomizeChat_board_frame_card[intent="feedback"]');
			// Transition screen
			block.setAttribute('status', 'changingback');
			// Delay transition screen
			setTimeout(function() {
				// Enable input
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_input').removeAttribute('disabled');
				// Enable form
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm').setAttribute('submitted', false);
				// Set debounce
				window.Atomize.Widgets.AtomizeChat.debounceSendFeedback = false;
				// Transition screen
				block.setAttribute('status', 'cover');
				// Clear input
				document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_input').value = '';
			}, 500);
		},
		// Report issue screen
		this.goToReportIssueScreen	= function() {
			// Block
			var block = document.querySelector('.AtomizeChat_board_frame_card[intent="feedback"]');
			// Get form
			var form = document.querySelector('.AtomizeChat_board_frame_card_feedbackForm');
			// Transition screen
			block.setAttribute('status', 'changing');
			// Set title
			document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_title').innerHTML = 'Report an issue';
			// Set placeholder
			document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_input').setAttribute('placeholder', 'I noticed that...');
			// Delay transition screen
			setTimeout(function() {
				// Set submit as invalid
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_submit').setAttribute('disabled', true);
				// Set form details
				form.setAttribute('type', 'issue');
				// Transition screen
				block.setAttribute('status', 'form');
				// Focus on textarea
				document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_input').focus();
			}, 500);
		};
		// Share idea screen
		this.goToShareIdeaScreen	= function() {
			// Block
			var block = document.querySelector('.AtomizeChat_board_frame_card[intent="feedback"]');
			// Get form
			var form = document.querySelector('.AtomizeChat_board_frame_card_feedbackForm');
			// Transition screen
			block.setAttribute('status', 'changing');
			// Set title
			document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_title').innerHTML = 'Share an idea';
			// Set placeholder
			document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_input').setAttribute('placeholder', 'I would love...');
			// Delay transition screen
			setTimeout(function() {
				// Set submit as invalid
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_feedbackForm_submit').setAttribute('disabled', true);
				// Set form details
				form.setAttribute('type', 'idea');
				// Transition screen
				block.setAttribute('status', 'form');
				// Focus on textarea
				document.querySelector('.AtomizeChat_board_frame_card_feedbackForm_input').focus();
			}, 500);
		};
		// Send to Atomize credit page
		this.sendToCreditPage		= function() {
			// Send to Atomize credit page
			window.open('https://www.atomizecrm.com/widget-credit', '_blank');
		};
		// Email message debounce
		this.debounceSendEmail		= false;
		// Message debounce
		this.debounceSend			= false;
		// Expand message area
		this.expandMessageArea		= function(e) {
			// Check if debounce
			if (window.Atomize.Widgets.AtomizeChat.debounceSend === false) {
				// Set to 1px height so that scroll height can be calculated
				e.target.style.height = '1px';
				// Set new height of textarea
				e.target.style.height = (e.target.scrollHeight) + 'px';
				// Set new height of composer
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer').style.height = (e.target.scrollHeight) + 'px';
			} else {
				// Reset message area
				window.Atomize.Widgets.AtomizeChat.resetMessageArea();
			}
		};
		// Expand message area
		this.defaultExpandMessageArea		= function() {
			// Check if debounce
			if (window.Atomize.Widgets.AtomizeChat.debounceSend === false) {
				// Get element
				var e = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea');
				// Set to 1px height so that scroll height can be calculated
				e.style.height = '1px';
				// Set new height of textarea
				e.style.height = (e.scrollHeight) + 'px';
				// Set new height of composer
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer').style.height = (e.scrollHeight) + 'px';
			} else {
				// Reset message area
				window.Atomize.Widgets.AtomizeChat.resetMessageArea();
			}
		};
		// Current convo instance token message composer
		this.currentConvo	= null;
		// Handle message composer
		this.handleMessageComposer	= function(e) {
			// Get message text
			var text = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea').value;
			// Check if enter key pressed
			if (e.which === 13 && text.trim().length > 0) {
				// Prevent adding line
				e.preventDefault();
				// Set debounce
				window.Atomize.Widgets.AtomizeChat.debounceSend = true;
				// Send message
				window.Atomize.Widgets.AtomizeChat.sendMessage(text, 'client');

				// TEMP
				// TEMP
				// TEMP
				// TEMP

				// window.Atomize.Widgets.AtomizeChat.sendEmail();

				// TEMP
				// TEMP
				// TEMP
				// TEMP
			}
		};
		// Handle message composer with email
		this.handleMessageEmail	= function(e) {
			// Get message text
			var text = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer_email input').value;
			// Check if enter key pressed
			if (e.which === 13 && text.trim().length > 0) {
				// Prevent adding line
				e.preventDefault();
				// Set debounce
				window.Atomize.Widgets.AtomizeChat.debounceSend = true;
				// Send message
				window.Atomize.Widgets.AtomizeChat.sendMessage(text, 'client');
				// Validate email input
				window.Atomize.Widgets.AtomizeChat.validateEmail(text);
			}
		};
		// Reset message composer
		this.resetMessageArea		= function() {
			// Clear inputs
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer_email input').value = '';
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea').value = '';

			// Set reset height
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea').style.height = '62px';
			// Focus on input
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea').focus();
			// Set container height
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer').style.height = '62px';




			// // Auto scroll to the newest message
			// document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollTop = document.querySelector('#MagicMessenger .MagicMessenger_board_main_body_main_messages').scrollHeight;


			// Set debounce
			window.Atomize.Widgets.AtomizeChat.debounceSend = false;
		};
		// // Send message with email address
		// this.sendEmailAddress		= function() {
		// 	// Get message text
		// 	var text = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer_email input').value.toLowerCase();
		// 	// Check if text was not empty
		// 	if (text.trim().length > 0) {
		// 		// Send message
		// 		window.Atomize.Widgets.AtomizeChat.sendMessage(text, 'client');
		// 		// Validate email input
		// 		window.Atomize.Widgets.AtomizeChat.validateEmail(text);
		// 	}
		// };
		// Validate email
		this.validateEmail			= function(text) {
			// Check if email address was valid
			if (window.Atomize.Widgets.AtomizeChat.isEmailValid(text) == true) {
				// Send message asking the query
				setTimeout(function() {
					// Instruction
					window.Atomize.Widgets.AtomizeChat.sendMessage(window.Atomize.Widgets.AtomizeChat.chatData.outOfOfficeDetailsRequest, 'agent');
					// Switch input of chat
					document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages').setAttribute('composer', 'normal');
				}, 1000);
			} else {
				// Send message asking for valid email
				setTimeout(function() {
					// Instruction
					window.Atomize.Widgets.AtomizeChat.sendMessage('Please enter a valid email address.', 'agent');
				}, 1000);
			}
		};
		// Send message
		this.sendMessageInput		= function() {
			// Get message text
			var text = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_composer textarea').value;
			// Check if text was not empty
			if (text.trim().length > 0) {
				// Send message
				window.Atomize.Widgets.AtomizeChat.sendMessage(text, 'client');
			}
		};
		// // Send email alert
		// this.sendEmail			= function() {
		// 	// Check if debounce
		// 	if (window.Atomize.Widgets.AtomizeChat.debounceSendEmail == false) {
		// 		// Get messages
		// 		var msgs = document.querySelectorAll('.AtomizeChat_board_frame_chat_messages_list_message[from="client"] .AtomizeChat_board_frame_chat_messages_list_message_main');
		// 		// Create data object
		// 		var data = {
		// 			clientEmail:	msgs[msgs.length - 2].innerText,
		// 			message:		msgs[msgs.length - 1].innerText,
		// 		};
		// 		// Create the request
		// 		this.request('/widget/chat/sendEmail/', data, function(data) {
		// 			// console.log(data);
		// 		});
		// 		// Set debounce
		// 		window.Atomize.Widgets.AtomizeChat.debounceSendEmail = true;
		// 	}
		// };
		// Check if email is valid
		this.isEmailValid			= function(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(String(email).toLowerCase());
		};
		// Set chatData object
		this.setChatData			= function(data) {
			// Set data
			window.Atomize.Widgets.AtomizeChat.chatData = data.data;
		};
		// Start new conversation
		this.startNewConversation	= function() {
			// Open convo
			window.Atomize.Widgets.AtomizeChat.openConversation(window.Atomize.Widgets.AtomizeChat.chatData, true, false);
		};
		// Open the conversation (chat) frame
		this.openConversation		= function(chat, newChat, chatToken) {
			// Set frame
			document.querySelector('#AtomizeChat .AtomizeChat_board').setAttribute('frame', 'loading');
			// Set opacity of header to 0
			document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = '0';
			// Get existing messages (that need to be cleared)
			oldMessages = document.querySelectorAll('#AtomizeChat .AtomizeChat_board_frame_chat_messages_list_message');
			// Clear chat frame
			for (var i = 0; i < oldMessages.length; i++) {
				// Delete messages
				oldMessages[i].remove();
			};
			// Check if fresh chat and that AI will send first message
			if (newChat == true) {
				// Check if outOfOffice was true
				if (chat.outOfOffice == true) {
					// Send out-of-office message
					window.Atomize.Widgets.AtomizeChat.sendMessage(chat.outOfOfficeMessage, 'agent');
					// Check if needs to capture email
					if (true) {
						// Send email request message
						window.Atomize.Widgets.AtomizeChat.sendMessage(chat.outOfOfficeEmailRequest, 'agent');
						// Set composer
						document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages').setAttribute('composer', 'email');
					}
				} else {
					setTimeout(function() {
						// Set first message
						window.Atomize.Widgets.AtomizeChat.sendMessage(chat.aiFirstMessage, 'agent');
					}, 750);
				}
				// Get composer status
				var composer = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages').getAttribute('composer');
				// Check composer
				if (composer == 'normal') {
					// Focus on composer
					document.querySelector('.AtomizeChat_board_frame_chat_messages_composer textarea').focus();
				} else {
					// Focus on composer
					document.querySelector('.AtomizeChat_board_frame_chat_messages_composer_email input').focus();
				}
				// Set frame
				document.querySelector('#AtomizeChat .AtomizeChat_board').setAttribute('frame', 'chat');
				// Set opacity of header to 0
				document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = '0';
			} else {

				// Set current convo
				window.Atomize.Widgets.AtomizeChat.currentConvo = chatToken;
				// Get chat data
				this.request('/widget/chat/getConvo/', {
					widgetInstance: 	window.Atomize.Widgets.AtomizeChat.widgetData.data.instanceToken,
					chatInstance: 		window.Atomize.Widgets.AtomizeChat.currentConvo,
				}, function(data) {
					// Set agent/company details (depending on whether the agent is a bot or a person)
					window.Atomize.Widgets.AtomizeChat.setAgentDetails(data.agent);
					// Load existing messages
					window.Atomize.Widgets.AtomizeChat.loadMessages(data.messages);
					// Work out composer status
					var composer = 'normal';
					// Set composer status
					document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages').setAttribute('composer', composer);
					// Check composer
					if (composer == 'normal') {
						// Focus on composer
						document.querySelector('.AtomizeChat_board_frame_chat_messages_composer textarea').focus();
					} else {
						// Focus on composer
						document.querySelector('.AtomizeChat_board_frame_chat_messages_composer_email input').focus();
					}
					// Set frame to chat
					document.querySelector('#AtomizeChat .AtomizeChat_board').setAttribute('frame', 'chat');
					// Set opacity of header to 0
					document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = '0';
				});
			}
		};
		// Set agent details
		this.setAgentDetails		= function(agent) {
			// Get details block
			var details = document.querySelector('.AtomizeChat_board_frame_chat_header');
			// Check if logo is emoji
			if (agent.iconSRC == '' || agent.iconSRC == null) {
				// Set logo to blank
				details.querySelector('.AtomizeChat_board_frame_chat_header_avatar').style.backgroundImage = 'none';
				// Set emoji
				details.querySelector('.AtomizeChat_board_frame_chat_header_avatar').innerHTML = agent.iconAlt;
			} else {
				// Set logo to blank
				details.querySelector('.AtomizeChat_board_frame_chat_header_avatar').style.backgroundImage = 'url("' + agent.iconSRC + '")';
				// Set emoji
				details.querySelector('.AtomizeChat_board_frame_chat_header_avatar').innerHTML = '';
			}
			// Set the name
			details.querySelector('.AtomizeChat_board_frame_chat_header_name').innerHTML = agent.name;
			// Set the role
			details.querySelector('.AtomizeChat_board_frame_chat_header_role').innerHTML = agent.role;
		};
		// Load messages every X seconds
		this.setMessageLoadInterval	= function(refreshRate, widgetInstance) {
			// Set interval
			setInterval(function() {
				// Update live items
				window.Atomize.Widgets.AtomizeChat.updateLiveItems(false);
			}, refreshRate);
		};
		// Update live items
		this.updateLiveItems		= function(callback) {
			// Check if already waiting for response
			if (window.Atomize.Widgets.AtomizeChat.liveRefreshDebounce == false) {
				// Set debounce
				window.Atomize.Widgets.AtomizeChat.liveRefreshDebounce = true;
				// Send for update
				window.Atomize.Widgets.AtomizeChat.getLiveItems(callback);
			}
		};
		// Hold last load time
		this.lastRefreshed			= null;
		// Live refresh debounce
		this.liveRefreshDebounce	= false;
		// Hold callback
		this.getLiveCallback		= false;
		// Get live items
		this.getLiveItems			= function(callback) {
			// Hold callback
			window.Atomize.Widgets.AtomizeChat.getLiveCallback = callback;
			// Send request
			this.request('/widget/chat/refreshLiveItems/', {
				widgetInstance: 	window.Atomize.Widgets.AtomizeChat.widgetData.data.instanceToken,
				lastRefreshed: 		window.Atomize.Widgets.AtomizeChat.lastRefreshed,
			}, window.Atomize.Widgets.AtomizeChat.refreshLiveItems);
		};
		// Refresh prev conversations listing
		this.refreshPrevChats			= function(data) {
			// Get template
			var template = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_temp');
			var chat;
			// Clear items
			window.Atomize.Widgets.AtomizeChat.clearElementsBySelector('.AtomizeChat_board_frame_card[intent="conversation"]');
			// Loop through chats
			for (var i = 0; i < 3; i++) {
				// Check if item doesn't exist
				if (!data.allChats[i]) {
					// Break loop
					break;
				}
				// Create element for chat
				chat = template.cloneNode(true);
				// Remove temp chat class
				chat.classList.remove('AtomizeChat_board_frame_card_temp');
				// Add real chat class
				chat.classList.add('AtomizeChat_board_frame_card');
				// Set the chat instance
				chat.setAttribute('instance', data.allChats[i].token);
				// Set name text
				chat.querySelector('.AtomizeChat_board_frame_card_name').innerHTML = data.allChats[i].agentDetails.name;
				// Set preview text
				chat.querySelector('.AtomizeChat_board_frame_card_preview').innerHTML = data.allChats[i].agentDetails.mostRecentMessage.message;
				// Get date now
				var date = new Date(data.allChats[i].agentDetails.mostRecentMessage.timesent * 1000);
				// Set time text
				chat.querySelector('.AtomizeChat_board_frame_card_time').innerHTML = window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getDate()) + '/' + window.Atomize.Widgets.AtomizeChat.addLeadingZero((parseInt(date.getMonth()) + 1)) + '/' + date.getFullYear() + ' at ' + date.getHours() + ':' + window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getMinutes());
				// Check if user has logo or icon
				if (data.allChats[i].agentDetails.iconSRC == '') {
					// Clear image
					chat.querySelector('.AtomizeChat_board_frame_card_avatar span').style.backgroundImage = 'none';
					// Set text
					chat.querySelector('.AtomizeChat_board_frame_card_avatar span').innerHTML = data.allChats[i].agentDetails.iconAlt
				} else {
					// Set image
					chat.querySelector('.AtomizeChat_board_frame_card_avatar span').style.backgroundImage = 'url("' + data.allChats[i].agentDetails.iconSRC + '")';
					// Clear text
					chat.querySelector('.AtomizeChat_board_frame_card_avatar span').innerHTML = '';
				}
				// Append new chat
				document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card').insertBefore(chat, document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_temp'));
				// Add event listener
				chat.addEventListener('click', window.Atomize.Widgets.AtomizeChat.openExistingChat);
			}
		},
		// Refresh live items
		this.refreshLiveItems			= function(data) {
			// Check if there is some chat data to update
			if (data.chatData && data.chatData.length > 0) {
				// Reset prev conversation listing
				window.Atomize.Widgets.AtomizeChat.refreshPrevChats(data);
				// Go through all chats
				for (var i = 0; i < data.chatData.length; i++) {
					// If client is on this chat currently
					if (window.Atomize.Widgets.AtomizeChat.currentConvo == data.chatData[i].token) {
						// Counter
						var notifs = 0;
						// Go through new messages
						for (var j = data.chatData[i].messages.length - 1; j >= 0; j--) {
							// Check if agent message
							if (data.chatData[i].messages[j].from == 'agent') {
								// Check if message hasn't been displayed already
								if (!document.querySelector('.AtomizeChat_board_frame_chat_messages_list_message[token="' + data.chatData[i].messages[j].id + '"]')) {
									// Add message to thread
									window.Atomize.Widgets.AtomizeChat.addMessageToThread(data.chatData[i].messages[j].id, data.chatData[i].messages[j].message, data.chatData[i].messages[j].from, data.chatData[i].messages[j].timesent, true);
									// Add to counter
									notifs++;
								}
							}
						}
						// Change agent info
						window.Atomize.Widgets.AtomizeChat.setAgentDetails(data.chatData[i].agent);
						// Check if notifications and if widget is not open
						if (notifs > 0 && !document.querySelector('#AtomizeChat[open="true"]')) {
							// As user is not focussed on chat, play ping sound to alert of new message
							window.Atomize.Widgets.AtomizeChat.alertSound_play();
						}
					} else {
						// Check if loaded
						if (window.Atomize.Widgets.AtomizeChat.widget.getAttribute('loaded')) {
							// Counter
							var notifs = 0;
							// Go through new messages
							for (var j = data.chatData[i].messages.length - 1; j >= 0; j--) {
								// Check if agent message
								if (data.chatData[i].messages[j].from == 'agent') {
									// As user is not focussed on chat, play ping sound to alert of new message
									window.Atomize.Widgets.AtomizeChat.alertSound_play();
									// Prevent page playing sound more than once
									break;
								}
							}
						}
					}

					// Set notification counter

				}
			}
			// Set last load time (most recent message time)
			window.Atomize.Widgets.AtomizeChat.lastRefreshed = data.lastRefreshed;
			// Set debounce
			window.Atomize.Widgets.AtomizeChat.liveRefreshDebounce = false;
			// Check if callback is set
			if (window.Atomize.Widgets.AtomizeChat.getLiveCallback != false) {
				// Do callback
				window.Atomize.Widgets.AtomizeChat.getLiveCallback(data);
			}
			// Clear callback
			window.Atomize.Widgets.AtomizeChat.getLiveCallback = false;
		};
		// Contain alert sound
		this.alertSound 			= null;
		// Play alert sound
		this.alertSound_play 			= function() {
			try {
				window.Atomize.Widgets.AtomizeChat.alertSound.play();
			} catch (e) {
			}
		};
		// Cancel alert sound
		this.alertSound_cancel 			= function() {
			window.Atomize.Widgets.AtomizeChat.alertSound.pause();
			window.Atomize.Widgets.AtomizeChat.alertSound.currentTime = 0;
		};
		// Load messages
		this.loadMessages			= function(messages) {
			// Loop through messages
			for (var i = messages.length - 1; i >= 0; i--) {
				// Add message to thread
				window.Atomize.Widgets.AtomizeChat.addMessageToThread(messages[i].id, messages[i].message, messages[i].from, messages[i].timesent, true);
			}
		};
		// Send message to chat
		this.sendMessage			= function(message, from) {
			// Add message to thread
			window.Atomize.Widgets.AtomizeChat.addMessageToThread('', message, from, new Date(), false);
			// Reset message area
			window.Atomize.Widgets.AtomizeChat.resetMessageArea();
		};
		// Add message to thread
		this.addMessageToThread		= function(token, message, from, dateSent, loaded) {
			// Get template
			var temp = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_list_message_temp').cloneNode(true);
			// Remove temp message class
			temp.classList.remove('AtomizeChat_board_frame_chat_messages_list_message_temp');
			// Add real message class
			temp.classList.add('AtomizeChat_board_frame_chat_messages_list_message');
			// Set from agent value
			temp.setAttribute('from', from);
			// Set token
			temp.setAttribute('token', token);
			// Get colour
			var colour = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_list').getAttribute('uiColor');
			// Check if loaded from DB
			if (loaded) {
				// Remove sending class
				temp.classList.remove('sending');
				// Get date now
				var date = new Date(dateSent * 1000);
				// Set message date
				temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_details_time').innerHTML = + date.getHours() + ':' + window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getMinutes());
				// Check if from not from agent
				if (from == 'client') {
					// Remove avatar from message
					temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_main_avatar').remove();
					// Set color
					temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_main').style.backgroundColor = colour;
				}
			} else {
				// Check if from agent
				if (from == 'agent') {
					// Remove sending class
					temp.classList.remove('sending');
					// Get date now
					var date = new Date();
					// Set message date
					temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_details_time').innerHTML = + date.getHours() + ':' + window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getMinutes());
				} else {
					// Remove avatar from message
					temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_main_avatar').remove();
					// Set color
					temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_main').style.backgroundColor = colour;


					// Send message
					this.request('/widget/chat/new-client-message/', {
						widgetInstance: 	window.Atomize.Widgets.AtomizeChat.widgetData.data.instanceToken,
						chatInstance: 		window.Atomize.Widgets.AtomizeChat.currentConvo,
						message: 			message,
					}, function(data) {

						if (window.AtomizeTestMode == true) {
							console.log(data);
						}

						// Check if hidden element exists
						if (document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_hidden')) {
							// Get prev chat list card (rather than start chat only)
							var convoList = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_hidden');
							// Add class
							convoList.classList.add('AtomizeChat_board_frame_card');
							// Remove temp class
							convoList.classList.remove('AtomizeChat_board_frame_card_hidden');
						}
						// Check if new convo trigger exists
						if (document.querySelector('.AtomizeChat_board_frame_card[intent="chat"]')) {
							// Remove trigger for new convo
							document.querySelector('.AtomizeChat_board_frame_card[intent="chat"]').remove();
						}

						// Check if item exists already
						if (document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card[instance="' + data.chatInstance + '"]')) {
							// Get existing convo card
							var convo = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card[instance="' + data.chatInstance + '"]');
							// Update most recent message
							convo.querySelector('.AtomizeChat_board_frame_card_preview').innerHTML = data.messageText;
						} else {
							// Add new convo to list
							var convo = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_temp[intent="conversation"]').cloneNode(true);
							// Add chat instance token to temp
							convo.setAttribute('instance', data.chatInstance);
							// Add real message class
							convo.classList.add('AtomizeChat_board_frame_card');
							// Remove temp message class
							convo.classList.remove('AtomizeChat_board_frame_card_temp');
							// Add details
							convo.querySelector('.AtomizeChat_board_frame_card_name').innerHTML = data.name;
							convo.querySelector('.AtomizeChat_board_frame_card_time').innerHTML = data.date;
							convo.querySelector('.AtomizeChat_board_frame_card_preview').innerHTML = data.messageText;
							// Check if user has logo or icon
							if (window.Atomize.Widgets.AtomizeChat.chatData.iconSRC == '') {
								// Clear image
								convo.querySelector('.AtomizeChat_board_frame_card_avatar span').style.backgroundImage = 'none';
								// Set text
								convo.querySelector('.AtomizeChat_board_frame_card_avatar span').innerHTML = window.Atomize.Widgets.AtomizeChat.chatData.iconAlt
							} else {
								// Set image
								convo.querySelector('.AtomizeChat_board_frame_card_avatar span').style.backgroundImage = 'url("' + window.Atomize.Widgets.AtomizeChat.chatData.iconSRC + '")';
								// Clear text
								convo.querySelector('.AtomizeChat_board_frame_card_avatar span').innerHTML = '';
							}
							// Append new convo
							document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card').insertBefore(convo, document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_temp'));
							// Add event listener
							convo.addEventListener('click', window.Atomize.Widgets.AtomizeChat.openExistingChat);
						}
						// Save current convo
						window.Atomize.Widgets.AtomizeChat.currentConvo = data.chatInstance;
						// Complete sending
						temp.classList.remove('sending');
						// Set token
						temp.setAttribute('token', data.message);
						// Get date now
						var date = new Date();
						// Set message date
						temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_details_time').innerHTML = + date.getHours() + ':' + window.Atomize.Widgets.AtomizeChat.addLeadingZero(date.getMinutes());
					});
				}
			}
			// Set message text
			temp.querySelector('.AtomizeChat_board_frame_chat_messages_list_message_main p').innerHTML = message;
			// Add animation to fade in
			temp.classList.add('AtomizeChat_board_frame_chat_messages_list_message_new');
			// Append new message
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_list').appendChild(temp);
			// Auto scroll to the newest message
			document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_list').scrollTop = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_chat_messages_list').scrollHeight;
		};
		// Add leading zero
		this.addLeadingZero			= function(num) {
			return num > 9 ? num : '0' + num
		};
		// Return to previous frame
		this.goBackFrame			= function(e) {
			// Get existing frame
			var target = document.querySelector('#AtomizeChat .AtomizeChat_board').getAttribute('frame');
			// Switch frame
			switch (target) {
				case 'chat':
				case 'allConvos':
				case 'loading':
					// Set target frame
					target = 'home';
					// Set opacity of header to 1
					document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = '1';
					// Set scroll position to top
					document.querySelector('.AtomizeChat_board_frame[frame="home"]').scrollTop = 0;
					// Clear current convo
					window.Atomize.Widgets.AtomizeChat.currentConvo = null;
				break;
			}
			// Set frame
			document.querySelector('#AtomizeChat .AtomizeChat_board').setAttribute('frame', target);
		};
		// Scrolling for home frame
		this.homeFrameScrolling		= function(e) {
			// Scroll opacity height range
			var range = 120;
			// Calculate range progress (and flip to be negative)
			var progress = ((e.target.scrollTop / range) - 1) * -1;
			// Check if scroll is past the range
			if (e.target.scrollTop >= range) {
				// Set opacity to 0
				document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = '0';
				// Set close button background colour
				document.querySelector('.AtomizeChat_close').setAttribute('semi-transparent', 'true');
			} else {
				// Set opacity to the percentage of range taken
				document.querySelector('.AtomizeChat_board_headbar_hero').style.opacity = progress;
				// Set close button background colour
				document.querySelector('.AtomizeChat_close').setAttribute('semi-transparent', 'false');
			}
		};
		// Toggle chat widget
		this.toggleWidget 			= function() {
			// Check toggle status
			if (window.Atomize.Widgets.AtomizeChat.widget.getAttribute('open') === 'true') {
				// Close widget
				window.Atomize.Widgets.AtomizeChat.closeWidget();
			} else {
				// Open widget
				window.Atomize.Widgets.AtomizeChat.openWidget(false, false);
			}
		};
		// Close chat widget
		this.closeWidget 			= function() {
			// Close widget
			window.Atomize.Widgets.AtomizeChat.widget.setAttribute('open', false);
		};
		// Open chat widget
		this.openWidget 			= function(e, callback) {
			// Close widget
			window.Atomize.Widgets.AtomizeChat.widget.setAttribute('open', true);
			// Check if callback was true
			if (callback != false) {
				// Perform callback
				callback(e);
			}
		};
		// Display widget
		this.displayWidget				= function(delay) {
			// Delay load
			setTimeout(function() {
				// Display widget
				window.Atomize.Widgets.AtomizeChat.widget.setAttribute('loaded', true);
				// Set the hero height
				window.Atomize.Widgets.AtomizeChat.setHeroHeight();

				if (window.AtomizeTestMode == true) {

					setTimeout(function() {
						window.Atomize.Widgets.AtomizeChat.widget.setAttribute('open', true);
					}, 500);

				}

				// setInterval(function() {
				// 	if (document.querySelector('.AtomizeChat_board_frame_chat_messages').getAttribute('ai') == 'true') {
				// 		document.querySelector('.AtomizeChat_board_frame_chat_messages').setAttribute('ai', false);
				// 	} else {
				// 		document.querySelector('.AtomizeChat_board_frame_chat_messages').setAttribute('ai', true);
				// 	}
				// }, 3000);

			}, delay);
		};
		this.setHeroHeight				= function() {
			// Set hero height
			document.querySelector('.AtomizeChat_board_headbar').style.height = document.querySelector('.AtomizeChat_board_headbar_hero').offsetHeight + 24 + 'px';
			// Set home padding top
			document.querySelector('.AtomizeChat_board_frame[frame="home"]').style.paddingTop = document.querySelector('.AtomizeChat_board_headbar').offsetHeight - 28 + 'px';
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
		// Initialise widget object
		window.Atomize.Widgets.AtomizeChat = new AtomizeChat();
	});


})();
