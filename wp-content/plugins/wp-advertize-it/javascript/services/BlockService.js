
function BlockService( $http, HttpResponseService, $filter,$rootScope) {

	var WPAIDEL = '#wpai-del#';
	
	var vm = this;
	
	this.model = {};
	this.masterModel = {};
	this.blockStates = {};
	
	this.blockStates[0] = {'changed':true,'opened':true,'current_ads':1};
	
    this.getBlocks = function() {
    	    	
		var data = 'action=get_blocks&nt='+new Date().getTime();
		
        var promise = $http({
            method : 'POST',
            url : ajaxurl,
            data:  data,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

        });
        
        return HttpResponseService.handle(promise, function(response) {        
                if (response instanceof Object) {
                	
                	vm.model.data = {};
                	vm.masterModel.data = {};                	
                	vm.blockStates.length = 0;
                	
                	
                	angular.forEach(response, function(value, key) {
                		
                		vm.blockStates[value.id] = {'changed':false, 'opened':false,'current_ads':1,'name':value.name};
                		
                		vm.model.data[value.id] = value;                		
                		value.promo_duration = parseInt(value.promo_duration,10);
                		value.promo_every = parseInt(value.promo_every,10);
                		value.promo_only_not_sold = value.promo_only_not_sold==1?true:false;
                		value.promotion = value.promotion==1?true:false;
                		value.rotate_ads = value.rotate_ads==1?true:false;
                		value.rotation_duration = parseInt(value.rotation_duration,10);
                		//value.alignment = value.alignment;
                		value.style = parseInt(value.style,10);
                		
                		if (value.default_ads){
                			value.default_adss = value.default_ads.split(WPAIDEL);
                		}else{
                			value.default_adss = [''];
                		}
                		
                		//value.current_ads=1;
                		
                		vm.masterModel.data[value.id] = angular.copy(value);
                	});
                	                    
                }
            });
    };
    
    this.createBlock  = function() {
    	var tempid = 0;
    	vm.model.data[tempid] = {'id':tempid, 
    			'name':'new Ads-Block',
    			'promo_duration': 10,
    			'promo_every' : 0,
    			'promotion' : 0,
    			'promo_only_not_sold' : 1,
    			'rotate_ads' : 0,
    			'rotation_duration' : 10,
    			'default_adss': [],
    			'alignment': 'default',
    			'style': 0};
    	
    	vm.masterModel.data[tempid] = angular.copy(vm.model.data[tempid]);
    	
    	vm.blockStates[tempid] = {'changed':true, 'opened':true,'current_ads':1,'name':'new Ads-Block'};
    	
    };
    
    this.saveBlock = function(block) {
    	    	
    	block.promo_only_not_sold = block.promo_only_not_sold ? 1:0;
    	block.rotate_ads = block.rotate_ads ? 1:0;
    	block.promotion = block.promotion==1?true:false;
    	
    	block.default_ads = '';
    	angular.forEach(block.default_adss, function(value,key){
    		block.default_ads = block.default_ads + WPAIDEL + value; 
    	});
    	block.default_ads = block.default_ads.substring(WPAIDEL.length);
    	
    	
    	var ajaxurl_post;
    	
    	if(block.id==0){
    		delete(vm.masterModel.data[0]);
    		delete(vm.model.data[0]);    		
    		ajaxurl_post = ajaxurl+'?action=create_block&nt='+new Date().getTime();
    	} else{
    		ajaxurl_post = ajaxurl+'?action=save_block&nt='+new Date().getTime();
    	}
    	
        var promise = $http({
            method : 'POST',
            url : ajaxurl_post,
            data:  { 'block' :block } 
        });
        
        return HttpResponseService.handle(promise, function(data) {
                if (data) {
                	
                	data.promo_duration = parseInt(data.promo_duration,10);
                	data.promo_every = parseInt(data.promo_every,10);
                	data.promo_only_not_sold = data.promo_only_not_sold==1 ? true:false;
                	data.promotion = data.promotion==1?true:false;
                	data.rotate_ads = data.rotate_ads==1?true:false;
                	data.rotation_duration = parseInt(data.rotation_duration,10);
                	data.style = parseInt(data.style,10);
            		
                	if (data.default_ads){
                		data.default_adss = data.default_ads.split(WPAIDEL);
            		}else{
            			data.default_adss = [''];
            		}
                	
                	vm.blockStates[data.id] = {'changed':false,'opened':true,'current_ads':1,'name':data.name};
                	
                	vm.model.data[data.id] = data;
                	vm.masterModel.data[data.id] = angular.copy(vm.model.data[data.id]);
                	return data;
                }
        });
	};
	
	this.deleteBlock = function(block){
		ajaxurl_post = ajaxurl+'?action=delete_block&nt='+new Date().getTime();
	
		var promise = $http({
	        method : 'POST',
	        url : ajaxurl_post,
	        data:  { 'block' :block.id } 
	    });
    
	    return HttpResponseService.handle(promise, function(data) {});
	};
	
   
    return {
    	getBlocks: this.getBlocks,
    	saveBlock: this.saveBlock,
    	createBlock: this.createBlock,
    	deleteBlock: this.deleteBlock,
    	model: this.model,
    	masterModel: this.masterModel,
    	blockStates: this.blockStates
    };
}

angular
    .module('app')
    .factory('BlockService', BlockService);