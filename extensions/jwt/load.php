<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_JWT')){
    class IFWP_JWT {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
       // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
			$general = ifwp_tab('', 'JWT');
            $general->add_field('load_jwt', [
				'label_description' => 'For details, see the <a href="https://github.com/firebase/php-jwt" target="_blank">PHP-JWT</a> by <a href="https://github.com/firebase" target="_blank">Firebase</a>.',
                'name' => 'Load PHP-JWT?',
            	'type' => 'switch',
            ]);
            if($general->load_jwt){
                ifwp_maybe_load_php_jwt();
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function load(){
            add_action('ifwp_loaded', [__CLASS__, 'ifwp_loaded']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// functions
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_jwt_decode')){
    function ifwp_jwt_decode(...$args){
		ifwp_maybe_load_php_jwt();
        return Firebase\JWT\JWT::decode(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_jwt_encode')){
    function ifwp_jwt_encode(...$args){
		ifwp_maybe_load_php_jwt();
        return Firebase\JWT\JWT::encode(...$args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_load_php_jwt')){
    function ifwp_maybe_load_php_jwt(){
		if(!class_exists('\Firebase\JWT\BeforeValidException')){
			require_once(plugin_dir_path(__FILE__) . 'php-jwt-5.2.0/src/BeforeValidException.php');
		}
		if(!class_exists('\Firebase\JWT\ExpiredException')){
			require_once(plugin_dir_path(__FILE__) . 'php-jwt-5.2.0/src/ExpiredException.php');
		}
		if(!class_exists('\Firebase\JWT\JWK')){
			require_once(plugin_dir_path(__FILE__) . 'php-jwt-5.2.0/src/JWK.php');
		}
		if(!class_exists('\Firebase\JWT\JWT')){
			require_once(plugin_dir_path(__FILE__) . 'php-jwt-5.2.0/src/JWT.php');
		}
		if(!class_exists('\Firebase\JWT\SignatureInvalidException')){
			require_once(plugin_dir_path(__FILE__) . 'php-jwt-5.2.0/src/SignatureInvalidException.php');
		}
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_JWT::load();
