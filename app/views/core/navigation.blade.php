<div class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="{{ URL::asset('images/logo.svg')}}" /> <strong>iRail</strong></a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a href="{{ URL::to('route') }}" ng-click="reset()"><i class="fa fa-road fa-12"></i> {{Lang::get('client.planNewRoute')}}</a></li>
                <li><a href="{{ URL::to('stations') }}"><i class="fa fa-search fa-12"></i> {{Lang::get('client.searchStations')}}</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>