{{-- <div id='MagicMessenger' loaded='false' open='false' frame='loading' instance='{{ $instanceToken }}'> --}}
<div id='MagicMessenger' loaded='false' open='true' frame='loading' instance='{{ $instanceToken }}'>


	<div class='MagicMessenger_trigger'>
		<div class='MagicMessenger_trigger_button'>
			<p class='MagicMessenger_trigger_button_open'></p>
			<p class='MagicMessenger_trigger_button_close'>Close</p>
		</div>
	</div>

	<div class='MagicMessenger_board'>
		<div class='MagicMessenger_board_main'>

			<div class='MagicMessenger_board_main_body' frame='loading'>
				<span class='MagicMessenger_board_main_body_loader'></span>
			</div>


			<div class='MagicMessenger_board_main_header'>
				<div class='MagicMessenger_board_main_header_back'></div>
				<div class='MagicMessenger_board_main_header_close'></div>
				<div class='MagicMessenger_board_main_header_details'>
					<div class='MagicMessenger_board_main_header_details_logo'>
						<img src='{{ $content['logo'] }}'>
					</div>
					<h1 class='MagicMessenger_board_main_header_details_greeting'>{{ $content['greeting'] }}</h1>
					<h2 class='MagicMessenger_board_main_header_details_description'>{{ $content['description'] }}</h2>
				</div>
			</div>


			<div class='MagicMessenger_board_main_body' frame='home'>

				<div class='MagicMessenger_board_main_body_card' card='conversation'>
				@if (true || @$cards->conversation->hasOngoing == true)

					<div class='MagicMessenger_board_main_body_card_header'>
						<h1>Your conversations</h1>
					</div>

					<div class='MagicMessenger_board_main_body_card_conversations' multipleAgents='false'>

						<div class='MagicMessenger_board_main_body_card_conversations_list'>
							<div class='MagicMessenger_board_main_body_card_conversations_list_ongoing'>
								<div class='MagicMessenger_board_main_body_card_conversations_list_ongoing_avatar'>R</div>
								<p class='MagicMessenger_board_main_body_card_conversations_list_ongoing_name'>
									Ryan Castle
									<span>2m ago</span>
								</p>
								<p class='MagicMessenger_board_main_body_card_conversations_list_ongoing_preview'>
									You: This is just a test message. Please create some more stuff
									<span></span>
								</p>
								<div class='MagicMessenger_board_main_body_card_conversations_list_ongoing_border'></div>
							</div>
							<div class='MagicMessenger_board_main_body_card_conversations_list_ongoing'>
								<div class='MagicMessenger_board_main_body_card_conversations_list_ongoing_avatar'>R</div>
								<p class='MagicMessenger_board_main_body_card_conversations_list_ongoing_name'>
									Ryan Castle
									<span>2m ago</span>
								</p>
								<p class='MagicMessenger_board_main_body_card_conversations_list_ongoing_preview'>
									You: This is just a test message. Please create some more stuff
									<span></span>
								</p>
								<div class='MagicMessenger_board_main_body_card_conversations_list_ongoing_border'></div>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_card_conversations_buttons'>

							<a class='MagicMessenger_btn MagicMessenger_board_main_body_card_conversations_start'>Send us a message<i></i></a>

							@if (true || @sizeof($cards->conversation->previousConversations) > 0)
								<a class='MagicMessenger_btn MagicMessenger_board_main_body_card_conversations_previous'>See previous</a>
							@endif

						</div>
					</div>

				@else

					<div class='MagicMessenger_board_main_body_card_header'>
						<h1>Start a conversation</h1>
						<p>The team typically replies within 10 minutes</p>
					</div>

					<div class='MagicMessenger_board_main_body_card_conversations' multipleAgents='false'>

						<div class='MagicMessenger_board_main_body_card_conversations_agents'>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_agent'>R</div>
							<div class='MagicMessenger_board_main_body_card_conversations_agents_overflow'></div>
						</div>

						<div class='MagicMessenger_board_main_body_card_conversations_buttons'>

							<a class='MagicMessenger_btn MagicMessenger_board_main_body_card_conversations_start'>Send us a message<i></i></a>

							@if (true || @sizeof($cards->conversation->previousConversations) > 0)
								<a class='MagicMessenger_btn MagicMessenger_board_main_body_card_conversations_previous'>See previous</a>
							@endif

						</div>
					</div>

				@endif
				</div>

			</div>


			<div class='MagicMessenger_board_main_body' frame='conversation'>

				<div class='MagicMessenger_board_main_body_head'>
					convo screen
				</div>

				<div class='MagicMessenger_board_main_body_main'>

					<div class='MagicMessenger_board_main_body_main_messages'>

						<div class='MagicMessenger_board_main_body_main_messages_messageTemplate' fromAgent='true'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p></p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'></span>
							</div>
						</div>




						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='true'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='false'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='false'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>asdasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='false'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='true'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd asdasdas asdasd asd asdasdadwa</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='false'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='true'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd asdasdas asdasd asd asdasdadwa</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='false'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='false'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='true'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd asdasdas asdasd asd asdasdadwa</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>

						<div class='MagicMessenger_board_main_body_main_messages_message' fromAgent='true'>
							<div class='MagicMessenger_board_main_body_main_messages_message_content'>
								<p>sdasdasdsadwwasd asdasd asdasdas asdasd asd asdasdadwa</p>
								<span class='MagicMessenger_board_main_body_main_messages_message_content_date'>12:34pm</span>
							</div>
						</div>


					</div>

					<div class='MagicMessenger_board_main_body_main_input'>
						<textarea placeholder='Write your message here' rows='1' autocapitalize='on'></textarea>
						<a class='MagicMessenger_board_main_body_main_send'></a>
					</div>

				</div>

			</div>


		</div>
	</div>

</div>
