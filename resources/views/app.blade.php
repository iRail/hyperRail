<!DOCTYPE html>
<html>
@include('core.head')
<body>
<!-- Navigation -->
<div class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}"><img class="irail-logo" src="{{ URL::asset('images/logo.svg')}}" /> <span class="navbar-name">iRail</span></a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
            <ul class="nav navbar-nav">
                <!-- <li><a href="{{ URL::to('route') }}" ng-click="resetplanner($event)"><i class="fa fa-road fa-12"></i> {{Lang::get('client.planNewRoute')}}</a></li>
                <li><a href="{{ URL::to('stations/NMBS') }}"><i class="fa fa-search fa-12"></i> {{Lang::get('client.searchStations')}}</a></li>-->
                <li>
                    <a href="#about">About</a>
                </li>
                <li>
                    <a href="#download">Download</a>
                </li>
                <li>
                    <a href="#how">How?</a>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<!-- /Navigation -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2 class="text-center slogan">Avoid that busy train.</h2>
            <img id="gif" class="center-block" src="{{ URL::asset('images/occupancy.gif')}}" />
            <h2 class="text-center slogan">Use an iRail app.</h2>
        </div>
    </div>
    <hr class="red">
    <div class="row">
        <div class="railer-parent col-md-12 col-lg-12 col-xs-12">
            <div class="railer-block clearfix">
                <img id="railer" src="{{ URL::asset('images/railer.png')}}" />
                <div id="railer-text">
                    <h2>Know how busy your train will be.</h2>
                    <p>Trains can get really crowded sometimes, so wouldn&#39;t it be great to know in advance how busy your train will be, so you can take an earlier or later one?</p>
                    <p>With iRail, we created just that. It shows you the occupancy of every train, and you can adjust it if the prediction is different from what you see on your train. iRail learns from that, so the more feedback you give, the more accurate it becomes!</p>
                </div>
            </div>
        </div>
    </div>
    <hr class="red">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2>Test the beta version now</h2>
            <p>iRail occupancy levels are still in beta. It means that its predictions might not be as accurate yet, and that it still lacks some functionalities. However, we want you to start using it, so we can use your feedback to improve it. The more you indicate the occupancy of the train you’re on, the better iRail will be able to predict busy trains in the future. So let’s get started!</p>

        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>