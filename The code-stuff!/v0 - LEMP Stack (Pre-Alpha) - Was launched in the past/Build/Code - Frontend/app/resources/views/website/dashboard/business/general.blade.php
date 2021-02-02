@extends('website.layouts.dashboard')

@section('content')
	@include('website.layouts.businessSettingsSidebar')
	<div id='settingsMain' org='{{ $org_token }}'>
		<div class='settings_convoFade'></div>
		<div class='settingsMain_main settings_form'>

			<h1>Business Settings</h1>

			<form id='businessSettingsForm' action='{{ route('dashboard-business-settings', ['org_token' => $org_token]) }}' method='POST'>
				@csrf

				<h3>Office Times</h3>

				<input type='hidden' name='timezone' value=''/>

				Mon AM (Morning)
				<input name='mon_am' value='{{ old('mon_am') }}' placeholder='0900-1200'/>
				<br/>
				Mon PM (Afternoon)
				<input name='mon_pm' value='{{ old('mon_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>

				Tue AM (Morning)
				<input name='tue_am' value='{{ old('tue_am') }}' placeholder='0900-1200'/>
				<br/>
				Tue PM (Afternoon)
				<input name='tue_pm' value='{{ old('tue_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>

				Wed AM (Morning)
				<input name='wed_am' value='{{ old('wed_am') }}' placeholder='0900-1200'/>
				<br/>
				Wed PM (Afternoon)
				<input name='wed_pm' value='{{ old('wed_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>

				Thu AM (Morning)
				<input name='thu_am' value='{{ old('thu_am') }}' placeholder='0900-1200'/>
				<br/>
				Thu PM (Afternoon)
				<input name='thu_pm' value='{{ old('thu_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>

				Fri AM (Morning)
				<input name='fri_am' value='{{ old('fri_am') }}' placeholder='0900-1200'/>
				<br/>
				Fri PM (Afternoon)
				<input name='fri_pm' value='{{ old('fri_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>

				Sat AM (Morning)
				<input name='sat_am' value='{{ old('sat_am') }}' placeholder='0900-1200'/>
				<br/>
				Sat PM (Afternoon)
				<input name='sat_pm' value='{{ old('sat_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>

				Sun AM (Morning)
				<input name='sun_am' value='{{ old('sun_am') }}' placeholder='0900-1200'/>
				<br/>
				Sun PM (Afternoon)
				<input name='sun_pm' value='{{ old('sun_pm') }}' placeholder='1230-1700'/>

				<br/>
				<br/>


			</form>

			<input type='submit' id='businessSettingsForm_submit' value='Save'/>

		</div>
	</div>
@endsection
