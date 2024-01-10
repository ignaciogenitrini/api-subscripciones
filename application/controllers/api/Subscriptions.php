<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . 'services/General_methods.php';
include APPPATH . 'services/Subscriptions_methods.php';

class Subscriptions extends CI_Controller {

	use General_methods;  
	use Subscriptions_methods;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Subscription_model');		
    }

	public function storeSubscription() {
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

		$result = $this->Subscription_model->storeSubscription($data);
		return $result;
	}

    public function getAllSubscriptions()
	{
		$result = $this->Subscription_model->getAllSubscriptions();
		return $result;
	}

    public function getSubscriptionsbyStatus($status_id)
	{	
		$allowed_methods = array(
			'get'
		);
		$requestValidate = $this->validateMethodRequest($allowed_methods);

		if ($requestValidate) {
			$subscriptionStatus = $this->getAllSubscriptionsStatus();
			
			if (is_array($subscriptionStatus) && array_key_exists('response', $subscriptionStatus) && count($subscriptionStatus['response']) > 0) {
				$subscriptionStatus = $subscriptionStatus['response'];
				$result = $this->ArrSubscriptionsbyStatus($status_id, $subscriptionStatus);
			} else {
				$result = $this->ArrSubscriptionsbyStatus($status_id, false);
			}
			
			if (!is_bool($result) && $result !== false) {
				echo json_encode(
					array(
						'success' 	=> true,
						'code' 		=> 200,
						'response' 	=> $result
					)
				);
	
				return true;
			}
		} else {
			echo json_encode(
				array(
					'success' 	=> false,
					'code' 		=> 405,
					'message' 	=> 'Method request not allowed',
				)
			);
			return false;
		}
	}
}