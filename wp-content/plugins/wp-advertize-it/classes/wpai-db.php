<?php 

if (!class_exists('WPAI_DB')) {
	
	class WPAI_DB
	{
		const REQUIRED_CAPABILITY = 'administrator';

// PLACEMENTS SECTION
		static function wpai_get_placements()  {
			global $wpdb;
				
			$table_name = $wpdb->prefix . "wpai_placements";
			//name columns expl to enable OBJECT_K on 'name'
			$placements = $wpdb->get_results("SELECT name,id,blockid,type,priority FROM $table_name order by name", OBJECT_K);
		
			//$defaultplacements = WPAI_Settings::get_default_settings()['placements'];
		
			return $placements;
		}
		
		static function wpai_get_placements_callback()  {
		    $defaultSettings = WPAI_Settings::get_default_settings();
		    $defaultplacements = $defaultSettings['placements'];
		    $dbplacements = WPAI_DB::wpai_get_placements();
		    
		    if (count($dbplacements)>0 && count($dbplacements)==count($defaultplacements)){
		    	
		    	$response = array(
		    			"STATUS"=>"OK", 
		    			"OBJ"=>array('dbplacements'=>$dbplacements, 'defaultplacements'=>$defaultplacements),
		    			"MSG"=>__("Placement read successfully!",'wpailang'),
		    			"MSG_HEADER"=>__("SUCCESS","wpailang"),);
		    }else{
		    	$response = array("STATUS"=>"ERROR", "MSG_HEADER"=>__("ERROR","wpailang"), "MSG"=>__("Error is occured by reading placements!",'wpailang'));
		    }
		    echo json_encode($response);
		    
			//echo json_encode(array('dbplacements'=>WPAI_DB::wpai_get_placements(), 'defaultplacements'=>$defaultplacements));
			die();
			exit;
		}
		
		static function wpai_get_placement($id)  {
			 
			global $wpdb;
							
			$table_name = $wpdb->prefix . "wpai_placements";
			$placement = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$id), ARRAY_A);
		
			return $placement; 
		}
		
		static function wpai_save_placement_callback()  {
			 
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
			
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
			
			$placement = $obj['placement'];
			
			$table_name = $wpdb->prefix . "wpai_placements";
			$prepared = self::prepareUpdateObject($placement, array('id'));
			
			$rows_affected = $wpdb->update( $table_name, $prepared, array('id' => $placement['id']));
			
			$placement = self::wpai_get_placement($placement['id']);
			if (($rows_affected==1 || $rows_affected==0) && $placement){
				$response = array("STATUS"=>"OK", "OBJ"=>$placement,"MSG"=>__("Placement saved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'), "MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
		}
		
		static function wpai_delete_placement_callback()  {
		
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
			
			/*
			 SWITCHED OF FOR THE CURRENT VERSION 
			 
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
		
			$placementid = $obj['placement'];
				
			$table_name = $wpdb->prefix . "wpai_placements";
				
			$rows_affected = $wpdb->delete( $table_name, array('id' => $placementid));
			
			if ($rows_affected==1 || $rows_affected==0){
				$response = array("STATUS"=>"OK", "OBJ"=>'removed',"MSG"=>__("Placement removed successfully!",'wpailang'));
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'));
			}
			echo json_encode($response);*/
			die();
			exit;
		}
		
		static function wpai_create_placement_callback()  {
		
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
				
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
		
				
			$placement = $obj['placement'];
				
			$table_name = $wpdb->prefix . "wpai_placements";
			$prepared = self::prepareUpdateObject($placement, array('id'));
				
			$rows_affected = $wpdb->insert($table_name , $prepared );
			
			$id = $wpdb->insert_id;
				
			$placement = self::wpai_get_placement($id);
			if ($rows_affected==1  && $placement){
				$response = array("STATUS"=>"OK", "OBJ"=>$placement,"MSG"=>__("Placement saved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
		}

// BLOCKS SECTION

		static function wpai_get_blocks()  {
			
			global $wpdb;
					
			$table_name = $wpdb->prefix . "wpai_blocks";
			$blocks = $wpdb->get_results("SELECT * FROM $table_name order by name", OBJECT_K);
		
			return $blocks;

		}
		
		static function wpai_get_blocks_callback()  {
		
			$blocks = WPAI_DB::wpai_get_blocks();
			
			if ((is_bool($blocks) === true) ){
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
			}else{
				$response = array("STATUS"=>"OK", "OBJ"=>$blocks,"MSG"=>__("Blocks retrieved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}
			
			echo json_encode($response);
			
			die();
			exit;
		}
		
		static function wpai_get_block($id)  {
		
			global $wpdb;
					
			$table_name = $wpdb->prefix . "wpai_blocks";
			$block = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$id), ARRAY_A);
		
			return $block;
		}
		
		static function wpai_save_block_callback()  {
		
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
				
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
		
				
			$block = $obj['block'];
				
			$table_name = $wpdb->prefix . "wpai_blocks";
			$prepared = self::prepareUpdateObject($block, array('id','default_adss','current_ads'));
				
			$rows_affected = $wpdb->update( $table_name, $prepared, array('id' => $block['id']));
			
			if ($rows_affected instanceof Boolean){
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			else{	
				$block = self::wpai_get_block($block['id']);
			
				if (($rows_affected==1 || $rows_affected==0) && $block){
					$response = array("STATUS"=>"OK", "OBJ"=>$block,"MSG"=>__("Block saved successfully!",'wpailang'), "MSG_HEADER"=>__("SUCCESS","wpailang"),);
				}else{
					$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
				}
			}
			echo json_encode($response);
			die();
			exit;
		}
		
		static function wpai_delete_block_callback()  {
		
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
		
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
		
			$blockid = $obj['block'];
		
			$table_name = $wpdb->prefix . "wpai_blocks";
		
			$rows_affected = $wpdb->delete( $table_name, array('id' => $blockid));
				
			if ($rows_affected==1 || $rows_affected==0){
				$response = array("STATUS"=>"OK", "OBJ"=>'removed',"MSG"=>__("Block removed successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'), "MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
		}
		
		static function wpai_create_block_callback()  {
		
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
		
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
		
		
			$block = $obj['block'];
		
			$table_name = $wpdb->prefix . "wpai_blocks";
			$prepared = self::prepareUpdateObject($block, array('id','default_adss'));
		
			$rows_affected = $wpdb->insert($table_name , $prepared );
				
			$id = $wpdb->insert_id;
		
			$block = self::wpai_get_block($id);
			if ($rows_affected==1  && $block){
				$response = array("STATUS"=>"OK", "OBJ"=>$block,"MSG"=>__("Block saved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
		}
		
// SETTINGS SECTION
		static function wpai_get_settings($placementid = 0)  {
		
			global $wpdb;
						
			$table_name = $wpdb->prefix . "wpai_settings";
		    $settings = $wpdb->get_results($wpdb->prepare("SELECT name,id,value,placementid FROM $table_name WHERE placementid= %d order by name", $placementid),OBJECT_K);
		      
		    if (!function_exists('qtrans_getSortedLanguages') && !function_exists('qtranxf_getSortedLanguages')){
		    	//$settings['suppress-language'] = array('name' => 'suppress-language','value' => '-1');
		    }
		    
			return $settings;
		}
		
		static function wpai_get_placement_settings($placementid)  {
		
			global $wpdb;
			$table_name = $wpdb->prefix . "wpai_settings";

		    $settings = $wpdb->get_results($wpdb->prepare("SELECT name,id,value,placementid FROM $table_name WHERE placementid= %d order by name", $placementid),OBJECT_K);

		    if($settings == false) {
		    	$placementid = 0;
		    	$settings = $wpdb->get_results($wpdb->prepare("SELECT name,id,value,placementid FROM $table_name WHERE placementid= %d order by name", $placementid),OBJECT_K);
		    }
		    
			return $settings;
		}

		static function wpai_get_settings_short($placementid = 0)  {
		
			global $wpdb;
							
			$table_name = $wpdb->prefix . "wpai_settings";
			$settings = $wpdb->get_results($wpdb->prepare("SELECT name,value FROM $table_name WHERE placementid= %d order by name", $placementid),OBJECT_K);
		
			if (!function_exists('qtrans_getSortedLanguages') && !function_exists('qtranxf_getSortedLanguages')){
				//$settings['suppress-language'] = array('name' => 'suppress-language','value' => '-1');
			}
		
			return $settings;
		}
		
		static function wpai_save_settings_callback($placementid = 0)  {
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
			
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
			
			$placementid = $obj['placementid'];
			$newsettings = $obj['settings']['data'];
			
			$table_name = $wpdb->prefix . "wpai_settings";
			
			$num_errors = 0;
			
			foreach($newsettings as $key=>$value){
				
				$updated = 0;
				
				if(is_array($value['value'])){
					$value['value'] = implode(",", $value['value']);
				}
				
				if ($value['id'] != "" && $value['id'] != 0){
					$updated = $wpdb->update($table_name, array('name'=>$value['name'],'value'=>$value['value'],'placementid'=>$value['placementid']), array('id'=>$value['id']), array( '%s','%s','%d' ), array( '%d' ));
				}else{
					$inserted = $wpdb->insert($table_name, array('name'=>$value['name'],'value'=>$value['value'],'placementid'=>$value['placementid']), array( '%s','%s', '%d' ), array( '%d' ));
				}
				
				if ($inserted != 1 && (is_bool($updated) && $updated==false)){
					$num_errors++;
				}
			}
			
			if ($num_errors==0){
				$response = array("STATUS"=>"OK", "OBJ"=>$block,"MSG"=>__("Settings saved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
			
		}
		
		static function wpai_save_placement_settings_callback()  {
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
			
			if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
				$obj = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
			}
			
			$newsettings = $obj['placementSettings']['data'];
			
			$table_name = $wpdb->prefix . "wpai_settings";
			
			$num_errors = 0;
			
			foreach($newsettings as $key=>$value){
				
				$updated = 0;
				
				if(is_array($value['value'])){
					$value['value'] = implode(",", $value['value']);
				}
				if($value['id'] != "" && $value['id'] !=0){
					$updated = $wpdb->update($table_name, array('name'=>$value['name'],'value'=>$value['value'],'placementid'=>$value['placementid']), array('id'=>$value['id']), array( '%s','%s','%d' ), array( '%d' ));
				}else{
					$inserted = $wpdb->insert($table_name, array('name'=>$value['name'],'value'=>$value['value'],'placementid'=>$value['placementid']), array( '%s','%s', '%d' ), array( '%d' ));
				}

				if($inserted != 1 && (is_bool($updated) && $updated==false)) {
					$num_errors++;
				}
			}
			
			if ($num_errors==0){
				$response = array("STATUS"=>"OK", "OBJ"=>$block,"MSG"=>__("Settings saved successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'),"MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
			
		}

		static function wpai_delete_placement_settings_callback()  {
			
			$placementid = $_REQUEST['p'];
		
			global $wpdb;
			
			if (!current_user_can(self::REQUIRED_CAPABILITY)) {
				wp_die('Access denied!');
			}
		
			$table_name = $wpdb->prefix . "wpai_settings";
		
			$rows_affected = $wpdb->delete($table_name, array('placementid' => $placementid));
				
			if ($rows_affected >= 1 || $rows_affected == 0){
				$response = array("STATUS"=>"OK", "OBJ"=>'removed',"MSG"=>__("Placement settings removed successfully!",'wpailang'),"MSG_HEADER"=>__("SUCCESS","wpailang"),);
			}else{
				$response = array("STATUS"=>"ERROR", "MSG"=>__("Error is occured!",'wpailang'), "MSG_HEADER"=>__("ERROR","wpailang"),);
			}
			echo json_encode($response);
			die();
			exit;
		}
		
//COMMON SECTION
		// add all available fields without excl keys in the list
		protected static function prepareUpdateObject($obj, $excl = array()){
			$prepared = array();
			foreach($obj as $key=>$value){
				if (!in_array($key, $excl)){
					$prepared[$key] = $value;
				}
			}
			
			return $prepared;
		}
	}
    		
}
?>