<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'/>
		<meta name='csrf-token' content='{{ csrf_token() }}'/>
		<meta name='organisation-id' content='{{ @Session::get('dashboard-currentOrg') }}'/>

		<link rel='stylesheet' type='text/css' href='{{ asset('css/styles.css') }}'/>
		<link rel='stylesheet' type='text/css' href='{{ asset('css/fontawesome.min.css') }}'/>

		<script src='{{ asset('js/jquery.slim.min.js') }}'></script>
		<script src='{{ asset('js/script.js') }}'></script>

		<link rel='icon' type='image/x-icon' href='{{ asset('favicon.ico') }}'/>
		<link rel='shortcut icon' type='image/x-icon' href='{{ asset('favicon.ico') }}'/>

		<!--
		@if (env('APP_URL') == 'http://18.130.253.250')
			<script src='http://18.130.253.250/chat-widget/main.min.js' defer></script>
		@else
			<script src='https://atomizecrm.com/chat-widget/main.min.js' defer></script>
		@endif
		-->

		@if (!isset($pageTitle))
			<title>AtomizeCRM</title>
		@else
			<title>{{ $pageTitle }} - AtomizeCRM</title>
		@endif
	</head>
	@switch(@Auth::user()->dashboard_theme_setting)
		@case('light')
			<body class='theme-color-light'>
		@break
		@case('dark')
			<body class='theme-color-dark'>
		@break
		@default
			<body class='theme-color-dark'>
		@break
	@endswitch

		@include('website.layouts.dashboard.navigationBar')
		@yield('content')
		@include('website.layouts.dashboard.DOMTemplates')
	</body>
</html>
