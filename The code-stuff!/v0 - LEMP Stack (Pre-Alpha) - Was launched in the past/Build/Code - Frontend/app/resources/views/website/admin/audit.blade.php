<h3>Audit Log</h3>

@foreach ($audit as $item)
	<pre>{{ var_dump($item) }}</pre>
	<hr/>
@endforeach
