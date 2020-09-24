<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Sessions')){
    class IFWP_Sessions {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
			$general = ifwp_tab('', 'Sessions');
            $general->add_field('support_native_php_sessions', [
				'label_description' => 'You shoud use in conjunction with the <a href="https://wordpress.org/plugins/wp-native-php-sessions/" target="_blank">WordPress Native PHP Sessions</a> plugin by <a href="https://pantheon.io/" target="_blank">Pantheon</a>.',
                'name' => 'Support native PHP Sessions?',
            	'type' => 'switch',
            ]);
            if($general->support_native_php_sessions){
                add_action('init', [__CLASS__, 'init']);
                add_action('wp_login', [__CLASS__, 'wp_login'], 10, 2);
                add_action('wp_logout', [__CLASS__, 'wp_logout']);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function init(){
            if(!session_id()){
        		session_start();
        	}
        	if(empty($_SESSION['ifwp_current_user_id'])){
        		$_SESSION['ifwp_current_user_id'] = get_current_user_id();
        	}
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function load(){
            add_action('ifwp_loaded', [__CLASS__, 'ifwp_loaded']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function wp_login($user_login, $user){
            $_SESSION['ifwp_current_user_id'] = $user->ID;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function wp_logout(){
            if(session_id()){
        		session_destroy();
        	}
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Sessions::load();
