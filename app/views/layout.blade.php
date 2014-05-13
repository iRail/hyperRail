<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iRail.be</title>
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap-theme.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('js/chosen_v1.1.0/chosen.min.css') }}">
    <script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('js/chosen_v1.1.0/chosen.jquery.js') }}"></script>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}"><img src="{{ URL::asset('images/logo.svg') }}"</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ URL::to('/') }}">Home</a></li>
                <li><a href="{{ URL::to('/stations') }}">Stations</a></li>
                <li><a href="{{ URL::to('/route') }}">Route</a></li>
                <li><a href="{{ URL::to('/apitest') }}">API Test</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<div class="container">
    <div>
        @yield('content')
    </div>
    <div class="row">
        <div class="col-sm-12">
                <a href="/about">Find out more about iRail.</a>
        </div>
    </div>
</div>
</body>
</html>