<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_add_admin_notice')){
	function ifwp_add_admin_notice($admin_notice = '', $class = 'error', $is_dismissible = false){
		if(!in_array($class, array('error', 'warning', 'success', 'info'))){
			$class = 'warning';
		}
		if($is_dismissible){
			$class .= ' is-dismissible';
		}
		$admin_notice = '<div class="notice notice-' . $class . '"><p>' . $admin_notice . '</p></div>';
        ifwp_on('admin_notices', function() use($admin_notice){
			echo $admin_notice;
		});
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_array_keys_exist')){
	function ifwp_array_keys_exist($keys, $array){
		foreach($keys as $key){
			if(!array_key_exists($key, $array)){
				return false;
			}
		}
		return true;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urldecode')){
	function ifwp_base64_urldecode($data = '', $strict = false){
		return base64_decode(strtr($data, '-_', '+/'), $strict);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urlencode')){
	function ifwp_base64_urlencode($data = ''){
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_clone_role')){
	function ifwp_clone_role($source_role = '', $destination_role = '', $display_name = ''){
        if($source_role and $destination_role and $display_name){
            $role = get_role($source_role);
            $capabilities = $role->capabilities;
            add_role($destination_role, $display_name, $capabilities);
        }
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_fix_rest_shutdown')){
	function ifwp_fix_rest_shutdown(){
		ifwp_on('wp_die_handler', function($function){
	        if($function === '_default_wp_die_handler'){ // check for another plugins
	            if(defined('REST_REQUEST') and REST_REQUEST){
	                $function = apply_filters('wp_die_json_handler', '_json_wp_die_handler');
	            }
	        }
	        return $function;
		});
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_format_function')){
	function ifwp_format_function($function_name = '', $args = []){
		$str = '';
		if($function_name){
			$str .= '<div style="color: #24831d; font-family: monospace; font-weight: 400;">' . $function_name . '(';
			$function_args = [];
			if($args){
				foreach($args as $arg){
					$arg = shortcode_atts([
                        'default' => 'null',
						'name' => '',
						'type' => '',
                    ], $arg);
					if($arg['default'] and $arg['name'] and $arg['type']){
						$function_args[] = '<span style="color: #cd2f23; font-family: monospace; font-style: italic; font-weight: 400;">' . $arg['type'] . '</span> <span style="color: #0f55c8; font-family: monospace; font-weight: 400;">$' . $arg['name'] . '</span> = <span style="color: #000; font-family: monospace; font-weight: 400;">' . $arg['default'] . '</span>';
					}
				}
			}
			if($function_args){
				$str .= ' ' . implode(', ', $function_args) . ' ';
			}
			$str .= ')</div>';
		}
		return $str;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_get_memory_size')){
	function ifwp_get_memory_size(){
	    if(!function_exists('exec')){
	        return 0;
	    }
	    exec('free -b', $output);
	    $output = $output[1];
	    $output = preg_replace('/\s+/', ' ', $output);
	    $output = trim($output);
	    $output = explode(' ', $output);
	    $output = $output[1];
	    return (int) $output;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_hide_recaptcha_badge')){
	function ifwp_hide_recaptcha_badge(){
		static $already_called = false;
        if(!$already_called){
            $already_called = true;
            ifwp_on('wp_head', function(){ ?>
				<style>
					.grecaptcha-badge {
						visibility: hidden !important;
					}
				</style><?php
			});
        }
		add_shortcode('ifwp_hide_recaptcha_badge', function($atts = [], $content = ''){
			$html = '<span class="ifwp-hide-recaptcha-badge">';
            $html .= 'This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.';
            $html .= '</span>';
            return $html;
		});
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_new')){
    function ifwp_new(...$args){
        if(!$args){
            return null;
        }
        $class_name = array_shift($args);
        if(!class_exists($class_name)){
            return null;
        }
        if($args){
            return new $class_name(...$args);
        } else {
            return new $class_name;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_off')){
    function ifwp_off($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
        return remove_filter($tag, $function_to_add, $priority, $accepted_args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_on')){
    function ifwp_on($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
        add_filter($tag, $function_to_add, $priority, $accepted_args);
		return _wp_filter_build_unique_id($tag, $function_to_add, $priority);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_prepare')){
    function ifwp_prepare(...$args){
        global $wpdb;
        if(!$args){
            return '';
        }
        if(strpos($args[0], '%') !== false and count($args) > 1){
            return str_replace("'", '', $wpdb->remove_placeholder_escape(call_user_func_array([$wpdb, 'prepare'], $args)));
        } else {
            return $args[0];
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_json')){
    function ifwp_seems_json($str = ''){
        return (is_string($str) and preg_match('/^\{\".*\"\:.*\}$/', $str));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_signon_without_password')){
	function ifwp_signon_without_password($user_login = '', $remember = false){
        if(is_user_logged_in()){
            return new WP_Error('authentication_failed', 'You are currently logged in.');
        }
		$function_key = ifwp_on('authenticate', function($user = null, $username = ''){
	        if($user !== null){
	            return $user;
	        }
	        $user = get_user_by('login', $username);
	        if($user){
	            return $user;
	        }
	        return null;
		}, 10, 2);
		$user = wp_signon([
            'user_login' => $user_login,
            'user_password' => '',
            'remember' => $remember,
        ]);
		ifwp_off('authenticate', $function_key);
        return $user;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
