@extends('layouts.default')
@section('content')
    <div id="main">
        @include('core.navigation')
        <div class="container">
        	<form action="{{ $url }}" method="post">
              <label>Do You Authorize iRail?</label><br />
              <input type="submit" name="authorized" value="yes">
              <input type="submit" name="authorized" value="no">
            </form>
        </div>
   	</div>
@include('core.footer')
<script>
    $("[data-toggle='tooltip']").tooltip();
</script>
</body>
</html>

@stop