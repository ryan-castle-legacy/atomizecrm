@extends('website.layouts.userAccounts')

@section('content')
	<section>
		<div class='container'>
            <h1>Create or Join an Oranisation</h1>

            @if ($errors->any())
                <pre>{{ var_dump($errors->all()) }}</pre>
            @endif

			<h3>Join an Oranisation</h3>
			<p><small>To join an existing Organisation, you should have received an email with a link to join-up.</small></p>
			<p><small>If you've not received this email, please ask your Organisation's administrator to re-send it.</small></p>
			<hr/>

			<h3>Create an Oranisation</h3>
            <form method='POST' action='{{ route('organisations-userCreate') }}'>
                @csrf
				<label for='name'>Your Organisation's name</label><br/>
                <input type='text' name='name' id='name' value='{{ old('name') }}' placeholder='e.g Acme Corp LLC' required/>
                <br/>
                <input type='submit' value='Create my Organisation'/>
            </form>
        </div>
	</section>
@endsection
