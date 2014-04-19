@extends('master')

@section('content')
<h1>Welcome!</h1>
<h3>Below is a live feed of the worlds tweets about love, hate, and other emotions</h3>
<p>Tweets counted so far: <span id="counter"></span></p>

<p>
    Tweets counted in the last:
    <span class="label label-info active">Minute <span class="badge badge-important" id="cur_min">0</span></span> 
    <span class="label label-info active">Hour <span class="badge badge-important" id="cur_hour">0</span></span> 
    <span class="label label-info active">24 hours <span class="badge badge-important" id="cur_day">0</span></span> 
</p>

<button type="button" class="btn btn-primary" id="feed-flow" data-pause-flow="true">Pause feed</button>

<table id="tweets" class="table table-hover">

</table>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://sockets.twendfinder.com/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js"></script>
<script>
/**
 * TODO: This must be rewritten in angular
 */

socket = io.connect('http://sockets.twendfinder.com:443');

socket.on('init', function()
{
    socket.emit('get-current-stats-data');
});

socket.on('tweet', function(data) {
    var status = JSON && JSON.parse(data) || $.parseJSON(data);
//    console.log(status)
    $('#tweets').prepend('<tr><td><img src="'+status.profile_pic+'" class="img-rounded" /></td><td><div><a href="https://twitter.com/'+status.screen_name+'" target="_blank">'+status.screen_name+'</a></div><div>'+status.text+'</div></td></tr>');

});

socket.on('tweetCount', function(data)
{
    $('#counter').text(data);
});

socket.on('statsCurrentData', function(data)
{
    var statsCurrentData = JSON && JSON.parse(data) || $.parseJSON(data);
    console.log('statsCurrentData');
    console.log(statsCurrentData);
    
    // update ui with stats
    var min = new Number(statsCurrentData.minute);
    var hour = new Number(statsCurrentData.hour);
    var day = new Number(statsCurrentData.day);
    $('#cur_min').text(min.toPrecision(10));
    $('#cur_hour').text(hour.toPrecision(10));
    $('#cur_day').text(day.toPrecision(10));
    
});

// get stats data every x period
var getStatsCurrent = setInterval(function()
{
    socket.emit('get-current-stats-data');
}, 60000);

$('#feed-flow').click(function(e)
{
    var target = $(e.target);

    var pause_flow = target.data('pause-flow');

    socket.emit('feed-flow', { paused: pause_flow});

    if (pause_flow == true) {
        target.removeClass('btn-primary').addClass('btn-success').text('Unpause feed');
        pause_flow = false;
    } else {
        target.removeClass('btn-success').addClass('btn-primary').text('Pause feed');
        pause_flow = true;
    }

    target.data('pause-flow', pause_flow);
    console.log(pause_flow);
});

</script>

@endsection

