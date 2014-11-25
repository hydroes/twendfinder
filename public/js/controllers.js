twendfinderApp.controller('TweetsCtrl', function ($scope, socket)
{
    $scope.statuses = [];

    socket.on('tweet', function(data)
    {
        var status = JSON && JSON.parse(data) || $.parseJSON(data);
        $scope.statuses.push(status);

    }).bindTo($scope);

    $scope.streamFlowPaused = false;

    $scope.toggleStreamFlow = function()
    {
        $scope.streamFlowPaused = ($scope.streamFlowPaused === false) ? true : false;

        socket.emit('feed-flow', { paused: $scope.streamFlowPaused});
    };
});

twendfinderApp.controller('StatsCtrl', function ($scope, $interval, socket)
{
    $scope.initialize = function()
    {
        socket.on('init', function()
        {
            socket.emit('get-current-stats-data');
        }).bindTo($scope);

        // @todo: bind destroy event
        socket.on('tweetCount', function(data)
        {
            $scope.counter = data;
        }).bindTo($scope);

        socket.on('statsCurrentData', function(data)
        {
            var statsCurrentData = angular.fromJson(data);

            // update ui with stats
            $scope.cur_min = statsCurrentData.minute;
            $scope.cur_hour = statsCurrentData.hour;
            $scope.cur_day = statsCurrentData.day;

        }).bindTo($scope);
        
        // get stats data every x period
        var getStatsCurrent = $interval(function()
        {
            socket.emit('get-current-stats-data');
        }, 60000);

        socket.on('stats-for-last', function(data)
        {
            console.log(data);
        }).bindTo($scope);

    };
});
