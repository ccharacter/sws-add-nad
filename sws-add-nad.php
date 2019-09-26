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


function addGF() {
	
}

function addACF() {
	
}


/*class AddNAD
{
	public function showTag($content) {
		if ( (is_page('home')) || (is_page('about'))) {
			return $content.'<span style="opacity:0.02">'.gethostname().'</span>';
		} else { 
			return $content;
		}
	}
	
    public function register($atts, $content = null)
    {
        return '<span style="opacity:0.02">'.gethostname().'</span>';
    }
    
	public function init()
    {
        add_shortcode('sws_server_tag', array($this, 'register'));
		add_action('the_content',array($this,'showTag'));
    }
}


$myVal=new AddNAD();
$myVal->init();*/

?>