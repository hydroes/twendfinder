@extends('master')

@section('content')
<h1>Welcome!</h1>
<h3>Below is a live feed of the worlds tweets about love, hate, and other emotions</h3>
<p>Tweets counted so far: <span id="counter"></span></p>

<button type="button" class="btn btn-default" id="feed-flow" data-pause-flow="true">Pause feed</button>

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
//    console.log(status)
    $('#tweets').prepend('<tr><td><img src="'+status.profile_pic+'" class="img-rounded" /></td><td><div><a href="https://twitter.com/'+status.screen_name+'" target="_blank">'+status.screen_name+'</a></div><div>'+status.text+'</div></td></tr>');
    
});

socket.on('tweetCount', function(data) {
    $('#counter').text(data);
});

$('#feed-flow').click(function(e) 
{
    var target = $(e.target);
    console.log(target);
    //socket.emit('feed-flow', { my: 'data' });
});


</script>

@endsection

