<?php

if(!class_exists('IFWP_Remote_Request')){
    class IFWP_Remote_Request {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function __construct($url = '', $args = []){
            $this->url = $url;
            $this->args = $args;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function delete($body = []){
            return $this->request('DELETE', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function get($body = []){
            return $this->request('GET', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function head($body = []){
            return $this->request('HEAD', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function options($body = []){
            return $this->request('OPTIONS', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function patch($body = []){
            return $this->request('PATCH', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function post($body = []){
            return $this->request('POST', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function put($body = []){
            return $this->request('PUT', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        function trace($body = []){
            return $this->request('TRACE', $body);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private $url = '', $args = [];

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function request($method = '', $body = []){
            $this->args['method'] = $method;
            if(!empty($body)){
                if(!empty($this->args['body'])){
                    $this->args['body'] = wp_parse_args($body, $this->args['body']);
                } else {
                    $this->args['body'] = $body;
                }
            }
            $response = wp_remote_request($this->url, $this->args);
            return ifwp_remote_response($response);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
