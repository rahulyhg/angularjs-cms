(function () {
    'use strict';
 
    var app = angular.module('aeadmin', ['ngRoute'], function($httpProvider){
	    
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
	
    app.controller('admin', function($scope, $http, contactServer, Data) {
	    
	    $scope.$watch(function(){ 
		    return Data.getValue();
		},
		function (value) {
	        if(value){
		        $scope.wp = value;
		    }
	    });
	    
	    // Set wallpaper BG
		contactServer.answer('getWp', null)
		.success(function(data){
			$scope.wp = data[0].file;
		});
	
		$scope.logout = function(){
			contactServer.answer('logout', null)
			.success(function(){
				window.location = '';
			})
			.error(function(data){
				console.log(data);
			});
		}
	
	});
	
	app.factory('contactServer', function($http) {
	    return {
	        answer: function(url, data) {
	            return $http.post(url, {data: data});
	        }
	    };
	});
	
	app.factory('Data', function(){
	    var data =
	        {
	            sharedValue: ''
	        };
	    
	    return {
	        getValue: function () {
	            return data.sharedValue;
	        },
	        setValue: function (sharedValue) {
	            data.sharedValue = sharedValue;
	        }
	    };
	});
 
}());