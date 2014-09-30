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
        $scope.streamFlowPaused = ($scope.streamFlowPaused === false) ? true : false;

        socket.emit('feed-flow', { paused: $scope.streamFlowPaused});
    };

    $scope.initialize = function()
    {

    };

});
