<?php
/**
* Plugin Name: Crop From Top
* Plugin URI: http://www.wpcube.co.uk/plugins/crop-from-top/
* Version: 1.0.4
* Author: WP Cube
* Author URI: http://www.wpcube.co.uk
* Description: Forces image resizing and cropping to occur from the top of an image instead of the center whenever an image is uploaded and resized by WordPress.
* License: GPL2
*/

/*  Copyright 2013 WP Cube (email : support@wpcube.co.uk)

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

/**
* Crop From Top Class
* 
* @package WP Cube
* @subpackage Crop From Top
* @author Tim Carr
* @version 1.0.4
* @copyright WP Cube
*/
class CropFromTop {
    /**
    * Constructor.
    */
    function CropFromTop() {
        // Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name = 'crop-from-top'; // Plugin Folder
        $this->plugin->displayName = 'Crop From Top'; // Plugin Name
        $this->plugin->version = '1.0.4';
        $this->plugin->folder = WP_PLUGIN_DIR.'/'.$this->plugin->name; // Full Path to Plugin Folder
        $this->plugin->url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        
        // Dashboard Submodule
        if (!class_exists('WPCubeDashboardWidget')) {
			require_once($this->plugin->folder.'/_modules/dashboard/dashboard.php');
		}
		$dashboard = new WPCubeDashboardWidget($this->plugin); 
		
		// Hooks
        add_action('admin_enqueue_scripts', array(&$this, 'adminScriptsAndCSS'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
        add_action('plugins_loaded', array(&$this, 'loadLanguageFiles'));
        add_action('image_resize_dimensions', array(&$this, 'imageCrop'), 10, 6);
    }
    
    /**
    * Register and enqueue any JS and CSS for the WordPress Administration
    */
    function adminScriptsAndCSS() {        
    	// CSS
        wp_enqueue_style($this->plugin->name.'-admin', $this->plugin->url.'css/admin.css', array(), $this->plugin->version); 
    }
    
    /**
    * Register the plugin settings panel
    */
    function adminPanelsAndMetaBoxes() {
        add_menu_page($this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array(&$this, 'adminPanel'), $this->plugin->url.'images/icons/small.png');
    }
    
	/**
    * Output the Administration Panel
    * Save POSTed data from the Administration Panel into a WordPress option
    */
    function adminPanel() {
        // Save Settings
        if (isset($_POST['submit'])) {
        	if (isset($_POST[$this->plugin->name])) {
        		update_option($this->plugin->name, $_POST[$this->plugin->name]);
				$this->message = __('Settings Updated.', $this->plugin->name);
			}
        }
        
        // Get latest settings
        $this->settings = get_option($this->plugin->name);
        
		// Load Settings Form
        include_once(WP_PLUGIN_DIR.'/'.$this->plugin->name.'/views/settings.php');  
    }
    
    /**
    * Loads plugin textdomain
    */
    function loadLanguageFiles() {
    	load_plugin_textdomain($this->plugin->name, false, $this->plugin->name.'/languages/');
    }
    
    /**
    * Crops an image from the top
    * Props: http://stephanis.info/2012/06/how-to-change-post-thumbnail-crop-position-in-wordpress-without-hacking-core/
    */
	function imageCrop( $payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop ){
		// Change this to a conditional that decides whether you 
		// want to override the defaults for this image or not.
		if( false )
			return $payload;
	
		if ( $crop ) {
			// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
			$aspect_ratio = $orig_w / $orig_h;
			$new_w = min($dest_w, $orig_w);
			$new_h = min($dest_h, $orig_h);
	
			if ( !$new_w ) {
				$new_w = intval($new_h * $aspect_ratio);
			}
	
			if ( !$new_h ) {
				$new_h = intval($new_w / $aspect_ratio);
			}
	
			$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
	
			$crop_w = round($new_w / $size_ratio);
			$crop_h = round($new_h / $size_ratio);
	
			$s_x = 0; // [[ formerly ]] ==> floor( ($orig_w - $crop_w) / 2 );
			$s_y = 0; // [[ formerly ]] ==> floor( ($orig_h - $crop_h) / 2 );
		} else {
			// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
			$crop_w = $orig_w;
			$crop_h = $orig_h;
	
			$s_x = 0;
			$s_y = 0;
	
			list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		}
	
		// if the resulting image would be the same size or larger we don't want to resize it
		if ( $new_w >= $orig_w && $new_h >= $orig_h )
			return false;
	
		// the return array matches the parameters to imagecopyresampled()
		// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}
}
$cft = new CropFromTop(); // Invoke class
?>
