@extends('layout')

@section('title')
Home
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
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
</div>
@stop