<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SubscriptionsStatus extends CI_Controller {
	 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Subscriptionstatus_model');		
    }

    public function getAllSubscriptionsStatus()
	{
		$result = $this->Subscriptionstatus_model->getAllSubscriptionsStatus();
		return $result;
	}
}