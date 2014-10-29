twendfinderApp.controller('TweetsCtrl', function ($scope, socket)
{
    $scope.statuses = [];

    socket.on('tweet', function(data)
    {
        var status = JSON && JSON.parse(data) || $.parseJSON(data);
        $scope.statuses.push(status);

    });

    $scope.streamFlowPaused = false;

    $scope.toggleStreamFlow = function()
    {
        $scope.streamFlowPaused = ($scope.streamFlowPaused === false) ? true : false;

        socket.emit('feed-flow', { paused: $scope.streamFlowPaused});
    };
});

twendfinderApp.controller('StatsCtrl', function ($scope, socket)
{
    $scope.initialize = function()
    {
        socket.on('init', function()
        {
            socket.emit('get-current-stats-data');
        });


        socket.on('tweetCount', function(data)
        {
            $('#counter').text(data);
        });

        socket.on('statsCurrentData', function(data)
        {
//            var statsCurrentData = JSON && JSON.parse(data) || $.parseJSON(data);
            var statsCurrentData = angular.fromJson(data);
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


    };
});
