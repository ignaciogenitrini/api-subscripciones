<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptionstatus_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();	
	}

	public function getAllSubscriptionsStatus() {
		$result = $this->db->query("SELECT 
			estados_subscripcion.id, 
			estados_subscripcion.estado
			FROM estados_subscripcion WHERE 
			estado IS NOT NULL 
			ORDER BY id DESC;")->result_array();

		if (!is_null($result) && count($result) > 0) {
			$message = array(
					'message' => 'Total status subscriptions',
					'status' => 200,
					'response' => $result
			);

			echo json_encode($message);
		} else {
			echo false;
		}
	}
}