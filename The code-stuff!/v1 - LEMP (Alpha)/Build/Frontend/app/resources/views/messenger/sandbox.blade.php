<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'/>
		<meta name='csrf-token' content='{{ csrf_token() }}'/>

		<link rel='stylesheet' type='text/css' href='{{ asset('css/sandbox-styles.css') }}'/>
		<script src='{{ asset('js/sandbox-script.js') }}'></script>


		<link rel='icon' type='image/x-icon' href='{{ asset('favicon.ico') }}'/>
		<link rel='shortcut icon' type='image/x-icon' href='{{ asset('favicon.ico') }}'/>

		<title>Widget Sandbox - AtomizeCRM</title>

	</head>
	<body>

		<div id='sandbox'>
		@php for ($i = 0; $i < 504; $i++) {
			echo "<a class='sandbox-square'></a>";
		} @endphp
		</div>

		<!-- AtomizeCRM Messenger Widget -->
		<script src='{{ env('APP_URL') }}/widgets/messenger.min.js' defer></script>
		<script>window.AtomizeCRMToken='71635510e4ab21194679bd6e9e9f0f01e79c8c63';</script>
		<!-- End AtomizeCRM Messenger Widget -->

	</body>
</html>
