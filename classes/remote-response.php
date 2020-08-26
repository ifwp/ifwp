<?php

if(!class_exists('IFWP_Remote_Response')){
    class IFWP_Remote_Response {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($response = [], $raw_response = null){
            if(ifwp_seems_remote_response($response)){
                $this->from_array($response);
                $this->raw_data = $this->data;
                $this->maybe_json_decode();
                $this->maybe_unserialize();
            } else {
                $this->message = __('Invalid object type.');
            }
            $this->raw_response = is_null($raw_response) ? $response : $raw_response;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function code(){
            return $this->code;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function data(){
            return $this->data;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function is_successful(){ // Alias of success()
            return $this->success;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function rest_ensure_response(){
            if($this->success){
                return $this->to_wp_rest_response();
            } else {
                return $this->to_wp_error();
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function message(){
            return $this->message;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function success(){
            return $this->success;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function raw_data(){
            return $this->raw_data;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function raw_response(){
            return $this->raw_response;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function to_wp_error(){
            return new WP_Error('ifwp_remote_response_error', $this->message, [
                'status' => $this->code,
            ]);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function to_wp_rest_response(){
            return new WP_REST_Response($this->data, $this->code);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private $code = 500, $data = '', $message = '', $raw_data = '', $raw_response = null, $success = false;

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function from_array($response = []){
            $this->code = intval($response['code']);
            $this->data = $response['data'];
            $this->message = strval($response['message']);
            $this->success = boolval($response['success']);
            if(!$this->code or !$this->message or $this->success !== ifwp_is_successful($this->code)){
                $this->code = 500;
                $this->message = __('Something went wrong.');
                $this->success = false;
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function maybe_json_decode(){
            if(ifwp_seems_json($this->data)){
                $this->data = json_decode($this->data, true);
                if(json_last_error() !== JSON_ERROR_NONE){
                    $this->code = 500;
                    $this->message = json_last_error_msg();
                    $this->success = false;
                }
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function maybe_unserialize(){
            $this->data = maybe_unserialize($this->data);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
