<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@iRail">
    <meta name="twitter:creator" content="@iRail">
    <meta name="twitter:title" content="iRail | {{$departureStation->name}} to {{str_replace('[NMBS/SNCB]', '', $station['headsign'])}}">
    <meta name="twitter:domain" content="https://iRail.be">
    <meta name="twitter:description" content="Train to {{str_replace(' [NMBS/SNCB]', '', $station['headsign'])}} departing at {{$departureStation->name}} leaves today at platform {{$station['platform']}} at {{date('H:i', strtotime($station['scheduledDepartureTime']))}}<?php if ($station['delay'] > 0){echo "with a delay of " . ($station['delay']/60) . ' minutes';}?>."><meta name="twitter:image" content="{{ URL::asset('images/train.jpg') }}">
    <meta property="og:title" content="iRail.be" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://irail.be" />
    <meta property="og:image" content="{{ URL::asset('apple-touch-icon-precomposed.png') }}" />
    <title>iRail | {{$departureStation->name}} to {{str_replace('[NMBS/SNCB]', '', $station['headsign'])}}</title>
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
                <div class="col-sm-6">
                    <img src="{{ URL::asset('images/train.svg') }}" />
                </div>
                <div class="col-sm-6">
                    <br/>
                    <br/>
                    <p class="label label-primary label-lg">{{date('H:i', strtotime($station['scheduledDepartureTime']))}}</p>
                    <?php
                    if ($station['delay'] > 0){
                        echo "<p class='label label-warning label-lg'>+" . ($station['delay']/60) . "' " . Lang::get('client.delay') . '</p>';
                    }   
                    if (is_array($station['delay']) && sizeof($station['delay'])>1) {
                        echo "<p class='label label-warning label-lg'>" . "cancelled" . " </p>";
                    }
                    if (Sentry::check()) {
                        $departure = $station['@id'];
                        $departure = str_replace("/", "%2F", $departure);
                        $departure = str_replace(":", "%3A", $departure);

                        if (!CheckinController::isAlreadyCheckedIn($departure, Sentry::getUser())) {
                            echo '<a href="/checkin/'.$departure.'" class="label label-success label-lg" style="margin-left: 20px;"">Check in</a>';
                        } else {
                            echo '<a href="/checkout/'.$departure.'" class="label label-warning label-lg" style="margin-left: 20px;"">Check out</a>';
                        }
                    }
                    ?>			
                    <br/>
                    <br/>
                    <p class="h2">{{Lang::get('client.platform')}} {{$station['platform']}}</p>
                    <p class="h1"><strong>{{$departureStation->name}}</strong></p>
                    <p class="h2">{{Lang::get('client.to')}}</p>
                    <p class="h1"><strong>{{str_replace("[NMBS/SNCB]", "", $station['headsign']);}}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>
