(function () {
    'use strict';
 
    var app = angular.module('aeadmin');
    
    app.config(function($routeProvider) {	
		$routeProvider
			.when('/users', {
	            templateUrl : 'files/pages/admin/users.html',
	            controller  : 'usersCtrl'
	        })
	});
	
	// Users controller
    app.controller('usersCtrl', function ($scope, $http, serverUsers) {
	    
	    $scope.showAllUsers = true;
	    
	    // Get all users for showing on users tab
	    serverUsers.answer('getAllUsers', null)
	    .success(function(data){
		    $scope.users = data;
	    })
	    .error(function(data){
		    console.log(data);
	    });
	    
	    // Edit user
	    $scope.editUser = function(id) {
		    console.log(id);
	    }
		
    });
    
    // Factory for getting settings definitions
    app.factory('serverUsers', function($http) {
	    return {
	        answer: function(url, data) {
	            return $http.post(url, {data: data});
	        }
	    };
	});
 
}());