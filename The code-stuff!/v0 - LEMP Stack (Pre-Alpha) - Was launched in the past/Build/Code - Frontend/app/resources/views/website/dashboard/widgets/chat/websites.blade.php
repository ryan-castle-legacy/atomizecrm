<h3>Add New Permitted URL</h3>
<form method='POST' action='{{ route('dashboard-messenger-websites', ['org_token' => $org_token]) }}'>
	@csrf
	URL: https://<input type='text' name='web_url' id='web_url' value=''/>
	<br/>
	<input type='submit' value='Add URL'/>
</form>
<hr/>

<h3>Websites</h3>

@if (@sizeof($websites) > 0)
	@foreach ($websites as $website)
		<pre>{{ var_dump($website) }}</pre>
		<hr/>
	@endforeach
@endif
