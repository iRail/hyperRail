<!DOCTYPE html>
<html lang="{!! Config::get('app.locale'); !!}" ng-app="irailapp" ng-controller="PlannerCtrl">
@include('core.head')
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            @include('_partials.route.planner')
            @include('_partials.route.loading')
            @include('_partials.route.errors')
            @include('_partials.route.empty')
            @include('_partials.route.results')
        </div>
    </div>
</div>
@include('core.footer')
<script>
    $("[data-toggle='tooltip']").tooltip();
</script>
</body>
</html>