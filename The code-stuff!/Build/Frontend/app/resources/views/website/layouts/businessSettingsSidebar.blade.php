<div id='businessSettingsSidebar'>

	<h1>{{ $org->name }}'s Settings</h1>

	<div class='links'>

		@if (@$navGroup == 'businessSettings_profile')
			<a href='{{ route('dashboard-business-profile', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-user-tie'></i>Your Profile</a>
		@else
			<a href='{{ route('dashboard-business-profile', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-user-tie'></i>Your Profile</a>
		@endif

		@if (@$navGroup == 'businessSettings_general')
			<a href='{{ route('dashboard-business-settings', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-building'></i>Business Settings</a>
		@else
			<a href='{{ route('dashboard-business-settings', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-building'></i>Business Settings</a>
		@endif

		<a href='{{ route('dashboard-settings-billing', ['org_token' => $org_token]) }}' target='_blank' class='nav_link'><i class='fad fa-money-bill-alt'></i>Billing Settings</a>

		@if (@$navGroup == 'businessSettings_messenger')
			<a href='{{ route('dashboard-messenger-settings', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-comments-alt'></i>Messenger Settings</a>
		@else
			<a href='{{ route('dashboard-messenger-settings', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-comments-alt'></i>Messenger Settings</a>
		@endif

		@if (@$navGroup == 'businessSettings_agents')
			<a href='{{ route('dashboard-settings-agents', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-users'></i>Manage Staff</a>
		@else
			<a href='{{ route('dashboard-settings-agents', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-users'></i>Manage Staff</a>
		@endif

	</div>

</div>
