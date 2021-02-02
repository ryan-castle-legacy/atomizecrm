<h3>Create New User</h3>
<form method='POST' action='{{ route('admin-users') }}'>
	@csrf
	First Name: <input type='text' name='firstname' id='firstname' value='{{ old('firstname') }}'/>
	<br/>
	Last Name: <input type='text' name='lastname' id='lastname' value='{{ old('lastname') }}'/>
	<br/>
	Email: <input type='email' name='email' id='email' value='{{ old('email') }}'/>
	<br/>
	Password: <input type='text' name='password' id='password'/>
	<br/>
	<input type='submit' value='Create User'/>
</form>
<hr/>

<h3>Users</h3>

@foreach ($users as $user)
	<pre>{{ var_dump($user) }}</pre>
	<hr/>
@endforeach
