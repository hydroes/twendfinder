@extends('master')

@section('content')
<h1>Welcome!</h1>
<p>Live twitter stream: <span id="counter"></span></p>


<div id="tweets"></div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://sockets.twendfinder.com/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
<script>
socket = io.connect('http://sockets.twendfinder.com:443');
//    socket.emit('tweets', function (data) {
//        console.log(data);
//    });

socket.on('tweet', function(data){

    $('#tweets').prepend(data + '<br /><hr />');
//        console.log(data)
});

socket.on('tweetCount', function(data){
    $('#counter').text(data);
});


</script>

@endsection

