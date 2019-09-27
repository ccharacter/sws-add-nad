<?php

/**
 * Plugin Name:       SWS Add NAD
 * Plugin URI:        https://ccharacter.com/custom-plugins/sws-add-nad/
 * Description:       Adds options for NAD entities to Gravity Forms and Advanced Custom Fields
 * Version:           1.32
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
		if (array_key_exists($title,$data)) { // if it's there, wipe it out
			unset($data[$title]);
		}	

		if (!(array_key_exists($title,$data))) { // add it
			$parts=explode("-",$class);
			$data[$title]=$this->getGFOpts($parts);
		
			//error_log(print_r($data,true),0);
			//error_log($class." | ".$title,0);
		
			update_option('gform_custom_choices',$data);
		}
	}
	
	public function getGFOpts($arr) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sws_add_nad';
	
		$one=$arr[1]; $othTxt="OTHER--NOT ON THIS LIST";
		if (isset($arr[2])) { $two=$arr[2]; } else { $two="";}
		if (isset($arr[3])) { $three=$arr[3]; } else {$three=""; }	

		switch($one) {
				case("conf"): $cond="where `u_tag`='N'"; break;
				case("union"): $cond="where `u_tag`='Y'"; break;
				default: $cond=""; break;
		}
		if (($two=="opt") || ($three=="opt")) { $col="concat(`full_text`,'|',`id`) as full_text "; $othTxt.="|X"; } 
			else { $col="`full_text`";}
			
		$sql="select $col from $table_name $cond order by `full_text`";
		$results = $wpdb->get_results($sql,ARRAY_A);
		
		//error_log(print_r($results,true),0);
		$optArr=array(); 
		foreach ($results as $row) { $optArr[]=$row['full_text']; }	

		if (($two=="oth") || ($three=="oth")) { $optArr[]=$othTxt; }	
	
		return $optArr;
	}
	
	public function addGF() {
		//$k=0;
		foreach ($this->typeArr as $class=>$title) { 
			//if ($k<2) { // process first 2 only
				$this->addGFCustom($class,$title);
			//	$k++;
			//}
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

// FIX BUG IN iThemes Security path
function it_icon_font_admin_enueue_scripts() {
        $url=plugins_url();
        wp_enqueue_style( 'ithemes-icon-font', $url."/better-wp-security/lib/icon-fonts/icon-fonts.css" );

}

add_action( 'admin_enqueue_scripts', 'it_icon_font_admin_enueue_scripts' );



?>
