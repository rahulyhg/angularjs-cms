(function () {
    'use strict';
 
    var app = angular.module('aeadmin');
    
    app.config(function($routeProvider) {	
		$routeProvider
			.when('/settings', {
		        templateUrl : 'files/pages/admin/settings.html',
		        controller  : 'settingsCtrl'
		    });
	});
	
	// Settings controller
    app.controller('settingsCtrl', ['$scope', '$http', 'updateSettings', 'Data', function ($scope, $http, updateSettings, Data) {
	    $('.loading').fadeIn();
	    
		// Define site on $scope
		$scope.site = {};
	   
	    // Get values from DB
		$http.post('getSettings').success(function(data) {
            $scope.site.title = data[0].site_title;
            $scope.site.desc = data[0].site_description;
            $scope.site.key = data[0].site_keywords;
            $('.loading').fadeOut();
        });
        
        
		$scope.settings = function() {
			var site = $scope.site;
			updateSettings.answer('updateSettings', site)
			.success(function(data){
				//console.log(data);
			})
			.error(function(data){
				console.log(data);
			});
		}
		
		$scope.wallpapers = false;
		$scope.wallpaper = function() {
			$('.loading').fadeIn();
			updateSettings.answer('getFiles', null)
			.success(function(data){
				$scope.wallpapers = true;
				$scope.files = data;
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});
		}
		
		$scope.$watch('wp', function (value) {
	        if(value){
		        Data.setValue(value);
		    }
	    });
		
		$scope.setWallpaper = function(id){
			$('.loading').fadeIn();
		    updateSettings.answer('setWallpaper', id)
		    .success(function(data){
			    updateSettings.answer('getWp', null)
				.success(function(data){
					$scope.wallpapers = false;
					$scope.wp = data[0].file;
					$('.loading').fadeOut();
				});
		    })
		    .error(function(data){
			    console.log(data);
		    });
		}	
		
    }]);
    
    // Factory for getting settings definitions
    app.factory('updateSettings', function($http) {
	    return {
	        answer: function(url, data) {
	            return $http.post(url, {data: data});
	        }
	    };
	});
 
}());