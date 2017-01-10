function keepfocus ($timeout) {
    return {
        restrict: 'A',
        scope: {
            objid: '@',
            index: '@',
            selectedobjid: '@'
        },
        link: function($scope, $element, attrs) {
            $scope.$watch("index", function(currentValue, previousValue) {
                if($scope.blockId == $scope.selectedBlockId)
                {
                    $timeout(function(){
                        $element[0].focus();
                    });
                }
            })
        }
    };
};

angular.module('app')
    .directive('keepfocus', keepfocus);