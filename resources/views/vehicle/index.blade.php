<!DOCTYPE html>
<html lang="{{ Config::get('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@iRail">
    <meta name="twitter:creator" content="@iRail">
    <meta name="twitter:title" content="iRail | {{$train_id}} to {{ end($stops)['station'] }}">
    <meta name="twitter:domain" content="https://iRail.be">
    <meta name="twitter:description"
          content="Train {{$train_id}} from {{$stops[0]['station']}} to {{ end($stops)['station'] }}">
    <meta name="twitter:image" content="{{ URL::asset('images/train.jpg') }}">
    <meta property="og:title" content="iRail.be"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://irail.be"/>
    <meta property="og:image" content="{{ URL::asset('apple-touch-icon-precomposed.png') }}"/>
    <title>iRail | {{$train_id}} to {{ end($stops)['station'] }}</title>
    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>

    <link rel="stylesheet" href="{{ URL::asset('builds/css/main.css') }}">

    <script src="{{ URL::asset('builds/js/scripts.js') }}"></script>
</head>
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row">
                <div class=" col-sm-6 col-sm-offset-3">
                    <h3>{{$train_id}}: {{ $stops[0]['station'] }} - {{ end($stops)['station'] }}</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th scope="col">{{ Lang::get('client.departureAtHour') }}</th>
                            <th scope="col">{{ Lang::get('client.delay') }}</th>
                            <th scope="col">{{ Lang::get('client.station') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($i =0; $i< count($stops); $i++)
                            <tr>
                                <td>@if($i <count($stops)-1 && $stops[$i]['left']==1&&$stops[$i+1]['left'] == 0)<img
                                            src="/images/train.svg" height="16px" width="16px">@endif</td>
                                <td>{{date('H:i',$stops[$i]['time'])}}</td>
                                <td>@if($stops[$i]['delay'] > 0)<span class="delay">+{{$stops[$i]['delay'] / 60 }}'</span>@endif</td>
                                <td>{{$stops[$i]['station']}}</td>
                            </tr>
                        @endfor
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>
