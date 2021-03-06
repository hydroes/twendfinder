<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <title>TwendFinder</title>

    <script src="bower_components/angular/angular.min.js"></script>
    <script src="bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="bower_components/angular-route/angular-route.min.js"></script>
    <script src="bower_components/moment/min/moment.min.js"></script>
    <script src="https://www.google.com/jsapi"></script>    

    <script src="/js/main.js"></script>
    <script src="/js/controllers.js"></script>
    <script src="http://sockets.twendfinder.com/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.min.js"></script>
    <script src="/js/services/socket-io.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-48914004-1', 'twendfinder.com');
        ga('send', 'pageview');
    </script>

  </head>
  <body ng-view>
  </body>
</html>