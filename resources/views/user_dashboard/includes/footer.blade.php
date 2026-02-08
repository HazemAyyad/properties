</div>
<div class="footer-dashboard">
    <p class="text-variant-2">©
        <script>
            document.write(new Date().getFullYear());
        </script> {{config('app.name')}}. All Rights Reserved.</p>
</div>
</div>

<div class="overlay-dashboard"></div>

</div>
</div>
<!-- /#page -->

</div>
<!-- go top -->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 286.138;"></path>
    </svg>
</div>

<!-- Javascript -->

<script type="text/javascript" src="{{asset('/site/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/plugin.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/chart.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/chart-init.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/jquery.nice-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/countto.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/shortcodes.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/jqueryui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- <script type="text/javascript" src="{{asset('/site/js/dashboard-menu.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/dashboard-menu.js')}}"></script> -->
@yield('scripts')
</body>

</html>
