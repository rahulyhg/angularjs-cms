<!DOCTYPE HTML>
<html lang="en" ng-app="aelogin">
<head>
	<meta charset="utf-8">
		<title>createit</title>
		<meta name="keywords" content="">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="<?= URL::base(); ?>files/css/aelogin.css">
		<script src="<?= URL::base(); ?>files/js/jquery.min.js"></script>
		<script src="<?= URL::base(); ?>files/js/angular.js"></script>
		<script src="<?= URL::base(); ?>files/js/aeLogin.js"></script>
</head>
<body ng-controller="login">
	<div id="page" style="background-image:url(<?= URL::base(); ?>files/images/{{wp}});">
		<div id="login">
			<form name="loginForm" ng-submit="submitForm()" novalidate>
		      
			    <label>Username</label>
			    <input type="text" ng-model="user.name" required>
			    <br>
	            <label>Password</label>
	            <input type="password" ng-model="user.password" required>
		        <br>
		        <label style="float:left; width:auto;">Remember me</label>
		        <input type="checkbox" ng-model="user.remember">
		        <div class="clearfix"><div>
		        <button type="submit" ng-disabled="loginForm.$invalid">Submit</button>
		        
		    </form>
		</div>
	</div>
</body>
</html>