<footer class="footer container">
    <hr/>
    <p class="small text-muted text-center"><a href="{{ URL::to('language') }}">{{Lang::get('client.language')}}</a> | 'iRail' &copy; 2015, Open Knowledge Belgium. <a href="http://hello.iRail.be" target="_blank"></a>{{Lang::get('client.isPartOf')}} <a href="http://www.openknowledge.be/" target="_blank">Open Knowledge Belgium</a>. | <a href="{{ URL::to('contributors') }}">{{Lang::get('contributors.title')}}</a> | <a href="https://github.com/iRail/hyperRail"><i class="fa fa-github"></i></a></p>

</footer>
<script>
    var _gaq = [['_setAccount', 'UA-263695-8'], ['_trackPageview']];
    (function(d, t) {
        var g = d.createElement(t),
                s = d.getElementsByTagName(t)[0];
        g.src = '//www.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g, s);
    }(document, 'script'));
</script>