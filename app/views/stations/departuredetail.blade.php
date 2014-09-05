@extends('layouts.default');
@section('header')
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
@stop
@section('content')
<div class="wrapper" ng-app="irailapp" ng-controller="DepartureDetailCtrl">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <?php $delay = $station['delay']/60; ?>

                    @if ($station['delay'] > 0)
                        <?php $image = min($delay / 5, 9); ?>

                        {{ HTML::image('images/irail_logo_delays-0' . $image .'.svg') }}
                    @else
                        <img src="{{ URL::asset('images/train.svg') }}" />
                    @endif
                </div>
                <div class="col-sm-6">
                    <br/>
                    <br/>
                    <p class="label label-primary label-lg">{{date('H:i', strtotime($station['scheduledDepartureTime']))}}</p>
                    <?php
                    if ($station['delay'] > 0){
                        echo "<p class='label label-warning label-lg'>+" . ($station['delay']/60) . "' " . Lang::get('client.delay') . '</p>';
                    } else if ($station['delay'] === 'cancel') {
                        echo "<p class='label label-warning label-lg'>" . "cancelled" . " </p>";
                    }
                    ?>
                    <br/>
                    <br/>
                    <p class="h2">{{Lang::get('client.platform')}} {{$station['platform']}}</p>
                    <p class="h1"><strong>{{$departureStation->name}}</strong></p>
                    <p class="h2">{{Lang::get('client.to')}}</p>
                    <p class="h1"><strong>{{str_replace("[NMBS/SNCB]", "", $station['headsign']);}}</strong></p>

                    <div id="checkin-button" style="text-align: center;">
                        <?php
                        if (Sentry::check()) {
                            $departure = $station['@id'];

                            if (!CheckinController::isAlreadyCheckedIn($departure, Sentry::getUser())) {
                                echo '<a href="#" class="btn btn-default btn-lg btn-success btn-wide label-lg" ng-click="checkin($event, \''. $departure .'\')">Check in</a>';
                            } else {
                                echo '<a href="#" class="btn btn-default btn-lg btn-warning btn-wide label-lg" ng-click="checkout($event, \''. $departure .'\')">Check out</a>';
                            }
                        }
                        ?>
                     </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
