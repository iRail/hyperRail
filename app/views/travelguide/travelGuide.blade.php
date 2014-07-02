<!DOCTYPE html>
<html lang="{{Config::get('app.locale');}}" ng-app="irailapp" ng-controller="">
    @include('core.head')
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <p> Reisagenda - todo</p>
        </div>
    </div>
</div>
    @include('core.footer')
<script>
    $("[data-toggle='tooltip']").tooltip();
</script>
</body>
</html>
