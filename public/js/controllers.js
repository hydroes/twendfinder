'use strict';

angular.module('twendfinderApp').controller('TweetsCtrl', function ($scope, socket)
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

angular.module('twendfinderApp').controller('StatsCtrl', function ($scope, $interval, socket)
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

        // request stats
        socket.emit('stats-for-last');
        socket.on('stats-for-last', function(statsData)
        {
            console.log('stats-for-last', statsData);
            $scope.drawChart();

        }).bindTo($scope);

    };

    $scope.drawChart = function() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Day');
        data.addColumn('number', 'Guardians of the Galaxy');
        data.addColumn('number', 'The Avengers');
        data.addColumn('number', 'Transformers: Age of Extinction');

        data.addRows([
            [1,  37.8, 80.8, 41.8],
            [2,  30.9, 69.5, 32.4],
            [3,  25.4,   57, 25.7],
            [4,  11.7, 18.8, 10.5],
            [5,  11.9, 17.6, 10.4],
            [6,   8.8, 13.6,  7.7],
            [7,   7.6, 12.3,  9.6],
            [8,  12.3, 29.2, 10.6],
            [9,  16.9, 42.9, 14.8],
            [10, 12.8, 30.9, 11.6],
            [11,  5.3,  7.9,  4.7],
            [12,  6.6,  8.4,  5.2],
            [13,  4.8,  6.3,  3.6],
            [14,  4.2,  6.2,  3.4]
        ]);

        var options = {
            chart: {
                title: 'Box Office Earnings in First Two Weeks of Opening',
                subtitle: 'in millions of dollars (USD)'
            },
            width: 900,
            height: 500,
            axes: {
                x: {
                    0: {side: 'top'}
                }
            }
        };

        var chart = new google.charts.Line(document.getElementById('line_top_x'));

        chart.draw(data, options);
    };

});
