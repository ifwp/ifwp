<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('_ifwp_download_google_api')){
    function _ifwp_download_google_api(){
        $dir = trailingslashit(IFWP_BASEDIR) . 'google-api';
        if(file_exists($dir)){
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
            $version = '5.4';
        }
        $url = 'https://github.com/googleapis/google-api-php-client/releases/download/v2.7.0/google-api-php-client-v2.7.0-PHP' . $version . '.zip';
        $attachment_id = ifwp_remote_download($url);
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
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('_ifwp_use_google_api')){
    function _ifwp_use_google_api(){
        if(class_exists('\Google_Client')){
            return true;
        }
        $dir = trailingslashit(IFWP_BASEDIR) . 'google-api';
        if(!file_exists($dir)){
            if(!_ifwp_download_google_api()){
                return false;
            }
        }
        $autoload = trailingslashit($dir) . 'vendor/autoload.php';
        require_once($autoload);
        return true;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_attachment_to_google_drive')){
    function ifwp_attachment_to_google_drive($atts = []){
        if(!_ifwp_use_google_api()){
            return false;
        }
        $atts = shortcode_atts([
            'attachment' => null,
            'chunk_size' => 0,
            'google_client' => null,
            'parents' => [],
        ], $atts);
        $post = get_post($atts['attachment']);
        if($post->post_type != 'attachment'){
            return false;
        }
        $attached_file = get_attached_file($post->ID);
        if(!file_exists($attached_file)){
            return false;
        }
        $chunk_size = wp_convert_hr_to_bytes($atts['chunk_size']);
        if(!$chunk_size){
            $chunk_size = 64 * MB_IN_BYTES;
        }
        $client = $atts['google_client'];
        if(!is_a($client, 'Google_Client')){
            return false;
        }
        $parents = $atts['parents'];
        wp_raise_memory_limit('admin');
        /*$current_limit = ini_get('memory_limit');
        $current_limit_int = wp_convert_hr_to_bytes($current_limit);
        $memory_size = ifwp_get_memory_size();*/
        try {
			$client->addScope('https://www.googleapis.com/auth/drive.file');
            $client->setDefer(true);
            $service = ifwp_new('Google_Service_Drive', $client);
            $file = ifwp_new('Google_Service_Drive_DriveFile');
            $file->setName($post->post_title);
            if($parents){
                if(!is_array($parents)){
                    $parents = [(string) $parents];
                } else {
                    $parents = array_slice($parents, 0, 1);
                }
                $file->setParents($parents);
            }
			$request = $service->files->create($file);
            $media = ifwp_new('Google_Http_MediaFileUpload', $client, $request, $post->post_mime_type, null, true, $chunk_size);
			$media->setFileSize(filesize($attached_file));
			$status = false;
			$handle = fopen($attached_file, 'rb');
			while(!$status and !feof($handle)){
				$chunk = ifwp_read_file_chunk($handle, $chunk_size);
				$status = $media->nextChunk($chunk);
			}
            fclose($handle);
            return $status;
		} catch (Throwable $t){ // Executed only in PHP 7, will not match in PHP 5
			return false;
		} catch (Exception $e){ // Executed only in PHP 5, will not be reached in PHP 7
			return false;
		}
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
