@extends('layouts.default')

@section('content')

<div class="col-md-4 col-md-offset-4">
      <div class="panel panel-info">
        <div class="panel-heading">Please Login</div>
        <div class="panel-body">
            {{ Form::open(array('url' => 'login')) }}
            @if($errors->has('login'))
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    {{ $errors->first('login', ':message') }}
                </div>
            @endif
            <div class="form-group">
                {{ Form::label('email', 'Email Address') }}
                {{ Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email Address')) }}
            </div>
            <div class="form-group">
                {{ Form::label('password', 'Password') }}
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
            </div>
            <div class="form-group">
                {{ Form::submit('Login', array('class' => 'btn btn-success')) }}
                {{ HTML::link('/', 'Cancel', array('class' => 'btn btn-danger')) }}
            </div>
            {{ Form::close() }}
        </div>
      </div>
</div>
