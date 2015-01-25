(function () {
    'use strict';
 
    var app = angular.module('aeadmin');
    
    app.config(function($routeProvider) {	
		$routeProvider
			.when('/files', {
		        templateUrl : 'files/pages/admin/files.html',
		        controller  : 'filesCtrl'
		    });
	});
	
    // Files Controller
	app.controller('filesCtrl', function($scope, $http, fileUpload, manageFiles) {	 
		
		$('.loading').fadeIn();
		$http.post('getFiles').success(function(data) {
            $scope.files = data;
            $('.loading').fadeOut();
        });
   
		// Upload file to server
	    $scope.uploadFile = function() {
		    var file = $scope.myFile;
		    if(typeof file === 'undefined' || file == 'undefined'){
				alert('Please browse a file to upload');
			}
			else{
				$('.loading').fadeIn();
				fileUpload.uploadFileToUrl(file, 'upload');
				$scope.myFile = 'undefined';
				document.getElementById('upload').value = '';
				setTimeout(function(){
					$http.post('getFiles').success(function(data) {
			            $scope.files = data;
			            $('.loading').fadeOut();
			        });
				}, 3000);
			}
		}
		
		$scope.editBox = false;
		$scope.fileUpdate = {};
		// Edit file
		$scope.editFile = function(id) {
			$('.loading').fadeIn();
			$scope.fileBg = true;
			$scope.editBox = true;
			manageFiles.files('editFile', id)
			.success(function(data){
				$scope.fileUpdate.id = data[0].id;
				$scope.fileUpdate.title = data[0].title;
	            $scope.fileUpdate.alt = data[0].alt;
	            $('.loading').fadeOut();
			})
		}

		$scope.saveFile = function(id) {
			$('.loading').fadeIn();
			var fileDetails = $scope.fileUpdate;
			manageFiles.files('saveFile', fileDetails)
			.success(function(data){
				console.log(data);
				$scope.fileUpdate = {};
				$scope.editBox = false;
				$scope.fileBg = false;
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});
		}
		
		// Delete file & update DB
		$scope.deleteFile = function(id) {
			var deleteFile = confirm('Are you sure?');
			if(deleteFile == true){
				$('.loading').fadeIn();
				manageFiles.files('deleteFile', id)
				.success(function(){
					$scope.fileUpdate = {};
					$scope.editBox = false;
					$scope.fileBg = false;
					$http.post('getFiles').success(function(data) {
			            $scope.files = data;
			            $('.loading').fadeOut();
			        });
				})
				.error(function(data){
					console.log(data);
				});
			}
		}
		
		// View file large size
		$scope.fileBg = false;
		$scope.viewFile = function(id) {
			document.getElementById(id).className = 'viewFileFull';
			$scope.fileBg = true;
		}
		
		// View file back in the list
		$scope.backToList = function(id) {
			document.getElementById(id).className = '';
			$scope.fileBg = false;
		}
		
		// Close edit box
		$scope.close = function() {
			$scope.fileBg = false;
			$scope.editBox = false;
		}
		
	});
	
	// Service for uploading files
	app.service('fileUpload', ['$http', function ($http) {
		this.uploadFileToUrl = function(file, url){
	        var fd = new FormData();
	        fd.append('file', file);
	        $http.post(url, fd, {
	            transformRequest: angular.identity,
	            headers: {'Content-Type': undefined}
	        })
	        .success(function(data){})
	        .error(function(data){});
	    }
	}]);
	
	// Service for server
	app.factory('manageFiles', function($http) {
	    return {
	        files: function(url, data) {
	            return $http.post(url, {data: data});
	        }
	    };
	});
	
	// Custom directive for uploading file
	app.directive('fileModel', ['$parse', function ($parse) {
	    return {
	        restrict: 'A',
	        link: function(scope, element, attrs) {
	            var model = $parse(attrs.fileModel);
	            var modelSetter = model.assign;
	            
	            element.bind('change', function(){
	                scope.$apply(function(){
	                    modelSetter(scope, element[0].files[0]);
	                });
	            });
	        }
	    };
	}]);

}());