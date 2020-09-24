<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Privacy')){
    class IFWP_Privacy {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
			$capabilities = [];
			foreach(wp_roles()->roles as $role => $args){
				foreach($args['capabilities'] as $capability => $status){
					if(!in_array($capability, $capabilities)){
						$capabilities[$capability] = $capability;
					}
				}
			}
            $privacy = ifwp_tab('', [
				'label' => 'Privacy',
				'icon' => 'dashicons-hidden',
			]);
            $privacy->add_field('hide_the_dashboard', [
                //'label_description' => "Behavior: Admin URLs will redirect to your site's main URL.",
				'name' => 'Hide the dashboard?',
				'type' => 'switch',
            ]);
			$privacy->add_field('hide_the_dashboard_capability', [
				'label_description' => "Recommended: edit_posts",
				'name' => 'Minimum capability required to show the dashboard:',
				'options' => $capabilities,
				'required' => true,
				'std' => 'edit_posts',
				'type' => 'select_advanced',
				'visible' => ['hide_the_dashboard', true],
			]);
            if($privacy->hide_the_dashboard){
            	add_action('admin_init', function() use($privacy){
					if(!wp_doing_ajax() and !current_user_can($privacy->get_option('hide_the_dashboard_capability', ''))){
						wp_safe_redirect(home_url());
						exit;
					}
				});
            }
			$privacy->add_field('hide_the_toolbar', [
				'name' => 'Hide the toolbar?',
				'type' => 'switch',
			]);
			$privacy->add_field('hide_the_toolbar_capability', [
				'label_description' => "Recommended: edit_posts",
				'name' => 'Minimum capability required to show the toolbar:',
				'options' => $capabilities,
				'required' => true,
				'std' => 'edit_posts',
				'type' => 'select_advanced',
				'visible' => ['hide_the_toolbar', true],
			]);
			if($privacy->hide_the_toolbar){
				add_filter('show_admin_bar', function($show) use($privacy){
					if(!current_user_can($privacy->get_option('hide_the_toolbar_capability', ''))){
						return false;
					}
					return $show;
				});
			}
			$privacy->add_field('hide_others_media', [
				'name' => 'Hide others media?',
				'type' => 'switch',
			]);
			$privacy->add_field('hide_others_media_capability', [
				'label_description' => "Recommended: edit_others_posts",
				'name' => 'Minimum capability required to show others media:',
				'options' => $capabilities,
				'required' => true,
				'std' => 'edit_others_posts',
				'type' => 'select_advanced',
				'visible' => ['hide_others_media', true],
			]);
			if($privacy->hide_others_media){
				add_filter('ajax_query_attachments_args', function($query) use($privacy){
					if(!current_user_can($privacy->get_option('hide_others_media_capability', ''))){
						$query['author'] = get_current_user_id();
					}
					return $query;
				});
			}
			$privacy->add_field('hide_others_posts', [
				'name' => 'Hide others posts?',
				'type' => 'switch',
			]);
			$privacy->add_field('hide_others_posts_capability', [
				'label_description' => "Recommended: edit_others_posts",
				'name' => 'Minimum capability required to show others posts:',
				'options' => $capabilities,
				'required' => true,
				'std' => 'edit_others_posts',
				'type' => 'select_advanced',
				'visible' => ['hide_others_posts', true],
			]);
			if($privacy->hide_others_posts){
				add_action('current_screen', function($current_screen) use($privacy){
					global $pagenow;
					if($pagenow == 'edit.php' and !current_user_can($privacy->get_option('hide_others_posts_capability', ''))){
						add_filter('views_' . $current_screen->id, function($views){
							foreach($views as $index => $view){
								$views[$index] = preg_replace('/ <span class="count">\([0-9]+\)<\/span>/', '', $view);
							}
							return $views;
						});
					}
				});
			}
			$privacy->add_field('hide_the_rest_api', [
				//'label_description' => "Not recommended if some of your endpoints shoud be public.",
				'name' => 'Hide the REST API?',
				'type' => 'switch',
			]);
			$privacy->add_field('hide_the_rest_api_capability', [
				'label_description' => "Recommended: read",
				'name' => 'Minimum capability required to show the REST API:',
				'options' => $capabilities,
				'required' => true,
				'std' => 'read',
				'type' => 'select_advanced',
				'visible' => ['hide_the_rest_api', true],
			]);
			if($privacy->hide_the_rest_api){
				add_filter('rest_authentication_errors', function($error) use($privacy){
					if($error){
						return $error;
					}
					if(!current_user_can($privacy->get_option('hide_the_rest_api_capability', ''))){
						return new WP_Error('rest_user_cannot_view', __('You need a higher level of permission.'), [
							'status' => 401,
						]);
					}
					return null;
				});
			}
			$privacy->add_field('hide_the_entire_site', [
				'name' => 'Hide the entire site?',
				'type' => 'switch',
			]);
			$privacy->add_field('hide_the_entire_site_capability', [
				'label_description' => "Recommended: read",
				'name' => 'Minimum capability required to show the entire site:',
				'options' => $capabilities,
				'required' => true,
				'std' => 'read',
				'type' => 'select_advanced',
				'visible' => ['hide_the_entire_site', true],
			]);
			$privacy->add_field('hide_the_entire_site_exclude_special_pages', [
				'label_description' => 'For details, see <a href="https://developer.wordpress.org/themes/basics/conditional-tags/#the-conditions-for" target="_blank">The Conditions For</a>.',
				'multiple' => true,
				'name' => 'Exclude special pages:',
				'options' => [
					'front_end' => 'The Front Page',
					'home' => 'The Main Page',
				],
				'placeholder' => 'Select pages',
				'type' => 'select_advanced',
				'visible' => ['hide_the_entire_site', true],
			]);
			$privacy->add_field('hide_the_entire_site_exclude_other_pages', [
				'multiple' => true,
				'name' => 'Exclude other pages:',
				'placeholder' => 'Select pages',
				'post_type' => 'page',
				'type' => 'post',
				'visible' => ['hide_the_entire_site', true],
			]);
			if($privacy->hide_the_entire_site){
				add_action('template_redirect', function() use($privacy){
					if(!in_array(get_the_ID(), $privacy->get_option('hide_the_entire_site_exclude_other_pages', []))){
						if(is_front_page() and in_array('front_end', (array) $privacy->hide_the_entire_site_exclude_special_pages)){
							return;
						}
						if(is_home() and in_array('home', (array) $privacy->get_option('hide_the_entire_site_exclude_special_pages', []))){
							return;
						}
						if(!is_user_logged_in()){
							auth_redirect();
						} else {
							if(!current_user_can($privacy->get_option('hide_the_entire_site_capability', ''))){
								wp_die('<h1>' . __('You need a higher level of permission.') . '</h1>' . '<p>' . __('Sorry, you are not allowed to access this page.') . '</p>', 403);
							}
						}
					}
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
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_Privacy::load();
