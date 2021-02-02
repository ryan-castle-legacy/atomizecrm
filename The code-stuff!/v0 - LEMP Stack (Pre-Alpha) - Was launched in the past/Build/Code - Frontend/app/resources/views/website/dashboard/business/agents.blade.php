@extends('website.layouts.dashboard')

@section('content')
	@include('website.layouts.businessSettingsSidebar')
	<div id='settingsMain' org='{{ $org_token }}'>
		<div class='settings_convoFade'></div>
		<div class='settingsMain_main'>
			<pre>
				{{ var_dump($org) }}
			</pre>

		</div>
	</div>
@endsection
