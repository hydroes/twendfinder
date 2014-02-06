@extends('master')

@section('content')
<h1>Welcome!</h1>
<p>Live twitter stream: <span id="counter"></span></p>


<table id="tweets" class="table table-hover">

</table>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://sockets.twendfinder.com/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
<script>
socket = io.connect('http://sockets.twendfinder.com:443');
//    socket.emit('tweets', function (data) {
//        console.log(data);
//    });

socket.on('tweet', function(data) {
    var status = JSON && JSON.parse(data) || $.parseJSON(data);
    
    $('#tweets').prepend('<tr><td><img src="'+status.profile_pic+'" class="img-rounded" /></td><td><div><a href="https://twitter.com/'+status.screen_name+'" target="_blank">'+status.screen_name+'</a></div><div>'+status.text+'</div></td></tr>');
    
});

socket.on('tweetCount', function(data) {
    $('#counter').text(data);
});


</script>

@endsection

