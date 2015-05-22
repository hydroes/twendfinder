angular.module('twendfinderApp', ['ngSanitize', 'socket-io']);

// define routes
app.config(['$routeProvider', function($routeProvider){  
	$routeProvider
	.when('/', {
		templateUrl:'templates/homepage.html',
	})
	.when('/loading', {
		templateUrl:'templates/loading.html',
	})
	.otherwise({
		redirectTo: '/'
	});
}]);