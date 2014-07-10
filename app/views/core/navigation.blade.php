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
                <li><a href="{{ URL::to('route') }}" ng-click="resetplanner($event)"><i class="fa fa-road fa-12"></i> {{Lang::get('client.planNewRoute')}}</a></li>
                <li><a href="{{ URL::to('stations/NMBS') }}"><i class="fa fa-search fa-12"></i> {{Lang::get('client.searchStations')}}</a></li>
                @if (Sentry::check())
                    <li><a href="{{ URL::to('logout') }}"><i class="fa fa-sign-out fa-12"></i> {{Lang::get('client.log_out')}}</a></li>
                @else
                    <li><a href="{{ URL::to('login') }}"><i class="fa fa-sign-in fa-12"></i> {{Lang::get('client.log_in')}}</a></li>
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>