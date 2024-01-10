<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!trait_exists('Subscriptions_methods')) {
    trait Subscriptions_methods {

        public function getAllSubscriptionsStatus() {
            $result = $this->db->query("SELECT 
                estados_subscripcion.id
                FROM estados_subscripcion WHERE 
                estado IS NOT NULL 
                ORDER BY id DESC;")->result_array();
    
            if (!is_null($result) && count($result) > 0) {
                $status_ids = array();

                foreach ($result as $value) {
                    if (array_key_exists('id', $value) && !is_null($value['id'])) {
                        $value = intval($value['id']);
                        array_push($status_ids, $value);
                    }
                }

                $message = array(
                        'message' => 'Total status subscriptions ids',
                        'status' => 200,
                        'response' => $status_ids
                );
    
                return $message;
            } else {
                echo false;
            }
        }

        public function ArrSubscriptionsbyStatus(string $status_id, array | bool $status_validated, int $limit = 10, int $offset = 0) {
            try {
                if (strlen($status_id) < 1 || is_null($status_id) || empty($status_id)) {
                    $message = array(
                        'success' 	=> false,
                        'code' 		=> 404,
                        'message' 	=> 'Status id is not valid or empty',
                    );
    
                    echo json_encode($message);
                    return false;	
                }
    
                if (is_null($status_validated) || (is_bool($status_validated) && $status_validated === false)) {
                    $message = array(
                        'success' 	=> false,
                        'code' 		=> 404,
                        'message' 	=> 'Unable to validate subscription status',
                    );
    
                    echo json_encode($message);
                    return false;	
                } 
    
                if (!in_array($status_id, $status_validated)) {
                    $message = array(
                        'success' 	=> false,
                        'code' 		=> 404,
                        'message' 	=> 'Unable to match subscription id status',
                    );
    
                    echo json_encode($message);
                    return false;	
                } 
    
                $query = "SELECT 
                subscripciones.id, 
                subscripciones.nombre_subscriptor,
                subscripciones.email,
                subscripciones.fecha_subscripcion,
                tipos_cobros.tipo_cobro,
                planes.nombre as plan_nombre,
                estados_subscripcion.estado as estado_subscripcion
                FROM subscripciones 
                LEFT JOIN tipos_cobros ON (tipos_cobros.id = subscripciones.tipo_cobro_id)
                LEFT JOIN planes ON (planes.id = subscripciones.plan_id)
                LEFT JOIN estados_subscripcion ON (estados_subscripcion.id = subscripciones.estado_sub_id)
                WHERE subscripciones.estado_sub_id = ?
                ORDER BY subscripciones.id DESC;";
    
                $status_id = intval($status_id);
                $result = $this->db->query($query, array($status_id))->result_array();
    
                if (!is_null($result) && count($result) > 0) {
                    $response = array(
                        'message' 	=> 'Total Subscriptions by status',
                        'status' 	=> 200,
                        'response' 	=> $result
                    );
                    
                    return $response;
                } else {
                    $response = array(
                        'message' 	=> 'None Total Subscriptions by status',
                        'status' 	=> 200,
                        'response' 	=> []
                    );
        
                    return $response;
                }
            } catch (Exception $e) {
                $response = json_encode(array(
                    'code' 		=> $e->getCode(),
                    'message' 	=> $e->getMessage()
                ));

                return $response;
            }	
        }

    }
}