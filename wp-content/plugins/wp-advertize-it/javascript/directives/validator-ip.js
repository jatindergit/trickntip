/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/

function ipEnum($compile, $timeout){
	var INTEGER_RANGES = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(,(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/;
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
    .directive('ipEnum', ipEnum);