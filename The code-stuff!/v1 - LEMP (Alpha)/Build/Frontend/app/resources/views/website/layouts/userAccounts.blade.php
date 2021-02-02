<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'/>
		<meta name='csrf-token' content='{{ csrf_token() }}'/>

		<!--
		<link rel='stylesheet' type='text/css' href='{{ asset('css/styles.css') }}'/>
		<script src='{{ asset('js/jquery.min.js') }}'></script>
		<script src='{{ asset('js/script.js') }}'></script>

		<link rel='icon' type='image/x-icon' href='{{ asset('favicon.ico') }}'/>
		<link rel='shortcut icon' type='image/x-icon' href='{{ asset('favicon.ico') }}'/>

		<link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.14.0/css/all.css' integrity='sha384-VhBcF/php0Z/P5ZxlxaEx1GwqTQVIBu4G4giRWxTKOCjTxsPFETUDdVL5B6vYvOt' crossorigin='anonymous'>

		@if (env('APP_URL') == 'http://18.130.253.250')
			<script src='http://18.130.253.250/chat-widget/main.min.js' defer></script>
		@else
			<script src='https://atomizecrm.com/chat-widget/main.min.js' defer></script>
		@endif
		-->

		@if (!isset($pageTitle))
			<title>Powering business growth through meaningful customer experiences - AtomizeCRM</title>
		@else
			<title>{{ $pageTitle }} - AtomizeCRM</title>
		@endif
	</head>
	<body>
		@include('website.layouts.userAccounts.navigationBar')
		@yield('content')
		@include('website.layouts.userAccounts.footer')
	</body>
</html>
