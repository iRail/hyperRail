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
</div>


@stop