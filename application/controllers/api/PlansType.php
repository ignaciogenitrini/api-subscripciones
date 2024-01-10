<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PlanType extends CI_Controller {
	 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Plantype_model');		
    }

    public function getAllPlanTypes()
	{
		$result = $this->Plantype_model->getAllPlanTypes();
		return $result;
	}
}