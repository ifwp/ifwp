<?php
/*
Author: IFWP
Author URI: https://github.com/ifwp
Description: Improvements and Fixes for WordPress.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network:
Plugin Name: IFWP
Plugin URI: https://github.com/ifwp/ifwp
Text Domain: ifwp
Version: 2020.9.24.2
*/

if(!defined('ABSPATH')){
    die("Hi there! I'm just a plugin, not much I can do when called directly.");
}
if(defined('IFWP')){
    wp_die("IFWP constant already exists.");
}
define('IFWP', __FILE__);
foreach(glob(plugin_dir_path(IFWP) . 'extensions/*', GLOB_ONLYDIR) as $dir){
    $file = $dir . '/load.php';
    if(file_exists($file)){
        require_once($file);
    }
}
do_action('ifwp_loaded');
