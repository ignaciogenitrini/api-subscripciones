<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paymenttype_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();	
	}

	public function getAllPaymentTypes() {
		$result = $this->db->query("SELECT 
			tipos_cobros.id, 
			tipos_cobros.tipo_cobro
			FROM tipos_cobros WHERE 
			tipo_cobro IS NOT NULL 
			ORDER BY id DESC;")->result_array();

		if (!is_null($result) && count($result) > 0) {
			$message = array(
					'message' => 'Total payment types',
					'status' => 200,
					'response' => $result
			);

			echo json_encode($message);
		} else {
			echo false;
		}
	}
}