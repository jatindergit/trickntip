<?php
require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
?>
<div align="right">
	<label for="sortby"><?php _e('Sort By :','wpailang');?></label>
	<select id="sortby" ng-model="placementOrder" style="width: 200px">
		<option value="name_i10n"><?php _e('Name','wpailang');?></option>
		<option value="priority"><?php _e('Priority','wpailang');?></option>
	</select>
</div>
<br/>

<uib-accordion close-others="true">

        <div uib-accordion-group ng-repeat="placement in placements.data | object2Array | orderBy:placementOrder track by $index" ng-hide="placement.name == null" is-open="placementStates.state[placement.id].opened">
        	<uib-accordion-heading>
        		<strong>{{placement.name_i10n}}</strong>  ({{placement.blockid==0?'<?php echo __('not assigned','wpailang');?>':allblocks.data[placement.blockid].name}})<p class="alignright"><strong>{{placement.priority}}</strong></p>
        	</uib-accordion-heading>

			<form novalidate  id="placement-{{placement.id}}">
				<div class="col-md-8 wpai-form-inline-captions">

					<span class="wpai-form-caption"><?php echo __('Placement Name','wpailang');?>: {{placement.name_i10n}}</span>
					<!--input keepFocus type="text" class="form-control" ng-model="placement.name" objid="{{placement.id}}" selectedobjid="{{selectedPlacementId}}" index="{{$index}}"-->

					<br />
					<span class="wpai-form-caption"><?php echo __('Ad Block','wpailang');?>:</span>
					<div class="btn-group" uib-dropdown is-open="status.isopen">
				      <button id="single-button" type="button" class="btn btn-default" uib-dropdown-toggle ng-disabled="disabled">
				        {{placement.blockid==0?'<?php echo __('Select Ad Block...','wpailang');?>':allblocks.data[placement.blockid].name}}<span class="caret"></span>
				      </button>
				      <ul class="uib-dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="single-button">
				      	<li>
				          <a href="#" ng-click="selectBlock(placement, null)"><?php echo __('empty selection','wpailang');?></a>
				        </li>
				        <!-- li ng-repeat="block in allblocks.data | orderBy:'name':false track by $index" ng-hide="block.name==null" -->
				        <li ng-repeat="block in allblocksArr| orderBy:'name':false track by $index"  ng-hide="block.name==null">
				          <a href="#" ng-click="selectBlock(placement, block)">{{block.name}}</a>
				        </li>
				      </ul>
				    </div>

				    &nbsp&nbsp&nbsp<span class="wpai-form-caption"><?php echo __('Priority','wpailang');?>:</span>
				    <input type="number" min="1" class="form-control" ng-model="placement.priority"></input>

				    <br/>
				    <br/>
					<button class="btn btn-sm" ng-click="openPlacementSettings(placement.id, placement.name_i10n)"><?php echo __('Settings', 'wpailang');?></button>
    				<br/>
    				<br/>

					<!-- input type="button"  class="btn btn-sm btn-danger" ng-click="delete(placement)" value="<?php echo __('Delete placement','wpailang');?>" / -->
					<input ng-disabled="!placementStates.state[placement.id].changed" type="button"  class="btn btn-sm btn-warning" ng-click="reset(placement)" value="<?php echo __('Reset Changes','wpailang');?>" />
					<input ng-disabled="!placementStates.state[placement.id].changed" type="submit" class="btn btn-sm btn-success" ng-click="update(placement)" value="<?php echo __('Save','wpailang');?>" />
				</div>

			</form>
    </div>
</uib-accordion>
