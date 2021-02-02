@extends('website.layouts.dashboard')

@section('content')
	@include('website.layouts.businessSettingsSidebar')
	<div id='settingsMain' org='{{ $org_token }}'>
		<div class='settings_convoFade'></div>
		<div class='settingsMain_main'>

			<h3>Set Chat Settings</h3>
			<form method='POST' action='{{ route('dashboard-messenger-settings', ['org_token' => $org_token]) }}'>
				@csrf


				<hr/>
				<input type='checkbox' name='startChatWithAI' id='startChatWithAI' disabled/> <label>Start with AI message?</label>
				<label>AI welcome message</label>
				@if (@$settings->startChatWithAI_message != '')
					<textarea name='startChatWithAI_message' style='width: 100%'  id='startChatWithAI_message'>{{ $settings->startChatWithAI_message }}</textarea>
				@else
					<textarea name='startChatWithAI_message' style='width: 100%' id='startChatWithAI_message'>Welcome! 👋 What can I help you with today?</textarea>
				@endif
				<hr/>

				<label>Out-of-office message</label>
				@if (@$settings->outOfOffice_message != '')
					<textarea name='outOfOffice_message' style='width: 100%'  id='outOfOffice_message'>{{ $settings->outOfOffice_message }}</textarea>
				@else
					<textarea name='outOfOffice_message' style='width: 100%' id='outOfOffice_message'>Hey 👋 Our team are currently unavailable for live chat, however we will contact you as soon as we are free!</textarea>
				@endif

				</br></br>
				<label>Out-of-office request email address message</label>
				@if (@$settings->outOfOffice_requestEmail != '')
					<textarea name='outOfOffice_requestEmail' style='width: 100%'  id='outOfOffice_requestEmail'>{{ $settings->outOfOffice_requestEmail }}</textarea>
				@else
					<textarea name='outOfOffice_requestEmail' style='width: 100%' id='outOfOffice_requestEmail'>Please enter your email address.</textarea>
				@endif

				</br></br>
				<label>Out-of-office request details of query</label>
				@if (@$settings->outOfOffice_requestDetails != '')
					<textarea name='outOfOffice_requestDetails' style='width: 100%'  id='outOfOffice_requestDetails'>{{ $settings->outOfOffice_requestDetails }}</textarea>
				@else
					<textarea name='outOfOffice_requestDetails' style='width: 100%' id='outOfOffice_requestDetails'>Thanks! Please type your query, we'll get in touch with you ASAP.</textarea>
				@endif

				<hr/>

				@if (@$settings->widget_primaryColour != '')
					Brand primary colour: <input type='text' name='widget_primaryColour' id='widget_primaryColour' value='{{ $settings->widget_primaryColour }}'/>
				@else
					Brand primary colour: <input type='text' name='widget_primaryColour' id='widget_primaryColour' value='#313131'/>
				@endif
				</br>
				@if (@$settings->widget_primaryAlt != '')
					Brand text colour: <input type='text' name='widget_primaryAlt' id='widget_primaryAlt' value='{{ $settings->widget_primaryAlt }}'/>
				@else
					Brand text colour: <input type='text' name='widget_primaryAlt' id='widget_primaryAlt' value='#ffffff'/>
				@endif
				</br>

				<hr/>

				@if (@$settings->supportEmailAddress != '')
					Support email address: <input type='text' name='supportEmailAddress' id='supportEmailAddress' value='{{ $settings->supportEmailAddress }}'/>
				@else
					Support email address: <input type='text' name='supportEmailAddress' id='supportEmailAddress' value='{{ Auth::user()->email }}'/>
				@endif


				<hr/>

				@if (@$settings->widget_logoSRC != '')
					Brand Logo URL: <input type='text' name='widget_logoSRC' id='widget_logoSRC' value='{{ $settings->widget_logoSRC }}'/>
				@else
					Brand Logo URL: <input type='text' name='widget_logoSRC' id='widget_logoSRC' value=''/>
				@endif
				</br>

				@if (@$settings->widget_iconSRC != '')
					Brand icon URL: <input type='text' name='widget_iconSRC' id='widget_iconSRC' value='{{ $settings->widget_iconSRC }}'/>
				@else
					Brand icon URL: <input type='text' name='widget_iconSRC' id='widget_iconSRC' value=''/>
				@endif
				</br>

				@if (@$settings->widget_iconAlt != '')
					Brand icon (instead of image): <input type='text' name='widget_iconAlt' id='widget_iconAlt' value='{{ $settings->widget_iconAlt }}'/>
				@else
					Brand icon (instead of image): <input type='text' name='widget_iconAlt' id='widget_iconAlt' value=''/>
				@endif
				</br>

				@if (@$settings->widget_greeting != '')
					Greeting text: <input type='text' name='widget_greeting' id='widget_greeting' value='{{ $settings->widget_greeting }}'/>
				@else
					Greeting text: <input type='text' name='widget_greeting' id='widget_greeting' value='How can we help?'/>
				@endif
				</br>
				@if (@$settings->widget_description != '')
					Support description: <input type='text' name='widget_description' id='widget_description' value='{{ $settings->widget_description }}'/>
				@else
					Support description: <input type='text' name='widget_description' id='widget_description' value="We are here to help."/>
				@endif
				</br>
				<hr/>

				@if (@$settings->widget_teamName != '')
					Business Name: <input type='text' name='widget_teamName' id='widget_teamName' value='{{ $settings->widget_teamName }}'/>
				@else
					Business Name: <input type='text' name='widget_teamName' id='widget_teamName' value=''/>
				@endif
				</br>

				@if (@$settings->widget_teamDescription != '')
					Support Team Description: <input type='text' name='widget_teamDescription' id='widget_teamDescription' value='{{ $settings->widget_teamDescription }}'/>
				@else
					Support Team Description: <input type='text' name='widget_teamDescription' id='widget_teamDescription' value='Customer Service Team'/>
				@endif
				</br>

				<hr/>


				@if (@$settings->widget_feedbackWidget == true)
					<input type='checkbox' name='widget_feedbackWidget' id='widget_feedbackWidget' checked/> <label>Show feedback block?</label>
				@else
					<input type='checkbox' name='widget_feedbackWidget' id='widget_feedbackWidget'/> <label>Show feedback block?</label>
				@endif


				<hr/>
				</br>

				<input type='submit' value='Save Chat Settings'/>
			</form>

		</div>
	</div>
@endsection
