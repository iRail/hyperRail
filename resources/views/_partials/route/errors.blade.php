<div class="row max-w5" ng-show="error">
    <div class="col-md-12 col-sm-12">
        <div class="well">
            <h1 class="center"><i class="fa fa-support fa-3x center"></i>
            </h1>
            <h3>{!! Lang::get('client.error')!!} <strong>{!! Lang::get('client.errorNoRoutes')!!}</strong></h3>
            <p>{!! Lang::get('client.errorExplanation')!!} <a href="mailto:iRail@list.iRail.be">{!! Lang::get('client.errorMail')!!}</a>.</p>
            <br/>
            <a href="#" ng-click="resetplanner($event)" class="btn btn-danger btn-lg btn-block"><i class="fa fa-chevron-left"></i> {!! Lang::get('client.errorReturn')!!}</a>
            <br/>
        </div>
    </div>
</div>
