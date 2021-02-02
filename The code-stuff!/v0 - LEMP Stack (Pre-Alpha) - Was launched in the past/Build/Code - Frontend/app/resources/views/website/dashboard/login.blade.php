@extends('website.layouts.public')

@section('content')
	<section id='login'>
		<div class='container'>

			<h1>Login</h1>

			@if ($errors->any())
				<pre>{{ var_dump($errors->all()) }}</pre>
			@endif

			<form method='POST' action='{{ route('dashboard-login') }}'>
				@csrf
				Email: <input type='email' name='email' id='email' value='{{ old('email') }}'/>
				<br/>
				Password: <input type='password' name='password' id='password'/>
				<br/>
				<input type='checkbox' name='remember' id='remember'>
				<label for='remember'>Keep me logged in</label>
				<br/>
				<input type='submit' value='Login'/>
			</form>


		</div>
		<svg viewBox='0 0 100 100' preserveAspectRatio='none'><polygon points='0,100 100,0 100,100'/></svg>
	</section>



@endsection
