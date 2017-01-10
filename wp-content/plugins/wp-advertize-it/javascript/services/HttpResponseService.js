
function HttpResponseService($http,$uibModal,$rootScope) {

	this.handle = function (promise, fn) {
		
			$rootScope.progress++;
		
        	return promise.then(
        		function(response) {
	        		if (response.data.STATUS === 'OK') {
	        			$rootScope.progress--;
	        			return fn(response.data.OBJ);
	        		}
	        		else {
	        			$uibModal.open({ 
	        				templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/usermsg.php?h='+escape(response.data.MSG_HEADER)+'&t='+escape(response.data.MSG),
	        				//scope: scope,
	        				size: 'sm',
	        				animation: true
	        	        });
	        		}
	        		$rootScope.progress--;
	        	},
	        	
	        	function(response) {
	        		$uibModal.open({ 
	        			templateUrl: '../wp-content/plugins/wp-advertize-it/views/templates/usermsg.php?h=ERROR&t='+escape('No responce from server'),
        				//scope: scope,
        				size: 'sm',
        				animation: true
        	        });
	        		$rootScope.progress--;
	        	}
        	);
        	
        };
        
    return{
    	handle : this.handle
    }
};

angular
	.module('app')
	.factory('HttpResponseService', HttpResponseService);