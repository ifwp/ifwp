<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Appearance')){
    class IFWP_Appearance {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $appearance = ifwp_tab('', [
				'label' => 'Appearance',
				'icon' => 'dashicons-admin-appearance',
			]);
            $appearance->add_field('enable_additional_code_editors', [
                'label_description' => 'Languages: CSS and JavaScript.',
				'name' => 'Enable additional code editors?',
            	'type' => 'switch',
            ]);
            if($appearance->enable_additional_code_editors){
            	IFWP_Additional_Code::load();
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

if(!class_exists('IFWP_Additional_Code')){
    class IFWP_Additional_Code {

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function admin_enqueue_scripts(){
			wp_enqueue_script('ace', plugin_dir_url(__FILE__) . 'src-min/ace.js', [], '1.4.12', true);
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function admin_print_footer_scripts(){ ?>
			<script type="text/javascript">
				jQuery(function($){
					if(typeof ace != 'undefined'){<?php
						foreach(['css', 'javascript'] as $mode){ ?>
							if($('#ifwp_<?php echo $mode; ?>_editor').length){
								var ifwp_<?php echo $mode; ?>_editor = ace.edit('ifwp_<?php echo $mode; ?>_editor');
								ifwp_<?php echo $mode; ?>_editor.$blockScrolling = Infinity;
								ifwp_<?php echo $mode; ?>_editor.setOptions({
									maxLines: 25,
									minLines: 5,
								});
								ifwp_<?php echo $mode; ?>_editor.getSession().on('change', function() {
									$('#ifwp_<?php echo $mode; ?>_code').val(ifwp_<?php echo $mode; ?>_editor.getSession().getValue()).trigger('change');
								});
								ifwp_<?php echo $mode; ?>_editor.getSession().setMode('ace/mode/<?php echo $mode; ?>');
								ifwp_<?php echo $mode; ?>_editor.getSession().setValue($('#ifwp_<?php echo $mode; ?>_code').val());
							}<?php
						} ?>
					}
				});
			</script><?php
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function init(){
			foreach([
				'css' => 'CSS',
				'javascript' => 'JavaScript',
			] as $mode => $label){
				register_post_type('ifwp_' . $mode . '_code', [
					'capability_type' => 'page',
					'label' => 'Additional ' . $label,
					'show_in_admin_bar' => false,
					'show_in_menu' => 'themes.php',
					'show_ui' => true,
					'supports' => ['title'],
				]);
			}
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function load(){
			add_action('admin_enqueue_scripts', [__CLASS__, 'admin_enqueue_scripts']);
			add_action('admin_print_footer_scripts', [__CLASS__, 'admin_print_footer_scripts']);
			add_action('init', [__CLASS__, 'init']);
			add_action('rwmb_meta_boxes', [__CLASS__, 'rwmb_meta_boxes']);
			// 999 should be enough for themes
			add_action('wp_enqueue_scripts', [__CLASS__, 'wp_enqueue_scripts'], 1000);
			add_action('wp_head', [__CLASS__, 'wp_head'], 1000);
			add_action('wp_print_footer_scripts', [__CLASS__, 'wp_print_footer_scripts'], 1000);
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function rwmb_meta_boxes($meta_boxes){
			foreach([
				'css' => 'CSS',
				'javascript' => 'JavaScript',
			] as $mode => $label){
				$meta_boxes[] = [
					'fields' => [
						[
							'id' => 'ifwp_' . $mode . '_code',
							'sanitize_callback' => 'none',
							'type' => 'hidden',
						],
						[
							'id' => 'custom_html',
							'std' => '<div id="ifwp_' . $mode . '_editor" style="border: 1px solid #e5e5e5; height: 0; margin-top: 6px; width: 100%;"></div>',
							'type' => 'custom_html',
						],
					],
					'post_types' => 'ifwp_' . $mode . '_code',
					'title' => 'Additional ' . $label,
				];
			}
			return $meta_boxes;
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function wp_enqueue_scripts(){
			if(!wp_script_is('jquery')){
				wp_enqueue_script('jquery');
			}
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function wp_head(){
			$posts = get_posts([
				'post_type' => 'ifwp_css_code',
				'posts_per_page' => -1,
			]);
			if($posts){
				echo '<style type="text/css" id="ifwp-additional-css">';
				foreach($posts as $post){
					if(apply_filters('ifwp_css_code', true, $post)){
						echo get_post_meta($post->ID, 'ifwp_css_code', true);
					}
				}
				echo '</style>';
			}
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		public static function wp_print_footer_scripts(){
			$posts = get_posts([
				'post_type' => 'ifwp_javascript_code',
				'posts_per_page' => -1,
			]);
			if($posts){
				echo '<script type="text/javascript" id="ifwp-additional-javascript">';
				foreach($posts as $post){
					if(apply_filters('ifwp_javascript_code', true, $post)){
						echo '/* ' . $post->post_title . " */\n";
						echo trim(get_post_meta($post->ID, 'ifwp_javascript_code', true)) . "\n";
					}
				}
				echo '</script>';
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

IFWP_Appearance::load();
