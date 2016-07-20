<!DOCTYPE html>
<html lang="en">
@include('core.head')
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row max-w5" >
                <div class="col-md-12 col-sm-12">
                    <div class="well">
                        <p class="text-center h1"><i class="fa fa-question-circle fa-3x center"></i>
                        </p>
                        <h3><strong>404</strong>: {{Lang::get('client.404ErrorTitle')}}</h3>
                        <p>{{Lang::get('client.404Explanation')}} <a href="mailto:iRail@list.iRail.be">{{Lang::get('client.errorMail')}}</a>.</p>
                        <br/>
                        <a href="/" class="btn btn-danger btn-lg btn-block"><i class="fa fa-chevron-left"></i> {{Lang::get('client.goHome')}}</a>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>
