(function () {
    'use strict';
 
    var app = angular.module('aeadmin');
    
    app.config(function($routeProvider) {	
		$routeProvider
			.when('/portfolio', {
	            templateUrl : 'files/pages/admin/portfolio.html',
	            controller  : 'portfolioController'
	        });
	});
	
	// Settings controller
    app.controller('portfolioController', function ($scope, $http, serverPortfolio) {
	    
	    $('.loading').fadeIn();
	    $scope.showAllPortfolio = true;
	    $scope.showPortfolio = false;
	    $scope.showDeletedPortfolio = false;
	    
	    // Define portfolio for scope
	    $scope.portfolio = {};
	    
	    // Show all portfolios on load
	    serverPortfolio.answer('getAllPortfolios', null)
	    .success(function(data){
		    $scope.allPortfolios = data;
		    $('.loading').fadeOut();
	    })
	    .error(function(data){
		    console.log(data);
	    });
	    
	    // Show all portfolios BTN
	    $scope.getAllPortF = function() {
		    if($scope.showPortfolio){
			    if($scope.leadImage == null){
				    var exit = confirm("Portfolio has not been saved yet.\nPlease add title and lead image.\n\nExit anyway?")
				    if(exit){
					    $('.loading').fadeIn();
					    serverPortfolio.answer('getAllPortfolios', null)
					    .success(function(data){
							$scope.showAllPortfolio = true;
						    $scope.showPortfolio = false;
						    $scope.showDeletedPortfolio = false;
							$scope.allPortfolios = data;
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
		    serverPortfolio.answer('getAllPortfolios', null)
		    .success(function(data){
				$scope.showAllPortfolio = true;
				$scope.showPortfolio = false;
				$scope.showDeletedPortfolio = false;
			    $scope.allPortfolios = data;
			    $('.loading').fadeOut();
		    })
		    .error(function(data){
			    console.log(data);
		    });
	    }
	    	    	    
	    // Show portfolio selected
	    $scope.editPortfolio = function(id) {
		    $('.loading').fadeIn();
		    $scope.portfolio.id = null;
			$scope.portfolio.title = null;
			$scope.portfolio.description = null;
			$scope.leadImage = null;
		    serverPortfolio.answer('editPortfolio', id)
		    .success(function(data){
			   $scope.showAllPortfolio = false;
			   $scope.showDeletedPortfolio = false;
			   $scope.showPortfolio = true;
			   
			   $scope.portfolio.id = data.id;
			   $scope.portfolio.title = data.title;
			   $scope.portfolio.category = data.category;
			   $scope.portfolio.description = data.description;
			   $scope.portfolio.link = data.link;
			   $scope.leadImage = data.lead_image_file;
			   $('.loading').fadeOut();
		    })
		    .error(function(){
			   console.log(data); 
		    });
	    }
	    
	    // New portfolio
	    $scope.newPortF = function() {
		    $scope.showAllPortfolio = false;
		    $scope.showPortfolio = true;
		    $scope.showDeletedPortfolio = false;
		    
			$scope.portfolio = null;
			$scope.leadImage = null;
	    }
	    
	    // Delete portfolio
	    $scope.deletePortfolio = function(id) {
		    var del = confirm('Are you sure?')	    
		    if(del){
			    $('.loading').fadeIn();
			 	serverPortfolio.answer('deletePortfolio', id)
			    .success(function(data){
				    $scope.allPortfolios = data;
				    $('.loading').fadeOut();
			    })
			    .error(function(data){
				    console.log(data);
			    });
			}		    
	    }
	    
	    // Delete portfolio from DB
	    $scope.deleteForever = function(id) {
			var del = confirm('Are you sure?')	    
			if(del){
				$('.loading').fadeIn();
			 	serverPortfolio.answer('deletePortfolioForever', id)
			    .success(function(data){
				    $scope.delPortfolios = data;
				    $('.loading').fadeOut();
			    })
			    .error(function(data){
				    console.log(data);
			    });
			}
	    }
	    
	    // Show deleted portfolios
	    $scope.deletedPortF = function() {
		    $('.loading').fadeIn();
		    serverPortfolio.answer('deletedPortF', null)
		    .success(function(data){
				$scope.showAllPortfolio = false;
			    $scope.showPortfolio = false;
			    $scope.showDeletedPortfolio = true;
				$scope.delPortfolios = data;
				$('.loading').fadeOut();
		    })
		    .error(function(data){
				console.log(data);
		    });
	    }
	    
	    // Undelete portfolio
	    $scope.unDelete = function(id) {
		    $('.loading').fadeIn();
			serverPortfolio.answer('unDeletePortF', id)
		    .success(function(data){
			    $scope.delPortfolios = data;
			    $('.loading').fadeOut();
		    })
		    .error(function(data){
			    console.log(data);
		    });
	    }
	    
	    // Show images for choosing one as lead image
		$scope.images = false;
		$scope.chooseLeadImage = function() {
			$('.loading').fadeIn();
			serverPortfolio.answer('getFiles', null)
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
			    pageId: $scope.portfolio.id,
			    imageId: id
			}];
			
			serverPortfolio.answer('portfolioLeadImage', data)
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
		
		// Save and Update page description
		$scope.save = function() {
			if($scope.portfolio.title == null){
				
				return alert("Please add a title");
			}
			
			$('.loading').fadeIn();
			$scope.portfolio.description = document.getElementById("desc").value;
			serverPortfolio.answer('saveAndUpdatePortfolio', $scope.portfolio)
			.success(function(data){
				$scope.portfolio = {};
				
				$scope.portfolio.id = data[0].id;
				$scope.portfolio.title = data[0].title;
				$scope.portfolio.category = data[0].category;
				$scope.portfolio.description = data[0].description;
				$scope.portfolio.link = data[0].link;
				$('.loading').fadeOut();
			})
			.error(function(data){
				console.log(data);
			});	
		}
	    		
    });
    
    // Factory for getting settings definitions
    app.factory('serverPortfolio', function($http) {
	    return {
	        answer: function(url, data) {
	            return $http.post(url, {data: data});
	        }
	    };
	});
 
}());