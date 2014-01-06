<!DOCTYPE html>
<html lang="en-GB">
  <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TwendFinder</title>
  </head>
  <body>
    @yield('content')


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://sockets.twendfinder.com/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
    <script>
    socket = io.connect('http://sockets.twendfinder.com:443');
//    socket.emit('tweets', function (data) {
//        console.log(data);
//    });

    socket.on('tweet', function(data){

        $('#tweets').html(data + '<br />' + $('#tweets').html());
//        console.log(data)
    });
  </script>

  </body>
</html>