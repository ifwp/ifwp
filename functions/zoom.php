<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_generate_zoom_jwt')){
	function ifwp_generate_zoom_jwt($api_key = '', $api_secret = ''){
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

if(!function_exists('ifwp_get_zoom_recording_by_uuid')){
	function ifwp_get_zoom_recording_by_uuid($uuid = ''){
        $download_token = ifwp_generate_zoom_jwt();
        $url = ifwp_prepare('https://api.zoom.us/v2/meetings/%s/recordings', urlencode(urlencode($uuid)));
        $args = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $download_token,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ];
        $response = ifwp_remote_get($url, $args);
        if($response->is_successful()){
            return $response->data();
        } else {
            return $response->to_wp_error();
        }
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_zoom_file')){
    function ifwp_seems_zoom_file($file = []){
        if(!ifwp_array_keys_exist(['meeting_id', 'recording_start', 'recording_end', 'file_type', 'download_url', 'deleted_time'], $recording)){
            return false;
        }
        if(!in_array($file['file_type'], ['CC', 'TIMELINE'])){
            if(!ifwp_array_keys_exist(['id', 'status', 'file_size', 'recording_type', 'play_url'], $recording)){
                return false;
            }
        }
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_insert_zoom_file')){
    function ifwp_maybe_insert_zoom_file($file = [], $recording = null){
        $recording = get_post($recording);
        if(!$recording or $recording->post_type != 'ifwp-zoom-recording'){
            return 0;
        }
        if(!ifwp_seems_zoom_file($file)){
            return 0;
        }
        /*if(!in_array($file['file_type'], ['MP4', 'M4A', 'TIMELINE', 'TRANSCRIPT', 'CHAT', 'CC'])){
            return 0;
        }
        if(!in_array($file['file_type'], ['CC', 'TIMELINE'])){
            if(!in_array($file['recording_type'], ['shared_screen_with_speaker_view(CC)', 'shared_screen_with_speaker_view', 'shared_screen_with_gallery_view', 'speaker_view', 'gallery_view', 'shared_screen', 'audio_only', 'audio_transcript', 'chat_file', 'TIMELINE'])){
                return 0;
            }
        }*/
        $md5 = md5(maybe_serialize($file));
        $args = [
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'md5',
                    'value' => $md5,
                ],
            ],
            'post_parent' => $recording->ID,
            'post_status' => 'private',
            'post_type' => 'ifwp-zoom-file',
            'posts_per_page' => 1,
        ];
        $post_ids = get_posts($args);
        if($post_ids){
            return $post_ids[0];
        } else {
            $postarr = [
                'post_parent' => $recording->ID,
                'post_status' => 'private',
                'post_title' => $md5,
                'post_type' => 'ifwp-zoom-file',
            ];
            $post_id = wp_insert_post($postarr);
            if(!$post_id){
                return 0;
            }
            update_post_meta($post_id, 'md5', $md5);
            foreach($file as $key => $value){
                update_post_meta($post_id, $key, $value);
            }
            return $post_id;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_insert_zoom_recording')){
    function ifwp_maybe_insert_zoom_recording($recording = []){
        if(empty($recording_object['uuid'])){
            return 0;
        }
        $uuid = $recording_object['uuid'];
        $args = [
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'uuid',
                    'value' => $uuid,
                ],
            ],
            'post_status' => 'private',
            'post_type' => 'ifwp-zoom-recording',
            'posts_per_page' => 1,
        ];
        $post_ids = get_posts($args);
        if($post_ids){
            return $post_ids[0];
        } else {
            $postarr = [
                'post_status' => 'private',
                'post_title' => $uuid,
                'post_type' => 'ifwp-zoom-recording',
            ];
            $post_id = wp_insert_post($postarr);
            if(!$post_id){
                return 0;
            }
            foreach($recording as $key => $value){
                update_post_meta($post_id, $key, $value);
            }
            return $post_id;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_register_zoom_file_post_type')){
	function ifwp_register_zoom_file_post_type(){
        register_post_type('ifwp-zoom-file', [
            'capability_type' => 'page',
            'labels' => [
                'name' => 'Zoom Files',
                'singular_name' => 'Zoom File'
            ],
            'menu_icon' => 'dashicons-media-video',
            'show_in_admin_bar' => false,
            'show_ui' => true,
            'supports' => ['custom-fields', 'title'],
        ]);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_register_zoom_recording_post_type')){
	function ifwp_register_zoom_recording_post_type(){
        register_post_type('ifwp-zoom-recording', [
            'capability_type' => 'page',
            'labels' => [
                'name' => 'Zoom Recordings',
                'singular_name' => 'Zoom Recording'
            ],
            'menu_icon' => 'dashicons-video-alt2',
            'show_in_admin_bar' => false,
            'show_ui' => true,
            'supports' => ['custom-fields', 'title'],
        ]);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_zoom_file_to_attachment')){
    function ifwp_zoom_file_to_attachment($file = null){
        $file = get_post($file);
        if(!$file or $file->post_type != 'ifwp-zoom-file'){
            return 0;
        }
        $args = [
            'fields' => 'ids',
            'post_parent' => $file->ID,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'posts_per_page' => 1,
        ];
        $post_ids = get_posts($args);
        if($post_ids){
            return $post_ids[0];
        } else {
            $start_time = get_post_meta($file->ID, 'start_time', true);
            $recording_type = get_post_meta($file->ID, 'recording_type', true);
            if(!$recording_type){
                $recording_type = $file_type;
            }
            $id = get_post_meta($file->ID, 'id', true);
            $file_type = get_post_meta($file->ID, 'file_type', true);
            $file_type = strtolower($file_type);
            $extension = ifwp_is_extension_allowed($file_type) ? $file_type : 'txt';
            $filename = date('Y-m-d-H-i-s', strtotime($start_time)) . '-' . str_replace('_', '-', $recording_type) . '-' . $id . '.' . $extension;
            $attachment_id = ifwp_remote_download($url, [
                'filename' => $filename,
            ], $file->ID);
            if(is_wp_error($attachment_id)){
                $empty_message = sprintf(__('File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your %1$s file or by %2$s being defined as smaller than %3$s in %1$s.'), 'php.ini', 'post_max_size', 'upload_max_filesize');
                if($attachment_id->get_error_message() == $empty_message){
                    // no es error
                }
                return 0;
            }
            return $attachment_id;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
