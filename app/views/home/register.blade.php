@extends('layouts.default')
@section('header')
    @parent
@stop
@section('content')
    <div class="wrapper">
        <div id="main">
            @include('core.navigation')
            <div class="container">
                <div class="row login">
                    <div class="col-md-4 col-md-offset-4">
                          <div class="panel panel-info">
                            <div class="panel-heading">Please Register</div>
                            <div class="panel-body">
                                {{ Form::open(array('url' => 'register')) }}
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                                        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                                    </div>
                                @endif
                                <div class="form-group">
                                    {{ Form::label('fname', 'First Name') }}
                                    {{ Form::text('first_name', '', array('class' => 'form-control', 'placeholder' => 'First Name')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('last_name', 'Last Name') }}
                                    {{ Form::text('last_name', '', array('class' => 'form-control', 'placeholder' => 'Last Name')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('email', 'Email Address') }}
                                    {{ Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email Address')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('password', 'Password') }}
                                    {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::submit('Register', array('class' => 'btn btn-success')) }}
                                    {{ HTML::link('/', 'Cancel', array('class' => 'btn btn-danger')) }}
                                </div>
                                {{ Form::close() }}
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop