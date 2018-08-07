@if(isset($force_ca))
  @include('includes.partials.ca_ga', ['ga_code' => config('analytics.ca-google-analytics')])
@elseif(config('analytics.google-analytics'))
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', '{{config("analytics.google-analytics")}}', 'auto');
    ga('send', 'pageview');

  </script>
@endif