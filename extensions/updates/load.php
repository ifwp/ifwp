<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// functions
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_build_update_checker')){
    function ifwp_build_update_checker(...$args){
        ifwp_maybe_load_plugin_update_checker();
        return \Puc_v4_Factory::buildUpdateChecker(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_load_plugin_update_checker')){
    function ifwp_maybe_load_plugin_update_checker(){
		if(!class_exists('\Puc_v4_Factory')){
			require_once(plugin_dir_path(__FILE__) . 'plugin-update-checker-4.9/plugin-update-checker.php');
		}
    }
}
