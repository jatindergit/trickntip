
function numRange($compile, $timeout){
	var INTEGER_RANGES = /^([0-9]+(-[0-9]+)?)(,([0-9]+(-[0-9]+)?))*$/;
	return {
	    require: 'ngModel',
	    link: function(scope, elm, attrs, ctrl) {
	      ctrl.$validators.integer = function(modelValue, viewValue) {
	        if (ctrl.$isEmpty(modelValue)) {
	          // consider empty models to be valid
	          return true;
	        }

	        if (INTEGER_RANGES.test(viewValue)) {
	          // it is valid
	          return true;
	        }

	        // it is invalid
	        return false;
	      };
	    }
	  };
};

angular.module('app')
    .directive('numRange', numRange);

