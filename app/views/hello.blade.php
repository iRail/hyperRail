@extends('layout')

@section('title')
Home
@stop

@section('content')

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
            <label for="email">Departure</label>
            {{ Form::text('departure', '', array('class' => 'form-control', 'placeholder' => 'Station name')) }}
        </div>
        <div class="form-group">
            <label for="email">Destination</label>
            {{ Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Station name')) }}
        </div>
        <h2>Choose date and time</h2>
        <div class="form-group">
            <label for="email">Date</label>
            {{ Form::text('date', '', array('class' => 'form-control', 'placeholder' => 'Date')) }}
        </div>
        <div class="form-group">
            <label for="email">Time</label>
            <div class="form-inline">
            <select class="form-control">
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
        <div class="form-group">
            <label for="email">Station search</label>
            {{ Form::text('departure', '', array('class' => 'form-control', 'placeholder' => 'Station name')) }}
        </div>
        {{ Form::open() }}
        {{ Form::button('See station information', array('class' => 'btn btn-primary')) }}
        {{ Form::button('See departures', array('class' => 'btn btn-default')) }}
        {{ Form::button('See arrivals', array('class' => 'btn btn-default')) }}
        {{ Form::close() }}
    </div>
</div>

@stop