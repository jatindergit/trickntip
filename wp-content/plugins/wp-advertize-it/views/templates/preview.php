<?php 
require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
require_once( dirname(__FILE__) . '/../../classes/wpai-db.php' );

if (current_user_can('administrator')){

	$id = $_REQUEST['id'];
	$type = $_REQUEST['type'];
	$ord = $_REQUEST['ord'];
	
	$WPAIDEL = '#wpai-del#';
	?>
	
	<div class="modal-header">
		<h3 class="modal-title"><?php echo __('Preview','wpailang');?></h3>
	</div>
	
	<div id="adscontent" class="modal-body" style="width:900px;height:700px">
		<?php 
			$block = WPAI_DB::wpai_get_block($id);
			if ($block){
				
				$adss = explode($WPAIDEL, $block[$type]);
				
				if (strpos($adss[$ord-1],'<script') !== false){
					$ret = $adss[$ord-1];
					//$ret = '<script type="text/javascript">$(function() {postscribe(\'#adscontent\', \''.$adss[$ord-1].'\');});</script>';
					//$ret = '<script type="text/javascript">postscribe("#adscontent", "<script>document.write(\"It works\");<\/script>");</script>';
				}else{
					$ret = $adss[$ord-1];
				}
				
				echo $ret;
			}
		?>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" type="button" ng-click="closePreview()">OK</button>
	</div>
<?php }?>