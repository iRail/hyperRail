<!DOCTYPE html>
<html lang="en">
@include('core.head')
<body>
<div class="container">
    <div class="row max-w5 error">
        <div class="col-md-12 col-sm-12">
        <div class="well">
            <h1 class="center"><i class="fa fa-exclamation-triangle fa-3x center"></i>
            </h1>
            <h3>{{Lang::get('client.error')}} <strong>{{Lang::get('client.errorNoLiveboard')}}</strong></h3>
            <p>{{Lang::get('client.errorExplanation')}} <a href="mailto:iRail@list.iRail.be">{{Lang::get('client.errorMail')}}</a>.</p>
            </div>
            </div>
    </div>
</div>

@include('core.footer')
</body>
</html>