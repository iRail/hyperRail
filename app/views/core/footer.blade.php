<footer class="footer container">
    <hr/>
    <p class="small"><a href="{{ URL::to('language') }}">{{Lang::get('client.language')}}</a> | &copy; 2014, OKFN Belgium. <a href="http://hello.iRail.be" target="_blank">{{Lang::get('client.isPartOf')}}</a> <a href="http://okfn.be/" target="_blank">Open Knowledge Foundation Belgium</a>. </p>
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
<script src="{{ URL::asset('builds/js/scripts.js') }}"></script>
