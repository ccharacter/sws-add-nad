<?php

/**
 * Plugin Name:       SWS Add NAD
 * Plugin URI:        https://ccharacter.com/custom-plugins/sws-add-nad/
 * Description:       Adds options for NAD entities to Gravity Forms and Advanced Custom Fields
 * Version:           1.01
 * Requires at least: 5.2
 * Requires PHP:      5.2
 * Author:            Sharon Stromberg
 * Author URI:        https://ccharacter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sws-add-nad
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__).'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/ccharacter/sws-add-nad/master/plugin.json',
	__FILE__,
	'sws-add-nad'
);

require_once plugin_dir_path(__FILE__)."data/func_table.php";

register_activation_hook( __FILE__, 'sws_add_nad_table' );
register_activation_hook( __FILE__, 'sws_add_nad_data' );




class AddNAD
{
	private $typeArr=array(
		"nad-conf"=>"NAD Conf",
		"nad-conf-opt"=>"NAD Conf/Code",
		"nad-conf-oth"=>"NAD Conf+Oth",
		"nad-conf-oth-opt"=>"NAD Conf/Code+Oth",
		"nad-union"=>"NAD Union",
		"nad-union-opt"=>"NAD Union/Code",
		"nad-union-oth"=>"NAD Union+Oth",
		"nad-union-oth-opt"=>"NAD Union/Code+Oth",
		"nad-all"=>"NAD Unions & Confs",
		"nad-all-opt"=>"NAD Unions & Confs/Code",
		"nad-all-oth"=>"NAD Unions & Confs+Oth",
		"nad-all-oth-opt"=>"NAD Unions & Confs/Code +Oth");
	
	public function addGFCustom($class,$title) {
		$old=get_option('gform_custom_choices');
		$data = maybe_unserialize($old); 
		if (!(array_key_exists($title,$data))) {
			$data[$title]=getGFOpts(explode("-",$class));
		
			//error_log(print_r($data,true),0);
		
			//update_option('gform_custom_choices',$data);
		}
	}
	
	public function getGFOpts($arr) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sws_add_nad';
		
		switch($arr[1]) {
				case("conf"): $cond="where `u_tag`='N'"; break;
				case("union"): $cond="where `u_tag`='Y'"; break;
				default: $cond=""; break;
		}
		if (($arr[2]=="opt") || ($arr[3]=="opt")) { $col="concat(`full_text`,'|',`id`)"; } 
			else { $col="`full_text`";}
			
		$sql="select $col from $table_name $cond order by `full_text`";
		$results = $wpdb->get_results($sql);
		
		error_log(print_r($results,true),0);
		
		return $results;
		
	}
	
	public function addGF() {
		foreach ($typeArr as $class=>$title) { 
			addGFCustom($class,$title);
		}
	}

	public function addACF() {

	}
	/*public function showTag($content) {
		if ( (is_page('home')) || (is_page('about'))) {
			return $content.'<span style="opacity:0.02">'.gethostname().'</span>';
		} else { 
			return $content;
		}
	}
	
    public function register($atts, $content = null)
    {
        return '<span style="opacity:0.02">'.gethostname().'</span>';
    }*/
    
	public function init()
    {
         register_activation_hook( __FILE__,  array($this,'addGF') );
    }
}


$myVal=new AddNAD();
$myVal->init();

?>