// Get existing container object (or create one if null)
window.AtomizeCRM = window.AtomizeCRM || {};


// Auto-calling of widget's methods
(function() {


	// Get existing container object for widgets (or create one if null)
	window.AtomizeCRM = window.AtomizeCRM || {};


	// Messenger widget object
	function AtomizeCRMMessenger() {
		// Set request URL
		this.requestBaseURL 		= 'http://xxx';
		// Set whether the first load of the widget has happened
		this.hasLoaded 				= false;
		// Storage of widget DOM object
		this.widget					= null;
		// Storage of active XMLHttpRequest objects
		this.openRequest 			= null;
		// Store cookie data
		this.cookies				= null;
		// Store interaction data
		this.interactions			= null;
		// Initialise widget
		this.init 					= function() {
			// Check if wrapper object was create
			if (typeof window.AtomizeCRM !== 'undefined' && window.AtomizeCRMToken) {
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
				instanceToken: 	this.getCookie('instanceToken'),
				websiteToken:	window.AtomizeCRMToken,
				dataGathered:	this.dataGathered,
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
			window.AtomizeCRM.Messenger.cookies[key] = value;
			// Get date now
			var nowPlus9 = new Date();
			// Add date in 9 months
			nowPlus9.setDate(nowPlus9.getDate() + 365); // Add a year
			// Set cookie
			document.cookie = key + '=' + value + ';expires=' + nowPlus9;
		};
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
			this.request('/widgets/messenger', this.getSession(), this.buildWidget);
		};
		// XMLHttpRequest handler
		this.request 				= function(target, data, callback) {
			// Create XML HTTP Request
			var request = new XMLHttpRequest();
			// Switch type of request
			switch (target) {
				case '/widgets/messenger':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				case '/widgets/submit-contact-data':
					// Open POST request
					request.open('POST', this.requestBaseURL + target);
				break;
				case '/widgets/send-message-from-contact':
					// Open POST request
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
						// Check if response data is a JSON object
						if (JSON.parse(request.responseText)) {
							// Parse response text through callback
							return callback(JSON.parse(request.responseText));
						}
					}
					// Print error
					return console.error(JSON.parse(request.responseText));
				}
			}
		};
		// Build widget for serving to the DOM
		this.buildWidget			= function(dataForBuild) {
			// Check if the widget was not approved for serving
			if (dataForBuild.data.error) {
				// Add an error to the console
				console.error(dataForBuild.data.errorMessage);
				// Stop the building as the widget is not reseved on an approved website
				return;
			}
			// Build widget DOM
			window.AtomizeCRM.Messenger.buildWidgetDOM(dataForBuild);
			// Update instanceToken cookie
			window.AtomizeCRM.Messenger.updateInstanceToken(dataForBuild.data.data.messengerInstance.instance_token);
			// Set the fully loaded and displayed
			window.AtomizeCRM.Messenger.hasLoaded = true;
			// Serve the widget
			window.AtomizeCRM.Messenger.serveWidget(dataForBuild.data.props.widgetLoadDelay);
			// Save the data for interactions to the object
			window.AtomizeCRM.Messenger.interactions = dataForBuild.data;
		};
		// Create the Stylesheet for the Widget
		this.parseAndProcessWidgetLoad	= function(messenger, styles, triggers, props) {
			// Create link tag for the CSS
			var link = document.createElement('link');
			// Add attribute for rel
			link.setAttribute('rel', 'stylesheet');
			// Add attribute for type
			link.setAttribute('type', 'text/css');
			// Set href attribute
			link.setAttribute('href', window.AtomizeCRM.Messenger.requestBaseURL + '/widgets/messenger.css');
			// Add tag to head
			document.getElementsByTagName('head')[0].appendChild(link);
			// Add onLoad function
			link.onload = function() {
				// Add widget to DOM body
				window.AtomizeCRM.Messenger.addWidgetToDOM(messenger);
				// Set add styles to the DOM (theme colours etc)
				window.AtomizeCRM.Messenger.addWidgetThemeStyles(styles, props);
				// Parse through and set the interaction triggers
				window.AtomizeCRM.Messenger.parseAndSetInteractionTriggers(triggers, props);
			};
		};
		// Set add styles to the DOM (theme colours etc)
		this.addWidgetThemeStyles			= function(styles, props) {
			// Create extra stylesheet element for the theme
			var themeStyles = document.createElement('style');
			// Add the launcherButtonBGColour style to the sheet
			themeStyles.innerHTML = 	'#AtomizeCRM-messenger-launcher-button { background-color:' + styles.launcherButtonBGColour + ' !important;}';
			// Add the launcherButtonBGColour style to the sheet
			themeStyles.innerHTML += 	'#AtomizeCRM-messenger-portal button { background-color:' + styles.launcherButtonBGColour + ' !important; border-color:' + styles.launcherButtonBGColour + ' !important;}';
			// Add the portalBGColour style to the sheet
			themeStyles.innerHTML += 	'#AtomizeCRM-messenger-portal { background-color:' + styles.portalBGColour + ' !important;}';
			// Add the portalHeroBGColour style to the sheet
			themeStyles.innerHTML += 	'#AtomizeCRM-messenger-portal-background { background-color:' + styles.portalHeroBGColour + ' !important;}';
			// Add the colour for sending a message to the sheet
			themeStyles.innerHTML += 	'.AtomizeCRM-messenger-composer .AtomizeCRM-messenger-composer-send svg { fill:' + styles.launcherButtonBGColour + ' !important;}';
			// Add the messageOptionBG and messageOptionText styles to the sheet
			themeStyles.innerHTML += 	'.AtomizeCRM-messenger-messages .AtomizeCRM-messenger-messages-choice { background-color:' + ('rgba(' + (styles.messageOptionBG.join(',')) + ',0.175)') + ' !important; color:' + ('rgba(' + (styles.messageOptionText.join(',')) + ',1)') + ' !important;}';
			// Add the border colour for messages-field
			themeStyles.innerHTML += 	'.AtomizeCRM-messenger-messages .AtomizeCRM-messenger-messages-field { border-color:' + ('rgba(' + (styles.messageOptionBG.join(',')) + ',0.675)') + ' !important;}';
			// Add the portalHeroBGColour and portalHeroTextColour styles to the sheet
			themeStyles.innerHTML += 	'.AtomizeCRM-messenger-messages .AtomizeCRM-messenger-messages-message { background-color:' + styles.portalHeroBGColour + ' !important; color:' + styles.portalHeroTextColour + ' !important;}';
			// Add the portalHeroBGColour and portalHeroTextColour styles to the sheet
			themeStyles.innerHTML += 	'.AtomizeCRM-messenger-messages .AtomizeCRM-messenger-messages-choice:hover { background-color:' + styles.portalHeroBGColour + ' !important; color:' + styles.portalHeroTextColour + ' !important;}';
			// Add the portalHeroTextColour style to the sheet
			themeStyles.innerHTML += 	'#AtomizeCRM-messenger-portal-frame-hero-banner { color:' + styles.portalHeroTextColour + ' !important;}';
			// Add the portalHeroTextColour style to the sheet
			themeStyles.innerHTML += 	'.AtomizeCRM-messenger-portal-frame-header { color:' + styles.portalHeroTextColour + ' !important;}';
			// Add the portalHeroTextColour style to the closer button
			themeStyles.innerHTML += 	'#AtomizeCRM-messenger-portal-header-closer { fill:' + styles.portalHeroTextColour + ' !important;}';
			// Add tag to head
			document.getElementsByTagName('head')[0].appendChild(themeStyles);
			// Set the launcher button styles based on launcher type
			window.AtomizeCRM.Messenger.setLauncherStyles(styles);
		};
		// Just a holder variable
		this.hasSetHeroVisuals = false;
		// Set the portal's hero banner visuals based on texts, logo, scrolling, etc.
		this.setPortalHeroVisuals	= function() {
			// Check if not already done
			if (window.AtomizeCRM.Messenger.hasSetHeroVisuals == false) {
				// Hold the total added to the top padding for props
				var addedPadding = 0;
				// Get the portal's hero banner element
				var banner = document.getElementById('AtomizeCRM-messenger-portal-frame-hero-banner');
				// Get the portal's hero card-container element
				var cardContainer = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="hero"] .AtomizeCRM-messenger-portal-frame-cards');
				// Check if there is a logo added to the banner
				if (document.getElementById('AtomizeCRM-messenger-portal-frame-hero-banner-logo')) {
					// Add the height of the logo to the added padding
					addedPadding += (document.getElementById('AtomizeCRM-messenger-portal-frame-hero-banner-logo').offsetHeight + 22);
				}
				// Add the height of the header text to the added padding
				addedPadding += banner.getElementsByClassName('AtomizeCRM-messenger-portal-frame-hero-banner-header')[0].offsetHeight;
				// Add the height of the description text to the added padding
				addedPadding += banner.getElementsByClassName('AtomizeCRM-messenger-portal-frame-hero-banner-desc')[0].offsetHeight;
				// Set the calculated top-padding of the card container
				cardContainer.style.paddingTop = (addedPadding + 'px');
				// Set card container scrolling eventListener
				cardContainer.addEventListener('scroll', window.AtomizeCRM.Messenger.portalHeroCardsScroll);
				// Mark as done
				window.AtomizeCRM.Messenger.hasSetHeroVisuals = true;
			}
		};
		// Respond to the hero cardContainer scrolling eventListener
		this.portalHeroCardsScroll 	= function(e) {
			// Get the portal's hero card-container element
			var cardContainer = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="hero"] .AtomizeCRM-messenger-portal-frame-cards');
			// Get the portal's hero banner items container
			var bannerItemsContainer = document.getElementById('AtomizeCRM-messenger-portal-frame-hero-banner-items');
			// Get the portal's hero banner logo
			var bannerLogo = document.getElementById('AtomizeCRM-messenger-portal-frame-hero-banner-logo');
			// Get the current scroll position for the card container
			var curScrollPos = cardContainer.scrollTop;
			// Get the max scroll position for the card container
			var maxScrollPos = parseInt(cardContainer.style.paddingTop);
			// Calculate the scroll progress
			var scrollProgress = (((curScrollPos / maxScrollPos) - 1) * -1);
			// Set the opacity of the banner items to the scroll progress
			bannerItemsContainer.style.opacity = scrollProgress;
			// Set the opacity of the banner logo to the scroll progress
			bannerLogo.style.opacity = scrollProgress;
			// Calculate the parallax-style progress
			var parallaxProgress = ((curScrollPos * -1) / 12);
			// Set parallax-style for the logo on scroll
			bannerLogo.style.top = (parallaxProgress + 'px');
			// Set parallax-style for the banner items on scroll
			bannerItemsContainer.style.top = (parallaxProgress + 'px');
			// Check if the close button needs to be highlighted (if scroll >= 32)
			if (curScrollPos >= 32) {
				// Add class to close button
				document.getElementById('AtomizeCRM-messenger-portal-header-closer').classList.add('scroll-progress');
			} else {
				// Remove class from close button
				document.getElementById('AtomizeCRM-messenger-portal-header-closer').classList.remove('scroll-progress');
			}
		};
		// Set the launcher button styles based on launcher type
		this.setLauncherStyles		= function(styles) {
			// Check if the launcher button needs height calculated
			if (styles.launcherType == 'text-button-right-center') {
				// Get the launcher button element
				var launcherButton = document.getElementById('AtomizeCRM-messenger-launcher-button');
				// Get the launcher cover element
				var launcherCover = document.getElementById('AtomizeCRM-messenger-launcher-button-cover');
				// Get the height of the text in the button
				var launcherCoverTextHeight = launcherCover.getElementsByTagName('p')[0].offsetWidth;
				// Set the height of the cover
				launcherCover.style.height = (launcherCoverTextHeight + 'px');
				// Set the top-margin of the button
				launcherButton.style.marginTop = ((launcherCoverTextHeight / -2) + 'px');
			}
		};
		// Add widget to DOM body
		this.addWidgetToDOM			= function(messenger) {
			// Add widget to body
			document.body.appendChild(messenger);
			// Create the eventListens for widget interaction
			window.AtomizeCRM.Messenger.createEventListeners();
		};
		// Build widget DOM
		this.buildWidgetDOM			= function(dataForBuild) {
			// Create Messenger container
			var widget = document.createElement('div');
			// Add data to container
			widget.setAttribute('id', 'AtomizeCRM-messenger');
			widget.setAttribute('loaded', 'false');
			widget.setAttribute('instance', dataForBuild.data.data.messengerInstance.instance_token);
			// Add widget from server's template
			widget.innerHTML = dataForBuild.widget;
			// Create the Stylesheet for the Widget
			window.AtomizeCRM.Messenger.parseAndProcessWidgetLoad(widget, dataForBuild.data.styles, dataForBuild.data.triggers, dataForBuild.data.props);
			// Set widget in object
			window.AtomizeCRM.Messenger.widget = document.getElementById('AtomizeCRM-messenger');
		};
		// Update instanceToken cookie
		this.updateInstanceToken		= function(token) {
			// Update instanceToken cookie
			window.AtomizeCRM.Messenger.setCookie('instanceToken', token);
		};
		// Serve the widget
		this.serveWidget				= function(widgetLoadDelay) {
			// Set timeout for data to load through
			setTimeout(function() {
				// Set the messenger as loaded
				document.getElementById('AtomizeCRM-messenger').setAttribute('loaded', window.AtomizeCRM.Messenger.hasLoaded);
			}, widgetLoadDelay + 250);
		};
		// Set background on the portal
		this.setPortalBackground		= function() {
			// Get frame that is displayed
			var frame = document.getElementById('AtomizeCRM-messenger-portal').getAttribute('frame');
			// Get background DOM item
			var bgDOM = document.getElementById('AtomizeCRM-messenger-portal-background')
			// Set the default height
			var height = 60;
			// Switch which frame is visible
			switch (frame) {
				case 'hero':
					// Set the background's height to the same as the hero's banner height
					height = (document.getElementById('AtomizeCRM-messenger-portal-frame-hero-banner').offsetHeight + 'px');
				break;
				case 'chat':
					// Set the background's height to the same as the chat's header height
					height = (document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"] .AtomizeCRM-messenger-portal-frame-header').offsetHeight + 'px');
				break;
			}
			// Set the background's height
			bgDOM.style.height = height;
		};
		// Hold portal's open/closed state
		this.portalIsOpen = false;
		// Toggle the portal's state as open/closed
		this.togglePortal				= function() {
			// Check if the portal frame has not been set yet
			if (document.getElementById('AtomizeCRM-messenger-portal').getAttribute('frame') == null) {
				// Set the portal's hero banner styles based on texts, logo, etc.
				window.AtomizeCRM.Messenger.setPortalHeroVisuals();
				// Set the portal frame as 'hero'
				document.getElementById('AtomizeCRM-messenger-portal').setAttribute('frame', 'hero');
				// Display the 'hero' frame
				document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="hero"]').classList.add('first-frame');
				document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="hero"]').classList.add('frame-in-left');
			}
			// Set background on the portal
			window.AtomizeCRM.Messenger.setPortalBackground();
			// Switch the portal's state
			window.AtomizeCRM.Messenger.portalIsOpen = !window.AtomizeCRM.Messenger.portalIsOpen;
			// Set the portal's 'is-open' attribute to $portalIsOpen new value
			document.getElementById('AtomizeCRM-messenger').setAttribute('portal-is-open', window.AtomizeCRM.Messenger.portalIsOpen);
		};
		// Close the portal's state as closed
		this.closePortal				= function() {
			// Switch the portal's state
			window.AtomizeCRM.Messenger.portalIsOpen = false;
			// Set the portal's 'is-open' attribute to $portalIsOpen new value
			document.getElementById('AtomizeCRM-messenger').setAttribute('portal-is-open', window.AtomizeCRM.Messenger.portalIsOpen);
		};




		// Create the eventListens for widget interaction
		this.createEventListeners	= function() {

			// Await a click of the messenger's launcher button
			document.getElementById('AtomizeCRM-messenger-launcher-button').addEventListener('click', window.AtomizeCRM.Messenger.togglePortal);

			// Await a click of the messenger's closer button
			document.getElementById('AtomizeCRM-messenger-portal-header-closer').addEventListener('click', window.AtomizeCRM.Messenger.closePortal);

			// Await a click of the messenger's back button
			document.getElementById('AtomizeCRM-messenger-portal-header-back').addEventListener('click', window.AtomizeCRM.Messenger.goBackAFrame);

		};
		// Parse through and set the interaction triggers
		this.parseAndSetInteractionTriggers	= function(triggers, props) {

			// console.log(props);
			// console.log('-----');
			// console.log(triggers);

			// Check if the chat feature is enabled
			if (props.chatFeature == true) {
				// Await a click of the button that switches the frame to chat rather than hero.
				document.querySelector('button[process-frame="chat"]').addEventListener('click', window.AtomizeCRM.Messenger.processPortalFrameToChat);

				// Await a click of the bot directional message choices
				// document.querySelector('').addEventListener('click', window.AtomizeCRM.Messenger.processChatInteractionFromVisitor);

				// Await the keyup of the message composer
				document.querySelector('.AtomizeCRM-messenger-composer textarea').addEventListener('keyup', window.AtomizeCRM.Messenger.processMessageSendButton);

				// Await a click of the message sender button
				document.querySelector('.AtomizeCRM-messenger-composer-send').addEventListener('click', window.AtomizeCRM.Messenger.submitMessageSendButton);
			}

		};
		// Send message from the visitor to the API
		this.sendMessengerMessageToAtomize = function(message) {
			// Get the instance token for this user
			var instanceToken = window.AtomizeCRM.Messenger.getCookie('instanceToken');
			// Create the random tracking ID
			var randomTrackingID = window.AtomizeCRM.Messenger.createUniqueId(24);
			// Get the instance token for this user
			var dataToSend = {
				sessionData:		window.AtomizeCRM.Messenger.getSession(),
				message:			window.AtomizeCRM.Messenger.encodeMessage(instanceToken, message),
				DOMTracker:			randomTrackingID,


				// Data about the agent/bot/etc should be added here once agents are added to the chats


			};
			// Send the data to the API
			window.AtomizeCRM.Messenger.request(
				'/widgets/send-message-from-contact',
				dataToSend,
				window.AtomizeCRM.Messenger.sendMessengerMessageToAtomizeDone
			);
			// Return the randon ID so that the messenger can track the request as success/error
			return randomTrackingID;
		};
		this.sendMessengerMessageToAtomizeDone	= function(data) {
			// Check if the message was saved
			if (data.status[0] == 'saved') {
				// Get the message DOM
				var messageDOM = document.querySelector('.AtomizeCRM-messenger-messages-message[tracking-send="' + data.DOMTracker + '"]');
				// Remove spinner
				document.querySelector('.AtomizeCRM-messenger-messages-message[tracking-send="' + data.DOMTracker + '"] .AtomizeCRM-messenger-messages-message-spinner').remove();
				// Remove the tracking as the message has been sent successfully
				messageDOM.removeAttribute('tracking-send');
			} else {
				console.log('Message failed being sent - mark as failed sent message');
			}
		};
		// Encode a message
		this.encodeMessage				= function(token, message) {
			// Store for processing
			var encodedMessage = '[' + token + ']';
			// Get message plus the token
			var messageToEncode = '[' + token + ']' + message;
			// Holding vars
			var thisItem, encodedItem;
			// Go through all items in the string
			for (var i = 0; i < messageToEncode.length; i++) {
				// Get the char code of the character
				thisItem = messageToEncode[i];

				// Encode based on token here (Needs some encoding here using the instanceToken as that is only known by visitor and org)
				encodedItem = thisItem;

				// Add encoded item to the encoded message
				encodedMessage = encodedMessage + encodedItem;
			}
			// Returns encoded message
			return encodedMessage;
		};
		// Submit the message from the website visitor
		this.submitMessageSendButton	= function(e) {
			// Get composer DOM
			var composer = document.querySelector('.AtomizeCRM-messenger-composer');
			// Get message DOM
			var messageDOM = composer.getElementsByTagName('textarea')[0];
			// Get send button
			var sendButton = document.querySelector('.AtomizeCRM-messenger-composer-send');
			// Get the messagesContainer DOM
			var messagesContainer = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"] .AtomizeCRM-messenger-messages');
			// Send message from the visitor to the API
			var sendingResult = window.AtomizeCRM.Messenger.sendMessengerMessageToAtomize(messageDOM.value);
			// Add message to the message list
			window.AtomizeCRM.Messenger.createDOMUserMessage(messagesContainer, messageDOM.value, sendingResult);
			// Clear composer for the next message
			messageDOM.value = '';
		};
		// Process the send button from the composer
		this.processMessageSendButton	= function(e) {
			// Get composer DOM
			var composer = document.querySelector('.AtomizeCRM-messenger-composer');
			// Get message DOM
			var messageDOM = composer.getElementsByTagName('textarea')[0];
			// Get send button
			var sendButton = document.querySelector('.AtomizeCRM-messenger-composer-send');
			// See if the composer has text in it
			if (messageDOM.value.trim().length > 0) {
				// Enable the sender
				sendButton.setAttribute('enabled', 'true');
			} else {
				// Disable the sender
				sendButton.setAttribute('enabled', 'false');
			}
		};
		// Process button click that switches the frame to chat.
		this.setPortalFrame	= function(frameToChangeTo) {
			// Set background on the portal
			window.AtomizeCRM.Messenger.setPortalBackground();
			// Get the portal
			var portal = document.getElementById('AtomizeCRM-messenger-portal');
			// Get current frame
			var currentFrame = portal.getAttribute('frame');
			// Get current frame DOM item
			var currentFrameDOM = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="' + currentFrame + '"]');
			// Get next frame DOM item
			var nextFrameDOM = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="' + frameToChangeTo + '"]');
			// Set the direction of the transition
			var transitionDirection = 'left';
			// Switch through which frame to use next
			switch (frameToChangeTo) {
				case 'chat':


				break;
			}
			// Check if there is a change needed
			if (currentFrame != frameToChangeTo) {
				// Remove first-frame class
				currentFrameDOM.classList.remove('first-frame');
				// Set frame
				portal.setAttribute('frame', frameToChangeTo);
				// Add the current frame 'frame-out' class
				currentFrameDOM.classList.add('frame-out-' + transitionDirection);
				// Remove the current frame 'frame-in' class
				currentFrameDOM.classList.remove('frame-in-' + transitionDirection);
				// Remove the next frame 'frame-out' class
				nextFrameDOM.classList.remove('frame-out-' + transitionDirection);
				// Add the next frame 'frame-in' class
				nextFrameDOM.classList.add('frame-in-' + transitionDirection);
			}
		};
		// Process button click that switches the frame to chat.
		this.goBackAFrame	= function(e) {
			// Set background on the portal
			window.AtomizeCRM.Messenger.setPortalBackground();
			// Get the portal
			var portal = document.getElementById('AtomizeCRM-messenger-portal');
			// Get current frame
			var currentFrame = portal.getAttribute('frame');
			// Get current frame DOM item
			var currentFrameDOM = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="' + currentFrame + '"]');
			// Set default frame to change to
			var frameToChangeTo = currentFrame;
			// Set the direction of the transition
			var currTransitionDirection, nextTransitionDirection  = 'left';
			// Switch through frames
			switch (currentFrame) {
				case 'chat':
					// Set the frame to change to
					frameToChangeTo = 'hero';
					// Set the frame direction
					currTransitionDirection = 'right';
				break;
			}
			// Get the DOM of the frame to go to
			var frameToChangeToDOM = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="' + frameToChangeTo + '"]');
			// Set frame
			portal.setAttribute('frame', frameToChangeTo);
			// Add the current frame 'frame-out' class
			currentFrameDOM.classList.add('frame-out-' + currTransitionDirection);
			// Remove the current frame 'frame-in' class
			currentFrameDOM.classList.remove('frame-in-left');
			currentFrameDOM.classList.remove('frame-in-right');
			// Remove the next frame 'frame-out' class
			frameToChangeToDOM.classList.remove('frame-out-' + nextTransitionDirection);
			// Add the next frame 'frame-in' class
			frameToChangeToDOM.classList.add('frame-in-' + nextTransitionDirection);
			// Delay for frame-out transition
			setTimeout(function() {
				// Remove the current frame 'frame-out' class
				currentFrameDOM.classList.remove('frame-out-left');
				currentFrameDOM.classList.remove('frame-out-right');
			}, 500)
		};

		// Store thread logic progress
		this.chatLogic = {};
		// Store data that was captured in the process
		this.dataGathered = {};



		// Process data into storage
		this.processDataGathered		= function(name, data) {
			// Store the data into the object
			window.AtomizeCRM.Messenger.dataGathered[name] = data;
			// Send the data to the API
			window.AtomizeCRM.Messenger.request('/widgets/submit-contact-data', window.AtomizeCRM.Messenger.getSession(), window.AtomizeCRM.Messenger.processDataGatheredDone);
		};
		this.processDataGatheredDone	= function(data) {

			console.log(data);

		};
		// Process button click that switches the frame to chat.
		this.processPortalFrameToChat	= function(e) {
			// Variable to be hold new DOM items later
			var generatedDOM;
			// Just a holding variable for use later
			var chatData, threadLogic, currentAgent;
			// Get the DOM of the chat frame
			var chatFrame = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"]');




			// Make sure this is re-written to check whether to reload a chat or start another!!!!!
			var resumeChat = false;



			// Set the portal's frame
			window.AtomizeCRM.Messenger.setPortalFrame('chat');
			// Check whether to resume a chat
			if (resumeChat) {


				// Get the chat already in process

				// Look at which stage the bot is on (if it is on a bot, etc)


			} else {

				// Check if the initial message in the new chat includes a bot
				if (window.AtomizeCRM.Messenger.interactions.props.chatStartWithBot) {

					// console.log(window.AtomizeCRM.Messenger.interactions.triggers['new-chat']['bot-start']['send-prompt-from-agent']);


					// Gather the messages, options, etc for the bot to deliver on the frame
					chatData = window.AtomizeCRM.Messenger.interactions.triggers['new-chat']['bot-start']['send-prompt-from-agent'];
					threadLogic = chatData['thread-logic'];
					currentAgent = chatData['agent'];



					// Create an ID for the logic flow
					var flowID = window.AtomizeCRM.Messenger.createUniqueId(24);
					// Store steps of this logic flow
					window.AtomizeCRM.Messenger.chatLogic[flowID] = { steps: threadLogic };
					// Set the DOM frame's logic-flow attribute
					chatFrame.setAttribute('flow', flowID);
					// Start on step-0 of the logic flow
					window.AtomizeCRM.Messenger.createBotProcessedMessageStep(threadLogic['step-0']);
				}

				// Check if the initial message in the new chat includes a agent message
				if (window.AtomizeCRM.Messenger.interactions.props.chatStartWithAgent) {

				}

				// Check if the initial message in the new chat includes a composer
				if (window.AtomizeCRM.Messenger.interactions.props.chatStartWithComposer) {

				}






				// console.log(props);
				// console.log('-----');
				// console.log(triggers);
				// console.log(window.AtomizeCRM.Messenger.interactions);

				// console.log(e);

			}
		};
		// Create a bot-processed messenger step
		this.createBotProcessedMessageStep		= function(step) {
			// Get the message container
			var messagesContainer = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"] .AtomizeCRM-messenger-messages');
			// Default value of var
			var hasDataBeenCapturedAlready = false;
			// Check if there is a message to display
			if (step.message != null && step.message != false && step.message.trim().length > 0) {
				// Check if gathering data is the step here
				if (step.type == 'gather-data') {
					// Get the name of the data that will be requested
					var nameOfData = step['actions-for-next'].field.name;


					console.log(nameOfData);
					// Go through the type of data
					switch (nameOfData) {
						case 'company-name':
							// Check if the data is already completed
							if (window.AtomizeCRM.Messenger.interactions.data.organisationContact.company_name != null) {
								// Set variable
								hasDataBeenCapturedAlready = true;
							}
						break;
						case 'email-address':
							// Check if the data is already completed
							if (window.AtomizeCRM.Messenger.interactions.data.organisationContact.email != null) {
								// Set variable
								hasDataBeenCapturedAlready = true;
							}
						break;
					}
					// See if the data needs to be captured
					if (hasDataBeenCapturedAlready == false) {
						// Display the message to gather data
						window.AtomizeCRM.Messenger.createDOMLogicMessage(messagesContainer, step.message, step['message-from']);
					} else {
						// Check if there is any message for already captured fields
						if (step['if-already-gathered'] != false && step['if-already-gathered']['message'] != false) {
							// Display the message
							window.AtomizeCRM.Messenger.createDOMLogicMessage(messagesContainer, step['if-already-gathered']['message'], step['message-from']);
						}
					}
				} else {
					// Create the message item
					window.AtomizeCRM.Messenger.createDOMLogicMessage(messagesContainer, step.message, step['message-from']);
				}
			}
			// Switch what type of action is needed for next
			switch (step['actions-for-next'].type) {
				case 'choice':
					// Loop through the choices that are given to the user
					for (var i = 0; i < step['actions-for-next'].choices.length; i++) {
						// Create the option item
						window.AtomizeCRM.Messenger.createDOMLogicOption(messagesContainer, step['actions-for-next'].choices[i]);
					}
				break;
				case 'gather-data':
					// Check whether the field needs shown
					if (hasDataBeenCapturedAlready) {
						// Progress to the next step
						window.AtomizeCRM.Messenger.progressLogicStep(step);
					} else {
						// Create the data-gathering item
						window.AtomizeCRM.Messenger.createDOMLogicDataGather(messagesContainer, step['actions-for-next']);
					}
				break;
				case 'progress':
					// Progress to the next step
					window.AtomizeCRM.Messenger.progressLogicStep(step);
				break;
			}
			// Check if the composer should be revealed
			if (step['reveal-composer'] == true) {
				// Show the composer
				window.AtomizeCRM.Messenger.openChatMessageComposer();
			} else {
				// Hide the composer
				window.AtomizeCRM.Messenger.closeChatMessageComposer();
			}
		};
		// Progress to the next step
		this.progressLogicStep				= function(step) {
			// Get the chat widget's flowID
			var flowID = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"]').getAttribute('flow');
			// Get what step goes next
			var nextLogicStep = window.AtomizeCRM.Messenger.chatLogic[flowID].steps[step['actions-for-next']['next-step']];
			// Check if this was part of a logic flow and there is a next step
			if (nextLogicStep) {
				// Go to processing the next logic slep
				window.AtomizeCRM.Messenger.createBotProcessedMessageStep(nextLogicStep);
			}
		};
		// Create a data-gathering item from the logic
		this.createDOMLogicDataGather		= function(container, data) {
			// Create the field element
			var fieldDOM = document.createElement('div');
			// Add data as attributes to fieldDOM
			fieldDOM.setAttribute('class', 'AtomizeCRM-messenger-messages-field');
			fieldDOM.setAttribute('logic-next', (data['next-step'].split('-')[1])); // Split 'step-1' to just the number
			// Create the avatar element
			var fieldDOMavatar = document.createElement('div');
			// Add data as attributes to fieldDOMDOMavatar
			fieldDOMavatar.setAttribute('class', 'AtomizeCRM-messenger-messages-message-avatar');
			fieldDOMavatar.setAttribute('is-bot', 'true');
			// Append the avatar to the messageDOM
			fieldDOM.appendChild(fieldDOMavatar);
			// Create the input container element
			var fieldInputDOM = document.createElement('div');
			// Add data as attributes to fieldDOMDOMavatar
			fieldInputDOM.setAttribute('class', 'AtomizeCRM-messenger-data-field');
			fieldInputDOM.setAttribute('status', 'awaiting-submit');
			// Append the field container to the fieldDOM
			fieldDOM.appendChild(fieldInputDOM);
			// Create the label element
			var fieldInputLabelDOM = document.createElement('label');
			// // Add the text to the label
			fieldInputLabelDOM.innerHTML = data.field.label;
			// Append the label to the fieldDOM
			fieldInputDOM.appendChild(fieldInputLabelDOM);
			// Create the field input container element
			var fieldInputContDOM = document.createElement('div');
			// Add data as attributes to fieldInputContDOM
			fieldInputContDOM.setAttribute('class', 'AtomizeCRM-messenger-data-field-cont');
			// Append the avatar to the fieldDOM
			fieldInputDOM.appendChild(fieldInputContDOM);
			// Create the field input element
			var fieldInputContInputDOM = document.createElement('input');
			// Add data as attributes to fieldInputContInputDOM
			fieldInputContInputDOM.setAttribute('type', data.field.type);
			fieldInputContInputDOM.setAttribute('name', data.field.name);
			fieldInputContInputDOM.setAttribute('placeholder', data.field.placeholder);
			// Append the input field to the container
			fieldInputContDOM.appendChild(fieldInputContInputDOM);
			// Create the field submit element
			var fieldInputContSubmitDOM = document.createElement('div');
			// Add data as attributes to fieldInputContSubmitDOM
			fieldInputContSubmitDOM.setAttribute('class', 'AtomizeCRM-messenger-data-field-submit');
			// Append the submit field to the container
			fieldInputContDOM.appendChild(fieldInputContSubmitDOM);
			// Append the fieldDOM to the message container
			container.appendChild(fieldDOM);
			// Add the event listeners for the submission button
			fieldInputContSubmitDOM.addEventListener('click', window.AtomizeCRM.Messenger.handleChatDataGatherInputSubmit);
			// Add the event listeners for the field when the error is cleared
			fieldInputContInputDOM.addEventListener('keyup', window.AtomizeCRM.Messenger.handleChatDataGatherInput);
		};
		// Handle data gathering input submission
		this.handleChatDataGatherInputSubmit	= function(e) {
			// Get the submission button
			var submissionBtn = e.target;
			// Get data field DOM
			var field = submissionBtn.closest('.AtomizeCRM-messenger-data-field');
			// Get the input
			var input = field.getElementsByTagName('input')[0];
			// Check if the status of the field is needing to be processed
			if (field.getAttribute('status') == 'awaiting-submit') {
				// Set the field as processing
				field.setAttribute('status', 'processing');
				// Disable the input field
				input.disabled = true;
				// Get the input's validation
				var validation = window.AtomizeCRM.Messenger.validateDataGatheringInput(input.getAttribute('name'), input.value);
				// Check if the input is valid
				if (validation == true) {
					// Add the data to the messenger object
					window.AtomizeCRM.Messenger.processDataGathered(input.getAttribute('name'), input.value)
					// Set the field as submitted
					field.setAttribute('status', 'submitted');
					// Get the closest field element that contains the next step in logic flow
					var fieldCont = field.closest('.AtomizeCRM-messenger-messages-field');
					// Get the chat widget's flowID
					var flowID = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"]').getAttribute('flow');
					// Get what step goes next
					var nextLogicStep = window.AtomizeCRM.Messenger.chatLogic[flowID].steps['step-' + fieldCont.getAttribute('logic-next')];
					// Check if this was part of a logic flow and there is a next step
					if (nextLogicStep) {
						// Go to processing the next logic slep
						window.AtomizeCRM.Messenger.createBotProcessedMessageStep(nextLogicStep);
					}
				} else {
					// Set the field as error
					field.setAttribute('status', 'error');
					// Enable the input field
					input.disabled = false;
				}
			}
		};
		// Handle data gathering input
		this.handleChatDataGatherInput	= function(e) {
			// Get the input
			var input = e.target;
			// Get data field DOM
			var field = input.closest('.AtomizeCRM-messenger-data-field');
			// Set the field as awaiting submission
			field.setAttribute('status', 'awaiting-submit');
		};
		// Return if the input is valid or not
		this.validateDataGatheringInput	= function(type, value) {
			// Switch the type of input expected
			switch (type) {
				case 'company-name':
					// Check if not empty
					if (value.trim().length > 0) {
						// Return true as not empty
						return true;
					}
				break;
				case 'email-address':
					// Check if not empty
					if (window.AtomizeCRM.Messenger.validateEmailAddress(value)) {
						// Return true as not empty
						return true;
					}
				break;
			}
			// Return default
			return false;
		};
		// Return if the email address is valid or not
		this.validateEmailAddress	= function(email) {
			// Create RegEx tests for returning the result
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			// Return the result
			return re.test(email);
		};
		// Respond to clicks on the logic options
		this.logicOptionClickEvent		= function(e) {
			// Get the DOM of the option that was clicked
			var option = (e.target).closest('.AtomizeCRM-messenger-messages-choice');
			// Get the messagesContainer DOM
			var messagesContainer = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"] .AtomizeCRM-messenger-messages');
			// Get the chat widget's flowID
			var flowID = document.querySelector('.AtomizeCRM-messenger-portal-frame[frame="chat"]').getAttribute('flow');
			// Get what step goes next
			var nextLogicStep = window.AtomizeCRM.Messenger.chatLogic[flowID].steps['step-' + option.getAttribute('logic-next')];



			// Store interaction into the database so that organisations can track interactions
			// window.AtomizeCRM.Messenger.processChatInteractionFromVisitor(e);


			// Add the option that was selected to the message thread
			window.AtomizeCRM.Messenger.createDOMUserMessage(messagesContainer, option.innerHTML);




			// Clear all text messages that were displayed for the logic
			var logicTexts = messagesContainer.querySelectorAll('.AtomizeCRM-messenger-messages-text');
			// Loop through the nodes found
			for (var i = 0; i < logicTexts.length; i++) {
				// Delete the node
				logicTexts[i].remove();
			}
			// Clear all choice that were displayed for the logic
			var logicChoices = messagesContainer.querySelectorAll('.AtomizeCRM-messenger-messages-choice');
			// Loop through the nodes found
			for (var i = 0; i < logicChoices.length; i++) {
				// Delete the node
				logicChoices[i].remove();
			}





			// console.log(nextLogicStep);





			// Check if there is a next step
			if (true) {
				// Process the next step
				window.AtomizeCRM.Messenger.createBotProcessedMessageStep(nextLogicStep);
			}

		};
		// Create a message item from the user
		this.createDOMUserMessage		= function(container, message, sendingToken = false) {
			// Create the message element
			var messageDOM = document.createElement('div');
			// Add data to messageDOM
			messageDOM.setAttribute('class', 'AtomizeCRM-messenger-messages-message');
			messageDOM.setAttribute('tracking-send', sendingToken);
			// Create the paragraph element
			var messageDOMparagraph = document.createElement('p');
			// Add the text to the paragraph
			messageDOMparagraph.innerHTML = message;
			// Append the paragraph to the messageDOM
			messageDOM.appendChild(messageDOMparagraph);
			// Check if the message is sending
			if (sendingToken != false) {
				// Create the spinner for sending
				var messageSpinner = document.createElement('div');
				// Add data to messageSpinner
				messageSpinner.setAttribute('class', 'AtomizeCRM-messenger-messages-message-spinner');
				// Add the text to the spinner
				messageSpinner.innerHTML = 'Sending';
				// Create the spinner item
				var messageSpinnerItem = document.createElement('span');
				// Append the spinner to the messageDOM
				messageSpinner.appendChild(messageSpinnerItem);
				// Append the spinner to the messageDOM
				messageDOM.appendChild(messageSpinner);
			}
			// Append the messageDOM to the message container
			container.appendChild(messageDOM);
		};
		// Create a option item from the logic
		this.createDOMLogicOption		= function(container, option) {
			// Create the option element
			var optionDOM = document.createElement('div');
			// Add data to optionDOM
			optionDOM.setAttribute('class', 'AtomizeCRM-messenger-messages-choice');
			optionDOM.setAttribute('logic-next', (option['next-step'].split('-'))[1]); // Split 'step-1' to just the number
			// Create the paragraph element
			var optionDOMparagraph = document.createElement('p');
			// Add the text to the paragraph
			optionDOMparagraph.innerHTML = option.message;
			// Append the paragraph to the optionDOM
			optionDOM.appendChild(optionDOMparagraph);
			// Append the optionDOM to the message container
			container.appendChild(optionDOM);
			// Create the eventListener for when the option is clicked
			optionDOM.addEventListener('click', window.AtomizeCRM.Messenger.logicOptionClickEvent);
		};
		// Create a message item from the logic
		this.createDOMLogicMessage		= function(container, message, messageFrom) {
			// Create the message element
			var messageDOM = document.createElement('div');
			// Check if the message is not anyone (not just a note message)
			if (messageFrom != false) {
				// Switch where the message is from
				switch (messageFrom) {
					case 'bot':
						// Add data as attributes to messageDOM
						messageDOM.setAttribute('class', 'AtomizeCRM-messenger-messages-message');
						messageDOM.setAttribute('sender', 'agent');
						// Create the avatar element
						var messageDOMavatar = document.createElement('div');
						// Add data as attributes to messageDOM
						messageDOMavatar.setAttribute('class', 'AtomizeCRM-messenger-messages-message-avatar');
						messageDOMavatar.setAttribute('is-bot', 'true');
						// Create the avatar element
						var messageDOMdata = document.createElement('div');
						// Add data as attributes to messageDOM
						messageDOMdata.setAttribute('class', 'AtomizeCRM-messenger-messages-message-data');
						// Add name of sender string
						var senderString = 'Bot';
						// Add date of send string
						var dateSentString = 'Just now.';
						// Set the text for the sender string + date string
						messageDOMdata.innerHTML = senderString + ' - ' + dateSentString;
						// Append the avatar to the messageDOM
						messageDOM.appendChild(messageDOMavatar);
						messageDOM.appendChild(messageDOMdata);
					break;
					case 'agent':
					break;
					case 'user':
					break;
				}
			} else {
				// Add data as attributes to messageDOM
				messageDOM.setAttribute('class', 'AtomizeCRM-messenger-messages-text');
			}
			// Create the paragraph element
			var messageDOMparagraph = document.createElement('p');
			// Add the text to the paragraph
			messageDOMparagraph.innerHTML = message;
			// Append the paragraph to the messageDOM
			messageDOM.appendChild(messageDOMparagraph);
			// Append the messageDOM to the message container
			container.appendChild(messageDOM);
		};
		// Open the composer for the user to send a message
		this.openChatMessageComposer			= function() {

			console.log('Open composer!');

			// Process display of the send button
			window.AtomizeCRM.Messenger.processMessageSendButton();
		};
		// Close the composer
		this.closeChatMessageComposer			= function() {

			console.log('Close composer!');

		};






		// Creates a unique ID for DOM elements
		this.createUniqueId 					= function(length) {
		    // Storage of the result
		    var result = '';
		    // All characters allowed
		    var characters = 'abcdefghijklmnopqrstuvwxyz';
		    // Loop through charaters
		    for (var i = 0; i < length; i++ ) {
		        // Find a random character
		        result += characters.charAt(Math.floor(Math.random() * characters.length));
		    }
		    // Return result
		    return result;
		};
		// Store interaction into the database so that organisations can track interactions
		this.processChatInteractionFromVisitor	= function(e) {
			console.log('Click on a message, choice, etc');
			console.log(e.target);
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
		window.AtomizeCRM.Messenger = new AtomizeCRMMessenger();
	});


})();
