<?php
global $sws_add_nad_db;
$sws_add_nad_db = '1.0';
function sws_add_nad_table() {
	global $wpdb;
	global $sws_add_nad_db;
	$table_name = $wpdb->prefix . 'sws_add_nad';
	
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
	  `row_id` int(11) NOT NULL AUTO_INCREMENT,
	  `id` char(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
	  `full_text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	  `u_tag` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'N',
	  PRIMARY KEY (`row_id`) USING BTREE
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'sws_add_nad_db', $sws_add_nad_db );
}
function sws_add_nad_data() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'sws_add_nad';
	
	
	$file= plugin_dir_path(__FILE__)."nad_entities.csv";
	//echo $file;
	$fp = fopen($file, 'r');
	if ($fp) {
		$csvArray = array();
		
		while ($row = fgetcsv($fp)) {
			$csvArray[] = $row;
		}
		
		fclose($fp);
		
		
		//foreach ($csvArray as $tmp) {	
			$tmp=$csvArray[1]; 
			$wpdb->replace( 
				$table_name, 
				array( 
					'row_id' => 1, 
					'id' => "test", 
					'full_text' => $tmp[2], 
					'u_tag' => "X",
				),
				array('%d','%s','%s','%s')
			);
		//}
	}

}