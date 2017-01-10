
function SettingsService( $http, HttpResponseService,$rootScope) {

	var vm = this;
	
	this.model = {};
	this.masterModel = {};
		
	this.cats = {};
	this.authors = {};
	this.post_types = {};
	this.post_formats = {};
	this.tags = {};
	this.posts = {};
	this.languages = {};
	
	this.masterdata = [this.cats, this.authors, this.posttypes, this.postformats, this.tags, this.posts, this.langs];
	
    this.getSettings = function() {
    	    	
		var data = 'action=get_settings&p=0&nt='+new Date().getTime();
		
		var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : data,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			}

		});
		
		return HttpResponseService.handle(promise, function(response) { 
			vm.model.data = [];
			vm.masterModel.data = [];
			var values = [];

			angular.forEach(response.masterSettings.options,
				function(value, key) {
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

					var placementid = (option_val && option_val.value!=-1) ? option_val.placementid : 0;
									
					var optionid = option_val ? option_val.id : 0;
									
					vm.model.data.push({
							'name' : key,
							'name_i10n' : value.txt,
							'type' : value.type,
							'value' : value.type==='number'?parseInt(values):values,
							'src' : value.src,
							'placeholder' : value.placeholder,
							'attrs' : value.attrs,
							'placementid':placementid,
							'id': optionid
					});
									
				});

				vm.masterModel.data = angular.copy(vm.model.data);
		});
		
    };
    
    this.getTags = function(){
    	var datatags = 'action=get_tags&nt='+new Date().getTime();
    	
		var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : datatags,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			},
        	cache: true

		});
		
		return HttpResponseService.handle(promise, function(response) {

			vm.tags.data = [];
						
			angular.forEach(response,
					function(value, key) {
									
						vm.tags.data.push({
							'key' : value.term_id,
							'value' : value.name
						});
					});					
			});
    }
    
    this.getCats = function(){
    	var datatags = 'action=get_cats&nt='+new Date().getTime();
    	
    	var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : datatags,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			},
        	cache: true

		});
    	return HttpResponseService.handle(promise,
				function(response) {
					vm.cats.data = [];
						
					angular.forEach(response,
							function(value, key) {
									
								vm.cats.data.push({
										'key' : value.term_id,
										'value' : value.name
								});
							});					
				});
    }
    
    this.getAuthors = function(){
    	var datatags = 'action=get_authors&nt='+new Date().getTime();
    	
		var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : datatags,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			},
        	cache: true

		});
		return HttpResponseService.handle(promise,
				function(response) {
						vm.authors.data = [];
						
						angular.forEach(response,
								function(value, key) {
									
									vm.authors.data.push({
										'key' : value.ID,
										'value' : value.user_nicename
									});
								});
						
				});
    }
    
    this.getPostFormats = function(){
    	var datatags = 'action=get_post_formats&nt='+new Date().getTime();

    	var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : datatags,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			},
        	cache: true

		});
		return HttpResponseService.handle(promise,
				function(response) {

						vm.post_formats.data = [];
						
						angular.forEach(response,
								function(value, key) {
							
									vm.post_formats.data.push({
										'key' : key,
										'value' : value
							
								});
							});
				});
    }
    
    this.getPostTypes = function(){
    	var datatags = 'action=get_post_types&nt='+new Date().getTime();
    	
		var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : datatags,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			},
        	cache: true

		});
		
		return HttpResponseService.handle(promise,
				function(response) {
					vm.post_types.data = [];
						
					angular.forEach(response,
							function(value, key) {
									
									vm.post_types.data.push({
										'key' : key,
										'value' : value
									});
					});
					
				});
    }
    
    this.getLanguages = function(){
    	var datatags = 'action=get_languages&nt='+new Date().getTime();
    	
		var promise = $http({
			method : 'POST',
			url : ajaxurl,
			data : datatags,
			headers : {
				'Content-Type' : 'application/x-www-form-urlencoded'
			},
        	cache: true

		});
		return HttpResponseService.handle(promise,
				function(response) {
					vm.languages.data = [];
					
					//if (response.data instanceof Object) {

						angular.forEach(response,
								function(value, key) {
									
									vm.languages.data.push({
										'key' : key,
										'value' : value
									});
								});
						
					//} else {
						// handle 0 if no plugin installed
					//}
				});
    }
    
    this.saveSettings = function() {
    	
    	var ajaxurl_post;
    	ajaxurl_post = ajaxurl+'?action=save_settings&nt='+new Date().getTime();
    	
        var promise = $http({
            method : 'POST',
            url : ajaxurl_post,
            data:  { 'settings' : vm.model }
        });
        
        return HttpResponseService.handle(promise, function(data) {
                if (data) {
                	vm.model.data = data;
                	vm.masterModel.data = angular.copy(vm.model.data);
                }
        });
	};

    return {
    	getSettings: this.getSettings,
    	getTags: this.getTags,
    	getCats: this.getCats,
    	getAuthors: this.getAuthors,
    	getLanguages: this.getLanguages,
    	getPostFormats: this.getPostFormats,
    	getPostTypes: this.getPostTypes,
    	saveSettings: this.saveSettings,
    	model: this.model,
    	masterModel: this.masterModel,
    	tags: this.tags,
    	cats: this.cats,
    	authors: this.authors,
    	post_formats: this.post_formats,
    	post_types:this.post_types,
    	languages:this.languages
    };
}

angular
    .module('app')
    .factory('SettingsService', SettingsService);