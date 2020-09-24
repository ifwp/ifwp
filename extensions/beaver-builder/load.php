<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Beaver_Builder_Plugin')){
    class IFWP_Beaver_Builder_Plugin {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $bb_plugin = ifwp_tab('Beaver Builder', 'Beaver Builder Plugin');
            add_action('plugins_loaded', function() use($bb_plugin){
            	if(ifwp_is_plugin_active('bb-plugin/fl-builder.php')){
            		$bb_plugin->add_field('expand_templates_on_navigation_menus', [
            			'label_description' => 'For details, see <a href="https://developer.wordpress.org/themes/functionality/navigation-menus/" target="_blank">Navigation Menus</a>, <a href="https://kb.wpbeaverbuilder.com/article/99-layout-templates-overview#saved-templates" target="_blank">Saved layout templates</a>, <a href="https://make.wordpress.org/support/user-manual/getting-to-know-wordpress/screen-options/" target="_blank">Screen Options</a> and <a href="https://kb.wpbeaverbuilder.com/article/139-set-up-a-mega-menu" target="_blank">Set up a Mega Menu</a>.',
            			'name' => 'Expand templates on navigation menus?',
            			'type' => 'switch',
            		]);
            		if($bb_plugin->expand_templates_on_navigation_menus){
            			add_filter('walker_nav_menu_start_el', function($item_output, $item, $depth, $args){
            				if($item->object == 'fl-builder-template'){
                                $item_output = $args->before;
                                $item_output .= do_shortcode('[fl_builder_insert_layout id="' . $item->object_id . '"]');
                                $item_output .= $args->after;
                            }
                            return $item_output;
            			}, 10, 4);
            		}
            		$bb_plugin->add_field('disable_column_resizing', [
            			'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/556-set-width-for-rows-and-content#row-drag-handles" target="_blank">Row drag handles</a>.',
                        'name' => 'Disable column resizing?',
            			'type' => 'switch',
            		]);
            		if($bb_plugin->disable_column_resizing){
            			add_action('wp_head', function(){
            				if(array_key_exists('fl_builder', $_GET)){ ?>
                                <style type="text/css">
                                    .fl-block-col-resize {
                                        display: none;
                                    }
                                </style><?php
                            }
            			});
            		}
            		$bb_plugin->add_field('disable_inline_editing', [
            			'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/658-disable-inline-editing" target="_blank">Disable inline editing</a>.',
                        'name' => 'Disable inline editing?',
            			'type' => 'switch',
            		]);
            		if($bb_plugin->disable_inline_editing){
            			add_filter('fl_inline_editing_enabled', '__return_false');
            		}
            		$bb_plugin->add_field('disable_row_resizing', [
            			'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/555-customize-row-resizing-behavior" target="_blank">Customize row resizing behavior</a>.',
                        'name' => 'Disable row resizing?',
            			'type' => 'switch',
            		]);
            		if($bb_plugin->disable_row_resizing){
            			add_filter('fl_row_resize_settings', function($settings){
            				$settings['userCanResizeRows'] = false;
                            return $settings;
            			});
            		}
            		$bb_plugin->add_field('use_bootstrap_colors', [
            			 'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/554-add-a-color-palette-to-the-beaver-builder-editor" target="_blank">Add a color palette to the Beaver Builder editor</a> and <a href="https://getbootstrap.com/docs/4.4/utilities/colors/" target="_blank">Colors</a>.',
                        'name' => 'Use Bootstrap colors?',
            			'type' => 'switch',
            		]);
            		if($bb_plugin->use_bootstrap_colors){
            			add_filter('fl_builder_color_presets', function($colors){
            				$bootstrap_colors = [
            					'007bff', // primary
                                '6c757d', // secondary
                                '28a745', // success
                                '17a2b8', // info
                                'ffc107', // warning
                                'dc3545', // danger
                                'f8f9fa', // light
                                '343a40', // dark
            				];
            				return array_values(array_unique(array_merge($colors, $bootstrap_colors)));
            			});
            		}
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

if(!class_exists('IFWP_Beaver_Builder_Theme')){
    class IFWP_Beaver_Builder_Theme {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            add_action('after_setup_theme', function() use($tag_manager){
                $current_theme = wp_get_theme();
                if($current_theme->get('Name') == 'Beaver Builder Theme' or $current_theme->get('Template') == 'bb-theme'){
                    $bb_theme = ifwp_tab('Beaver Builder', 'Beaver Builder Theme');
                    add_action('admin_enqueue_scripts', function() use($bb_theme){
            			if($bb_theme->is_current_screen()){
            				wp_enqueue_script('wp-api');
            			}
            		});
            		add_action('admin_footer', function() use($bb_theme){
            			if($bb_theme->is_current_screen()){ ?>
            				<script type="text/javascript">
            					jQuery(function($){
            						$('#ifwp_reboot_default_styles').on('click', function(e){
            							e.preventDefault();
            							if(confirm('Are you sure?')){
            								$('#ifwp_reboot_default_styles').text('Wait...');
            								$.ajax({
            									beforeSend: function(xhr){
            										xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            									},
            									method: 'GET',
            									url: wpApiSettings.root + 'ifwp/v1/reboot-beaver-builder-theme-default-styles',
            								}).done(function(response){
            									$('#ifwp_reboot_default_styles').text('Done.');
            									setTimeout(function(){
            										$('#ifwp_reboot_default_styles').text('Reboot');
            									}, 1000);
            								});
            							}
            						});
            					});
            				</script><?php
            			}
            		});
            		add_action('rest_api_init', function() use($bb_theme){
            			register_rest_route('ifwp/v1', '/reboot-beaver-builder-theme-default-styles', [
            				'callback' => function(){
            					$mods = get_theme_mods();
            					$mods['fl-scroll-to-top'] = 'enable';
            					$mods['fl-framework'] = 'bootstrap-4';
            					$mods['fl-awesome'] = 'fa5';
            					$mods['fl-body-bg-color'] = '#ffffff';
            					$mods['fl-accent'] = '#007bff';
            					$mods['fl-accent-hover'] = '#007bff';
            					$mods['fl-heading-text-color'] = '#343a40';
            					$mods['fl-heading-font-family'] = 'Open Sans';
            					$mods['fl-h1-font-size'] = 40;
            					$mods['fl-h1-font-size_medium'] = 33;
            					$mods['fl-h1-font-size_mobile'] = 28;
            					$mods['fl-h1-line-height'] = 1.2;
            					$mods['fl-h1-line-height_medium'] = 1.2;
            					$mods['fl-h1-line-height_mobile'] = 1.2;
            					$mods['fl-h2-font-size'] = 32;
            					$mods['fl-h2-font-size_medium'] = 28;
            					$mods['fl-h2-font-size_mobile'] = 24;
            					$mods['fl-h2-line-height'] = 1.2;
            					$mods['fl-h2-line-height_medium'] = 1.2;
            					$mods['fl-h2-line-height_mobile'] = 1.2;
            					$mods['fl-h3-font-size'] = 28;
            					$mods['fl-h3-font-size_medium'] = 25;
            					$mods['fl-h3-font-size_mobile'] = 22;
            					$mods['fl-h3-line-height'] = 1.2;
            					$mods['fl-h3-line-height_medium'] = 1.2;
            					$mods['fl-h3-line-height_mobile'] = 1.2;
            					$mods['fl-h4-font-size'] = 24;
            					$mods['fl-h4-font-size_medium'] = 22;
            					$mods['fl-h4-font-size_mobile'] = 20;
            					$mods['fl-h4-line-height'] = 1.2;
            					$mods['fl-h4-line-height_medium'] = 1.2;
            					$mods['fl-h4-line-height_mobile'] = 1.2;
            					$mods['fl-h5-font-size'] = 20;
            					$mods['fl-h5-font-size_medium'] = 19;
            					$mods['fl-h5-font-size_mobile'] = 16;
            					$mods['fl-h5-line-height'] = 1.2;
            					$mods['fl-h5-line-height_medium'] = 1.2;
            					$mods['fl-h5-line-height_mobile'] = 1.2;
            					$mods['fl-h6-font-size'] = 16;
            					$mods['fl-h6-font-size_medium'] = 16;
            					$mods['fl-h6-font-size_mobile'] = 16;
            					$mods['fl-h6-line-height'] = 1.2;
            					$mods['fl-h6-line-height_medium'] = 1.2;
            					$mods['fl-h6-line-height_mobile'] = 1.2;
            					$mods['fl-body-text-color'] = '#6c757d';
            					$mods['fl-body-font-family'] = 'Open Sans';
            					$mods['fl-body-font-size'] = 16;
            					$mods['fl-body-font-size_medium'] = 16;
            					$mods['fl-body-font-size_mobile'] = 16;
            					$mods['fl-body-line-height'] = 1.5;
            					$mods['fl-body-line-height_medium'] = 1.5;
            					$mods['fl-body-line-height_mobile'] = 1.5;
            					update_option('theme_mods_' . get_option('stylesheet'), $mods);
            					return $mods;
            				},
            				'permission_callback' => function(){
            					return current_user_can('manage_options');
            				},
            			]);
            		});
            		$bb_theme->add_field('reboot_default_styles', [
            			'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/153-customizer-settings-the-general-tab" target="_blank">Customizer settings: The General tab</a> and <a href="https://getbootstrap.com/docs/4.0/content/reboot/" target="_blank">Reboot</a>.',
            			'name' => 'Reboot default styles?',
            			'std' => '<button id="ifwp_reboot_default_styles" class="button">Reboot</button>',
            			'type' => 'custom_html',
            		]);
            		$bb_theme->add_field('remove_default_styles', [
            			'label_description' => 'You must <a href="' . admin_url('options-general.php?page=fl-builder-settings#tools') . '">clear cache</a> for new settings to take effect.',
            			'name' => 'Remove default styles?',
            			'type' => 'switch',
            		]);
            		if($bb_theme->remove_default_styles){
            			add_filter('fl_theme_compile_less_paths', function($paths){
            				foreach($paths as $index => $path){
            					if($path == FL_THEME_DIR . '/less/theme.less'){
            						$paths[$index] = plugin_dir_path(__FILE__) . 'theme.less';
            					}
            				}
            				return $paths;
            			});
            		}
            		$bb_theme->add_field('remove_presets', [
            			'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/152-customizer-settings-the-presets-tab" target="_blank">Customizer settings: The Presets tab</a>.',
            			'name' => 'Remove presets?',
            			'type' => 'switch',
            		]);
            		if($bb_theme->remove_presets){
            			add_action('customize_register', function($wp_customize){
            				$wp_customize->remove_section('fl-presets');
            			}, 20);
            		}
            		$bb_theme->add_field('use_bootstrap_colors', [
            			'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/553-add-color-presets-to-customizer" target="_blank">Add color presets to Customizer</a> and <a href="https://getbootstrap.com/docs/4.4/utilities/colors/" target="_blank">Colors</a>.',
            			'name' => 'Use Bootstrap colors?',
            			'type' => 'switch',
            		]);
            		if($bb_theme->use_bootstrap_colors){
            			add_action('customize_controls_print_footer_scripts', function(){ ?>
            				<script type="text/javascript">
            					var bootstrap_colors = [
            						'#007bff', // primary
            						'#6c757d', // secondary
            						'#28a745', // success
            						'#17a2b8', // info
            						'#ffc107', // warning
            						'#dc3545', // danger
            						'#f8f9fa', // light
            						'#343a40', // dark
            					];
            					jQuery(function($){
            						$('.wp-picker-container').iris({
            							mode: 'hsl',
            							controls: {
            								horiz: 'h', // square horizontal displays hue
            								vert: 's', // square vertical displays saturdation
            								strip: 'l' // slider displays lightness
            							},
            							palettes: bootstrap_colors,
            						});
            					});
            				</script><?php
            			});
            		}
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

if(!class_exists('IFWP_Beaver_Themer_Plugin')){
    class IFWP_Beaver_Themer_Plugin {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $bb_theme_builder = ifwp_tab('Beaver Builder', 'Beaver Themer Plugin');
            add_action('plugins_loaded', function() use($bb_theme_builder){
            	if(ifwp_is_plugin_active('bb-theme-builder/bb-theme-builder.php')){
            		$bb_theme_builder->add_field('fix_the_loop', [
            			'label_description' => 'For details, see <a href="https://developer.wordpress.org/themes/basics/conditional-tags/#inside-the-loop" target="_blank">Inside The Loop</a> and <a href="https://codex.wordpress.org/The_Loop" target="_blank">The Loop</a>.',
            			'name' => 'Fix the loop?',
            			'type' => 'switch',
            		]);
            		if($bb_theme_builder->fix_the_loop){
            			add_action('fl_theme_builder_before_render_content', function(){
            				global $wp_query;
                        	if(!$wp_query->in_the_loop){
                        		$wp_query->in_the_loop = true;
                        		add_action('fl_theme_builder_after_render_content', function(){
                        			global $wp_query;
                        			$wp_query->in_the_loop = false;
                        		});
                        	}
            			});
            		}
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
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Beaver_Builder_Plugin::load();
IFWP_Beaver_Builder_Theme::load();
IFWP_Beaver_Themer_Plugin::load();
