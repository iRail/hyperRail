<!DOCTYPE html>
<html>
@include('core.apphead')
    <!-- Google Webfonts -->
    <link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=PT+Sans:400,700'>
<body class="apps-page">
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}">
            <span>
                <img class="irail-logo" src="{{ URL::asset('images/logo.svg')}}" />
            </span>
            <span class="navbar-name">iRail</span></a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
            <ul class="nav navbar-nav">
                <li>
                    <a data-toggle="collapse" data-target=".navbar-collapse" href="#about">    About
                    </a>
                </li>
                <li>
                    <a data-toggle="collapse" data-target=".navbar-collapse" href="#how">
                        How?
                    </a>
                </li>
                <li>
                    <a data-toggle="collapse" data-target=".navbar-collapse" href="#download">
                        Download
                    </a>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container-fluid">
    <div class="fix row">
        <div class="col-md-12 col-lg-12 col-xs-11">
            <h2 class="hero text-center">Avoid that busy train.</h2>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-11 center-block">
            <img id="mascotte" class="center-block" src="{{ URL::asset('images/occupancy-mascottes.gif')}}" />
        </div>
        <div class="col-md-12 col-lg-12 col-xs-11">
            <h2 class="hero text-center use-irail">Use iRail.</h2>
        </div>
    </div>
    <hr class="red"/>
    <div class="row apps-page-part" id="about">
        <div class="col-md-10 col-lg-10 col-xs-20 center">
            <div class="col-md-4">
                <img id="phone" src="{{ URL::asset('images/phone.png')}}" />
            </div>
            <div class="col-md-8">
                    <h2 class="apps-header">Know how busy your train will be.</h2>
                    <p class="apps-text">
                        Trains can get really crowded sometimes, so wouldn't it be great to <strong>know in advance how busy your train will be</strong>, so you can take an earlier or later one?
                    </p>
                    <p class="apps-text">
                        With iRail, we created just that. It shows you the occupancy of every train, and you can adjust it if the prediction is different from what you see on your train. <strong>iRail will learn</strong> from that, so the more feedback you give, the better it will predict!
                    </p>
            </div>
        </div>
    </div>
    <hr class="red">
    <div class="row apps-page-part" id="download">
        <div class="col-md-12 col-lg-12 col-xs-11">
            <h2 class="apps-header text-center">Test the beta version now</h2>
        </div>
        <div class="app-text col-md-8 col-lg-8 col-xs-11">
            <p class="apps-text">
                iRail occupancy levels are still in beta. It means that its predictions might not be as accurate yet, and that it still lacks some functionalities. However, we want you to start using it, so we can <strong>use your feedback to improve it</strong>. The more you indicate the occupancy of the train you're on, the better
                iRail will be able to predict busy trains in the future. So let's get started! 
            </p>
        </div>

        <!-- Plan a route -->
        <div class="col-md-12 col-lg-12 col-xs-11 text-center button">
            <a href="/route" class="plan-route-button text-center">
                Plan a route
            </a>
        </div>
        <!-- /Plan a route -->
        <div class="app-text col-md-8 col-lg-8 col-xs-11">
            <p class="apps-text">
                Additionally, iRail occupancy rates will be available on <strong>Railer</strong> and <strong>BeTrains</strong> soon. Download
                them now, so you can be the first to use the new feature!
            </p>
        </div>
        <!-- Link to apps -->
        <div class="row">
            <!-- Railer app -->
            <div class="col-md-5 col-lg-5 col-xs-11 col-md-offset-1 col-lg-offset-1 text-center">

                <img class="app-image" src="{{URL::asset('images/Railer.png') }}" alt="Railer iOS app" />
                <h3 class="app-name app-name-ios">
                    Railer
                    <small> for iOS</small>
                </h3>

                <a href="https://itunes.apple.com/be/app/railer-voor-nmbs/id591205121" class="app-link ios-app-link">
                    <img src="{{ URL::asset('images/get-on-appstore.png')}}" alt="Get on AppStore" width="50%"/>
                </a>
            </div>
            <!-- /Railer app -->
            <!-- BeTrains app -->
            <div class="col-md-5 col-lg-5 col-xs-11 col-md-offset-1 col-lg-offset-1 text-center">
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
    <div class="row apps-page-part" id="how">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <h2 class="apps-header text-center">How does it work?</h2>
        </div>
        <!--<div class="col-md-8 col-lg-8 col-xs-12 col-md-offset-1 col-lg-offset-1">-->
            <p class="col-md-8 col-lg-8 col-xs-11 no-margin-bottom app-text apps-text">
                iRail assigns an <strong>occupancy level</strong> (Not Busy/Busy/Very Busy) to every stretch between stops,
                for every train, every day. For a bunch of trains in the peak hours, we already know between
                which stations they're full based on data we got from NMBS/SNCB and a survey together with
                <strong>TreinTramBus</strong>. 
            </p>
            <br />
            <p class="col-md-8 col-lg-8 col-xs-11 app-text apps-text">
                For other trains, we don't have information yet. That is where you come into play as a user.
                The more feedback you give, the more iRail will learn about busy trains. We will use this to
                predict busy trains for other other times in the future.
            </p>
        <!--</div>-->
    </div>
    <!-- /How does it work?-->
    <!-- Imagine story -->
    <div class="row">
        <div class="col-md-8 col-lg-8 irail-red-border">
            <img src="{{ URL::asset('images/brugge-gent-brussels-illustration.svg' )}}" />

            <p class="apps-text">
                Imagine you’re taking a train from Bruges to Brussels, with one stop in Ghent. iRail
                assigns the occupancy level you entered to the stretch between Bruges and Ghent, and
                will then use a nifty <strong>occupancy transfer algorithm</strong> to predict the occupancy for the stretch between Ghent and Brussels.
            </p>
            <br/>
            <p class="apps-text">
                If the iRail prediction is incorrect, you or another user could correct it. Over time, iRail
                will learn to show the correct occupancy levels.
            </p>
        </div>

        <div class="app-text col-md-8 col-lg-8 col-xs-11 col-md-offset-1 col-lg-offset-1">
            <p class="apps-text">
                So that's it: the more you use Spitsgids, the better it will become!
            </p>
        </div>
    </div>
    <!-- /end story -->
</div>
@include('core.footer')
</body>
</html>