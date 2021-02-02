(function() {

	var stickNavToHeader = function() {
		// Check if not top
		if (window.scrollY > 0) {
			// Make nav sticky
			document.querySelector('nav').classList.add('sticky');
		} else {
			// Remove sticky from nav
			document.querySelector('nav').classList.remove('sticky');
		}
	};

	// Check when window scrolls
	window.addEventListener('scroll', stickNavToHeader);
	window.addEventListener('touchmove', stickNavToHeader);


	// Initialise agent messenger
	var agentMessage = function() {
		// Set messenger events
		setMessengerEvents();
		// Set default inbox
		setInboxType();
		// Set live refresh
		setMessageLoadInterval(15000);
	};


	// Load messages every X seconds
	var setMessageLoadInterval	= function(refreshRate) {
		// Set interval
		setInterval(function() {
			// Update live items
			updateLiveItems(false);
		}, refreshRate);
		// Update live items
		updateLiveItems(false);
	};


	// Create debounce
	var liveRefreshDebounce = false;
	// Update live items
	var updateLiveItems		= function(callback) {
		// Check if already waiting for response
		if (liveRefreshDebounce == false) {
			// Set debounce
			liveRefreshDebounce = true;
			// Send for update
			getLiveItems(callback);
		}
	};


	// Hold last load time
	var lastRefreshed		= null;
	// Hold callback
	var getLiveCallback		= false;
	// Get live items
	var getLiveItems		= function(callback) {
		// Hold callback
		getLiveCallback = callback;
		// Get org_token
		var org_token = $('#messengerMain').attr('org');
		// Get live items
		$.ajax({
			method:			'POST',
			url:			'/ajax/refreshLiveChatItems/',
			data:			{
				_token:			$('meta[name="csrf-token"]').attr('content'),
				lastRefreshed: 	lastRefreshed,
				org_token: 		org_token,
			},
			crossDomain: 	true,
			dataType:		'json',
			success: 		function(data) {
				// Refresh live items
				refreshLiveItems(data);
			},
		});
	};


	// Refresh live items
	this.refreshLiveItems			= function(data) {
		console.log(data);


		// Check if there is some chat data to update
		if (data.queueChatData && data.queueChatData.length > 0) {
			// Go through all chats
			for (var i = 0; i < data.queueChatData.length; i++) {
				// If client is on this chat currently
				if ($('.messengerMain_main').attr('token') == data.queueChatData[i].token) {
					// Counter
					var notifs = 0;
					// Go through new messages
					for (var j = data.queueChatData[i].messages.length - 1; j >= 0; j--) {
						// Check if agent message
						if (data.queueChatData[i].messages[j].from == 'client') {
							// Check if message hasn't been displayed already
							if ($('.messengerMain_messages_list_message_main[token="' + data.queueChatData[i].messages[j].id + '"]').length == 0) {
								// Add message to thread
								addMessageToThread(
									data.queueChatData[i].messages[j].id,
									data.queueChatData[i].messages[j].message,
									data.queueChatData[i].messages[j].from,
									data.queueChatData[i].messages[j].timesent,
									true);
								// Add to counter
								notifs++;
							}
						}
					}
					// Check if notifications and if widget is not open
					if (notifs > 0) {
						// As user is not focussed on chat, play ping sound to alert of new message
						alertSound_play();
					}
				}

				// Set notification counter

			}
		}


		// Check if there is some chat data to update
		if (data.assignedChatData && data.assignedChatData.length > 0) {
			// Go through all chats
			for (var i = 0; i < data.assignedChatData.length; i++) {
				// If client is on this chat currently
				if ($('.messengerMain_main').attr('token') == data.assignedChatData[i].token) {
					// Counter
					var notifs = 0;
					// Go through new messages
					for (var j = data.assignedChatData[i].messages.length - 1; j >= 0; j--) {
						// Check if agent message
						if (data.assignedChatData[i].messages[j].from == 'client') {
							// Check if message hasn't been displayed already
							if ($('.messengerMain_messages_list_message_main[token="' + data.assignedChatData[i].messages[j].id + '"]').length == 0) {
								// Add message to thread
								addMessageToThread(
									data.assignedChatData[i].messages[j].id,
									data.assignedChatData[i].messages[j].message,
									data.assignedChatData[i].messages[j].from,
									data.assignedChatData[i].messages[j].timesent,
									true);
								// Add to counter
								notifs++;
							}
						}
					}
					// Check if notifications and if widget is not open
					if (notifs > 0) {
						// As user is not focussed on chat, play ping sound to alert of new message
						alertSound_play();
					}
				}

				// Set notification counter

			}
		}


		// Set last load time (most recent message time)
		lastRefreshed = data.lastRefreshed;
		// Set debounce
		liveRefreshDebounce = false;
		// Check if callback is set
		if (getLiveCallback != false) {
			// Do callback
			getLiveCallback(data);
		}
		// Clear callback
		getLiveCallback = false;
	};


	// Contain alert sound
	var alertSound 			= null;
	// Play alert sound
	var alertSound_play 			= function() {
		try {
			alertSound.play();
		} catch (e) {
		}
	};
	// Cancel alert sound
	var alertSound_cancel 			= function() {
		alertSound.pause();
		alertSound.currentTime = 0;
	};


	// get conversations
	var getMessengerConversations = function() {
		// Get inbox
		var inbox = $('.messengerIndexSwitches').attr('inbox');
		// Get org_token
		var org_token = $('#messengerMain').attr('org');
		// Clear all convos
		$('.messengerChats_convo:not(.messengerChats_convo_loader):not(.messengerChats_convo_temp)').remove();
		// Make empty item invisible
		$('.emptyConvoList').removeClass('visible');
		// Create loading convos
		createConvoLoaderPlaceholder();
		// Get convos
		$.ajax({
			method:			'GET',
			url:			'/ajax/getMessengerConvos/' + inbox + '/' + org_token,
			data:			{
				_token:			$('[name="_token"]').val(),
			},
			crossDomain: 	true,
			dataType:		'json',
			success: 		function(data) {
				// Create real convo list
				createRealConvoList(data.currentInbox, data.altInboxCounter);
			},
		});
	};


	// Create placeholder item
	var createRealConvoList = function(data, altData) {
		// Get temp
		var temp = $('.messengerChats_convo.messengerChats_convo_temp');
		var clone;
		// Clear all placeholders
		$('.messengerChats_convo:not(.messengerChats_convo_temp)').remove();// Loop to create placeholders
		// Switch inbox
		switch ($('.messengerIndexSwitches').attr('inbox')) {
			case 'assigned':
				// Set counters
				$('a[inbox="assigned"] span')[0].innerHTML = data.length;
				$('a[inbox="queue"] span')[0].innerHTML = altData;
			break;
			case 'queue':
				// Set counters
				$('a[inbox="assigned"] span')[0].innerHTML = altData;
				$('a[inbox="queue"] span')[0].innerHTML = data.length;
			break;
		}
		// Check if there are no convos
		if (data.length == 0) {
			// Make empty item visible
			$('.emptyConvoList').addClass('visible');
		} else {
			// Loop through data
			for (var i = data.length - 1; i >= 0; i--) {
				// Create clone
				clone = temp.clone();
				// Remove temp class
				clone.removeClass('messengerChats_convo_temp');
				// Add instance ID
				clone.attr('instance', data[i].token);
				// Check if name exists
				if (typeof data[i].client.firstname == 'string' || typeof data[i].client.lastname == 'string') {
					// Add name of client
					clone.find('.messengerChats_convo_name')[0].innerHTML = data[i].client.firstname + ' ' + data[i].client.lastname;
				} else {
					// Add placeholder name
					clone.find('.messengerChats_convo_name')[0].innerHTML = 'Unnamed Client';
				}
				// Check if most recent message was found
				if (data[i].mostRecentMessage) {
					// Check if agent sent message
					if (data[i].mostRecentMessage.from == 'agent') {
						// Add preview of message
						clone.find('.messengerChats_convo_preview')[0].innerHTML = 'You: ' + data[i].mostRecentMessage.message;
					} else {
						// Add preview of message
						clone.find('.messengerChats_convo_preview')[0].innerHTML = 'Client: ' + data[i].mostRecentMessage.message;
					}
					// Get date now
					var date = new Date(data[i].mostRecentMessage.timesent * 1000);
					// Set time text
					clone.find('.messengerChats_convo_time')[0].innerHTML = addLeadingZero(date.getDate()) + '/' + addLeadingZero((parseInt(date.getMonth()) + 1)) + '/' + date.getFullYear() + ' at ' + date.getHours() + ':' + addLeadingZero(date.getMinutes());
				} else {
					// Add preview of message
					clone.find('.messengerChats_convo_preview')[0].innerHTML = 'No message available.';
					// Get date now
					var date = new Date();
					// Set time text
					clone.find('.messengerChats_convo_time')[0].innerHTML = addLeadingZero(date.getDate()) + '/' + addLeadingZero((parseInt(date.getMonth()) + 1)) + '/' + date.getFullYear() + ' at ' + date.getHours() + ':' + addLeadingZero(date.getMinutes());
				}
				// Set text
				clone.find('.messengerChats_convo_avatar span')[0].innerHTML = '<i class="fad fa-user-circle"></i>';
				// Add clone to list
				clone.insertAfter('.messengerChats_convo.messengerChats_convo_temp');
				// Add event listener
				clone.click(openConvo);
			}
		}
		// Set the container as loading
		$('.messengerChats').removeClass('loading');
		// Open convo that was moved from queue to inbox
		$('.messengerChats_convo[instance="' + goToConvoJustAssignedTo + '"]').click();
		// Reset holding var
		goToConvoJustAssignedTo = false;
	};


	// Open convo
	var openConvo = function(e) {
		// Get convo card
		var card = $(e.target).closest('.messengerChats_convo');
		// Make loader visible
		$('#messengerMain .messengerMain_main').attr('frame', 'loading');
		// Set current convo
		$('#messengerMain .messengerMain_main').attr('token', card.attr('instance'));
		// Remove current card
		$('.messengerChats_convo.current').removeClass('current');
		// Add current card
		$('.messengerChats_convo[instance="' + card.attr('instance') + '"]').addClass('current');
		// Get convos
		$.ajax({
			method:			'GET',
			url:			'/ajax/getMessengerMessages/' + card.attr('instance'),
			data:			{
				_token:			$('[name="_token"]').val(),
			},
			crossDomain: 	true,
			dataType:		'json',
			success: 		function(data) {
				// Load messages
				loadConvoMessages(data);
			},
		});
	};


	// Store convo token
	var convoToken = false;
	// Load messages
	var loadConvoMessages = function(data) {
		// Store convo token
		convoToken = data.convo.token;
		// Clear existing messages
		$('#messengerMain .messengerMain_messages_list_message:not(.messengerMain_messages_list_message_temp)').remove();
		// Load messages
		loadMessages(data.messages);
		// Load messages
		loadMessageClientData(data.client, data.convo);
		// Set the frame as messages
		$('#messengerMain .messengerMain_main').attr('frame', 'messages');
		// Scroll to bottom of messages
		$('.messengerMain_messages_list').scrollTop($('.messengerMain_messages_list')[0].scrollHeight);
	};


	// Variable storage
	var assignmentClickAdded = false;
	// Load client data
	var loadMessageClientData			= function(client, convo) {
		// Set default name
		var name = 'Unnamed Client';
		// Check if name exists
		if (typeof client.firstname == 'string' || typeof client.lastname == 'string') {
			// Add name of client
			name = client.firstname + ' ' + client.lastname;
		}
		// Add name of client
		$('.messengerMain_main .messengerMain_profile p')[0].innerHTML = name;
		// Set the client ID
		$('.messengerMain_main .messengerMain_profile h6 span')[0].innerHTML = client.token;
		// Add name of client
		$('.messengerMain_main .messengerMain_messages_info_name')[0].innerHTML = name;
		// Set the client ID
		$('.messengerMain_main .messengerMain_messages_info_token')[0].innerHTML = client.token;
		// Check if the inbox is queue
		if ($('.messengerIndexSwitches').attr('inbox') == 'queue') {
			// Enable button
			$('.messengerMain_messages_info_assignSelfToConvo')[0].removeAttribute('disabled');
			// Allow assignment
			$('.messengerMain_messages_info_assignSelfToConvo').show();
			// Check if added
			if (assignmentClickAdded == false) {
				// Add event listener
				$('.messengerMain_messages_info_assignSelfToConvo').click(assignLocalAgentToConvo);
				// Change check
				assignmentClickAdded = true;
			}
		} else {
			// Disallow assignment
			$('.messengerMain_messages_info_assignSelfToConvo').hide();
		}
		// Get date now
		var date = new Date(convo.timestarted * 1000);
		// Set time text
		$('.messengerMain_messages_info_timestarted span')[0].innerHTML = addLeadingZero(date.getDate()) + '/' + addLeadingZero((parseInt(date.getMonth()) + 1)) + '/' + date.getFullYear() + ' at ' + date.getHours() + ':' + addLeadingZero(date.getMinutes());
	};


	// Variable storage
	var goToConvoJustAssignedTo = false;
	// Assign the locally signed in agent to the chat
	var assignLocalAgentToConvo			= function() {
		// Get convo token
		var token = $('.messengerMain_main').attr('token');
		// Disable button
		$('.messengerMain_messages_info_assignSelfToConvo').attr('disabled', 'disabled');
		// Assign agent
		$.ajax({
			method:			'POST',
			url:			'/ajax/assignLocalAgent/',
			data:			{
				_token:			$('meta[name="csrf-token"]').attr('content'),
				convo:			token,
			},
			crossDomain: 	true,
			dataType:		'json',
			success: 		function(data) {
				// Create
				goToConvoJustAssignedTo = data;
				// Go to convo in inbox
				$('.messengerIndexSwitches a[inbox="assigned"]').click();
			},
		});
	};


	// Send message to chat
	var sendMessage			= function(message, from) {
		// Add message to thread
		addMessageToThread('', message, from, new Date(), false);
		// Reset message area
		resetMessageArea();
	};


	// Load messages
	var loadMessages			= function(messages) {
		// Loop through messages
		for (var i = messages.length - 1; i >= 0; i--) {
			// Add message to thread
			addMessageToThread(messages[i].id, messages[i].message, messages[i].from, messages[i].timesent, true);
		}
	};


	// Add message to thread
	var addMessageToThread		= function(token, message, from, dateSent, loaded) {
		// Get template
		var temp = document.querySelector('.messengerMain_messages_list_message_temp').cloneNode(true);
		// Remove temp message class
		temp.classList.remove('messengerMain_messages_list_message_temp');
		// Set from agent value
		temp.setAttribute('from', from);
		// Set token
		temp.setAttribute('token', token);
		// Check if loaded from DB
		if (loaded) {
			// Remove sending class
			temp.classList.remove('sending');
			// Get date now
			var date = new Date(dateSent * 1000);
			// Set message date
			temp.querySelector('.messengerMain_messages_list_message_main_details_time').innerHTML = + date.getHours() + ':' + addLeadingZero(date.getMinutes());
			// Check if from not from agent
			if (from == 'agent') {
				// Remove avatar from message
				temp.querySelector('.messengerMain_messages_list_message_main_avatar').remove();
			}
		} else {
			// Check if from agent
			if (from == 'client') {
				// Remove sending class
				temp.classList.remove('sending');
				// Get date now
				var date = new Date();
				// Set message date
				temp.querySelector('.messengerMain_messages_list_message_main_details_time').innerHTML = + date.getHours() + ':' + addLeadingZero(date.getMinutes());
			} else {
				// Remove avatar from message
				temp.querySelector('.messengerMain_messages_list_message_main_avatar').remove();
				// Remove sending class
				temp.classList.add('sending');
				// Send message
				$.ajax({
					method:			'POST',
					url:			'/ajax/sendAgentMessage/',
					data:			{
						_token:			$('meta[name="csrf-token"]').attr('content'),
						convo:			$('.messengerMain_main').attr('token'),
						message:		message,
					},
					crossDomain: 	true,
					dataType:		'json',
					success: 		function(data) {
						console.log(data);

						// Get existing convo card
						var convo = $('.messengerChats_convo[instance="' + data.chatInstance + '"]');
						// Update most recent message
						convo.find('.messengerChats_convo_preview')[0].innerHTML = data.messageText;

						// } else {
						// 	// Add new convo to list
						// 	var convo = document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_temp[intent="conversation"]').cloneNode(true);
						// 	// Add chat instance token to temp
						// 	convo.setAttribute('instance', data.chatInstance);
						// 	// Add real message class
						// 	convo.classList.add('AtomizeChat_board_frame_card');
						// 	// Remove temp message class
						// 	convo.classList.remove('AtomizeChat_board_frame_card_temp');
						// 	// Add details
						// 	convo.querySelector('.AtomizeChat_board_frame_card_name').innerHTML = data.name;
						// 	convo.querySelector('.AtomizeChat_board_frame_card_time').innerHTML = data.date;
						// 	convo.querySelector('.AtomizeChat_board_frame_card_preview').innerHTML = data.messageText;
						// 	// Check if user has logo or icon
						// 	if (window.Atomize.Widgets.AtomizeChat.chatData.iconSRC == '') {
						// 		// Clear image
						// 		convo.querySelector('.AtomizeChat_board_frame_card_avatar span').style.backgroundImage = 'none';
						// 		// Set text
						// 		convo.querySelector('.AtomizeChat_board_frame_card_avatar span').innerHTML = window.Atomize.Widgets.AtomizeChat.chatData.iconAlt
						// 	} else {
						// 		// Set image
						// 		convo.querySelector('.AtomizeChat_board_frame_card_avatar span').style.backgroundImage = 'url("' + window.Atomize.Widgets.AtomizeChat.chatData.iconSRC + '")';
						// 		// Clear text
						// 		convo.querySelector('.AtomizeChat_board_frame_card_avatar span').innerHTML = '';
						// 	}
						// 	// Append new convo
						// 	document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card').insertBefore(convo, document.querySelector('#AtomizeChat .AtomizeChat_board_frame_card_temp'));
						// 	// Add event listener
						// 	convo.addEventListener('click', window.Atomize.Widgets.AtomizeChat.openExistingChat);
						// }
						// // Save current convo
						// window.Atomize.Widgets.AtomizeChat.currentConvo = data.chatInstance;

						console.log('x');
						console.log(temp);
						// Complete sending
						temp.classList.remove('sending');
						// Set token
						temp.setAttribute('token', data.message);
						// Get date now
						var date = new Date();
						// Set message date
						temp.querySelector('.messengerMain_messages_list_message_main_details_time').innerHTML = + date.getHours() + ':' + addLeadingZero(date.getMinutes());
					},
				});
			}
		}
		// Set message text
		temp.querySelector('.messengerMain_messages_list_message_main p').innerHTML = message;
		// Add animation to fade in
		temp.classList.add('messengerMain_messages_list_message_main_new');
		// Append new message
		document.querySelector('.messengerMain_messages_list').appendChild(temp);
		// Auto scroll to the newest message
		$('.messengerMain_messages_list').scrollTop($('.messengerMain_messages_list')[0].scrollHeight);
	};




















	// Add leading zero
	var addLeadingZero = function(num) {
		return num > 9 ? num : '0' + num;
	};


	// Create placeholder item
	var createConvoLoaderPlaceholder = function() {
		// Set the container as loading
		$('.messengerChats').addClass('loading');
		// Get temp
		var temp = $('.messengerChats_convo.messengerChats_convo_temp');
		var clone;
		// Loop to create placeholders
		for (var i = 0; i < 20; i++) {
			// Create clone
			clone = temp.clone();
			// Add loader class
			clone.addClass('messengerChats_convo_loader');
			// Remove temp class
			clone.removeClass('messengerChats_convo_temp');
			// Add clone to list
			clone.insertAfter('.messengerChats_convo.messengerChats_convo_temp');
		}
	};


	// Set event listeners
	var setMessengerEvents = function() {
		// Set event listener
		$('.messengerIndexSwitches a').click(setInboxType);
		// Send message
		$('.messengerMain_messages_composer_send').click(sendMessageInput);
		// Expand message area
		$('.messengerMain_messages_composer textarea').on('input', expandMessageArea);
		// Handle message composer
		$('.messengerMain_messages_composer textarea').on('keydown', handleMessageComposer);
	};


	// Send message
	var sendMessageInput		= function() {
		// Get message text
		var text = $('.messengerMain_messages_composer textarea')[0].value;
		// Check if text was not empty
		if (text.trim().length > 0) {
			// Send message
			sendMessage(text, 'agent');
		}
	};


	// Create debounce for sending
	var debounceSend = false;
	// Expand message area
	var expandMessageArea		= function(e) {
		// Check if debounce
		if (debounceSend === false) {
			// Set to 1px height so that scroll height can be calculated
			e.target.style.height = '1px';
			// Set new height of textarea
			e.target.style.height = (e.target.scrollHeight) + 'px';
			// Set new height of composer
			$('.messengerMain_messages_composer')[0].style.height = (e.target.scrollHeight) + 'px';
		} else {
			// Reset message area
			resetMessageArea();
		}
	};


	// Handle message composer
	var handleMessageComposer	= function(e) {
		// Get message text
		var text = $('.messengerMain_messages_composer textarea')[0].value;
		// Check if enter key pressed
		if (e.which === 13 && text.trim().length > 0) {
			// Prevent adding line
			e.preventDefault();
			// Set debounce
			debounceSend = true;
			// Send message
			sendMessage(text, 'agent');
		}
	};


	// Send message to chat
	var sendMessage			= function(message, from) {
		// Add message to thread
		addMessageToThread('', message, from, new Date(), false);
		// Reset message area
		resetMessageArea();
	};


	// Reset message composer
	var resetMessageArea		= function() {
		// Clear inputs
		$('.messengerMain_messages_composer textarea')[0].value = '';
		// Set reset height
		$('.messengerMain_messages_composer textarea')[0].style.height = '62px';
		// Focus on input
		$('.messengerMain_messages_composer textarea')[0].focus();
		// Set container height
		$('.messengerMain_messages_composer')[0].style.height = '62px';
		// Scroll to bottom of list
		$('.messengerMain_messages_list').scrollTop($('.messengerMain_messages_list')[0].scrollHeight);
		// Set debounce
		debounceSend = false;
	};


	// Set the inbox's type
	var setInboxType = function(e) {
		// Set convo as non-selected
		$('.messengerMain_main').attr('frame', 'noConvo');
		// Clear stored convo token
		convoToken = false;
		// Check if click
		if (!e) {
			// Set default inbox
			var inbox = 'assigned';
			// Check if inbox is set
			if (window.location.hash) {
				// Check if it's valid
				if (window.location.hash == '#assigned' ||
					window.location.hash == '#queue'
				) {
					// Set inbox as hash
					inbox = window.location.hash.substring(1);
				}
			}
			// Set inbox as hash
			$('.messengerIndexSwitches a[href="#' + inbox + '"]').click();
			// Stop
			return;
		}
		// Set the inbox switched
		$('.messengerIndexSwitches').attr('inbox', $(e.target).attr('inbox'));
		// Check whether type is enabled
		if ($(e.target).attr('inbox') == 'assigned') {
			// Set whether type is enabled
			$('#messengerMain').attr('type_enabled', 'true');
		} else {
			// Set whether type is enabled
			$('#messengerMain').attr('type_enabled', 'false');
		}
		// Get conversations
		getMessengerConversations();
	};


	// Delay loading until all DOM content is loaded
	document.addEventListener('DOMContentLoaded', function() {
		// Check if inbox
		if ($('#messengerMain').length) {
			// Initialise agent messenger
			agentMessage();
		}




		$('#businessSettingsForm_submit').on('click', function(e) {
			// Get time
			var time = new Date()
			// Set timezone input
			$('#businessSettingsForm input[name="timezone"]').val(time.getTimezoneOffset());
			// Submit
			$('#businessSettingsForm').submit();
		});

	});












})();
