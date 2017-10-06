<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('images/apple-touch-icon-precomposed.png') }}">
    <meta property="og:title" content="iRail.be" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://irail.be" />
    <meta property="og:image" content="{{ URL::asset('images/irail_logo.png') }}" />
    <meta property="og:description" content="iRail is the best way to search for trains in Belgium." />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@iRail">
    <meta name="twitter:title" content="iRail">
    <meta name="twitter:description" content="iRail is the best way to search for trains in Belgium.">
    <meta name="twitter:image" content="{{ URL::asset('images/irail_logo.png') }}" />
    <meta name="twitter:url" content="https://irail.be/" />
    <title>iRail routeplanner</title>

    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>

    <link rel="stylesheet" href="{{ URL::asset('builds/css/main.css?20171003') }}">

    <script src="{{ URL::asset('builds/js/scripts.js?20171003') }}"></script>

    <meta name="theme-color" content="#C72929" />

    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "MobileApplication",
      "name": "iRail.be",
      "potentialAction": {
        "@type": "ViewAction",
        "target": [
          "https://irail.be",
          "https://play.google.com/store/apps/details?id=be.hyperrail.android",
          "http://play.google.com/store/apps/details?id=tof.cv.mpp",
          {
            "@type": "EntryPoint",
            "urlTemplate": "https://itunes.apple.com/be/app/railer-voor-nmbs/id591205121",
            "actionApplication": {
              "@type": "SoftwareApplication",
              "@id": "591205121",
              "name": "Railer App",
              "operatingSystem": "iOS"
            }
          }
        ]
      }
    }
    </script>
</head>
