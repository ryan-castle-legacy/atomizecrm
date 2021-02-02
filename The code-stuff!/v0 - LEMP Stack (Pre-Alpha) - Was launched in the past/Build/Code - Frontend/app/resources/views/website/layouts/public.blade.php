<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
		<meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no'/>
		<meta name='csrf-token' content='{{ csrf_token() }}'/>

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

		<script async src='https://www.googletagmanager.com/gtag/js?id=UA-175640763-1'></script>
		<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','UA-175640763-1');</script>

		<script>(function(h,o,t,j,a,r){h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};h._hjSettings={hjid:1950573,hjsv:6};a=o.getElementsByTagName('head')[0];r=o.createElement('script');r.async=1;r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;a.appendChild(r);})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');</script>

		@if (!isset($pageTitle))
			<title>Powering business growth through meaningful customer experiences - AtomizeCRM</title>
		@else
			<title>{{ $pageTitle }} - AtomizeCRM</title>
		@endif
	</head>
	<body>
		@include('website.layouts.navbar')
		@yield('content')
		@include('website.layouts.publicFooter')
	</body>
</html>
