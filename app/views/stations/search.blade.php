@extends('layout')

@section('title')
Home
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <h1>Station search</h1>
        <div class="form-group">
            <label for="station">Station search</label>
            <br/>
            <?php
            $json = file_get_contents('http://irail.dev/data/stations.json');
            $data = json_decode($json);
            ?>
            <select class="chosen-select">
                @foreach ($data->stations as $station)
                <option value="{{$station->id}}">{{$station->name}}</option>
                @endforeach
            </select>
        </div>
        {{ Form::open() }}
        {{ Form::button('See station information', array('class' => 'btn btn-primary')) }}
        {{ Form::button('See departures', array('class' => 'btn btn-default')) }}
        {{ Form::button('See arrivals', array('class' => 'btn btn-default')) }}
        {{ Form::close() }}
        <hr/>
    </div>
</div>
<script>
    $(".chosen-select").chosen();
</script>

@stop