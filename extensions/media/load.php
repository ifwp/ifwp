<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Media')){
    class IFWP_Media {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
			$media = ifwp_tab('', [
				'label' => 'Media',
				'icon' => 'dashicons-admin-media',
			]);
			$media->add_field('add_larger_image_sizes', [
				'label_description' => 'Image sizes: hd and full-hd.',
				'name' => 'Add larger image sizes?',
				'type' => 'switch',
			]);
			if($media->add_larger_image_sizes){
				add_image_size('hd', 1280, 1280);
				add_image_size('full-hd', 1920, 1920);
				add_filter('image_size_names_choose', function($sizes){
					$sizes['hd'] = 'HD';
					$sizes['full-hd'] = 'Full HD';
					return $sizes;
				});
			}
			$media->add_field('remove_accents', [
				'label_description' => 'Context: filenames.',
				'name' => 'Remove accents?',
				'type' => 'switch',
			]);
			if($media->remove_accents){
				add_filter('sanitize_file_name', 'remove_accents');
			}
			$media->add_field('solve_file_type_conflicts', [
				'label_description' => 'File types: audio and video.',
				'name' => 'Solve file type conflicts?',
				'type' => 'switch',
			]);
			if($media->solve_file_type_conflicts){
				add_filter('wp_check_filetype_and_ext', function($wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime){
					if($wp_check_filetype_and_ext['ext'] and $wp_check_filetype_and_ext['type']){
						return $wp_check_filetype_and_ext;
					}
					if(strpos($real_mime, 'audio/') === 0 or strpos($real_mime, 'video/') === 0){
						$filetype = wp_check_filetype($filename);
						if(in_array(substr($filetype['type'], 0, strcspn($filetype['type'], '/')), ['audio', 'video'])){
							$wp_check_filetype_and_ext['ext'] = $filetype['ext'];
							$wp_check_filetype_and_ext['type'] = $filetype['type'];
						}
					}
					return $wp_check_filetype_and_ext;
				}, 10, 5);
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

IFWP_Media::load();
