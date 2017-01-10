function PlacementController($rootScope, $scope,  PlacementService, BlockService, SettingsService, $uibModal, $sce) {
    var vm = this;
    
    $scope.placements = PlacementService.model; 
    $scope.masterPlacements = PlacementService.masterModel;
    $scope.placementStates = {};
    $scope.placementStates.state = PlacementService.placementStates;
    
    $scope.allblocks = BlockService.model;
    
    $scope.allblocksArr = Object.keys($scope.allblocks.data).map(function(key) {
        return $scope.allblocks.data[key];
      });

    $scope.languages = SettingsService.languages;
    SettingsService.getLanguages();

    $scope.buttonStates = {'save':false, 'reset':true}

    $scope.placementOrder = 'name_i10n';
    
    $rootScope.mtabs = [];
    
    $scope.tabs = {'blocks':false, 'placement':true, 'settings':false};
    
    PlacementService.getPlacements().then(function(){    	
    	angular.forEach($scope.placements.data, function(value) {
    		
    		PlacementService.placementStates[value.id] = {'changed':false,'opened':false};
    		
    		$scope.$watch("placements.data['"+value.id+"'] | json", function(newValue, oldValue){
    			if (newValue){ 
    				var obj = $scope.placements.data[value.id];
	    			if (JSON.stringify($scope.masterPlacements.data[value.id]) != JSON.stringify(obj)){
	    				PlacementService.placementStates[obj.id].changed = true;
	    			}else{
	    				PlacementService.placementStates[obj.id].changed = false;
	    			}
    			}
    			
    		});
    		
    		//for new elements
    		$scope.$watchCollection(function() {return PlacementService.placementStates;}, function(newcol, oldcol){
    			$scope.$watch("placements.data["+(newcol.length-1)+"] | json", function(newValue, oldValue){ 
    				if (newValue){ 
	        			var obj = angular.copy($scope.placements.data[(newcol.length-1)]);
	        			delete obj.$$hashKey;
	        			if (JSON.stringify($scope.masterPlacements.data[obj.id]) != JSON.stringify(obj)){
	        				PlacementService.placementStates[obj.id].changed = true;
	        			}else{
	        				PlacementService.placementStates[obj.id].changed = false;
	        			}
    				}
    			});        		
        	});
    		
    	});
    });
    
    $scope.update = function(placement) {
    	if (placement){
    		$scope.masterPlacements.data[placement.id] = angular.copy($scope.placements.data[placement.id]);
    	}
    	
    	PlacementService.savePlacement(placement);    	
    };

    $scope.reset = function(placement) {
    	if (placement){
    		$scope.placements.data[placement.id] = angular.copy($scope.masterPlacements.data[placement.id]);
    	}
    };
    
    $scope.delete = function(placement) {
    	if (placement){
    		delete($scope.masterPlacements.data[placement.id]);
    		delete($scope.placements.data[placement.id]); 
    		
    		PlacementService.deletePlacement(placement);
    	}
    };
    
    $scope.selectBlock = function(placement, block){
    	if (block){
    		placement.blockid = block.id;
    	}else{
    		placement.blockid = 0;
    	}
    }

    $scope.openPlacementSettings = function(id, name) {
        $scope.placementName = name;
        $scope.placementId = id;
        PlacementService.getPlacementSettings(id);
        $scope.placementSettings = PlacementService.placementSettings;
        $scope.modalInstance = $uibModal.open({ 
          templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/placement-settings.php',
          scope: $scope,
          size: 'lg',
          backdrop : 'static'
        });
   }

   $scope.printOption = function(setting){
        if (setting.type == 'text'){
            var placeholder = "";
            if (setting.placeholder!==undefined){
                placeholder = "placeholder='"+setting.placeholder+"'";
            }
            return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7" wpai-input-container><input class="form-control" type="text" '+ placeholder + ' ng-model="setting.value" '+setting.attrs+' ng-change="changeButtonStatus()"/></div>');
        }else if (setting.type == 'array'){
            if (setting.name == 'suppress-language' && $scope.languages.data.length == 0){
                return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><p>This option is only available with the plugin <a href="https://de.wordpress.org/plugins/qtranslate-x/">qTranslate X</a> or <a href="https://wordpress.org/plugins/mqtranslate/">mqTranslate</a>.</p></div>');
            }else{
                return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container">' + 
                                        '<select multiple class="form-control wpai-form-caption" ng-model="setting.value" '+setting.attrs+' ng-change="changeButtonStatus()">' +
                                            '<option ng-repeat="item in '+setting.src+'.data" value="{{item.key}}">{{item.value}}</option>' +
                                        '</select></div>'
                        );
            }
        }else if (setting.type == 'checkbox'){
            return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><input class="form-control" type="checkbox" ng-model="setting.value" '+setting.attrs+' ng-change="changeButtonStatus()"/></div>');
        }else if (setting.type == 'number'){
            return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><input class="form-control" type="number" ng-model="setting.value" '+setting.attrs+' ng-change="changeButtonStatus()"/></div>');     }
    }

    $scope.updatePlacement = function() {
        PlacementService.savePlacementSettings();
        $scope.closePlacementSettings();
    }

    $scope.closePlacementSettings = function() {
       $scope.modalInstance.close();
       $scope.buttonStates = {'save':false, 'reset':true}
    };

    $scope.changeButtonStatus = function() {
        $scope.buttonStates = {'save':true, 'reset':true}
    };

    $scope.resetPlacementSettings = function(id) {
        PlacementService.deletePlacementSettings(id);
        PlacementService.getPlacementSettings(id);
        $scope.placementSettings = PlacementService.placementSettings;
        $scope.buttonStates = {'save':false, 'reset':false}
    }
}

angular
    .module('app')
    .controller('PlacementController', PlacementController);