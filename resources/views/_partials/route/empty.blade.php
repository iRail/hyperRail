<div class="row max-w5" ng-show="results && connections.length === 0"">
<div class="col-md-12 col-sm-12">
    <div class="well">
        <h1 class="text-center"><i class="fa fa-support fa-3x text-center"></i>
        </h1>
        <h3>{{Lang::get('client.errorNoRoutes')}}</h3>
        <p>{{Lang::get('client.errorHoliday')}} <a href="mailto:iRail@list.iRail.be">{{Lang::get('client.errorMail')}}</a>.</p>
        <br/>
        <a href="#" ng-click="resetplanner($event)" class="btn btn-danger btn-lg btn-block"><i class="fa fa-chevron-left"></i> {{Lang::get('client.errorReturn')}}</a>
        <br/>
    </div>
</div>
</div>
