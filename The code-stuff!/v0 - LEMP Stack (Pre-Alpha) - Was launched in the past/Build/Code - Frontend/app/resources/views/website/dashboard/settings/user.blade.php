@extends('website.layouts.dashboard')

@section('content')
	@include('website.layouts.accountSettingsSidebar')
	<div id='settingsMain' org='{{ $org_token }}'>
		<div class='settings_convoFade'></div>
		<div class='settingsMain_main settings_form'>

			<h1>Account Settings</h1>

		</div>
	</div>
@endsection
