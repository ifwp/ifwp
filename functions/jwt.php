<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('_ifwp_include_jwt')){
    function _ifwp_include_jwt(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            if(!class_exists('\Firebase\JWT\BeforeValidException')){
                require_once(plugin_dir_path(IFWP) . 'includes/php-jwt-5.2.0/src/BeforeValidException.php');
            }
            if(!class_exists('\Firebase\JWT\ExpiredException')){
                require_once(plugin_dir_path(IFWP) . 'includes/php-jwt-5.2.0/src/ExpiredException.php');
            }
            if(!class_exists('\Firebase\JWT\JWK')){
                require_once(plugin_dir_path(IFWP) . 'includes/php-jwt-5.2.0/src/JWK.php');
            }
            if(!class_exists('\Firebase\JWT\JWT')){
                require_once(plugin_dir_path(IFWP) . 'includes/php-jwt-5.2.0/src/JWT.php');
            }
            if(!class_exists('\Firebase\JWT\SignatureInvalidException')){
                require_once(plugin_dir_path(IFWP) . 'includes/php-jwt-5.2.0/src/SignatureInvalidException.php');
            }
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_jwt_decode')){
    function ifwp_jwt_decode(...$args){
        _ifwp_include_jwt();
        return call_user_func_array(['\Firebase\JWT\JWT', 'decode'], $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_jwt_encode')){
    function ifwp_jwt_encode(...$args){
        _ifwp_include_jwt();
        return call_user_func_array(['\Firebase\JWT\JWT', 'encode'], $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
