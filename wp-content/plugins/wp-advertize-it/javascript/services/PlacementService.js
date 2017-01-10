
function PlacementService( $http, HttpResponseService, $filter,$rootScope) {

	var vm = this;
	
	this.model = {};
	this.masterModel = {};
	this.placementStates = {};
    this.placementSettings = {};
	
	this.placementStates[0] = {'changed':true,'opened':true};
	
    this.getPlacements = function() {
    	    	
		var data = 'action=get_placements&nt='+new Date().getTime();
		
        var promise = $http({
            method : 'POST',
            url : ajaxurl,
            data:  data,
            headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

        });
        
        return HttpResponseService.handle(promise, function(response) {        
                	vm.model.data = {};
                	vm.masterModel.data = {};
                	vm.placementStates.length = 0;
                	                	
                	angular.forEach(response.dbplacements, function(value, key) {
                		
                		vm.placementStates[value.id] = {'changed':false, 'opened':false};
                		
                        value.priority = parseInt(value.priority);
                		vm.model.data[value.id] = value;
                		vm.model.data[value.id].name_i10n = response.defaultplacements[value.name].txt;
                		vm.model.data[value.id].defval = response.defaultplacements[value.name].val;
                		vm.masterModel.data[value.id] = angular.copy(value);
                		
                	});               	
           });
 
    };
    
    this.createPlacement  = function() {
    	var tempid = 0;
    	vm.model.data[tempid] = {'id':tempid, 
    			'name':'new Placement'};
    	
    	vm.masterModel.data[tempid] = angular.copy(vm.model.data[tempid]);
    	
    	vm.placementStates[tempid] = {'changed':true, 'opened':true};
    	
    };
    
    this.savePlacement = function(placement) {
    	
    	var ajaxurl_post;
    	
    	if(placement.id==0){
    		delete(vm.masterModel.data[0]);
    		delete(vm.model.data[0]);    		
    		ajaxurl_post = ajaxurl+'?action=create_placement&nt='+new Date().getTime();
    	} else{
    		ajaxurl_post = ajaxurl+'?action=save_placement&nt='+new Date().getTime();
    	}
    	
    	
        var promise = $http({
            method : 'POST',
            url : ajaxurl_post,
            data:  { 'placement' :{
            	'blockid': placement.blockid, 
            	'id': placement.id, 
            	'name': placement.name,
            	'type': placement.type,
                'priority' : placement.priority}} 
        });
        
        return HttpResponseService.handle(promise, function(data) {
                if (data) {
  
                	vm.placementStates[data.id] = {'changed':false,'opened':true};
                	
                	vm.model.data[data.id].blockid = data.blockid;//only blockid can be changed now
                    data.priority = parseInt(data.priority);
                    vm.model.data[data.id].priority = data.priority;
                	vm.masterModel.data[data.id] = angular.copy(vm.model.data[data.id]);
               }               
        });
	};
	
	this.deletePlacement = function(block){
		ajaxurl_post = ajaxurl+'?action=delete_placement&nt='+new Date().getTime();
	
	    var promise = $http({
	        method : 'POST',
	        url : ajaxurl_post,
	        data:  { 'placement' :placement.id } 
	    });
    
	    return HttpResponseService.handle(promise, function(data) {});
	};

    this.getPlacementSettings = function(id) {
                
        var data = 'action=get_placement_settings&p=' + id + 'nt='+new Date().getTime();
        
        var promise = $http({
            method : 'POST',
            url : ajaxurl,
            data : data,
            headers : {
                'Content-Type' : 'application/x-www-form-urlencoded'
            }
        });

        return HttpResponseService.handle(promise, function(response) {
            
            vm.placementSettings.data = [];
            var values = [];
            
            angular.forEach(response.placementSettings.options, function(value, key) {
                // value representing stored value
                option_val = response.settings[key];
                                
                if (value.type === 'array'){
                    if (option_val && option_val.value && option_val.value!=-1){
                        values = option_val.value.split(",");
                    }
                }else if (value.type === 'checkbox'){
                    if (option_val){
                        values = option_val.value == 1;
                    }
                }else{
                    values = option_val?option_val.value:'';
                }
                                
                var optionid = option_val.id > 29 ? option_val.id : 0;
                                
                vm.placementSettings.data.push({
                        'name' : key,
                        'name_i10n' : value.txt,
                        'type' : value.type,
                        'value' : value.type==='number'?parseInt(values):values,
                        'src' : value.src,
                        'placeholder' : value.placeholder,
                        'attrs' : value.attrs,
                        'placementid':id,
                        'id': optionid
                });                     
            });
        });
    };

    this.savePlacementSettings = function() {
        
        var ajaxurl_post;
        ajaxurl_post = ajaxurl+'?action=save_placement_settings&nt='+new Date().getTime();
        
        var promise = $http({
            method : 'POST',
            url : ajaxurl_post,
            data:  { 'placementSettings' : vm.placementSettings }
        });
        
        return HttpResponseService.handle(promise, function(data) {
                if (data) {
                    vm.placementSettings.data = data;
                }
        });
    };

    this.deletePlacementSettings = function(id) {

        var data = 'action=delete_placement_settings&p=' + id + 'nt=' + new Date().getTime();

        var promise = $http({
            method : 'POST',
            url : ajaxurl,
            data : data,
            headers : {
                'Content-Type' : 'application/x-www-form-urlencoded'
            }
        });

        return HttpResponseService.handle(promise, function(response) {
        });
    }
   
    return {
    	getPlacements: this.getPlacements,
    	savePlacement: this.savePlacement,
    	createPlacement: this.createPlacement,
    	deletePlacement: this.deletePlacement,
        getPlacementSettings: this.getPlacementSettings,
        savePlacementSettings: this.savePlacementSettings,
        deletePlacementSettings:this.deletePlacementSettings,
    	model: this.model,
    	masterModel: this.masterModel,
    	placementStates: this.placementStates,
        placementSettings: this.placementSettings
    };
}

angular
    .module('app')
    .factory('PlacementService', PlacementService);