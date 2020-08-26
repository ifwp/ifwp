<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_add_larger_image_sizes')){
	function ifwp_add_larger_image_sizes(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_image_size('hd', 1280, 1280);
            add_image_size('full-hd', 1920, 1920);
            ifwp_on('image_size_names_choose', function($sizes){
                $sizes['hd'] = 'HD';
                $sizes['full-hd'] = 'Full HD';
        		return $sizes;
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_enqueue_bootstrap')){
    function ifwp_enqueue_bootstrap(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_action('wp_enqueue_scripts', function(){
                $src = plugin_dir_url(IFWP) . 'assets/css/bootstrap.min.css';
                wp_enqueue_style('bootstrap', $src, [], '4.5.0');
                $src = plugin_dir_url(IFWP) . 'assets/js/bootstrap.min.js';
                wp_enqueue_script('bootstrap', $src, ['jquery'], '4.5.0', true);
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_enqueue_bs_custom_file_input')){
    function ifwp_enqueue_bs_custom_file_input(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_action('wp_enqueue_scripts', function(){
                $src = plugin_dir_url(IFWP) . 'assets/js/bs-custom-file-input.min.js';
                wp_enqueue_script('bs-custom-file-input', $src, ['jquery'], '1.3.4', true);
				wp_add_inline_script('bs-custom-file-input', 'jQuery(function($){ bsCustomFileInput.init(); });');
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_enqueue_popper')){
    function ifwp_enqueue_popper(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_action('wp_enqueue_scripts', function(){
				$src = plugin_dir_url(IFWP) . 'assets/js/popper.min.js';
                wp_enqueue_script('popper', $src, ['jquery'], '1.16.1-lts', true);
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_support_floating_labels')){
    function ifwp_support_floating_labels(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_action('wp_enqueue_scripts', function(){
                $src = plugin_dir_url(IFWP) . 'assets/css/floating-labels.css';
                $ver = filemtime(plugin_dir_path(IFWP) . 'assets/css/floating-labels.css');
                wp_enqueue_style('ifwp-floating-labels', $src, [], $ver);
                $src = plugin_dir_url(IFWP) . 'assets/js/floating-labels.js';
                $ver = filemtime(plugin_dir_path(IFWP) . 'assets/js/floating-labels.js');
                wp_enqueue_script('ifwp-floating-labels', $src, ['jquery'], $ver, true);
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
