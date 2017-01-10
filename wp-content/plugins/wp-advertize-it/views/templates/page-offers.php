<div class="wrap" ng-app="app">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2><?php esc_html_e(WPAI_NAME); ?></h2>
    
     <div ng-controller="AppController as vm" ng-cloak>
     	<uib-tabset active="active">
     	
     		<uib-tab select="goState('blocks')" 
     			uib-tooltip="<?php echo __('Define here different ad blocks by pasting adsense code. These blocks can then be placed at different locations on your site.','wpailang');?>"
     			tooltip-placement="right"> 
      			<uib-tab-heading>
        			<i class="glyphicon glyphicon-euro"></i> 
        			<?php echo __('Blocks','wpailang');?>
      			</uib-tab-heading>
 			     <div class="content" ui-view="blocks"></div>
    		</uib-tab>
    		
    		<uib-tab select="goState('placements')" 
    			tooltip-placement="right"
    			uib-tooltip="<?php echo __('Select for each location which ad block you would like to see displayed','wpailang');?>">
    			<uib-tab-heading>
        			<i class="glyphicon glyphicon-th-large"></i><?php echo __('Placements','wpailang');?>
      			</uib-tab-heading>
    			 <div class="content" ui-view="placements"></div>
    		</uib-tab>
    		
    		<uib-tab select="goState('settings')"     			
    			tooltip-placement="right"
    			uib-tooltip="<?php echo __('Set options influencing how the ads are displayed.','wpailang');?>">
      			<uib-tab-heading>
        			<i class="glyphicon glyphicon-cog"></i>
        			<?php echo __('Settings','wpailang');?>
      			</uib-tab-heading>
				<div class="content" ui-view="settings"></div>
    		</uib-tab>
    		
    		<uib-tab select="goState('info')" 
    			tooltip-placement="right"
    			uib-tooltip="<?php echo __('You should know this','wpailang');?>">
      			<uib-tab-heading>
        			<i class="glyphicon glyphicon-eye-open"></i>
        			<?php echo __('Hints','wpailang');?>
      			</uib-tab-heading>
 			     <div class="content" ui-view="info"></div>
    		</uib-tab>
    		
    		<li id="tab-progress-container" class="btn" >
	    		<img src="<?php echo WPAI_PLUGIN_URL . '/images/loading.gif';?>" ng-hide="progress &lt; 1" ng-show="progress &gt; 0"/>
    		</li>
  		</uib-tabset>
     </div>
	 <div id="wpai-admin-footer"><?php echo __('Do you like our <strong>WP Advertize It</strong> plugin? If yes - give us your ','wpailang');?><a href="https://wordpress.org/support/view/plugin-reviews/wp-advertize-it?filter=5#postform" target="_blank" class="wpai-rating-link">&#9733;&#9733;&#9733;&#9733;&#9733;</a> <?php echo __('rating! Thank you!','wpailang');?> </div>
</div>
