@extends('layouts.default')
@section('content')
<div class="wrapper">
    <div id="main">
        <div class="container">
			<h2>Password Reset</h2>

			<div>
				To reset your password, complete this form: {{ URL::to('password/reset', array($token)) }}.
			</div>
		</div>
	</div>
</div>
@stop