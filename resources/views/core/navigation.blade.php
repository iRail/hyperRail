<div class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}">
                <span><img class="irail-logo" src="{{ URL::asset('images/logo.svg')}}" /></span>
                <span class="navbar-name">iRail</span>
            </a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a href="{{ URL::to('route') }}" ng-click="resetplanner($event)"><i class="fa fa-road fa-12"></i> {{Lang::get('client.planNewRoute')}}</a></li>
                <li><a href="{{ URL::to('stations/NMBS') }}"><i class="fa fa-search fa-12"></i> {{Lang::get('client.searchStations')}}</a></li>
                <li><a href="{{ URL::to('spitsgids') }}">Spitsgids</a></li>
                <li><a href="https://docs.irail.be" target="_blank">{{Lang::get('client.apidocs')}}</a></li>
                <li><a href="https://hello.irail.be" target="_blank">Blog</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
