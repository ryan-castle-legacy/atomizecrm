<nav id='navDash'>
	@if (Auth::check())
		<a href='{{ route('dashboard-home') }}' class='logo'></a>
	@else
		<a href='{{ route('public-home') }}' class='logo'></a>
	@endif

	@if (Auth::check())
	<div class='links'>

		{{-- <a href='{{ route('dashboard-home', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-tachometer'></i>Dashboard</a> --}}
		<a class='nav_link nav_link_soon'><i class='fad fa-tachometer'></i>Dashboard<span>Coming soon</span></a>

		{{-- <a class='nav_link nav_link_soon'><i class='fad fa-store'></i>Sales<span>Coming soon</span></a> --}}

		{{-- @if (@$navGroup == 'clients')
			<a href='{{ route('dashboard-messenger-clients', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-user'></i>Clients</a>
		@else
			<a href='{{ route('dashboard-messenger-clients', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-user'></i>Clients</a>
		@endif --}}
		<a class='nav_link nav_link_soon'><i class='fad fa-user'></i>Clients<span>Coming soon</span></a>

		@if (@$navGroup == 'chats')
			<a href='{{ route('dashboard-messenger-chats', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-comments-alt'></i>Support Chats</a>
		@else
			<a href='{{ route('dashboard-messenger-chats', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-comments-alt'></i>Support Chats</a>
		@endif

		<a class='nav_link nav_link_soon'><i class='fad fa-head-side-brain'></i>Knowledge Base<span>Coming soon</span></a>

		<a class='nav_link nav_link_soon'><i class='fad fa-calendar-check'></i>Appointments<span>Coming soon</span></a>

		{{-- <a class='nav_link nav_link_soon'><i class='fad fa-analytics'></i>Analytics<span>Coming soon</span></a> --}}

		{{-- @if (@$navGroup == 'feedback')
			<a href='{{ route('dashboard-feedback', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-lightbulb-on'></i>Feedback</a>
		@else
			<a href='{{ route('dashboard-feedback', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-lightbulb'></i>Feedback</a>
		@endif --}}
		<a class='nav_link nav_link_soon'><i class='fad fa-lightbulb'></i>Feedback<span>Coming soon</span></a>

	</div>



	<div class='accountLinks'>

		<div class='accountLinks_businessDropdown'>
			<div class='accountLinks_businessDropdown_cover'>
				<span class='accountLinks_businessDropdown_cover_avatar'><i class='fad fa-store'></i></span>
				<p class='accountLinks_businessDropdown_cover_name'>{{ $org->name }}</p>
			</div>
			<div class='accountLinks_businessDropdown_dropdown'>
				<a href='{{ route('dashboard-business-profile', ['org_token' => $org_token]) }}' class='nav_dropdown_link'><i class='fad fa-user-tie'></i>Your Profile</a>
				<a href='{{ route('dashboard-business-settings', ['org_token' => $org_token]) }}' class='nav_dropdown_link'><i class='fad fa-building'></i>Business Settings</a>
				<a href='{{ route('dashboard-settings-billing', ['org_token' => $org_token]) }}' target='_blank' class='nav_dropdown_link'><i class='fad fa-money-bill-alt'></i>Billing Settings</a>
				<a href='{{ route('dashboard-messenger-settings', ['org_token' => $org_token]) }}' class='nav_dropdown_link'><i class='fad fa-comments-alt'></i>Messenger Settings</a>
				<a href='{{ route('dashboard-settings-agents', ['org_token' => $org_token]) }}' class='nav_dropdown_link'><i class='fad fa-users'></i>Manage Staff</a>
			</div>
		</div>

		<div class='accountLinks_userDropdown'>
			<div class='accountLinks_userDropdown_cover'>
				<span class='accountLinks_userDropdown_cover_avatar'><i class='fad fa-user-circle'></i></span>
				<p class='accountLinks_userDropdown_cover_name'>{{ Auth::user()->firstname.' '.Auth::user()->lastname }}</p>
				<p class='accountLinks_userDropdown_cover_email'>{{ Auth::user()->email }}</p>
			</div>
			<div class='accountLinks_userDropdown_dropdown'>
				<a href='{{ route('dashboard-settings-account', ['org_token' => $org_token]) }}' class='nav_dropdown_link'><i class='fad fa-cog'></i>Account Settings</a>
				<a href='{{ route('dashboard-logout') }}' class='nav_dropdown_link'><i class='fad fa-sign-out-alt'></i>Sign Out</a>

				@if (Auth::user()->email == 'xxx')
					</br>
					<a href='{{ route('admin-widgets') }}' class='nav_dropdown_link'><i class='fad fa-user-crown'></i>Widget Sessions</a>
					<a href='{{ route('admin-users') }}' class='nav_dropdown_link'><i class='fad fa-user-crown'></i>User List</a>
					<a href='{{ route('admin-organisations') }}' class='nav_dropdown_link'><i class='fad fa-user-crown'></i>Organisation List</a>
					<a href='{{ route('admin-chats') }}' class='nav_dropdown_link'><i class='fad fa-user-crown'></i>Chats List</a>
					<a href='{{ route('admin-audit') }}' class='nav_dropdown_link'><i class='fad fa-user-crown'></i>Audit Log</a>
				@endif
			</div>
		</div>

	</div>
	@endif
</nav>
