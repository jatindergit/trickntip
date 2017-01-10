function BlockController($rootScope, $scope,  BlockService, $uibModal,$timeout) {
    var vm = this;
    
    $scope.blocks = BlockService.model; 
    $scope.masterBlocks = BlockService.masterModel;
    $scope.blockStates = {};
    $scope.blockStates.state = BlockService.blockStates;
    
    $scope.selectedBlockId = 10;
    
    $scope.previewads = '';
    
    $scope.alignment = {
    	    options: [
    	      'default',
    	      'left',
    	      'center',
    	      'right'
    	    ],
    	    selected: 'default'
    	  };
    
    $rootScope.mtabs = [{ targetTab:'blocks', title:'Add Ads-Block', content:'Dynamic content 1' }];
    
    $scope.tabs = {'blocks':true, 'placement':false, 'settings':false};
    
    BlockService.getBlocks().then(function(){
    
    	angular.forEach($scope.blocks.data, function(value) {
    		
    		BlockService.blockStates[value.id] = {'changed':false,'opened':false,'current_ads':1,'name':$scope.blocks.data[value.id].name};
    		
    		$scope.$watch("blocks.data['"+value.id+"'] | json", function(newValue, oldValue){
    			if (newValue){ //not for removed items

    				var obj = $scope.blocks.data[value.id];
	    			if (JSON.stringify($scope.masterBlocks.data[value.id]) != JSON.stringify(obj)){
	    				BlockService.blockStates[obj.id].changed = true;
	    			}else{
	    				BlockService.blockStates[obj.id].changed = false;
	    			}
    			}
    			
    		});
    		
    		//for new elements
    		$scope.$watchCollection(function() {return BlockService.blockStates;}, function(newcol, oldcol){
    			$scope.$watch("blocks.data["+(newcol.length-1)+"] | json", function(newValue, oldValue){ 
    				if (newValue){ //not for removed items
	    				//var obj = $scope.blocks.data[(newcol.length-1)];    
	    				//angular added $$hashKey to objects (after sorting/filtering). Therefore copy and delete the hash. 
	        			var obj = angular.copy($scope.blocks.data[(newcol.length-1)]);
	        			delete obj.$$hashKey;
	        			if (JSON.stringify($scope.masterBlocks.data[obj.id]) != JSON.stringify(obj)){
	        				BlockService.blockStates[obj.id].changed = true;
	        			}else{
	        				BlockService.blockStates[obj.id].changed = false;
	        			}
    				}
    			});        		
        	});
    		
    	});
    });
    
    $scope.update = function(block) {
    	if (block){
    		$scope.masterBlocks.data[block.id] = angular.copy($scope.blocks.data[block.id]);
    	}
    	
    	var  newblock = false;
    	if (block.id==0){
    		newblock = true;
    	}
    	
    	var promise_data = BlockService.saveBlock(block);
    	
    	if (newblock){
    		
    		promise_data.then(function(data){
    			$scope.$watch("blocks.data['"+data.id+"'] | json", function(newValue, oldValue){
    	
					if (newValue){ //not for removed items

						var obj = $scope.blocks.data[data.id];
						if (JSON.stringify($scope.masterBlocks.data[data.id]) != JSON.stringify(obj)){
							BlockService.blockStates[obj.id].changed = true;
						}else{
							BlockService.blockStates[obj.id].changed = false;
						}
					}

				});
    		});
    	}
    	
    };

    $scope.reset = function(block) {
    	if (block){
    		$scope.blocks.data[block.id] = angular.copy($scope.masterBlocks.data[block.id]);
    	}
    };
    
    $scope.delete = function(block) {
    	if (block){
    		delete($scope.masterBlocks.data[block.id]);
    		delete($scope.blocks.data[block.id]); 
    		
    		BlockService.deleteBlock(block);
    	}
    };
    
    $scope.openPreview = function (ads, type) {
    		
    	$scope.modalInstance = $uibModal.open({ 
          templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/preview.php?id='+ads.id+'&type='+type+'&ord='+$scope.blockStates.state[ads.id].current_ads+'&co=' + Math.random().toString(36).slice(2),
          scope: $scope,
          size: 'lg'

        });

   };
   
   $scope.closePreview=function(){
	   //$scope.modalInstance.dismiss();
	   $scope.modalInstance.close();// also works I think
	};
	
	$scope.addAds = function(block){
		var ads_len = block.default_adss.length;
		block.default_adss.push('');

		BlockService.blockStates[block.id].current_ads = ads_len+1;
	}
	
	$scope.removeAds = function(block){
		
		block.default_adss.splice(BlockService.blockStates[block.id].current_ads-1,1);
		
		//BlockService.blockStates[block.id].current_ads = ads_len+1;
	}
}

angular
    .module('app')
    .controller('BlockController',BlockController);
    