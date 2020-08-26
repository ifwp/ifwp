<?php
/*
Author: Vidsoe
Author URI: https://vidsoe.com
Description: Improvements and Fixes for WordPress
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network:
Plugin Name: IFWP
Plugin URI: https://github.com/ifwp/ifwp
Text Domain: ifwp
Version: 2020.8.25.1
*/

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!defined('ABSPATH')){
    die("Hi there! I'm just a plugin, not much I can do when called directly.");
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(defined('IFWP')){
    die("IFWP constant already exists.");
}
define('IFWP', __FILE__);
$wp_upload_dir = wp_upload_dir();
define('IFWP_BASEDIR', trailingslashit($wp_upload_dir['basedir']) . 'ifwp');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

foreach(glob(plugin_dir_path(IFWP) . 'functions/*.php') as $functions){
    require_once($functions);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

ifwp_build_update_checker('https://github.com/ifwp/ifwp', IFWP, 'ifwp');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

ifwp_on('after_setup_theme', function(){
    $src = get_stylesheet_directory() . '/ifwp-functions.php';
    if(file_exists($src)){
        require_once($src);
    }
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

ifwp_on('wp_enqueue_scripts', function(){
    $src = plugin_dir_url(IFWP) . 'functions.js';
    $ver = filemtime(plugin_dir_path(IFWP) . 'functions.js');
    wp_enqueue_script('ifwp-functions', $src, ['jquery'], $ver, true);
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
