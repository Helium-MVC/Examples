
<?php if(isset($_SERVER['ENV']) && $_SERVER['ENV'] == 'production'): ?>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.7/angular.min.js"></script>	
	
	<script type="text/javascript" src="/js/scripts.js"></script>
	
<?php else: ?>
	<!-- JQuery -->
	<script type="text/javascript" src="/js/libs/jquery-3.3.1.js"></script>
	<!-- Bootstrap And Requirements -->
	<script type="text/javascript" src="/js/libs/popper.js"></script>
	<script type="text/javascript" src="/js/libs/tooltip.js"></script>
	<script type="text/javascript" src="/js/bootstrap/bootstrap.js"></script>
	<!-- AngularJS -->
	<script type="text/javascript" src="/js/libs/angular/angular1.6.7.js"></script>
	<!-- AngularJS Sanitize -->
	<script type="text/javascript" src="/js/libs/angular/angular-sanitize.js"></script>
	<!-- AngularJS  Router-->
	<script type="text/javascript" src="/js/libs/angular/angular-route.js"></script>
	<!-- AngularJS  Resource-->
	<script type="text/javascript" src="/js/libs/angular/angular-resource.js"></script>
	
	<!-- Load Wsyiwig Editor -->
	<script type="text/javascript" src="/js/libs/angular-wysiwyg.js"></script>
	<script type="text/javascript" src="/js/libs/bootstrap-colorpicker-module.js"></script>
	
	<!-- App -->
	<script type="text/javascript" src="/js/app/app.js"></script>
	
	<!-- Directives -->
	<script type="text/javascript" src="/js/directives/cbValue.js"></script>
	
	<!-- Post Controller -->
	<script type="text/javascript" src="/js/controllers/posts.js"></script>
	<!-- User Controller -->
	<script type="text/javascript" src="/js/controllers/users.js"></script>
	
	<!-- Posts Model -->
	<script type="text/javascript" src="/js/resources/posts.js"></script>
	<!-- User Model -->
	<script type="text/javascript" src="/js/resources/users.js"></script>
<?php endif; ?>
