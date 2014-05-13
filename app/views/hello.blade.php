@extends('layout')

@section('title')
Home
@stop

@section('content')

<link rel="stylesheet" href="{{ URL::asset('js/bootstrap-datepicker/css/datepicker3.css') }}">
<script src="{{ URL::asset('js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<div class="row">
    <div class="col-md-12">
        <br/>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            iRail has received a facelift! Yes, that's right. The new iRail is now a hypermedia driven API. To learn more about the API, visit
            <a href="http://docs.irail.be">docs.irail.be</a>. If you'd just like to find out which trains ride when, go ahead and put the new iRail to use.
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <h1>Route planner</h1>
        {{ Form::open() }}
        <h2>Locations</h2>
        <div class="form-group">
            <label for="station">Departure</label>
            <br/>
            <?php
            // TODO: move this to a controller
            $json = file_get_contents('http://irail.dev/data/stations.json');
            $data = json_decode($json);
            ?>
            <select class="chosen-select">
                @foreach ($data->stations as $station)
                <option value="{{$station->id}}">{{$station->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="station">Destination</label>
            <br/>
            <select data-placeholder="Choose a station..." class="chosen-select">
                @foreach ($data->stations as $station)
                <option value="{{$station->id}}">{{$station->name}}</option>
                @endforeach
            </select>
        </div>
        <h2>Choose date and time</h2>
        <div class="form-group">
            <label for="email">Date</label>
            {{ Form::text('date', '', array('class' => 'form-control datepicker', 'placeholder' => 'Date')) }}
        </div>
        <div class="form-group">
            <label for="email">Time</label>
            <div class="form-inline">
            <select data-placeholder="Choose a station..." class="form-control">
                <option>Arrival time</option>
                <option>Departure time</option>
            </select>
            {{ Form::text('hour', '', array('class' => 'form-control', 'placeholder' => 'Time')) }}
            </div>
        </div>
        {{ Form::submit('Check train times', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
        <hr/>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <h1>Liveboard</h1>
        <label for="station">Destination</label>
        <br/>
        <select data-placeholder="Choose a station..." class="chosen-select">
            @foreach ($data->stations as $station)
            <option value="{{$station->id}}">{{$station->name}}</option>
            @endforeach
        </select>
        {{ Form::open() }}
        <br/>
        {{ Form::button('See station information', array('class' => 'btn btn-primary')) }}
        {{ Form::button('See departures', array('class' => 'btn btn-default')) }}
        {{ Form::button('See arrivals', array('class' => 'btn btn-default')) }}
        {{ Form::close() }}
    </div>
</div>
<script>
    $(".chosen-select").chosen();
    $('.datepicker').datepicker();
</script>

@stop