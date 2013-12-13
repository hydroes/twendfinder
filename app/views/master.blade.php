<!DOCTYPE html>
<html lang="en-GB">
  <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TwendFinder</title>
  </head>
  <body>
    @yield('content')
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>

    <script>
        // WAMP session object
        var sess;
        var wsuri = "ws://sockets.twendfinder.com:8080";

        window.onload = function() {

           // connect to WAMP server
           ab.connect(wsuri,

              // WAMP session was established
              function (session) {

                 sess = session;
                 console.log("Connected to " + wsuri);

                 // subscribe to topic, providing an event handler
                 sess.subscribe("http://example.com/simple", onEvent);
              },

              // WAMP session is gone
              function (code, reason) {

                 sess = null;
                 console.log("Connection lost (" + reason + ")");
              }
           );
        };

        function onEvent(topic, event) {
           console.log(topic);
           console.log(event);
        }

        function publishEvent()
        {
           sess.publish("http://example.com/simple", {a: "foo", b: "bar", c: 23});
        }

        function callProcedure() {
           // issue an RPC, returns promise object
           sess.call("http://example.com/simple/calc#add", 23, 7).then(

              // RPC success callback
              function (res) {
                 console.log("got result: " + res);
              },

              // RPC error callback
              function (error, desc) {
                 console.log("error: " + desc);
              }
           );
        }
    </script>
  </body>
</html>