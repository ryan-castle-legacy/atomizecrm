{{-- <p>
	@if (Auth::check())
		<a href='{{ route('dashboard-home') }}'>Dashboard</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-logout') }}'>Log Out</a>

		@if (Auth::user()->email == 'xxx')
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='{{ route('admin-widgets') }}'>Widget Sessions</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='{{ route('admin-users') }}'>User List</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='{{ route('admin-organisations') }}'>Organisation List</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='{{ route('admin-chats') }}'>Chats List</a>
		@endif
	@else
		<a href='{{ route('dashboard-login') }}'>Login</a>
		{{-- &nbsp;&nbsp;&nbsp;&nbsp;
		<a href='{{ route('dashboard-register') }}'>Register</a> -- }}
	@endif
</p> --}}

{{-- @foreach ($orgs as $org)
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
@endforeach --}}


<nav>
	<div class='container'>
		<a href='{{ route('public-home') }}' class='logo'></a>

		<div class='links'>
		@if (Auth::check())
			<a href='{{ route('dashboard-home') }}' class='nav_button'>Dashboard</a>
		@else
			<a href='{{ route('dashboard-login') }}' class='nav_link'>Login</a>
			<a href='[openatomizenewchat]' data='I would love to grow my business using AtomizeCRM! My email address address is: ' class='nav_button'>Register</a>
		@endif
		</div>
	</div>
</nav>
