<!DOCTYPE html>
<html lang="{{Config::get('app.locale');}}" manifest=".appcache">
    @include('core.head')
    @include('core.navigation')
<body>
	@yield('content')
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js') }}
	{{ HTML::script('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js') }}
	@include('core.footer')
</body>
</html>
