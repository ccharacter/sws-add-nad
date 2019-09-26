<?php
global $sws_add_nad_db;
$sws_add_nad_db = '1.0';
function sws_add_nad_table() {
	global $wpdb;
	global $sws_add_nad_db;
	$table_name = $wpdb->prefix . 'sws_add_nad';
	
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
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
	
	$welcome_name = 'test_entry';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'matching_opts';
	
	/*$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'meta_key' => $welcome_name, 
			'meta_value' => $welcome_text, 
		) 
	);*/
}