<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('apple-touch-icon-precomposed.png') }}">
    <meta property="og:title" content="iRail.be" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://irail.be/app" />
    <meta property="og:image" content="{{ URL::asset('apple-touch-icon-precomposed.png') }}" />
    <meta property="og:description" content="iRail is the best way to search trains in Belgium, and now you can see their occupancy as well." />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@iRail">
    <meta name="twitter:title" content="iRail">
    <meta name="twitter:description" content="iRail is the best way to search trains in Belgium, and now you can see their occupancy as well.">
    <meta name="twitter:image" content="http://irail.be/images/occupancy-mascottes.gif" />
    <meta name="twitter:url" content="http://irail.be/" />
    <title>iRail.be</title>

    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>

    <link rel="stylesheet" href="{{ URL::asset('builds/css/main.css?201607262') }}">

    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "MobileApplication",
      "name": "iRail.be",
      "potentialAction": {
        "@type": "ViewAction",
        "target": [
          "https://irail.be",
          "android-app://CHANGETHISTOBETRAINSLINK",
          {
            "@type": "EntryPoint",
            "urlTemplate": "full RAILERLINK",
            "actionApplication": {
              "@type": "SoftwareApplication",
              "@id": "RAILERID",
              "name": "Railer App",
              "operatingSystem": "iOS"
            }
          }
        ]
      }
    }
    </script>
    <script src="{{ URL::asset('builds/js/scripts.js?201607262') }}"></script>
</head>