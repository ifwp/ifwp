<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// classes
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_HTTP')){
    class IFWP_HTTP {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // static public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function ifwp_loaded(){
            $general = ifwp_tab('', 'HTTP');
            $general->add_field('support_authorization_header', [
				'label_description' => 'For details, see <a href="https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/" target="_blank">Authentication</a>.',
                'name' => 'Support authorization header?',
            	'type' => 'switch',
            ]);
            if($general->support_authorization_header){
            	add_filter('mod_rewrite_rules', [__CLASS__, 'mod_rewrite_rules']);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function load(){
            add_action('ifwp_loaded', [__CLASS__, 'ifwp_loaded']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        static public function mod_rewrite_rules($rules){
            return str_replace("RewriteEngine On\n", "RewriteEngine On\nRewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]\n", $rules);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // dynamic protected
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected $url = '', $args = [];

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function request($method = '', $body = []){
            $this->args['method'] = $method;
            if(!empty($body)){
                if(!empty($this->args['body'])){
                    $this->args['body'] = wp_parse_args($body, $this->args['body']);
                } else {
                    $this->args['body'] = $body;
                }
            }
            $response = wp_remote_request($this->url, $this->args);
            return ifwp_response($response);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // dynamic public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($url = '', $args = []){
            $this->url = $url;
            $this->args = $args;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function delete($body = []){
            return $this->request('DELETE', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function get($body = []){
            return $this->request('GET', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function head($body = []){
            return $this->request('HEAD', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function options($body = []){
            return $this->request('OPTIONS', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function patch($body = []){
            return $this->request('PATCH', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function post($body = []){
            return $this->request('POST', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function put($body = []){
            return $this->request('PUT', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function trace($body = []){
            return $this->request('TRACE', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!class_exists('IFWP_Response')){
    class IFWP_Response {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // dynamic protected
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function from_array($response = []){
            $this->code = intval($response['code']);
            $this->data = $response['data'];
            $this->message = strval($response['message']);
            $this->success = boolval($response['success']);
            if(!$this->code or !$this->message or $this->success != ifwp_seems_successful($this->code)){
                $this->code = 500;
                $this->message = __('Something went wrong.');
                $this->success = false;
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function maybe_json_decode(){
            if(ifwp_seems_json($this->data)){
                $this->data = json_decode($this->data, true);
                if(json_last_error() != JSON_ERROR_NONE){
                    $this->code = 500;
                    $this->message = json_last_error_msg();
                    $this->success = false;
                }
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function maybe_unserialize(){
            $this->data = maybe_unserialize($this->data);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // dynamic public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public $code = 500, $data = '', $message = '', $raw_data = '', $raw_response = null, $success = false;

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($response = null){
            $code = 500;
            $data = '';
            $message = '';
            $success = false;
            switch(true){
                case ifwp_seems_response($response):
                    $code = $response['code'];
                    $data = $response['data'];
                    $message = $response['message'];
                    $success = $response['success'];
                    break;
                case is_a($response, 'Requests_Exception'):
                    $data = $response->getData();
                    $message = $response->getMessage();
                    break;
                case is_a($response, 'Requests_Response'):
                    $code = $response->status_code;
                    $data = $response->body;
                    $message = get_status_header_desc($code);
                    $success = ifwp_seems_successful($code);
                    break;
                case is_wp_error($response):
                    $data = $response->get_error_data();
                    $message = $response->get_error_message();
                    break;
                case ifwp_seems_wp_http_requests_response($response):
                    $code = wp_remote_retrieve_response_code($response);
                    $data = wp_remote_retrieve_body($response);
                    $message = wp_remote_retrieve_response_message($response);
                    if(!$message){
                        $message = get_status_header_desc($code);
                    }
                    $success = ifwp_seems_successful($code);
                    break;
                default:
                    $message = __('Invalid object type.');
            }
            $this->raw_data = $data;
            $this->raw_response = $response;
            $this->from_array(compact('code', 'data', 'message', 'success'));
            $this->maybe_json_decode();
            $this->maybe_unserialize();
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function rest_ensure_response(){
            if($this->success){
                return $this->to_wp_rest_response();
            } else {
                return $this->to_wp_error();
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function to_wp_error(){
            if(is_wp_error($this->raw_response)){
                return $this->raw_response;
            } else {
                return new WP_Error('v_remote_response_error', $this->message, [
                    'status' => $this->code,
                ]);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function to_wp_rest_response(){
            return new WP_REST_Response($this->data, $this->code);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// functions
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_download')){
    function ifwp_download($url = '', $args = [], $parent = 0){
        if(!$url){
            return new WP_Error('ifwp_download_url_required', 'URL is required.');
        }
        $wp_upload_dir = wp_upload_dir();
        if(empty($args['filename'])){
            $filename = preg_replace('/\?.*/', '', basename($url));
            $args['filename'] = '';
        } else {
            $filename = basename($args['filename']);
            if(strpos($args['filename'], $wp_upload_dir['basedir']) !== 0){ // provided dir is outside uploads
                $args['filename'] = ''; // so just take the provided filename
            }
        }
        $filetype_and_ext = wp_check_filetype($filename);
        $type = $filetype_and_ext['type'];
        if(!$type){
            return new WP_Error('ifwp_download_invalid_filename', 'Invalid filename.');
        }
        if(!$args['filename']){
            $filename = wp_unique_filename($wp_upload_dir['path'], $filename);
            $args['filename'] = trailingslashit($wp_upload_dir['path']) . $filename;
        }
        $args['stream'] = true;
        $max_execution_time = ini_get('max_execution_time');
        if(empty($args['timeout'])){ // A value of 0 will allow an unlimited timeout.
            $args['timeout'] = $max_execution_time;
        }
        if($args['timeout'] and $max_execution_time and $args['timeout'] == $max_execution_time){
            $args['timeout'] = (int) $args['timeout'] -= 10; // Prevents timeout error
        }
        if(ifwp_seems_cloudflare()){
            if($args['timeout'] === 0 or $args['timeout'] > 90){
                $args['timeout'] = 90; // Prevents error 524: https://support.cloudflare.com/hc/en-us/articles/115003011431#524error
            }
        }
        $response = ifwp_http($url, $args)->get();
        if(!$response->success){
            @unlink($args['filename']);
            return $response->to_wp_error();
        }
        $filetype_and_ext = wp_check_filetype_and_ext($args['filename'], $filename);
        $type = $filetype_and_ext['type'];
        if(!$type){
            @unlink($args['filename']);
            return new WP_Error('ifwp_download_invalid_filetype', 'Invalid filetype.');
        }
        $post_id = wp_insert_attachment([
            'guid' => str_replace($wp_upload_dir['basedir'], $wp_upload_dir['baseurl'], $args['filename']),
            'post_mime_type' => $type,
            'post_status' => 'inherit',
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
        ], $args['filename'], $parent, true);
        if(is_wp_error($post_id)){
            @unlink($args['filename']);
            return $post_id;
        }
        return $post_id;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_http')){
    function ifwp_http($url = '', $args = []){
        return new IFWP_HTTP($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_response')){
    function ifwp_response($response = null){
        return new IFWP_Response($response);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_response_error')){
    function ifwp_response_error($message = '', $code = 500, $data = ''){
        if(!$message){
            $message = get_status_header_desc($code);
        }
        $success = false;
        return ifwp_response(compact('code', 'data', 'message', 'success'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_response_success')){
    function ifwp_response_success($data = '', $code = 200, $message = ''){
        if(!$message){
            $message = get_status_header_desc($code);
        }
        $success = true;
        return ifwp_response(compact('code', 'data', 'message', 'success'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_cloudflare')){
    function ifwp_seems_cloudflare(){
        return !empty($_SERVER['HTTP_CF_RAY']);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_response')){
    function ifwp_seems_response($response = []){
        return ifwp_array_keys_exist(['code', 'data', 'message', 'success'], $response);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_successful')){
    function ifwp_seems_successful($code = 0){
        return ($code >= 200 and $code < 300);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_wp_http_requests_response')){
    function ifwp_seems_wp_http_requests_response($response = []){
        return (ifwp_array_keys_exist(['headers', 'body', 'response', 'cookies', 'filename', 'http_response'], $response) and is_a($response['http_response'], 'WP_HTTP_Requests_Response'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// loader
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

IFWP_HTTP::load();
