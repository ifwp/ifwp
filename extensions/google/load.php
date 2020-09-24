<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Google')){
    class IFWP_Google {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function download_google_api(WP_REST_Request $request){
            $wp_upload_dir = wp_upload_dir();
            $dir = $wp_upload_dir['basedir'] . '/ifwp/google-api';
            if(is_dir($dir)){
                return true;
            }
            if(!wp_mkdir_p($dir)){
                return false;
            }
            if(is_php_version_compatible('7.4')){
                $version = '7.4';
            } elseif(is_php_version_compatible('7.0')){
                $version = '7.0';
            } elseif(is_php_version_compatible('5.6')){
                $version = '5.6';
            } else {
                return false;
            }
            $url = 'https://github.com/googleapis/google-api-php-client/releases/download/v2.7.0/google-api-php-client-v2.7.0-PHP' . $version . '.zip';
            $attachment_id = ifwp_download($url);
            if(is_wp_error($attachment_id)){
                return false;
            }
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $access_type = get_filesystem_method();
            if($access_type != 'direct'){
                return false;
            }
            if(!WP_Filesystem()){
                return false;
            }
            $zip = get_attached_file($attachment_id);
            $result = unzip_file($zip, $dir);
            if(is_wp_error($result)){
                return false;
            }
            return true;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $analytics = ifwp_tab('Google', [
				'label' => 'Analytics',
				'icon' => 'dashicons-google',
			]);
            $analytics->add_field('tracking_id', [
				'label_description' => 'You shoud be using <a href="https://tagmanager.google.com/" target="_blank">Tag Manager</a> instead of <a href="https://analytics.google.com/" target="_blank">Analytics</a>.',
                'name' => 'Tracking ID',
                'placeholder' => 'UA-123456789-1',
                'type' => 'text',
            ]);
            if($analytics->tracking_id){
                add_action('wp_head', function() use($analytics){ ?>
                    <!-- Global site tag (gtag.js) - Google Analytics -->
                    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analytics->tracking_id; ?>"></script>
                    <script>
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());

                        gtag('config', '<?php echo $analytics->tracking_id; ?>');
                    </script><?php
                });
            }
            $api = ifwp_tab('Google', [
				'label' => 'API',
				'icon' => 'dashicons-google',
			]);
			$wp_upload_dir = wp_upload_dir();
            $dir = $wp_upload_dir['basedir'] . '/ifwp/google-api';
			if(is_dir($dir)){
                $api->add_field('load_google_api', [
					'label_description' => 'For details, see <a href="https://github.com/googleapis/google-api-php-client" target="_blank">Google APIs Client Library for PHP</a>.',
					'name' => 'Load Google API?',
					'type' => 'switch',
					'std' => true,
				]);
				if($api->get_option('load_google_api', true)){
        			require_once($dir . '/vendor/autoload.php');
				}
            } else {
				$api->add_field([
					'label_description' => 'For details, see <a href="https://github.com/googleapis/google-api-php-client" target="_blank">Google APIs Client Library for PHP</a>.',
					'name' => 'Download Google API?',
					'type' => 'custom_html',
					'std' => '<button id="' . $api->tab_id . '_download_google_api" class="button">Download</button>',
				]);
				add_action('admin_enqueue_scripts', function() use($api){
					if($api->is_current_screen()){
						wp_enqueue_script('wp-api');
					}
				});
				add_action('admin_footer', function() use($api){
					if($api->is_current_screen()){ ?>
						<script type="text/javascript">
							jQuery(function($){
								$('#<?php echo $api->tab_id; ?>_download_google_api').on('click', function(e){
									e.preventDefault();
									if(confirm('Are you sure?')){
										$('#<?php echo $api->tab_id; ?>_download_google_api').text('Wait...');
										$.ajax({
											beforeSend: function(xhr){
												xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
											},
											method: 'GET',
											url: wpApiSettings.root + 'ifwp/v1/download-google-api',
										}).done(function(response){
											$('#<?php echo $api->tab_id; ?>_download_google_api').text('Done.');
											setTimeout(function(){
												$('#<?php echo $api->tab_id; ?>_download_google_api').text('Download');
											}, 1000);
										});
									}
								});
							});
						</script><?php
					}
				});
			}
            $tag_manager = ifwp_tab('Google', [
				'label' => 'Tag Manager',
				'icon' => 'dashicons-google',
			]);
            $tag_manager->add_field('container_id', [
                'label_description' => 'For details, see <a href="https://tagmanager.google.com/" target="_blank">Tag Manager</a>.',
                'name' => 'Container ID',
                'placeholder' => 'GTM-A1B2C3D',
                'type' => 'text',
            ]);
            if($tag_manager->container_id){
                add_action('after_setup_theme', function() use($tag_manager){
                    $current_theme = wp_get_theme();
                	if($current_theme->get('Name') == 'Beaver Builder Theme' or $current_theme->get('Template') == 'bb-theme'){
                        $action = 'fl_body_open';
                    } else {
                        $action = 'wp_footer';
                    }
                    add_action($action, function() use($tag_manager){ ?>
                        <!-- Google Tag Manager (noscript) -->
                        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $tag_manager->container_id; ?>"
                        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
                        <!-- End Google Tag Manager (noscript) --><?php
                    });
                });
                add_action('wp_head', function() use($tag_manager){ ?>
                    <!-- Google Tag Manager -->
                    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                    })(window,document,'script','dataLayer','<?php echo $tag_manager->container_id; ?>');</script>
                    <!-- End Google Tag Manager --><?php
                });
            }
            $recaptcha = ifwp_tab('Google', [
				'label' => 'reCAPTCHA',
				'icon' => 'dashicons-google',
			]);
            $recaptcha->add_field('hide_recaptcha_badge', [
                'label_description' => 'You must use the <strong>[ifwp_hide_recaptcha_badge]</strong> shortcode.',
                'name' => 'Hide recaptcha badge?',
                'type' => 'switch',
            ]);
            if($recaptcha->hide_recaptcha_badge){
                add_action('init', function(){
                    add_shortcode('ifwp_hide_recaptcha_badge', function($atts = [], $content = ''){
            			return '<span class="ifwp-hide-recaptcha-badge">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.</span>';
            		});
                });
                add_action('wp_head', function(){ ?>
                    <style type="text/css">
                        .grecaptcha-badge {
                            visibility: hidden !important;
                        }
                    </style><?php
                });
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function load(){
            add_action('ifwp_loaded', [__CLASS__, 'ifwp_loaded']);
            add_action('rest_api_init', [__CLASS__, 'rest_api_init']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function rest_api_init(){
            register_rest_route('ifwp/v1', '/download-google-api', [
                'callback' => [__CLASS__, 'download_google_api'],
                'permission_callback' => function(){
                    return current_user_can('manage_options');
                },
            ]);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Google::load();
