<?php

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
