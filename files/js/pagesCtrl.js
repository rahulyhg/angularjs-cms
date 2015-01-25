(function () {
    'use strict';
 
    var app = angular.module('aeadmin');
    
    app.config(function($routeProvider) {	
		$routeProvider
			.when('/pages', {
		        templateUrl : 'files/pages/admin/pages.html',
		        controller  : 'pagesCtrl'
		    });
	});
	
	// Settings controller
    app.controller('pagesCtrl', function ($scope, $http, serverPages) {
	    $('.loading').fadeIn();
		// Define page on view
	    $scope.page = {};
	    
	    // Show all pages
	    $scope.showAllPages = true;
	    $scope.showPage = false;
	    $scope.showDeletedPages = false;
	    
	    // Get all pages for view
	    serverPages.answer('getAllPages', null)
	    .success(function(data){
		   $scope.allPages = data;
		   $('.loading').fadeOut();
	    })
	    .error(function(data){
		   console.log(data);
	    });
	    
	    // Show all pages when coming back to main page
	    $scope.getAllPages = function() {
		    if($scope.showPage){
			    if($scope.leadImage == null){
				    var exit = confirm("Page has not been saved yet.\nPlease add title and lead image.\n\nExit anyway?")
				    if(exit){
					    $('.loading').fadeIn();
					    serverPages.answer('getAllPages', null)
					    .success(function(data){
							$scope.showAllPages = true;
							$scope.showPage = false;
							$scope.showDeletedPages = false;
							$scope.allPages = data;
							$('.loading').fadeOut();
							
							return;
					    })
					    .error(function(data){
							console.log(data);
					    });
				    }
				    
				    return;
			    }
			}
		    
		    $('.loading').fadeIn();
			serverPages.answer('getAllPages', null)
		    .success(function(data){
				$scope.showAllPages = true;
				$scope.showPage = false;
				$scope.showDeletedPages = false;
				$scope.allPages = data;
				$('.loading').fadeOut();
		    })
		    .error(function(data){
				console.log(data);
		    });
	    }
	    
	    // Show page selected
	    $scope.editPage = function(id) {
		    $('.loading').fadeIn();
		    $scope.page.id = null;
			$scope.page.title = null;
			$("#pageContent").jqteVal("");
			$scope.leadImage = null;
		    serverPages.answer('editPage', id)
		    .success(function(data){
			   $scope.showAllPages = false;
			   $scope.showDeletedPages = false;
			   $scope.showPage = true;
			   
			   $scope.page.id = data['page'][0].id;
			   $scope.page.title = data['page'][0].title;
			   $("#pageContent").jqteVal(data['page'][0].content);
			   $scope.leadImage = data['page'][0].lead_image_file;
			   $scope.templates = data['templates'];
			   setTimeout(function(){
					document.getElementById("template").selectedIndex = data['page'][0].template;
				}, 10);
				$('.loading').fadeOut();
		    })
		    .error(function(){
			   console.log(data); 
		    });
	    }
	    
	    // New page
	    $scope.newPage = function() {
			$scope.showAllPages = false;
			$scope.showPage = true;
			$scope.showDeletedPages = false;
			serverPages.answer('getTemplates', null)
			.success(function(data){
				$scope.page.id = null;
				$scope.page.title = null;
				$("#pageContent").jqteVal("");
				$scope.leadImage = null;
				$scope.templates = data;
			})
			.error(function(data){
				console.log(data);
			});			
	    }
	    
	    // Delete page
	    $scope.deletePage = function(id) {
		    var del = confirm('Are you sure?')	    
		    if(del){
			    $('.loading').fadeIn();
			 	serverPages.answer('deletePage', id)
			    .success(function(data){
				    $scope.allPages = data;
				    $('.loading').fadeOut();
			    })
			    .error(function(data){
				    console.log(data);
			    });
			}		    
	    }
	    
	    // Delete page from DB
	    $scope.deleteForever = function(id) {
			var del = confirm('Are you sure?')	    
			if(del){
				$('.loading').fadeIn();
			 	serverPages.answer('deleteForever', id)
			    .success(function(data){
				    $scope.delPages = data;
				    $('.loading').fadeOut();
			    })
			    .error(function(data){
				    console.log(data);
			    });
			}
	    }
	    
	    // Undelete page
	    $scope.unDelete = function(id) {
		    $('.loading').fadeIn();
			serverPages.answer('unDelete', id)
		    .success(function(data){
			    $scope.delPages = data;
			    $('.loading').fadeOut();
		    })
		    .error(function(data){
			    console.log(data);
		    });
	    }
	    
	    // Show deleted pages
	    $scope.deletedPages = function() {
		    $('.loading').fadeIn();
		    serverPages.answer('deletedPages', null)
		    .success(function(data){
				$scope.showDeletedPages = true;
				$scope.showAllPages = false;
				$scope.showPage = false;
				$scope.delPages = data;
				$('.loading').fadeOut();
		    })
		    .error(function(data){
				console.log(data);
		    });
	    }
	    
	    // Timestamp date function for saving in database
	    function date() {
			Number.prototype.zero = function(base,chr){
				var  len = (String(base || 10).length - String(this).length)+1;
				return len > 0? new Array(len).join(chr || '0')+this : this;
			}
			var d = new Date;
			var date = 
				[d.getFullYear().zero(),
				d.getDate().zero(),
				(d.getMonth()+1).zero()].join('/')+' '+
				[d.getHours().zero(),
				d.getMinutes().zero(),
				d.getSeconds().zero()].join(':');
				
			return date;
		}
	    
	    // Add images to content
	    $scope.addFiles = false;
	    $scope.addImage = function() {
		    $('.loading').fadeIn();
			serverPages.answer('getFiles', null)
			.success(function(data){
				$scope.addFiles = true;
				$scope.imgs = data;
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});
	    }
	    
	    // Get image selected from DB to content area
	    $scope.setImage = function(id) {
		    $('.loading').fadeIn();
			serverPages.answer('getFileName', id)
			.success(function(data){
				var file = data[0].file;
				var image = document.createElement('img');
				image.setAttribute('class', 'textEreaImage');
				image.setAttribute('src', 'files/images/'+file);
				$('.jqte_editor').append(image);
				$scope.addFiles = false;
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});
		}
		
		// Set scope date
		$scope.page.date = date();
		
		// Show images for choosing one as lead image
		$scope.images = false;
		$scope.chooseLeadImage = function() {
			$('.loading').fadeIn();
			serverPages.answer('getFiles', null)
			.success(function(data){
				$scope.images = true;
				$scope.files = data;
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});
		}
		
		// Set lead image and update $scope
		$scope.setLeadImage = function(id) {
			$('.loading').fadeIn();
			
			var data = [];
			data = [{
			    pageId: $scope.page.id,
			    imageId: id
			}];
			
			serverPages.answer('setLeadImage', data)
			.success(function(data){
				if(data == 'no'){
					$('.loading').fadeOut();
					$scope.images = false;
					return alert('Please save page first\nand then choose lead image');
				}
				else{
					// Set lead image
					$scope.leadImage = data[0].file;
					$('.loading').fadeOut();
				}
				$scope.images = false;
			})
			.error(function(data){
				console.log(data);
			});
		}
		
		// Save and Update page contents
		$scope.save = function() {
			if($scope.page.title == null){
				
				return alert("Please add a title");
			}
			
			$('.loading').fadeIn();
			var cont = document.getElementById("pageContent").value;
			var template = document.getElementById('template').value;
			var page = [{
				id: $scope.page.id,
				title: $scope.page.title,
				date: $scope.page.date,
				content: cont,
				template: template
			}];
			serverPages.answer('saveAndUpdate', page)
			.success(function(data){
				$scope.page = {};
	
				$scope.page.id = data[0].id;
				$scope.page.title = data[0].title;

				$scope.page.date = date();
				
				$(".jqte_editor").jqteVal(data[0].content);
				
				setTimeout(function(){
					document.getElementById("template").selectedIndex = data[0].template;
				}, 10);
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});			
		}
		
    });
    
    // Factory for getting settings definitions
    app.factory('serverPages', function($http) {
	    return {
	        answer: function(url, data) {
	            return $http.post(url, {data: data});
	        }
	    };
	});
 
}());