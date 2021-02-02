<h3>Create New Organisation</h3>
<form method='POST' action='{{ route('admin-organisations') }}'>
	@csrf
	Name: <input type='text' name='org_name' id='org_name' value='{{ old('org_name') }}'/>
	<br/>
	<input type='submit' value='Create Organisation'/>
</form>
<hr/>

<h3>Organisations</h3>

@foreach ($orgs as $org)
	<pre>{{ var_dump($org) }}</pre>

	<h4>Add Agent</h4>
	<form method='POST' action='{{ route('admin-organisations-agents') }}'>
		@csrf
		<input type='hidden' name='org_id' value='{{ $org->id }}'/>
		User Id: <select name='user_id'>
		@foreach ($users as $user)
			<option value='{{ $user->id }}'>{{ $user->firstname.' '.$user->lastname.' [User ID: '.$user->id.']' }}</option>
		@endforeach
		</select>
		<br/>
		<input type='checkbox' name='is_admin' id='is_admin'/> <label>Is user owner?</label>
		<br/>
		<input type='submit' value='Add Agent'/>
	</form>
	@foreach ($org->agents as $agent)
		<hr/>
		<form method='POST' action='{{ route('admin-organisations-agents-update') }}'>
			@csrf
			<input type='hidden' name='org_id' value='{{ $agent->org_id }}'/>
			<input type='hidden' name='user_id' value='{{ $agent->user_id }}'/>

			<label>Agent name</label>
			@if (@$agent->name != '')
				<input name='agent_name' id='agent_name' value='{{ $agent->name }}'/>
			@else
				<input name='agent_name' id='agent_name' value='{{ $agent->details->firstname.' '.$agent->details->lastname }}'/>
			@endif
			<br/>

			<label>Agent role</label>
			@if (@$agent->role != '')
				<input name='agent_role' id='agent_role' value='{{ $agent->role }}'/>
			@else
				<input name='agent_role' id='agent_role' value='Customer Service Agent'/>
			@endif
			<br/>

			<label>Agent avatar</label>
			@if (@$agent->iconSRC != '')
				<input name='agent_iconSRC' id='agent_iconSRC' value='{{ $agent->iconSRC }}'/>
			@else
				<input name='agent_iconSRC' id='agent_iconSRC' value=''/>
			@endif
			<br/>

			@if (@$agent->is_admin == true)
				<input type='checkbox' name='is_admin' id='is_admin' checked/> <label>Is user owner?</label>
			@else
				<input type='checkbox' name='is_admin' id='is_admin'/> <label>Is user owner?</label>
			@endif

			<br/>
			<input type='submit' value='Update Agent'/>
		</form>
	@endforeach

	<hr/>
@endforeach
