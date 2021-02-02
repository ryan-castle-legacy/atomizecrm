<div class='AtomizeChat_toggle' style='background-color: {{ $content['primaryColor'] }}'>
	<i class='AtomizeChat_toggle_icon'>
		{{-- <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 width="36px" height="36px" viewBox="0 0 36 36" style="enable-background:new 0 0 36 36;" xml:space="preserve">
			<g fill="none" fill-rule="evenodd"><g><polygon points="0 36 36 36 36 0 0 0"></polygon><path d="M31.1059281,19.4468693 L10.3449666,29.8224462 C8.94594087,30.5217547 7.49043432,29.0215929 8.17420251,27.6529892 C8.17420251,27.6529892 10.7473302,22.456697 11.4550902,21.0955966 C12.1628503,19.7344961 12.9730756,19.4988922 20.4970248,18.5264632 C20.7754304,18.4904474 21.0033531,18.2803547 21.0033531,17.9997309 C21.0033531,17.7196073 20.7754304,17.5095146 20.4970248,17.4734988 C12.9730756,16.5010698 12.1628503,16.2654659 11.4550902,14.9043654 C10.7473302,13.5437652 8.17420251,8.34697281 8.17420251,8.34697281 C7.49043432,6.9788693 8.94594087,5.47820732 10.3449666,6.1775158 L31.1059281,16.553593 C32.298024,17.1488555 32.298024,18.8511065 31.1059281,19.4468693" fill="{{ $content['primaryColorAlt'] }}"></path></g></g>
		</svg> --}}
	</i>
</div>

<div class='AtomizeChat_board' frame='home'>

	<div class='AtomizeChat_board_headbar' style='background-color: {{ $content['primaryColor'] }}'>
		<div class='AtomizeChat_close'></div>
		<div class='AtomizeChat_back'></div>
		<div class='AtomizeChat_board_headbar_hero'>
			@if ($content['logo'] != '')
				<img class='AtomizeChat_board_headbar_hero_logo' src='{{ $content['logo'] }}'/>
			@endif
			<h1 class='AtomizeChat_board_headbar_hero_greeting'>{{ $content['greeting'] }}</h1>
			<h2 class='AtomizeChat_board_headbar_hero_description'>{{ $content['description'] }}</h2>
		</div>
	</div>

	<div class='AtomizeChat_board_frame' frame='home'>

		@if (@sizeof($content['previousChats']) == 0)
			<div class='AtomizeChat_board_frame_card' intent='chat'>
				<p>Chat to us about anything...</p>
				<i class='AtomizeChat_board_frame_card_startchat' style='border-color: {{ $content['primaryColor'] }}'>
					<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 width="36px" height="36px" viewBox="0 0 36 36" style="enable-background:new 0 0 36 36;" xml:space="preserve">
						<g fill="none" fill-rule="evenodd"><g><polygon points="0 36 36 36 36 0 0 0"></polygon><path d="M31.1059281,19.4468693 L10.3449666,29.8224462 C8.94594087,30.5217547 7.49043432,29.0215929 8.17420251,27.6529892 C8.17420251,27.6529892 10.7473302,22.456697 11.4550902,21.0955966 C12.1628503,19.7344961 12.9730756,19.4988922 20.4970248,18.5264632 C20.7754304,18.4904474 21.0033531,18.2803547 21.0033531,17.9997309 C21.0033531,17.7196073 20.7754304,17.5095146 20.4970248,17.4734988 C12.9730756,16.5010698 12.1628503,16.2654659 11.4550902,14.9043654 C10.7473302,13.5437652 8.17420251,8.34697281 8.17420251,8.34697281 C7.49043432,6.9788693 8.94594087,5.47820732 10.3449666,6.1775158 L31.1059281,16.553593 C32.298024,17.1488555 32.298024,18.8511065 31.1059281,19.4468693" fill="{{ $content['primaryColor'] }}"></path></g></g>
					</svg>
				</i>
			</div>
		@endif


		@if (@sizeof($content['previousChats']) == 0)
			<div class='AtomizeChat_board_frame_card_hidden' intent='conversations'>
		@else
			<div class='AtomizeChat_board_frame_card' intent='conversations'>
		@endif
			<p class='AtomizeChat_board_frame_card_convo_title'>Your Conversations<a>See All</a></p>

			<div class='AtomizeChat_board_frame_card_temp' intent='conversation'>
				<div class='AtomizeChat_board_frame_card_avatar'>
					<span style='background-image: url("")'></span>
				</div>
				<p class='AtomizeChat_board_frame_card_name'></p>
				<p class='AtomizeChat_board_frame_card_preview'></p>
				<p class='AtomizeChat_board_frame_card_time'></p>
			</div>

			@for ($i = 0; $i < 3; $i++)
				{{-- Check if the chat exists --}}
				@if (@isset($content['previousChats'][$i]))
					<div class='AtomizeChat_board_frame_card' intent='conversation' instance='{{ $content['previousChats'][$i]->token }}'>
						<div class='AtomizeChat_board_frame_card_avatar'>
						@if ($content['previousChats'][$i]->agent_details['iconSRC'] != '')
							<span style='background-image: url("{{ $content['previousChats'][$i]->agent_details['iconSRC'] }}")'></span>
						@else
							<span>{{ $content['previousChats'][$i]->agent_details['iconAlt'] }}</span>
						@endif
						</div>
						<p class='AtomizeChat_board_frame_card_name'>{{ $content['previousChats'][$i]->agent_details['name'] }}</p>
						<p class='AtomizeChat_board_frame_card_preview'>{{ $content['previousChats'][$i]->agent_details['mostRecentMessage']->message }}</p>
						<p class='AtomizeChat_board_frame_card_time'>{{ date('jS M y \a\t H:ia', strtotime($content['previousChats'][$i]->agent_details['mostRecentMessage']->date_updated)) }}</p>
					</div>
				@endif
			@endfor

			<div class='AtomizeChat_start_chat' style='border-color: {{ $content['primaryColor'] }};'>
				<p style='color: {{ $content['primaryColor'] }};'>Chat to us about anything</p>
				<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 width="36px" height="36px" viewBox="0 0 36 36" style="enable-background:new 0 0 36 36;" xml:space="preserve">
					<g fill="none" fill-rule="evenodd"><g><polygon points="0 36 36 36 36 0 0 0"></polygon><path d="M31.1059281,19.4468693 L10.3449666,29.8224462 C8.94594087,30.5217547 7.49043432,29.0215929 8.17420251,27.6529892 C8.17420251,27.6529892 10.7473302,22.456697 11.4550902,21.0955966 C12.1628503,19.7344961 12.9730756,19.4988922 20.4970248,18.5264632 C20.7754304,18.4904474 21.0033531,18.2803547 21.0033531,17.9997309 C21.0033531,17.7196073 20.7754304,17.5095146 20.4970248,17.4734988 C12.9730756,16.5010698 12.1628503,16.2654659 11.4550902,14.9043654 C10.7473302,13.5437652 8.17420251,8.34697281 8.17420251,8.34697281 C7.49043432,6.9788693 8.94594087,5.47820732 10.3449666,6.1775158 L31.1059281,16.553593 C32.298024,17.1488555 32.298024,18.8511065 31.1059281,19.4468693" fill="{{ $content['primaryColor'] }}"></path></g></g>
				</svg>
			</div>

		</div>


		@if ($blocks['feedback'] == true)
			<div class='AtomizeChat_board_frame_card' intent='feedback' status='cover'>
				<div class='AtomizeChat_board_frame_card_feedbackOptions'>
					<div class='AtomizeChat_board_frame_card_feedbackOptions_option' type='issue' style='border-color: {{ $content['primaryColor'] }}'>
						<i></i>
						<p>Report an issue</p>
					</div>
					<div class='AtomizeChat_board_frame_card_feedbackOptions_option' type='idea' style='border-color: {{ $content['primaryColor'] }}'>
						<i></i>
						<p>Share an idea</p>
					</div>
				</div>
				<div class='AtomizeChat_board_frame_card_feedbackForm' type='' submitted='false'>
					<div class='AtomizeChat_board_frame_card_feedbackForm_back'></div>
					<p class='AtomizeChat_board_frame_card_feedbackForm_title'></p>
					<textarea class='AtomizeChat_board_frame_card_feedbackForm_input' placeholder=''></textarea>
					<div class='AtomizeChat_board_frame_card_feedbackForm_submit' disabled style='background-color: {{ $content['primaryColor'] }}'>Send feedback</div>
				</div>
				<div class='AtomizeChat_board_frame_card_feedbackSuccess'>
					<p class='AtomizeChat_board_frame_card_feedbackSuccess_icon'></p>
					<p class='AtomizeChat_board_frame_card_feedbackSuccess_title'>Thank you for your feedback!</p>
					<div class='AtomizeChat_board_frame_card_feedbackSuccess_reset' style='background-color: {{ $content['primaryColor'] }}'>Submit more feedback</div>
				</div>
			</div>
		@endif

		<div class='AtomizeChat_board_home_credit'>Powered by<span></span></div>

	</div>


	<div class='AtomizeChat_board_frame' frame='loading'>
		<span class='AtomizeChat_board_frame_body_loader'></span>
	</div>


	<div class='AtomizeChat_board_frame' frame='chat'>
		<div class='AtomizeChat_board_frame_chat_header'>
			@if ($content['iconSRC'] != '')
				<div class='AtomizeChat_board_frame_chat_header_avatar' style='background-image: url("{{ $content['iconSRC'] }}")'></div>
			@elseif ($content['iconAlt'] != '')
				<div class='AtomizeChat_board_frame_chat_header_avatar'>{{ $content['iconAlt'] }}</div>
			@endif
			<p class='AtomizeChat_board_frame_chat_header_name'>{{ $content['teamName'] }}</p>
			<p class='AtomizeChat_board_frame_chat_header_role'>{{ $content['teamDescription'] }}</p>
		</div>
		<div class='AtomizeChat_board_frame_chat_messages' ai='false' composer='normal'>
			<div class='AtomizeChat_board_frame_chat_messages_list' uiColor='{{ $content['primaryColor'] }}'>

				<div class='AtomizeChat_board_frame_chat_messages_list_message_temp sending' from='agent'>
					<div class='AtomizeChat_board_frame_chat_messages_list_message_main'>
						<p></p>
						@if ($content['iconSRC'] != '')
							<div class='AtomizeChat_board_frame_chat_messages_list_message_main_avatar' style='background-image: url("{{ $content['iconSRC'] }}")'></div>
						@elseif ($content['iconAlt'] != '')
							<div class='AtomizeChat_board_frame_chat_messages_list_message_main_avatar'>{{ $content['iconAlt'] }}</div>
						@endif
						<div class='AtomizeChat_board_frame_chat_messages_list_message_details'>
							<p class='AtomizeChat_board_frame_chat_messages_list_message_details_time'>Sending<span></span></p>
						</div>
					</div>
				</div>

			</div>

			{{-- <div class='AtomizeChat_board_frame_chat_messages_AIoptions'>

				<div class='AtomizeChat_board_frame_chat_messages_list_message' from='client'>
					<div class='AtomizeChat_board_frame_chat_messages_list_message_main'>
						<p>Mercedes-Benz</p>
					</div>
					<div class='AtomizeChat_board_frame_chat_messages_list_message_main'>
						<p>AMG</p>
					</div>
					<div class='AtomizeChat_board_frame_chat_messages_list_message_main'>
						<p>Mercedes-Benz Maybach</p>
					</div>
				</div>

			</div> --}}

			<div class='AtomizeChat_board_frame_chat_messages_composer_email'>
				<input type='text' placeholder='your@email.com'/>
				<a class='AtomizeChat_email_send'>
					<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 width="36px" height="36px" viewBox="0 0 36 36" style="enable-background:new 0 0 36 36;" xml:space="preserve">
						<g fill="none" fill-rule="evenodd"><g><polygon points="0 36 36 36 36 0 0 0"></polygon><path d="M31.1059281,19.4468693 L10.3449666,29.8224462 C8.94594087,30.5217547 7.49043432,29.0215929 8.17420251,27.6529892 C8.17420251,27.6529892 10.7473302,22.456697 11.4550902,21.0955966 C12.1628503,19.7344961 12.9730756,19.4988922 20.4970248,18.5264632 C20.7754304,18.4904474 21.0033531,18.2803547 21.0033531,17.9997309 C21.0033531,17.7196073 20.7754304,17.5095146 20.4970248,17.4734988 C12.9730756,16.5010698 12.1628503,16.2654659 11.4550902,14.9043654 C10.7473302,13.5437652 8.17420251,8.34697281 8.17420251,8.34697281 C7.49043432,6.9788693 8.94594087,5.47820732 10.3449666,6.1775158 L31.1059281,16.553593 C32.298024,17.1488555 32.298024,18.8511065 31.1059281,19.4468693" fill="{{ $content['primaryColor'] }}"></path></g></g>
					</svg>
				</a>
			</div>

			<div class='AtomizeChat_board_frame_chat_messages_composer'>
				<textarea placeholder='Write your message here...' rows='1' autocapitalize='on'></textarea>
				<a class='AtomizeChat_send'>
					<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 width="36px" height="36px" viewBox="0 0 36 36" style="enable-background:new 0 0 36 36;" xml:space="preserve">
						<g fill="none" fill-rule="evenodd"><g><polygon points="0 36 36 36 36 0 0 0"></polygon><path d="M31.1059281,19.4468693 L10.3449666,29.8224462 C8.94594087,30.5217547 7.49043432,29.0215929 8.17420251,27.6529892 C8.17420251,27.6529892 10.7473302,22.456697 11.4550902,21.0955966 C12.1628503,19.7344961 12.9730756,19.4988922 20.4970248,18.5264632 C20.7754304,18.4904474 21.0033531,18.2803547 21.0033531,17.9997309 C21.0033531,17.7196073 20.7754304,17.5095146 20.4970248,17.4734988 C12.9730756,16.5010698 12.1628503,16.2654659 11.4550902,14.9043654 C10.7473302,13.5437652 8.17420251,8.34697281 8.17420251,8.34697281 C7.49043432,6.9788693 8.94594087,5.47820732 10.3449666,6.1775158 L31.1059281,16.553593 C32.298024,17.1488555 32.298024,18.8511065 31.1059281,19.4468693" fill="{{ $content['primaryColor'] }}"></path></g></g>
					</svg>
				</a>
			</div>

		</div>
	</div>


	<div class='AtomizeChat_board_frame' frame='allConvos'>
		<div class='AtomizeChat_board_frame_chats_header'>
			<p class='AtomizeChat_board_frame_chats_header_name'>All Conversations</p>
		</div>

		<div class='AtomizeChat_board_frame_chats_messages'>
			<div class='AtomizeChat_board_frame_chats_messages_list'>

				<div class='AtomizeChat_board_frame_chats_message_temp'>
					<div class='AtomizeChat_board_frame_chats_messages_avatar'>
						<span style='background-image: url("")'></span>
					</div>
					<p class='AtomizeChat_board_frame_chats_messages_name'></p>
					<p class='AtomizeChat_board_frame_chats_messages_preview'></p>
					<p class='AtomizeChat_board_frame_chats_messages_time'></p>
				</div>

			</div>
		</div>
	</div>


</div>
