/**
 * 
 *  NOT USED!
 * 
 */

function compileDropdown($compile, $timeout){
	return {
        restrict: "E",
        priority: 1000,
        terminal: true,
        compile: function(tElement, tAttrs, transclude) {

            var tplEl = angular.element('<select></select>');
            tplEl.attr("ng-options", "i.value.term_id as i.value.name for i in meta.'++'.data");

            for(attr in tAttrs.$attr) {
                tplEl.attr(tAttrs.$attr[attr], tAttrs[attr]);
            }
            
            
            return function(scope, element, attrs) {

                var compiledEl = $compile(tplEl)(scope);
                //compiledEl = compiledEl.append(scope.meta.tags.data);
                element.replaceWith(compiledEl);
            }
        }
    };
};

angular.module('app')
    .directive('compileDropdown', compileDropdown);