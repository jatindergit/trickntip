<?php 
require_once( dirname(__FILE__) . '/../../../../../wp-load.php' );
require_once( dirname(__FILE__) . '/../../classes/wpai-db.php' );

$header = $_REQUEST['h'];
$text = $_REQUEST['t'];
?>

<div class="modal-header">
	<h3 class="modal-title"><?php echo $header;?></h3>
</div>

<div id="user-msg" class="modal-body">
	<p><?php echo $text;?></p>
</div>
<div class="modal-footer">
	<button class="btn btn-primary" type="button" ng-click="$close(0)">OK</button>
</div>