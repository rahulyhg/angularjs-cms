<!DOCTYPE HTML>
<html lang="en" ng-app="aeadmin">
<head>
	<meta charset="utf-8">
	<title>createit</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?= URL::base(); ?>files/css/aeadmin.css">
	<link rel="stylesheet" type="text/css" href="<?= URL::base(); ?>files/css/jquery-te-1.4.0.css">
	<script src="<?= URL::base(); ?>files/js/jquery.min.js"></script>
	<script src="<?= URL::base(); ?>files/js/jquery-te-1.4.0.min.js"></script>
	<script src="<?= URL::base(); ?>files/js/angular.js"></script>
	<script src="<?= URL::base(); ?>files/js/angular-route.min.js"></script>
	<script src="<?= URL::base(); ?>files/js/app.js"></script>
	<script src="<?= URL::base(); ?>files/js/adminCtrl.js"></script>
	<script src="<?= URL::base(); ?>files/js/filesCtrl.js"></script>
	<script src="<?= URL::base(); ?>files/js/settingsCtrl.js"></script>
	<script src="<?= URL::base(); ?>files/js/pagesCtrl.js"></script>
	<script src="<?= URL::base(); ?>files/js/portfolioCtrl.js"></script>
	<script src="<?= URL::base(); ?>files/js/usersCtrl.js"></script>
</head>
<body ng-controller="admin">

	<div id="page" style="background-image:url(<?= URL::base(); ?>files/images/{{wp}});">
	
		<header>
			<nav>
				<ul>
					<li>
						<a href="#/">Dashboard</a>
					</li>
					<li>
						<a href="#pages">Pages</a>
					</li>
					<li>
						<a href="#portfolio">Portfolio</a>
					</li>
					<li>
						<a href="#files">Files</a>
					</li>
					<!-- <li>
						<a href="#users">Users</a>
					</li> -->
					<li>
						<a href="#settings">Settings</a>
					</li>
					<li>
						<a ng-click="logout()">Logout</a>
					</li>
				</ul>
			</nav>
		</header>
		
		<div id="main">
			
			<div ng-view></div>
		
		<!-- Main end -->	
		</div>
		
	<!-- Page end -->
	</div>

</body>