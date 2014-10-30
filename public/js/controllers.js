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

        // @todo: bind destroy event
        socket.on('tweetCount', function(data)
        {
            $scope.counter = data;
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
            $scope.cur_min = min;
            $scope.cur_hour = hour;
            $scope.cur_day = day;

        });

        // get stats data every x period
        var getStatsCurrent = setInterval(function()
        {
            socket.emit('get-current-stats-data');
        }, 60000);


    };
});
