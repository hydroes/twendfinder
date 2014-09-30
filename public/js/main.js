/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// My controller
var twendfinderApp = angular.module('twendfinderApp', []);

twendfinderApp.controller('TweetsCtrl', function ($scope)
{
    var socket = io.connect('http://sockets.twendfinder.com:443');
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
        console.log('CLIQed');
        $scope.streamFlowPaused = ($scope.streamFlowPaused === false) ? true : false;

        console.log('flow toggle: ', $scope.streamFlowPaused);

        socket.emit('feed-flow', { paused: $scope.streamFlowPaused});

        //        if (pause_flow == true) {
        //            target.removeClass('btn-primary').addClass('btn-success').text('Unpause feed');
        //            pause_flow = false;
        //        } else {
        //            target.removeClass('btn-success').addClass('btn-primary').text('Pause feed');
        //            pause_flow = true;
        //        }

        //        target.data('pause-flow', pause_flow);
        


    };

});

