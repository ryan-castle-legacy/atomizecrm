<h3>Convo</h3>

<form method='POST' action='{{ route('dashboard-messenger-chats-chat-assignAgent', ['convo_token' => $convo->token, 'org_token' => $org_token]) }}'>
	@csrf
	<h4>Assigned Agent:
		<select name='assigned_agent'>
			<option value='not_assigned'>Not assigned</option>
			@for ($i = 0; $i < sizeof($agents); $i++)
				@if ($convo->assigned_agent == $agents[$i]->user_id)
					<option value='{{ $agents[$i]->user_id }}' selected>{{ $agents[$i]->firstname.' '.$agents[$i]->lastname }}</option>
				@else
					<option value='{{ $agents[$i]->user_id }}'>{{ $agents[$i]->firstname.' '.$agents[$i]->lastname }}</option>
				@endif
			@endfor
		</select>
		<button type='submit'>Save</button>
	</h4>
</form>

@if ($convo->assigned_agent === Auth::user()->id)
	<hr/>
	<form method='POST' action='{{ route('dashboard-messenger-chats-chat-sendMessage', ['convo_token' => $convo->token, 'org_token' => $org_token]) }}'>
		@csrf
		<input type='text' name='message' placeholder='Type the message...'/>
		<button type='submit'>Send</button>
	</form>
@endif

<hr/>

@if (@sizeof($messages) > 0)
	@foreach ($messages as $message)
		<pre>{{ var_dump($message) }}</pre>
		<hr/>
	@endforeach
@endif
