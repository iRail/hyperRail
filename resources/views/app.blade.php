<!DOCTYPE html>
<html>
@include('core.head')
<!-- Google Webfonts -->
    <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=PT+Sans:400,700'>
<body>
<!-- Navigation -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
        </div>
        <!--/.nav-collapse -->
    </div>
</div>
<!-- /Navigation -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Avoid that busy train.</h2>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12 center-block">
            <img width="25%" height="100%" class="center-block" src="{{ URL::asset('images/occupancy.gif')}}" />
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2 class="text-center apps-header">Use an iRail app.</h2>
        </div>
    </div>
    <hr class="red"/>
    <div class="row" id="about" style="padding-top:70px;">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="col-md-3 col-lg-3 col-xs-12 col-md-offset-3 col-lg-offset-2 col-xs-offset-2">
                <img width="70%" height="100%" src="{{ URL::asset('images/railer.png')}}" />
            </div>
            <div class="col-md-6 col-lg-6 col-xs-12">
                    <h2 class="apps-header">Know how busy your train will be.</h2>
                    <p class="apps-text">
                        Trains can get really crowded sometimes, so wouldn&#39;t it be great to <strong>know in advance how busy your train will be</strong>, so you can take an earlier or later one?
                    </p>
                    <p class="apps-text">
                        With iRail, we created just that. It shows you the occupancy of every train, and you can adjust it if the prediction is different from what you see on your train. <strong>iRail learns</strong> from that, so the more feedback you give, the more accurate it becomes!
                    </p>
            </div>
        </div>
    </div>
    <hr class="red">
    <div class="row" id="download" style="padding-top:70px;">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2 class="apps-header text-center">Test the beta version now</h2>
        </div>
        <div class="col-md-10 col-lg-10 col-xs-12 col-md-offset-1 col-lg-offset-1">
            <p class="apps-text">
                iRail occupancy levels are still in beta. It means that its predictions might not be as accurate yet, and that it still lacks some functionalities. However, we want you to start using it, so we can <strong>use your feedback to improve it</strong>. The more you indicate the occupancy of the train you’re on, the better iRail will be able to predict busy trains in the future. So let’s get started!
            </p>
        </div>

        <!-- Plan a route -->
        <div class="col-md-10 col-lg-10 col-xs-12 col-md-offset-1 col-lg-offset-1 text-center">
            <a href="/route" class="plan-route-button text-center">
                <img src="{{ URL::asset('images/plan-route-button.svg')}}" alt="Plan a route"/>
            </a>
        </div>
        <!-- /Plan a route -->
        <div class="col-md-10 col-lg-10 col-xs-12 col-md-offset-1 col-lg-offset-1">
            <p class="apps-text">
                Additionally, iRail occupancy rates will be available on <strong>Railer</strong> and <strong>BeTrains</strong> soon. Download them now, so you can be the first to use the new feature!
            </p>
        </div>
        <!-- Link to apps -->
        <div class="row">
            <!-- Railer app -->
            <div class="col-md-5 col-lg-5 col-xs-12 col-md-offset-1 col-lg-offset-1 col-xs-offset-3">

                <img class="app-image" src="{{URL::asset('images/Railer.png') }}" alt="Railer iOS app" />
                <h3 class="app-name app-name-ios">
                    Railer
                    <small> for iOS</small>
                </h3>

                <a href="https://itunes.apple.com/be/app/railer-voor-nmbs/id591205121" class="app-link ios-app-link">
                    <img src="{{ URL::asset('images/get-on-playstore.png')}}" alt="Get on AppStore" width="50%"/>
                </a>
            </div>
            <!-- /Railer app -->
            <!-- BeTrains app -->
            <div class="col-md-5 col-lg-5 col-xs-12 col-md-offset-1 col-lg-offset-1 col-xs-offset-3">
                <img class="app-image" src="{{ URL::asset('images/BeTrains.png') }}" alt="BeTrains Android app"/>
                <h3 class="app-name">
                    BeTrains
                    <small> for Android</small>
                </h3>
                <a href="https://play.google.com/store/apps/details?id=tof.cv.mpp" class="app-link">
                    <img src="{{ URL::asset('images/get-on-playstore.png')}}" alt="Get on Playstore" width="50%"/>
                </a>

            </div>
            <!-- /BeTrains app -->
        </div>
        <!-- /Link to apps -->
    </div>
    <hr class="red" />
    <!-- How does it work? -->
    <div class="row" id="how" style="padding-top:70px;">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2 class="apps-header text-center">How does it work?</h2>
        </div>
        <div class="col-md-10 col-lg-10 col-xs-12 col-md-offset-1 col-lg-offset-1">
            <p class="apps-text">
                iRail assigns an <strong>occupancy level</strong> (Not Busy/Busy/Very Busy) to every stretch between stops,
                for every train, every day. For a bunch of trains in the peak hours, we already know between
                which stations they’re full based on data we got from NMBS/SNCB and a survey together with
                <strong>TreinTramBus</strong>. 
            </p>
            <br />
            <p class="apps-text">
                For other trains, we don’t have information yet. That is where you come into play as a user.
                The more feedback you give, the more iRail will learn about busy trains. We can use this to
                predict busy trains for other other times as well.
            </p>
        </div>
    </div>
    <!-- /How does it work?-->
    <!-- Imagine story -->
    <div class="row">
        <div class="col-md-10 col-lg-10 col-xs-12 col-md-offset-1 col-lg-offset-1" style="border:1px solid rgb(191,41,41); ">
            <img src="{{ URL::asset('images/brugge-gent-brussels-illustration.svg' )}}" />

            <p class="apps-text">
                Imagine you’re taking a train from Bruges to Brussels, with one stop in Ghent. iRail
                assigns the occupancy level you entered to the stretch between Bruges and Ghent, and
                then uses our nifty <strong>occupancy transfer algorithm</strong> to predict the occupancy for the stretch
                between Ghent and Brussels.
            </p>
            <br/>
            <p class="apps-text">
                If the iRail prediction is incorrect, you or another user could correct it. Over time, iRail
                will learn to show the correct occupancy levels.
            </p>
        </div>

        <div class="col-md-10 col-lg-10 col-xs-12 col-md-offset-1 col-lg-offset-1">
            <p class="apps-text">
                So that’s it: the more you use Spitsgids, the better it will become!
            </p>
        </div>
    </div>
    <!-- /end story -->

<!--

    <div class="row thank-you-footer">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <p class="apps-text">
                TreinTramBus <br/>
                NMBS<br/>
            </p>
        </div>
    </div>

    -->
</div>
@include('core.footer')
</body>
</html>