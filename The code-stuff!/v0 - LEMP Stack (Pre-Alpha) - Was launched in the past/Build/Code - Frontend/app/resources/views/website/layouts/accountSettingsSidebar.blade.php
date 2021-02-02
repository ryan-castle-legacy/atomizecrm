<div id='businessSettingsSidebar'>

	<h1>Your Account Settings</h1>

	<div class='links'>

		@if (@$navGroup == 'accountSettings_main')
			<a href='{{ route('dashboard-settings-account', ['org_token' => $org_token]) }}' class='nav_link current'><i class='fad fa-cog'></i>Your Account</a>
		@else
			<a href='{{ route('dashboard-settings-account', ['org_token' => $org_token]) }}' class='nav_link'><i class='fad fa-cog'></i>Your Account</a>
		@endif

		<a class='nav_link nav_link_soon'><i class='fad fa-plug'></i>Integrations<span>Coming soon</span></a>

	</div>

</div>
