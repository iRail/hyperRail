@extends('layout')

@section('title')
Home
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1>Station search</h1>
        <div class="form-group">
            <label for="email">Station search</label>
            {{ Form::text('departure', '', array('class' => 'form-control', 'placeholder' => 'Station name')) }}
        </div>
        {{ Form::open() }}
        {{ Form::button('See station information', array('class' => 'btn btn-primary')) }}
        {{ Form::button('See departures', array('class' => 'btn btn-default')) }}
        {{ Form::button('See arrivals', array('class' => 'btn btn-default')) }}
        {{ Form::close() }}
        <hr/>
    </div>
</div>

@stop