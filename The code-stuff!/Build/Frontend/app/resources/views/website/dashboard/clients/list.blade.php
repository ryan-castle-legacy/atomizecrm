<h3>Clients</h3>

@if (@sizeof($clients) > 0)
	@foreach ($clients as $client)
		<pre>{{ var_dump($client) }}</pre>
		<hr/>
	@endforeach
@endif
