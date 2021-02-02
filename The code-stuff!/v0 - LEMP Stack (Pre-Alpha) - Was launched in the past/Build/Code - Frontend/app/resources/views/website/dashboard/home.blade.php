@extends('website.layouts.dashboard')

@section('content')
	<h1>Dashboard Home</h1>

	<hr/>

	@foreach ($orgs as $org)
		<h3>{{ $org->name }}</h3>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-messenger-chats', ['org_token' => $org->token]) }}'>Chats</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-messenger-clients', ['org_token' => $org->token]) }}'>Clients</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-messenger-settings', ['org_token' => $org->token]) }}'>Messenger Settings</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-messenger-websites', ['org_token' => $org->token]) }}'>Permitted Websites</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-feedback', ['org_token' => $org->token]) }}'>Feedback</a>
		<br/>
	@endforeach
@endsection
