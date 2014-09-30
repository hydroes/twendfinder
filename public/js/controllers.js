twendfinderApp.controller('TweetsCtrl', function ($scope, socket)
{
    $scope.statuses = [];

    socket.on('tweet', function(data)
    {
        $scope.$apply(function() {
            var status = JSON && JSON.parse(data) || $.parseJSON(data);
            $scope.statuses.push(status);
        });

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

    };
});
