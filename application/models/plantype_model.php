<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plantype_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();	
	}
	
	public function getAllPlanTypes() {
		$result = $this->db->query("SELECT 
			planes.id, 
			planes.nombre,
			planes.precio
			FROM planes WHERE 
			nombre IS NOT NULL AND
			precio IS NOT NULL 
			ORDER BY id DESC;")->result_array();

		if (!is_null($result) && count($result) > 0) {
			$message = array(
					'message' => 'Total plan types',
					'status' => 200,
					'response' => $result
			);

			echo json_encode($message);
		} else {
			echo false;
		}
	}
}