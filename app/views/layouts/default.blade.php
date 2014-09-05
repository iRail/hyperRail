<!DOCTYPE html>
<html lang="{{Config::get('app.locale');}}">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		@section('header')
			@include('core.head')
		@show
		<link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>
		<link rel="stylesheet" href="{{ URL::asset('builds/css/main.css') }}">
	</head>
<body>
	@yield('content')
	@include('core.footer')
	{{ HTML::script('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js') }}
	@yield('pageScripts')
</body>
</html>
