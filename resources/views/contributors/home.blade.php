<!DOCTYPE html>
<html lang="{!! Config::get('app.locale');!!}" ng-app="irailapp" ng-controller="PlannerCtrl">
@include('core.head')
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <section class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{Lang::get('contributors.title')}}</h1>
                    <p>{{Lang::get('contributors.description')}}</p>
                    <ul>
                        <li>Pieter Colpaert</li>
                        <li>Yeri Tiete</li>
                        <li>Nico Verbruggen</li>
                        <li>Brecht Van de Vyvere</li>
                        <li>Bram Devries</li>
                        <li>Andreas De Lille</li>
                        <li>Tim Joosten</li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</div>
@include('core.footer')
<script>
    $("[data-toggle='tooltip']").tooltip();
</script>
</body>