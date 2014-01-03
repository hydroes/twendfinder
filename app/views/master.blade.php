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

    <script src="/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
    <script>
    socket = io.connect('http://192.241.120.110:443');
    socket.on('news', function (data) {
     console.log(data);
     socket.emit('my other event', { my: 'data' });
    });
  </script>

  </body>
</html>