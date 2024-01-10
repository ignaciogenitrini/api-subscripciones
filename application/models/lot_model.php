<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lot_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();	
	}

	public function storeLot(array $subscriptions, array $status) {
		if (is_null($subscriptions) || count($subscriptions) < 0) {
			$message = array(
				'success' 	=> false,
				'code' 		=> 404,
				'message' 	=> 'Tolal subscriptions empty',
			);

			echo json_encode($message);
			return false;	
		}

		if (is_null($status) || count($status) < 0) {
			$message = array(
				'success' 	=> false,
				'code' 		=> 404,
				'message' 	=> 'Lot status empty',
			);

			echo json_encode($message);
			return false;	
		}

		$lotStatus = intval($status[0]['id']);
		$lotDate = date('Y-m-d H:i:s');

		$newLot = array(
			'cobros' 	=> json_encode($subscriptions),
			'fecha' 	=> $lotDate,
			'estado' 	=> $lotStatus,
		);

		$this->db->insert('lote_cobros', $newLot);

		if ($this->db->affected_rows() > 0) {		
			$message = array(
				'success' 		=> true,
				'code' 			=> 200,
				'message' 		=> 'Lot stored successfully',
			);

			echo json_encode($message);
			return true;
		} else {
			$message = array(
				'success' 	=> false,
				'code' 		=> 404,
				'message' 	=> 'Lot stored failed',
				'response' 	=> array()
			);

			echo json_encode($message);
			return false;
		}
	}
	
	public function getAllLotStatus() {
		$result = $this->db->query("SELECT 
			estados_lote.id, 
			estados_lote.estado
			FROM estados_lote WHERE 
			estados_lote.estado IS NOT NULL 
			ORDER BY estados_lote.id DESC;")->result_array();

		if (!is_null($result) && count($result) > 0) {
			$message = array(
					'message' => 'Total lot status',
					'status' => 200,
					'response' => $result
			);

			echo json_encode($message);
		} else {
			$message = array(
				'message' => 'Total lot status',
				'status' => 200,
				'response' => []
			);

			echo json_encode($message);
		}
	}

	public function getAllLotPayments() {
		$result = $this->db->query("SELECT 
		lote_cobros.id, 
		lote_cobros.cobros,
		lote_cobros.fecha,
		lote_cobros.estado
		FROM lote_cobros WHERE 
		lote_cobros.cobros IS NOT NULL AND
		lote_cobros.fecha IS NOT NULL AND
		lote_cobros.estado IS NOT NULL 
		ORDER BY lote_cobros.id DESC;")->result_array();

		if (!is_null($result) && count($result) > 0) {
			$message = array(
					'message' => 'Total lot payments',
					'status' => 200,
					'response' => $result
			);

			echo json_encode($message);
		} else {
			$message = array(
				'message' => 'Total lot payments',
				'status' => 200,
				'response' => []
			);

			echo json_encode($message);
		}
	}
}