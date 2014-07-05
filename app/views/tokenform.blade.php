<!-- app/views/tokenform.blade.php -->

{{ Form::open(array(
	'url' 			=> '/token?grant_type=authorization_code'
)) }}

	<h3> Enter your authentication code </h3>
 	<input name="_token" type="hidden" >
 	<input name="code" type="text">

 	<input type="submit" text="submit">

{{ Form::close() }}