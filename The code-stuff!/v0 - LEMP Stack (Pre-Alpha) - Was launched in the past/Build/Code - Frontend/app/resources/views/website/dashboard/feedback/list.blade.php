<h3>Feedback</h3>

@if (@sizeof($feedback) > 0)
	@foreach ($feedback as $item)
		<pre>{{ var_dump($item) }}</pre>
		<hr/>
	@endforeach
@endif
