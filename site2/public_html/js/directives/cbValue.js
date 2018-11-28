AngularApp.directive('cbTrueValue', [
function() {
	return {
		restrict : 'A',
		require : 'ngModel',
		link : function(scope, element, attrs, ngModel) {
			element.on('click', function(e) {
				scope.ngModel = (ngModel.$viewValue == false || ngModel.$viewValue== 0) ? 0 : 1;
				
				ngModel.$setViewValue(scope.ngModel);
				ngModel.$render();
				
				scope.$apply();
				
				ngModel.$parsers.push(function(v) {
					return v ? scope.$eval(attrs.cbTrueValue) : scope.$eval(attrs.cbFalseValue);
				});
			});

		}
	};
}]);

