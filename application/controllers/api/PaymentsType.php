<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PaymentType extends CI_Controller {
	 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Paymenttype_model');		
    }

    public function getAllPaymentTypes()
	{
		$result = $this->Paymenttype_model->getAllPaymentTypes();
		return $result;
	}
}