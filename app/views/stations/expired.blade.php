<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>iRail.be</title>
    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap-sass/lib/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('bower_components/fontawesome/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('bower_components/animate.css/animate.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">

    <script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular/angular.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular-animate/angular-animate.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/bootstrap-sass/dist/js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular-bootstrap/ui-bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/irailapp/app.js') }}"></script>
</head>
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row max-w5" >
                <div class="col-md-12 col-sm-12">
                    <div class="well">
                        <p class="center h1"><i class="fa fa-hand-o-right fa-3x center"></i>
                        </p>
                        <h3><strong>Oops</strong>: {{Lang::get('client.ExpiredErrorTitle')}}</h3>
                        <p>{{Lang::get('client.ExpiredExplanation')}} <a href="http://archive.irail.be">archive.irail.be</a>.</p>
                        <br/>
                        <a href="/" class="btn btn-danger btn-lg btn-wide"><i class="fa fa-chevron-left"></i> {{Lang::get('client.goHome')}}</a>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>
