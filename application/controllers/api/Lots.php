<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'services/General_methods.php';
include APPPATH . 'services/Lots_methods.php';
include APPPATH . 'services/Subscriptions_methods.php';

class Lots extends CI_Controller {
	 
	use General_methods;  
	use Lots_methods;
	use Subscriptions_methods;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Lot_model');	
		$this->load->model('Subscription_model');		
    }

    public function getAllLotStatus()
	{
		$result = $this->Lot_model->getAllLotStatus();
		return $result;
	}

    public function getAllLotPayments()
	{
		$result = $this->Lot_model->getAllLotPayments();
		return $result;
	}

	public function getDetailLot()
	{
		$allowed_methods = array(
			'post'
		);
		$requestValidate = $this->validateMethodRequest($allowed_methods);
		
		$data = ($this->input->post()) ? $this->input->post() : array();

		if ((is_bool($requestValidate) && $requestValidate === false) && is_null($data) || count($data) == 0) {
			echo json_encode(
				array(
					'success' => false,
					'code' => 404,
					'message' => 'Invalid params or invalid method request for subscription store',
				)
			);

			return false;
		}

		$result = $this->getTotalDetailLot($data['date']);
		return $result;
	}

	public function getAmountAndPaymentLot()
	{
		$allowed_methods = array(
			'post'
		);
		$requestValidate = $this->validateMethodRequest($allowed_methods);
		
		$data = ($this->input->post()) ? $this->input->post() : array();

		if ((is_bool($requestValidate) && $requestValidate === false) && is_null($data) || count($data) == 0) {
			echo json_encode(
				array(
					'success' => false,
					'code' => 404,
					'message' => 'Invalid params or invalid method request for subscription store',
				)
			);

			return false;
		}

		$result = $this->getAmountAndPaymentDetailLot($data['date']);
		return $result;
	}

	public function storeLot() {
		$allowed_methods = array(
			'post'
		);
		$requestValidate = $this->validateMethodRequest($allowed_methods);

		if (is_bool($requestValidate) && $requestValidate === false) {
			echo json_encode(
				array(
					'success' => false,
					'code' => 404,
					'message' => 'Invalid method request for store lot',
				)
			);

			return false;
		}

		$subscriptionStatus = $this->getAllSubscriptionsStatus();
		$status_id = 1; // subscriptions actives
		
		if (is_array($subscriptionStatus) && array_key_exists('response', $subscriptionStatus) && count($subscriptionStatus['response']) > 0) {
			$subscriptionStatus = $subscriptionStatus['response'];
			$subscriptionActives = $this->ArrSubscriptionsbyStatus($status_id, $subscriptionStatus)['response'];
			$init_status_lot = $this->getAllLotStatusByID(1)['response'];
			
			if (!is_array($subscriptionActives) || is_null($subscriptionActives) || count($subscriptionActives) <= 0) {
				return false;
			}

			if (is_bool($init_status_lot) && $init_status_lot === false) {
				return false;
			}	

			$result = $this->Lot_model->storeLot($subscriptionActives, $init_status_lot);
			return $result;
		} 

		return false;
	}
	
}