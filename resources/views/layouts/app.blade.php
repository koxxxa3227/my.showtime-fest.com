<?php
// Запрещаем кэширование страниц
header("Expires: Mon, 01 Jul 2009 00:20:00 GMT"); // Давно прошедшая дата
header("Cache-Control: no-cache, must-revalidate");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Scripts -->
	{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
	<script src="/js/vue/vue.min.js"></script>
	<script src="/js/vue/axios.min.js"></script>
	<script src="/js/vue/moment-with-locales.js"></script>

	{{--<script src="/js/jquery-3.3.1.min.js"></script>--}}
	<script src="/js/jquery-3.3.1.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

	<script src="/js/jquery-ui-1.12.1.js"></script>
	<script src="/js/popper-1.14.7.min.js"></script>

	{{--<script src="/js/bootstrap-4.3.1.min.js"></script>--}}
	<script src="/js/bootstrap-4.3.1.js"></script>

	<meta HTTP-EQUIV="Expires" CONTENT="0"> 
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	{{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">--}}

	<link id="favicon" rel="shortcut icon" href="{{ asset('uploads/fav.png') }}" sizes="16x16 32x32 48x48"
	      type="image/png">

	<link rel="stylesheet" href="{{ mix( 'css/main.css' ) }}">
	<link rel="stylesheet" href="/css/fix.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	@stack('head')
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
	      integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-138312761-1"></script>
	<script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-138312761-1');
	</script>
</head>
<body>
<div id="app">
	@include('particles.header')
	<main class="py-4 mt-5">
		@if(session('flash'))
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-4">
						<div class="alert alert-{{ session('flash-type') }}" role="alert">
							{!! session('flash') !!}
						</div>
					</div>
				</div>
			</div>
		@endif
		@yield('content')
	</main>
	@if(url()->current() != url('/'))
		@include('particles.footer')
	@endif
	@stack('script')
</div>
</body>
</html>
