<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!trait_exists('Lots_methods')) {
    trait Lots_methods {
        public function getAllLotStatusByID(int $id) {
            $query = "SELECT 
            estados_lote.id, 
            estados_lote.estado
            FROM estados_lote WHERE 
            estados_lote.id = ?
            ORDER BY estados_lote.id DESC;";

            $result = $this->db->query($query, array($id))->result_array();
    
            if (!is_null($result) && count($result) > 0) {
                $response = array(
                    'message' => 'Status exists',
                    'status' => 200,
                    'response' => $result
                );
    
                return $response;
            } else {
                return false;
            }
        }

        public function getTotalDetailLot(string $date) {
            try {
                if (strlen($date) > 0 && !is_null($date)) { 
                    $lotDate = date('Y-m-d', strtotime($date));
        
                    $query = "SELECT 
                    lote_cobros.cobros
                    FROM lote_cobros WHERE 
                    lote_cobros.cobros IS NOT NULL AND
                    lote_cobros.fecha IS NOT NULL AND
                    lote_cobros.estado IS NOT NULL AND
                    lote_cobros.fecha <= '". $lotDate ." 23:59:59' AND
                    lote_cobros.fecha >= '". $lotDate ." 00:00:00'
                    ORDER BY lote_cobros.id DESC;";
        
                    $result = $this->db->query($query)->result_array();
        
                    if (!is_null($result) && count($result) > 0) {
                        $message = array(
                            'message' => 'Lot found successfully by date',
                            'status' => 200,
                            'response' => json_decode($result[0]['cobros'])
                        );
            
                        echo json_encode($message);
                    } else {
                        $message = array(
                            'message' => 'Lot not found by date',
                            'status' => 404,
                            'response' => []
                        );
            
                        echo json_encode($message);
                    }
                } else {
                    $message = array(
                        'message' => 'Date parameter invalid',
                        'status' => 404,
                        'response' => []
                    );
        
                    echo json_encode($message);
                }
            } catch (Exception $e) {
                echo json_encode(array(
                    'code' 		=> $e->getCode(),
                    'message' 	=> $e->getMessage()
                ));
                return false;
            }
        }

        public function getAmountAndPaymentDetailLot(string $date) {
            try {
                if (strlen($date) > 0 && !is_null($date)) { 
                    $lotDate = date('Y-m-d', strtotime($date));
        
                    $query = "SELECT 
                    lote_cobros.cobros
                    FROM lote_cobros WHERE 
                    lote_cobros.cobros IS NOT NULL AND
                    lote_cobros.fecha IS NOT NULL AND
                    lote_cobros.estado IS NOT NULL AND
                    lote_cobros.fecha <= '". $lotDate ." 23:59:59' AND
                    lote_cobros.fecha >= '". $lotDate ." 00:00:00'
                    ORDER BY lote_cobros.id DESC;";
        
                    $result = $this->db->query($query)->result_array();//[0]['cobros'];
                   
                    if (!isset($result[0]) || count($result[0]) <= 0 || !array_key_exists('cobros', $result[0])) {
                        $message = array(
                            'message' => 'Lot not found by date',
                            'status' => 404,
                            'response' => []
                        );
            
                        echo json_encode($message);
                        return false;
                    } 
                    
                    // decode lot
                    $result = $result[0]['cobros'];
                    $result = json_decode($result);
                    
                    if (!is_null($result) && count($result) > 0) {
                        // payments quantity for lot 
                        $paymentsForLot = (int) (isset($result) && !is_null($result)) ? count($result) : 0;
                        
                        // total amount for lot
                        $totalAmountForLots = 0;

                        foreach ($result as $lot) {
                            $planType = $this->getPlanByName($lot->plan_nombre)['response'];

                            if (is_bool($planType) && $planType == false) {
                                $message = array(
                                    'message' => 'Plan type not matched',
                                    'status' => 404,
                                    'response' => []
                                );
                    
                                echo json_encode($message);
                            }

                            if (is_array($planType) && array_key_exists('precio', $planType)) {
                                $planTypePrice = doubleval($planType['precio']);
                                $totalAmountForLots = $totalAmountForLots + $planTypePrice;
                            }
                        }
                        
                        $message = array(
                            'message' => 'Total amount & total quantity payments for lot',
                            'status' => 200,
                            'response' => [
                                'Total amount' => $totalAmountForLots,
                                'Total payments' => $paymentsForLot
                            ]
                        );


                        echo json_encode($message);
                    } else {
                        $message = array(
                            'message' => 'Lot not found by date',
                            'status' => 404,
                            'response' => []
                        );
            
                        echo json_encode($message);
                    }
                } else {
                    $message = array(
                        'message' => 'Date parameter invalid',
                        'status' => 404,
                        'response' => []
                    );
        
                    echo json_encode($message);
                }
            } catch (Exception $e) {
                echo json_encode(array(
                    'code' 		=> $e->getCode(),
                    'message' 	=> $e->getMessage()
                ));
                return false;
            }
        }
    }
}