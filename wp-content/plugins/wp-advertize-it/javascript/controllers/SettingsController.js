function SettingsController($rootScope, $scope, SettingsService, $sce) {
    var vm = this;
    
    $rootScope.mtabs = [];
    
    $scope.tabs = {'blocks':false, 'placement':false, 'settings':true};
    
    $scope.settings = SettingsService.model;    
    $scope.masterSettings = SettingsService.masterModel;
    $scope.meta = {};
    $scope.meta.tags = SettingsService.tags;
    $scope.meta.cats = SettingsService.cats;
    $scope.meta.authors = SettingsService.authors;
    $scope.meta.languages = SettingsService.languages;
    $scope.meta.post_formats = SettingsService.post_formats;
    $scope.meta.post_types = SettingsService.post_types;
    
    $scope.changed = false;
    $scope.changedFalse = false;
    
    SettingsService.getTags();
    SettingsService.getCats();
    SettingsService.getAuthors();
    SettingsService.getPostFormats();
    SettingsService.getPostTypes();
    SettingsService.getLanguages();
    
    SettingsService.getSettings().then(function(){    	

    		$scope.$watch("settings| json", function(newValue, oldValue){ //['"+value.name+"'] | json
    			if (newValue!=oldValue && !$scope.changedFalse){
    				$scope.changed = true;
    			}
    			if ($scope.changedFalse){
    				$scope.changedFalse = false;
    			}
    		});

    });
    
    $scope.printOption = function(setting){
    	if (setting.type == 'text'){
    		var placeholder = "";
    		if (setting.placeholder!==undefined){
    			placeholder = "placeholder='"+setting.placeholder+"'";
    		}
    		return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7" wpai-input-container><input class="form-control" type="text" '+ placeholder + ' ng-model="setting.value" '+setting.attrs+'/></div>');
    	}else if (setting.type == 'array'){
    		if (setting.name == 'suppress-language' && SettingsService.languages.data.length == 0){
    			return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><p>This option is only available with the plugin <a href="https://de.wordpress.org/plugins/qtranslate-x/">qTranslate X</a> or <a href="https://wordpress.org/plugins/mqtranslate/">mqTranslate</a>.</p></div>');
    		}else{
    			return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><select multiple class="form-control wpai-form-caption" ng-options="item.key as item.value for item in meta.'+setting.src+'.data" ng-model="setting.value" '+setting.attrs+' ></select></div>');//
    		}
    	}else if (setting.type == 'checkbox'){
    		return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><input class="form-control" type="checkbox" ng-model="setting.value" '+setting.attrs+'/></div>');
    	}else if (setting.type == 'number'){
            if (setting.name == 'max-ads-count'){
    	       return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><input class="form-control" type="number" ng-model="setting.value" min="0" uib-tooltip="Set to 0 to disable the limitation"'+setting.attrs+'/></div>');
            }else {
                return $sce.trustAsHtml('<span class="col-md-4 wpai-input-container">'+setting.name_i10n+'</span><div class="col-md-7 wpai-input-container"><input class="form-control" type="number" ng-model="setting.value" min="0"'+setting.attrs+'/></div>');
            }
        }
    }
    
    $scope.reset = function(){
    	$scope.settings.data = angular.copy($scope.masterSettings.data );
    	$scope.changed = false;
    	$scope.changedFalse = true;
    }
    
    $scope.update = function(){
    	$scope.masterSettings.data = angular.copy($scope.settings.data );
    	SettingsService.saveSettings();
    	$scope.changed = false;
    }
}

angular
    .module('app')
    .controller('SettingsController', SettingsController);