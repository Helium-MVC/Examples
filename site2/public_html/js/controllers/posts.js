AngularApp.controller('PostsCtrl', ['$scope', 'Posts','$timeout', 'messageTimeout', '$sce',
function($scope, Posts, $timeout, messageTimeout, $sce) {
	
	$scope.alerts = [];
	
	$scope.isProcessing = false;
	
	//Load the current posts data
	$scope.initSync = function(post_id) {

		Posts.get({
			post_id : post_id
		}, function(response) {

			//Assign the post data to a scope
			$scope.data = Object.assign($scope.data, response);
		});
	};

	$scope.create = function($event) {

		//Stop any default submits
		$event.preventDefault();

		//Clone data to new object
		var data = Object.assign({},$scope.data);
		
		//Set the spinner to true to alert user
		$scope.isProcessing = true;

		//Attempt to create the post
		Posts.save({}, data, function(response) {
			
			//Success
			window.location = '/posts/view/' + response.post_id;
		}, function(response) {
			
			//Error message
			$scope.validationMessage= $sce.trustAsHtml(response.data);
			
			$scope.isProcessing= false;

			$timeout(function() {
				$scope.validationMessage = '';
			}, messageTimeout);
		});

	};
	
	$scope.update = function($event) {

		//Stop any default submits
		$event.preventDefault();

		//Clone data to new object
		var data = Object.assign({},$scope.data);
		
		//Set the spinner to true to alert user
		$scope.isProcessing = true;

		//Attempt to update the post
		Posts.update({}, data, function(response) {
			
			//Success
			window.location = '/posts/view/' + response.post_id;
		}, function(response) {
			
			//Error message
			$scope.validationMessage = $sce.trustAsHtml(response.data);
			
			$scope.isProcessing= false;

			$timeout(function() {
				$scope.validationMessage = '';
			}, messageTimeout);
		});

	};

}]); 