<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Login')){
    class IFWP_Login {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $login = ifwp_tab('', [
            	'label' => 'Login',
            	'icon' => 'dashicons-wordpress',
            ]);
            $login->add_field('use_custom_logo', [
            	'image_size' => 'medium',
            	'label_description' => 'Display size: medium / 2.',
            	'max_file_uploads' => 1,
                'max_status' => false,
                'name' => 'Use custom logo:',
                'type' => 'image_advanced',
            ]);
            if($login->use_custom_logo){
            	add_action('login_enqueue_scripts', function() use($login){
            		$custom_logos = $login->get_option('use_custom_logo', []);
                    $custom_logo = wp_get_attachment_image_src($custom_logos[0], 'medium'); ?>
            		<style type="text/css">
            			#login h1 a,
            			.login h1 a {
            				background-image: url(<?php echo $custom_logo[0]; ?>);
            				background-size: <?php echo $custom_logo[1] / 2; ?>px <?php echo $custom_logo[2] / 2; ?>px;
            				height: <?php echo $custom_logo[2] / 2; ?>px;
            				width: <?php echo $custom_logo[1] / 2; ?>px;
            			}
            		</style><?php
                });
            }
            $login->add_field('use_local_header', [
            	'label_description' => 'Context: text and URL.',
                'name' => 'Use local header?',
                'type' => 'switch',
            ]);
            if($login->use_local_header){
                add_filter('login_headertext', function($login_headertext){
            		return get_option('blogname');
                });
            	add_filter('login_headerurl', function($login_headerurl){
            		return home_url();
                });
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

if(!function_exists('ifwp_login_without_password')){
	function ifwp_login_without_password($user_login = '', $remember = false){
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
		ifwp_off('authenticate', $function_key, 10, 2);
        return $user;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Login::load();
