<?php 
require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
?>

<div align="left" style="padding:5px 0 0">
	<ul>
		<li id="tab-btn-container" class="btn btn-primary" 
			ng-click="active=0;tabs[tab.targetTab]=true;vm.createObj();">    			    
			<i class="glyphicon glyphicon-plus"></i>
				<?php echo __('Add Ads-Block','wpailang');?>
		</li>
	</ul>
</div>

<uib-accordion close-others="true">
			
        <div uib-accordion-group ng-repeat="block in blocks.data | orderObjectBy:'name':false track by $index" ng-hide="block.name == null" is-open="blockStates.state[block.id].opened">
        	<uib-accordion-heading class="wpai-block-heading">
        		<strong>{{block.name}}</strong>  <span style="float:right;">Short code:[showad block={{block.id}}]</span>
        	</uib-accordion-heading>
			
			<form novalidate  id="block-{{block.id}}" class="col-md-12">
				<div class="col-md-12">
					<div class="col-md-4">
						
						<label><?php echo __('Ads-Block Name','wpailang');?>: 
							<input keepFocus type="text" class="form-control" ng-model="blockStates.state[block.id].name"
								objid="{{block.id}}" selectedobjid="{{selectedBlockId}}" index="{{$index}}"
								ng-blur="block.name = blockStates.state[block.id].name">
						</label>
						
						<br/>
						
						<div class="checkbox">
							<label style="padding:0;"><?php echo __('Alignment','wpailang');?>:<select style="margin-left:5px;" class="" ng-model="block.alignment" ng-options="o as o for o in alignment.options"></select></label>
							<label style="padding:0;"><?php echo __('Margin','wpailang');?>:<input type="number" style="width:50px;" ng-model="block.style"><?php echo __('px','wpailang');?></select></label>
						</div>						
						
						<div class="checkbox">
							<label><input type="checkbox" style="margin-top:1px;" ng-model="block.promotion"><?php echo __('Promote the Ad Block','wpailang');?></label>
						</div>
						
					</div>
					
					<div class="col-md-8" style="min-width:700px;">
						
						<label compilexxx><?php echo __('Default Ads','wpailang') . ', {{blockStates.state[block.id].current_ads}}' . __(' of ','wpailang' ) . '{{block.default_adss.length}}' . ' ( <a href="#" ng-click="openPreview(block, \'default_ads\')">' . __('Preview','wpailang') . '</a> )';?>&nbsp;
						<i ng-show="block.default_adss[blockStates.state[block.id].current_ads-1].indexOf('&lt;script')!=-1 && block.rotate_ads" 
							ng-hide="block.default_adss[blockStates.state[block.id].current_ads-1].indexOf('&lt;script')==-1 || block.rotate_ads == false" class="glyphicon glyphicon-warning-sign"
							uib-tooltip="<?php echo __('Not all ads javascripts support rotation (writing into document from an asynchronously loaded javascript). Test it.','wpailang');?>"></i>
							<textarea id="def_ads" cols="100" rows="5" class="form-control" ng-model="block.default_adss[blockStates.state[block.id].current_ads-1]"/>
						</label>
						
						<div class="wpai-pagination-cont">
							<label><?php echo __('Rotate Ads','wpailang');?> <input type="checkbox" ng-model="block.rotate_ads"></label>&nbsp;&nbsp;
							<label ng-show="block.rotate_ads==1" ng-hide="block.rotate_ads==0"><?php echo __('every','wpailang');?> <input type="number" ng-model="block.rotation_duration"><?php echo __('sec','wpailang');?></label>
							<label ng-show="block.rotate_ads==1" ng-hide="block.rotate_ads==0"><input type="button"  class="btn btn-sm btn-primary" ng-click="addAds(block)" value="<?php echo __('Add Ads','wpailang');?>" /></label>
							<label ng-show="block.rotate_ads==1" ng-hide="block.rotate_ads==0"><input type="button"  class="btn btn-sm btn-danger" ng-click="removeAds(block)" value="<?php echo __('Remove Ads','wpailang');?>" ng-disabled="block.default_adss.length<2" /></label>
							<label ng-show="block.rotate_ads==1" ng-hide="block.rotate_ads==0">
								<ul uib-pagination max-size="4" rotate="true" ng-model="blockStates.state[block.id].current_ads" 
								total-items="block.default_adss.length" items-per-page="1" max-size="10" class="pagination-sm" boundary-links="true" rotate="false" 
								previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" num-pages="numPages"></ul>
							</label>
						</div>
						
					</div>
				</div>
				<div class="col-md-12 wpai-block-promote" ng-show="block.promotion" ng-hide="!block.promotion" >
					<div class="col-md-4">
						<label><?php echo __('Promotion duration (sec)','wpailang');?>: 
						<input type="number" class="form-control" ng-model="block.promo_duration">
						</label>
		
						<br />
						
						<label><?php echo __('Show promotion every (sec)','wpailang');?>: 
						<input uib-popover="<?php echo __('Negative number or 0 stops the promotion','wpailang');?>" popover-trigger="mouseenter" type="number" class="form-control" ng-model="block.promo_every">
						</label>
						
						<div class="checkbox">
							<label><input type="checkbox" ng-model="block.promo_only_not_sold"><?php echo __('Show only if not sold currently','wpailang');?></label>
						</div>
					</div>
					<div class="col-md-8" style="min-width:700px;">
						<label><?php echo __('Currently sold Ads','wpailang') . ' ( <a href="#" ng-click="openPreview(block, \'sold_ads\')">' . __('Preview','wpailang') . '</a> )';?>:
							<textarea uib-popover="<?php echo __('If the Ad is not empty, the \'Default ads\' will be not shown to your visitors. The promotion ad can be still shown.','wpailang');?>" popover-trigger="mouseenter" cols="100" rows="5" class="form-control" ng-model="block.sold_ads"/>
						</label>
						<br/>
						<label><?php echo __('Block\'s self promotion','wpailang');?> ( <a href="#" ng-click="openPreview(block, 'promo')"> <?php echo __('Preview','wpailang');?> </a> ):  
							<textarea cols="100" rows="5" class="form-control" ng-model="block.promo"/>
						</label>
					</div>
				</div>
				<div class="col-md-12" style="text-align:right;">
					<input type="button"  class="btn btn-sm btn-danger" ng-click="delete(block)" value="<?php echo __('Delete Block','wpailang');?>" />
					<input ng-disabled="!blockStates.state[block.id].changed" type="button"  class="btn btn-sm btn-warning" ng-click="reset(block)" value="<?php echo __('Reset Changes','wpailang');?>" />
					<input ng-disabled="!blockStates.state[block.id].changed" type="submit" class="btn btn-sm btn-success" ng-click="update(block)" value="<?php echo __('Save','wpailang');?>" />
				</div>
			</form>
    </div>
</uib-accordion>