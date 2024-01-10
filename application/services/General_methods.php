<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!trait_exists('General_methods')) {    
    trait General_methods {    

        public function validateMethodRequest($allowed_methods) {
            $request_method = $this->input->server('REQUEST_METHOD');

            if (in_array(strtolower($request_method), $allowed_methods)) { 
                return true;
            } else {
                return false;
            }
        }

        public function getPlanByName(string $name) {
            $query = "SELECT 
            planes.precio 
            FROM planes WHERE 
            planes.nombre LIKE '%". $name ."%'
            ORDER BY planes.id DESC;";

            $result = $this->db->query($query)->result_array()[0];

            if (!is_null($result) && count($result) > 0) {
                $response = array(
                    'message' => 'Plan payment found',
                    'status' => 200,
                    'response' => $result
                );
    
                return $response;
            } else {
                return false;
            }
        }

    }
}