function compile($compile, $timeout){
    return{
        restrict:'A',
        link: function(scope,elem,attrs){
            $timeout(function(){                
                $compile(elem.contents())(scope);                
            });
        }        
    }
};

angular.module('app')
    .directive('compile', compile);