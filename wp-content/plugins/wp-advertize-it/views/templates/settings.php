
<?php 
	require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
?>
<div class="col-md-8" id="wpai-settings">
	<ul class="wpai-form-inline-captions">
		<li compile ng-repeat="setting in settings.data track by $index" ng-bind-html="printOption(setting)">
			
		</li>
		
		<br/>
	</ul>
	<input ng-disabled="!changed" type="button"  class="btn btn-sm btn-warning" ng-click="reset()" value="<?php echo __('Reset Changes','wpailang');?>" />
	<input ng-disabled="!changed" type="submit" class="btn btn-sm btn-success" ng-click="update()" value="<?php echo __('Save','wpailang');?>" />
</div>