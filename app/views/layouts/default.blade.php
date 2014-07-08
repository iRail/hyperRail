<!DOCTYPE html>
<html lang="{{Config::get('app.locale');}}" ng-app="irailapp" ng-controller="PlannerCtrl">
    @include('core.head')
<body>
	@yield('content')
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js') }}
	{{ HTML::script('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js') }}
</body>
</html>