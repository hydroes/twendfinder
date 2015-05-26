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
            $scope.drawChart(statsData);

        }).bindTo($scope);

    };

    $scope.drawChart = function(statsData) {
        var graph = new google.visualization.DataTable();

        var rows = [];
        var rowNames = [];

        // add the data to the graph
        for (var i = 0; i < statsData.length; i++) {
            var counterName = statsData[i].counterName;
            
            if (i === 0) {
                var dateColumn = moment(statsData[i].counterData[0].time).format('HH:mm:ss');
                graph.addColumn('number', dateColumn);
            }

            graph.addColumn('number', counterName);

            // get data for row
            for (var y = 0; y < statsData[i].counterData.length; y++) {
                var counterTime = statsData[i].counterData[y].time;
                var counterValue = statsData[i].counterData[y].value;
            
                if (rows[counterTime] === undefined) {
                    rows[counterTime] = [];
                    rowNames.push(counterTime);

                    // first row column must be time:
                    rows[counterTime].push(counterTime);
                }

                rows[counterTime].push(counterValue);
            }

        }

        for (var i = 0; i < rowNames.length; i++) {
            graph.addRow(rows[rowNames[i]]);
        }

        var options = {
            chart: {
                title: 'Emotional chart',
                subtitle: 'Twitter emotions'
            },
            width: '100%',
            height: 500,
            axes: {
                x: {
                    0: {side: 'top'}
                }
            }
        };

        var chart = new google.charts.Line(document.getElementById('line_top_x'));

        chart.draw(graph, options);
    };

});
