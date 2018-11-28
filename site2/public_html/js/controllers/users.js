AngularApp.controller('UsersCtrl', ['$scope', 'Users','$timeout', 'messageTimeout', '$sce',
function($scope, Users, $timeout, messageTimeout, $sce) {
	
	$scope.alerts = [];
	
	$scope.isProcessing = false;
	
	//Load the current posts data
	$scope.initSync = function(user_id) {

		Users.get({
			user_id : user_id
		}, function(response) {

			//Assign the post data to a scope
			$scope.data = response;
		});
	};

	$scope.register = function($event) {

		//Stop any default submits
		$event.preventDefault();

		//Clone data to new object
		var data = Object.assign({},$scope.data);
		
		//Set the spinner to true to alert user
		$scope.isProcessing = true;

		//Attempt to create the post
		Users.register({}, data, function(response) {
			//Success
			window.location = '/profile/' + response.user_id;
		}, function(response) {
			
			//Error message
			$scope.validationMessage = $sce.trustAsHtml(response.data);
			
			$scope.isProcessing= false;

			$timeout(function() {
				$scope.validationMessage = '';
			}, messageTimeout);
		});

	};
	
	$scope.updateInfo = function($event) {

		//Stop any default submits
		$event.preventDefault();

		//Clone data to new object
		var data = Object.assign({},$scope.data);
		
		//Remove the email, we don't want to update that here
		delete data['email'];
		
		//Set the spinner to true to alert user
		$scope.isProcessing = true;

		//Attempt to update the post
		Users.update({}, data, function(response) {
			
			//Success
			$scope.updateInfoMessage = '<div class="alert alert-success">Information Successfully Updated</div>';
		}, function(response) {
			
			//Error message
			$scope.updateInfoMessage = $sce.trustAsHtml(response.data);
			
			$scope.isProcessing= false;

			$timeout(function() {
				$scope.updateInfoMessage = '';
			}, messageTimeout);
		});

	};
	
	$scope.updateEmail = function($event) {

		//Stop any default submits
		$event.preventDefault();

		//Clone data to new object
		var data = Object.assign({},$scope.data);
		
		//Set the spinner to true to alert user
		$scope.isProcessing = true;

		//Attempt to update the post
		Users.email({}, data, function(response) {
			
			//Success
			$scope.updateEmailMessage = '<div class="alert alert-success">Email Successfully Updated</div>';
		}, function(response) {
			
			//Error message
			$scope.updateEmailMessage = $sce.trustAsHtml(response.data);
			
			$scope.isProcessing= false;

			$timeout(function() {
				$scope.updateEmailMessage = '';
			}, messageTimeout);
		});

	};
	
	$scope.updatePassword = function($event) {

		//Stop any default submits
		$event.preventDefault();

		//Clone data to new object
		var data = Object.assign({},$scope.data);
		
		//Set the spinner to true to alert user
		$scope.isProcessing = true;

		//Attempt to update the post
		Users.password({}, data, function(response) {
			
			//Success
			$scope.updatePasswordMessage = '<div class="alert alert-success">Password Successfully Updated</div>';
		}, function(response) {
			
			//Error message
			$scope.updatePasswordMessage = $sce.trustAsHtml(response.data);
			
			$scope.isProcessing= false;

			$timeout(function() {
				$scope.updatePasswordMessage = '';
			}, messageTimeout);
		});

	};

}]); 