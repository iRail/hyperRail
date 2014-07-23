@extends('layouts.default')
@section('header')
    @parent
@stop
@section('content')
<div class="wrapper">
    <div id="main">
        @include('core.navigation-iframe')
        <div class="container">
            <div class="row login">
                <div class="col-md-4 col-md-offset-4">
                      <div class="panel">
                            <div class="panel-heading"><p class="h3">Authorize application</p></div>
                        <div class="panel-body">
                            <form action="{{ $url }}" method="post">
                                <div class="form-group" style="text-align:center;">
                                    <h3>Do you authorize {{ $appName }}?</h3>
                                </div>
                                <div class="form-group" style="text-align:center;">
                                    <input type="submit" class="btn btn-info btn-lg" name="authorized" value="Yes">
                                    <input type="submit" class="btn btn-default btn-lg" name="authorized" value="No">
                                </div>
                            </form>

                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
