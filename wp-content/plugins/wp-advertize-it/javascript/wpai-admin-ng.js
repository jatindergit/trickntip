angular.module('app', ['ui.router','ui.bootstrap']);//'ngAnimate'


function routesConfig($stateProvider, $locationProvider,$urlRouterProvider, $httpProvider) {
    $stateProvider
       .state('blocks', {
            views: {
                'blocks': {
                	templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/blocks.php',
                    controller: 'BlockController',
                    controllerAs: 'blockCtrl'
                }                
            }
        })
        .state('placements', {            
            views: {
                'placements': {
                    templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/placements.php',
                    controller: 'PlacementController',
                    controllerAs: 'placementCtrl'
                }
            }
        }) 
        .state('settings', {            
            views: {
                'settings': {
                    templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/settings.php',
                    controller: 'SettingsController',
                    controllerAs: 'settingsCtrl'
                }
            }
        })
        .state('info', {            
            views: {
                'info': {
                    templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/info.php'
                }
            }
        })
        .state('preview', {            
            views: {
                'info': {
                    templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/preview.php'
                }
            }
        });

$locationProvider.html5Mode(false);
    
    //$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
/*
    $urlRouterProvider.rule(function ($injector, $location) {
        var slashHashRegex,
            matches,
            path = $location.url();

        // check to see if the path already has a slash where it should be
        if (path[path.length - 1] === '/' || path.indexOf('/?') > -1) {
            return path.substring(0, path.length - 1);
        }

        // if there is a trailing slash *and* there is a hash, remove the last slash so the route will correctly fire
        slashHashRegex = /\/(#[^\/]*)$/;
        matches = path.match(slashHashRegex);
        if (1 < matches.length) {
            return path.replace(matches[0], matches[1]);
        }
    });*/
}

function AppController($rootScope, $scope, $animate, $timeout,$window,$state,BlockService,PlacementService) { 
    var vm = this;

    //this.PlacementService = PlacementService;
    
    /*$rootScope.$on('$stateChangeSuccess', function(e, toState, to, fromState, from) {
        vm.activeSection = toState.name;
                
    });*/
   
/*    vm.nextPage = function(){
    	//alert('next');
    };*/
    $rootScope.goState = function(state) {
    	$state.go(state);
    };
     
    $rootScope.mtabs = [];
    
    $scope.tabs = {'blocks':true, 'placements':false};
    
    this.createObj = function(){
    	if ($state.current.name == 'blocks'){
    		BlockService.createBlock();
    	}else if ($state.current.name == 'placements'){
    		PlacementService.createPlacement();
    	}
	}
    
    $rootScope.progress = 0;
    
    return {
    	createObj: this.createObj
    };
    
}

angular.module('app')
  .controller('AppController', AppController)
  .config(routesConfig)
  .filter('orderObjectBy', function() {
	  return function(items, field, reverse) {
		    var filtered = [];
		    angular.forEach(items, function(item) {
		      filtered.push(item);
		    });
		    filtered.sort(function (a, b) {
		      return (a[field].toLowerCase() > b[field].toLowerCase() ? 1 : -1);
		    });
		    if(reverse) filtered.reverse();
		    return filtered;
		  };
		})
  .filter('object2Array', function() {  
        return function(input) {
            var out = []; 
            for(i in input){
                out.push(input[i]);
            }
            return out;
        }
    });