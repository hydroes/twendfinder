angular.module('twendfinderApp', ['ngSanitize', 'ngRoute', 'socket-io']);

// define routes
angular.module('twendfinderApp').config(['$routeProvider', function($routeProvider){  
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

google.load('visualization', '1.1', {packages: ['line']});
google.setOnLoadCallback(function() {
	angular.bootstrap(document.body, ['twendfinderApp']);
});