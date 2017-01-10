<?php 
	require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
?>
<div id="wpai-placement-settings">
	<div class="modal-header">
		<h3 class="modal-title">{{placementName}} - <?php echo __('Settings','wpailang');?></h3>
	</div>
	<div class="modal-body" style="min-height: 400px">
		<ul>
			<li compile ng-repeat="setting in placementSettings.data track by $index" ng-bind-html="printOption(setting)" >
		
			</li>
		</ul>
	</div>
	<div class="modal-footer">
		<input ng-disabled="!buttonStates.save" type="button" class="btn btn-success" ng-click="updatePlacement()" value="<?php echo __('Save and Close','wpailang');?>" />
		<input ng-disabled="!buttonStates.reset" type="button" class="btn btn-warning" ng-click="resetPlacementSettings(placementId)" value="<?php echo __('Reset to default','wpailang');?>" />
		<input type="button" class="btn btn-primary" ng-click="closePlacementSettings()" value="<?php echo __('Close','wpailang');?>" />
	</div>
</div>