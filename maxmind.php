<?php
/*
Plugin Name: Maxmind.eu Hotel Reservations
Plugin URI: http://www.vanmeerdervoort.nl/wordpress-maxmind-plugin.html
Description: Includes the Maxmind.eu Hotel Booking system in your wordpress as a widget. You need a Maxmind.eu account!
Version: 1.0.6
Author: Vincent Pompe van Meerdervoort
Author URI: http://www.vanmeerdervoort.nl

Copyright 2013 vanMeerdervoort  (email : vincent@vanmeerdervoort.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain('vm_maxmind_lang', false, basename( dirname( __FILE__ ) ) . '/languages' );

/////* Create the Settings page *//////////////////////////////

	
	// create custom plugin settings menu
	add_action('admin_menu', 'create_menu');

	function create_menu() {

		//create menu
		//add_menu_page('Maxmind', 'Maxmind Settings', 'administrator', __FILE__, 'vm_maxmind_settings_page',plugins_url('/images/icon.png', __FILE__));
		add_options_page( 'Maxmind Settings', 'Maxmind Settings', 'manage_options', 'vm_maxmind', 'vm_maxmind_settings_page' );

		//call register settings function
		add_action( 'admin_init', 'register_vm_maxmind_settings' );
	}


	function register_vm_maxmind_settings() {
		//register our settings
		register_setting( 'vm_maxmind_settings', 'vm_templatename' );
		register_setting( 'vm_maxmind_settings', 'vm_hotelid' );
		register_setting( 'vm_maxmind_settings', 'vm_couponcode' );
		register_setting( 'vm_maxmind_settings', 'vm_corporate' );
		register_setting( 'vm_maxmind_settings', 'vm_select_hotel' );
		register_setting( 'vm_maxmind_settings', 'vm_layout' );
		register_setting( 'vm_maxmind_settings', 'vm_maxmind_url' );
		register_setting( 'vm_maxmind_settings', 'vm_maxmind_button_class' );
	}

	function vm_maxmind_settings_page() {
	?>
	<div class="wrap">
	<h2>Maxmind.eu Settings</h2>

	<form method="post" action="options.php">
	    <?php 
			settings_fields( 'vm_maxmind_settings' ); 
			//do_settings( 'vm_maxmind_settings' );
		?>
	    <table class="form-table">

<tr valign="top">
	        <th scope="row"></th>
	        <td></td>
	        </tr>
	        <tr valign="top">
	        <th scope="row">Template name</th>
	        <td><input type="text" name="vm_templatename" value="<?php echo get_option('vm_templatename'); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        <th scope="row">Hotel ID</th>
	        <td><input type="text" name="vm_hotelid" value="<?php echo get_option('vm_hotelid'); ?>" /></td>
	        </tr>
			<tr valign="top">
	        <th scope="row">Coupon Code</th>
	        <td><input type="checkbox" name="vm_couponcode" value="1" <?php checked( 1 == get_option('vm_couponcode')); ?>" /> Display coupon code field</td>
	        </tr>
			<tr valign="top">
	        <th scope="row">Corporate</th>
	        <td><input type="checkbox" name="vm_corporate" value="1" <?php checked( 1 == get_option('vm_corporate')); ?>" /> Display Corporate pricing field</td>
	        </tr>
			<tr valign="top">
	        <th scope="row">Select Hotel</th>
	       <td><input type="checkbox" name="vm_select_hotel" value="1" <?php checked( 1 == get_option('vm_select_hotel')); ?>" /> Allow Hotel selection</td>
	        </tr>
			<tr valign="top">
	        <th scope="row">Layout</th>
	        <td><input type="text" name="vm_layout" value="<?php echo get_option('vm_layout'); ?>" /> (0,1,2)</td>
	        </tr>
			<tr valign="top">
	        <th scope="row">Maxmind URL</th>
	        <td><input type="text" name="vm_maxmind_url" value="<?php echo get_option('vm_maxmind_url'); ?>" /> (the url of your personalized page on maxengine)</td>
	        </tr>
				<tr valign="top">
	        <th scope="row">Maxmind Button class</th>
	        <td><input type="text" name="vm_maxmind_button_class" value="<?php echo get_option('vm_maxmind_button_class'); ?>" /> (a class for your button)</td>
	        </tr>
	
	    </table>

	    <?php submit_button(); ?>

	</form>
    <table class="form-table">
    <tr>
    <td>
    <h3>How To</h3>
     Fill out the form above, save and add the Maxmind widget to your sidebar (Appearance > Widgets). All data needed should be provided to you by Maxmind.eu<br />You can use a shortcode in your posts and pages to create a link to your Maxmind.eu reservation page on the Maxmind servers: [vm-maxmind-button text="Text on your button"]. The button will have the class added in the 'Maxmind Button class' field.<br />
     <h3>Support</h3>
     Please note I have nothing to do with the Maxmind.eu company and <strong>can not</strong> provide support for your account, registration or whatever else concering Maxmind.eu. <a href="http://www.maxmind.eu" target="_blank">You should contact them directly</a>.<br />
     I do provide support for this plugin, although my time is limited so I will not be able to respond very fast. For support concerning this plugin, contact me through my website: <a href="http://www.vanmeerdervoort.nl/contact.html">vanMeerdervoort.nl</a>.
    </td>
    </tr></table>
	</div>
	<?php 
	}

/////////////////////////////////END SETTINGS PAGE






/////* Add the actions *//////////////////////////////


add_action('admin_menu', 'vm_maxmind_settings_box');



/////* Import the scripts *//////////////////////////////

if(!is_admin()) {
// the template
wp_enqueue_style("maxmindCSS","https://secure.maxengine.eu/modules/frontend/booking/stylesheet.php?template=".get_option('vm_templatename'),false,"");

wp_enqueue_script("jquery");

wp_enqueue_script("maxmind", "https://secure.maxengine.eu/js/jquery-ui-1.8.16.custom.min.js",array("jquery"), "",1);
}


// END IMPORT SCRIPT FILES /////////////////////////////

// CREATE WIDGET //

add_action('init', 'widget_maxmind_register');
function widget_maxmind_register() {
	
	$prefix = 'maxmind'; // $id prefix
	$name = __('Maxmind');
	$widget_ops = array('classname' => 'widget_maxmind', 'description' => __('This is an example of widget,which you can add many times'));
	$control_ops = array('width' => 200, 'height' => 200, 'id_base' => $prefix);
	
	$options = get_option('widget_maxmind');
	if(isset($options[0])) unset($options[0]);
	
	if(!empty($options)){
		foreach(array_keys($options) as $widget_number){
			wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'widget_maxmind', $widget_ops, array( 'number' => $widget_number ));
			wp_register_widget_control($prefix.'-'.$widget_number, $name, 'widget_maxmind_control', $control_ops, array( 'number' => $widget_number ));
		}
	} else{
		$options = array();
		$widget_number = 1;
		wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'widget_maxmind', $widget_ops, array( 'number' => $widget_number ));
		wp_register_widget_control($prefix.'-'.$widget_number, $name, 'widget_maxmind_control', $control_ops, array( 'number' => $widget_number ));
	}
}

function widget_maxmind($args, $vars = array()) {
    extract($args);
    $widget_number = (int)str_replace('maxmind-', '', @$widget_id);
    $options = get_option('widget_maxmind');
	  //$options = get_option("vm_maxmind_widget_options");

    if(!empty($options[$widget_number])){
    	$vars = $options[$widget_number];
    }
	
    // widget open tags
		echo $before_widget;
		
		// print title from admin 
		if(!empty($vars['title'])){
			echo $before_title . $vars['title'] . $after_title;
			
		} 
		global $mx_language;
		
		$mx_language = $vars['language'];
		
	

		function load_scripts_maxmind()  {
			global $mx_language;
			
			echo "<script src='https://secure.maxengine.eu/modules/frontend/booking/javascript.php?template=".get_option('vm_templatename')."&hl=".$mx_language."&hotel_id=".get_option('vm_hotelid')."' type='text/javascript'></script>";
			
			if(get_option('vm_corporate') == 1){$corp = "true"; }else{ $corp="false";}
			if(get_option('vm_couponcode') == 1){$coup = "true"; }else{ $coup="false";}
			if(get_option('vm_select_hotel') == 1){$sel = "true"; }else{ $sel="false";}
			
			$layout=get_option('vm_layout');
			
			echo"
			<script type='text/javascript'>
			  jQuery(document).ready(function($) {
				
			$('#vm_maxmind_booking').booking({layout:".$layout.",corporate_rate:".$corp.",coupon_code:".$coup.",hotel_select:".$sel." });	
			  });
			</script>";
			
			}
			
			add_action('wp_footer', 'load_scripts_maxmind', 20);
		
		// print content and widget end tags
    echo '  <div id="vm_maxmind_booking"></div>';
    echo $after_widget;
}


function widget_maxmind_control($args) {

	$prefix = 'maxmind'; // $id prefix
	
	$options = get_option('widget_maxmind');
	if(empty($options)) $options = array();
	if(isset($options[0])) unset($options[0]);
		
	// update options array
	if(!empty($_POST[$prefix]) && is_array($_POST)){
		foreach($_POST[$prefix] as $widget_number => $values){
			if(empty($values) && isset($options[$widget_number])) // user clicked cancel
				continue;
			
			if(!isset($options[$widget_number]) && $args['number'] == -1){
				$args['number'] = $widget_number;
				$options['last_number'] = $widget_number;
			}
			$options[$widget_number] = $values;
		}
		
		// update number
		if($args['number'] == -1 && !empty($options['last_number'])){
			$args['number'] = $options['last_number'];
		}

		// clear unused options and update options in DB. return actual options array
		$options = vm_smart_multiwidget_update($prefix, $options, $_POST[$prefix], $_POST['sidebar'], 'widget_maxmind');
	}
	
	// $number - is dynamic number for multi widget, gived by WP
	// by default $number = -1 (if no widgets activated). In this case we should use %i% for inputs
	//   to allow WP generate number automatically
	$number = ($args['number'] == -1)? '%i%' : $args['number'];

	// now we can output control
	$opts = @$options[$number];
	
	$title = @$opts['title'];
	$language = @$opts['language'];
	
	 
	?>
    Title<br />
		<input type="text" name="<?php echo $prefix; ?>[<?php echo $number; ?>][title]" value="<?php echo $title; ?>" />
    Language (nl,de,en,fr,es)<br />
		<input type="text" name="<?php echo $prefix; ?>[<?php echo $number; ?>][language]" value="<?php echo $language; ?>" />

	<?
}

// helper function can be defined in another plugin
if(!function_exists('vm_smart_multiwidget_update')){
	function vm_smart_multiwidget_update($id_prefix, $options, $post, $sidebar, $option_name = ''){
		global $wp_registered_widgets;
		static $updated = false;

		// get active sidebar
		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();
		
		// search unused options
		foreach ( $this_sidebar as $_widget_id ) {
			if(preg_match('/'.$id_prefix.'-([0-9]+)/i', $_widget_id, $match)){
				$widget_number = $match[1];
				
				// $_POST['widget-id'] contain current widgets set for current sidebar
				// $this_sidebar is not updated yet, so we can determine which was deleted
				if(!in_array($match[0], $_POST['widget-id'])){
					unset($options[$widget_number]);
				}
			}
		}
		
		// update database
		if(!empty($option_name)){
			update_option($option_name, $options);
			$updated = true;
		}
		
		// return updated array
		return $options;
	}
}


// CREATE BUTTON SHORTCODE

function vm_maxmind_button($atts) {
	extract(shortcode_atts(array(
	      'text' => "Book",
	   ), $atts));
	
   	$vm_button = "<a href='".get_option('vm_maxmind_url')."' class='".get_option('vm_maxmind_button_class')."'><span>$text</span></a>";
	return $vm_button;
	
}

function register_vm_shortcodes(){
   add_shortcode('vm-maxmind-button', 'vm_maxmind_button');
}

add_action( 'init', 'register_vm_shortcodes');
?>