@extends('layouts.default')
@section('content')
<div class="container">
    <br/>
    <div class="row routeplanner view1 well" ng-show="planning">
        <div class="col-sm-5 col-md-4">
            <img src="images/logoLarge.svg" alt="Logo iRail" class="center">
        </div>
        <div class="col-sm-7 col-md-6">
            <h1><strong>iRail.be</strong></h1>
            <p class="h2">Welcome. Hallo. Bonjour.</p>
            <hr/>
            <p>Gelieve uw taal te kiezen. Please choose your language. Choissisez votre langue s.v.p.</p>
            <hr/>
            <ul class="ul-lg">
                <li><a href="route?lang=nl">Nederlands</a></li>
                <li><a href="route?lang=fr">Français</a></li>
                <li><a href="route?lang=en">English</a></li>
            </ul>
        </div>
    </div>
</div>
@stop
