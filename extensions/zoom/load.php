<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Zoom')){
    class IFWP_Zoom {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $api = ifwp_tab('Zoom', 'API');
            $api->add_field('api_key', [
				'label_description' => 'For details, see <a href="https://marketplace.zoom.us/docs/guides/auth/jwt#key-secret" target="_blank">Accessing your API Key & Secret</a>.',
                'name' => 'API Key',
                'type' => 'text',
            ]);
            $api->add_field('api_secret', [
                'name' => 'API Secret',
                'type' => 'text',
            ]);
            $api->add_field([
                'std' => '<button id="' . $api->tab_id . '_hide_password" class="button">Hide secret</button>',
                'type' => 'custom_html',
            ]);
            add_action('admin_footer', function() use($api){
                if($api->is_current_screen()){ ?>
                    <script type="text/javascript">
                        function <?php echo $api->tab_id; ?>_toggle_password(){
                            var element = jQuery('#<?php echo $api->tab_id; ?>_api_secret');
                            if(element.attr('type') == 'text'){
                                element.attr('type', 'password');
                                jQuery('#<?php echo $api->tab_id; ?>_hide_password').text('Show secret');
                            } else {
                                element.attr('type', 'text');
                                jQuery('#<?php echo $api->tab_id; ?>_hide_password').text('Hide secret');
                            }
                        }
                        jQuery(function($){<?php
                            echo $api->tab_id; ?>_toggle_password();
                            $('#<?php echo $api->tab_id; ?>_hide_password').on('click', function(e){
                                e.preventDefault();<?php
                                echo $api->tab_id; ?>_toggle_password();
                            });
                        });
                    </script><?php
                }
            });
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

if(!function_exists('ifwp_generate_zoom_jwt')){
	function ifwp_generate_zoom_jwt($api_key = '', $api_secret = ''){
		if(!$api_key){
			$api_key = ifwp_tab_option('Zoom', 'API', 'api_key', '');
		}
		if(!$api_secret){
			$api_secret = ifwp_tab_option('Zoom', 'API', 'api_secret', '');
		}
        if(!$api_key or !$api_secret){
            return '';
        }
        $payload = [
            'iss' => $api_key,
            'exp' => time() + DAY_IN_SECONDS, // GMT time
        ];
        return ifwp_jwt_encode($payload, $api_secret);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Zoom::load();
