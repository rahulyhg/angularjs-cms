var app = angular.module('aelogin', [], function($httpProvider){
	
	$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
	
	var param = function(obj) {
		var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
		
		for(name in obj) {
		value = obj[name];
		
		if(value instanceof Array) {
		for(i=0; i<value.length; ++i) {
		subValue = value[i];
		fullSubName = name + '[' + i + ']';
		innerObj = {};
		innerObj[fullSubName] = subValue;
		query += param(innerObj) + '&';
		}
		}
		else if(value instanceof Object) {
		for(subName in value) {
		subValue = value[subName];
		fullSubName = name + '[' + subName + ']';
		innerObj = {};
		innerObj[fullSubName] = subValue;
		query += param(innerObj) + '&';
		}
		}
		else if(value !== undefined && value !== null)
		query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
		}
		
		return query.length ? query.substr(0, query.length - 1) : query;
	};
	
	$httpProvider.defaults.transformRequest = [function(data) {
	return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
	}];

});

app.controller('login', function($scope, $http, check, contactServer) {
	
	// Set wallpaper BG
	contactServer.answer('getWp', null)
	.success(function(data){
		$scope.wp = data[0].file;
	});
	
	// Auth
	$scope.submitForm = function() {

		if ($scope.loginForm.$valid) {
			var data = [];
			data = [{
			    username: $scope.user.name,
			    password: $scope.user.password,
			    remember: $scope.user.remember
			}];
			
			check.answer('check', data)
			.success(function(data){
				if(data == 'yes'){
					window.location = 'aeAdmin#/';
				}
				if(data == 'no'){
					alert('Make sure you entered correctly\nusername and password.');
				}
			})
			.error(function(data){
				console.warn(data);
			});
						
		}

	};

});

app.factory('check', function($http) {
    return {
        answer: function(url, data) {
            return $http.post(url, {data: data});
        }
    };
});

app.factory('contactServer', function($http) {
    return {
        answer: function(url, data) {
            return $http.post(url, {data: data});
        }
    };
});