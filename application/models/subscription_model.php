<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscription_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();	
		$this->load->library('form_validation');
	}

	public function storeSubscription($data) {
		$email_subscription = (isset($data['email_subscription'])) ? $data['email_subscription'] : null;
		
		if (!is_null($email_subscription) && strlen($email_subscription) > 0) {
			$emailExists = $this->getSubscriptionsbyEmail($email_subscription);
			
			if (is_bool($emailExists) && $emailExists == true) {
				$message = array(
					'success' 	=> false,
					'code' 		=> 404,
					'message' 	=> 'Email address at least one active subscription',
				);

				echo json_encode($message);
				return false;	
			}
		}

		$this->form_validation->set_rules('name_subscription', 'Name subscription', 'required');
		$this->form_validation->set_rules('payment_type_id', 'Payment type id subscription', 'required');
		$this->form_validation->set_rules('plan_id', 'Plan type id subscription', 'required');
		$this->form_validation->set_rules('status_sub_id', 'Status subscription', 'required');

		if ($this->form_validation->run() == FALSE) {
			$message = array(
				'success' 	=> false,
				'code' 		=> 404,
				'message' 	=> 'Params request validation failed',
			);

			echo json_encode($message);
			return false;	
		} else {			
			$name_subscription = (isset($data['name_subscription'])) ? $data['name_subscription'] : null;
			$payment_type_id = (isset($data['payment_type_id'])) ? intval($data['payment_type_id']) : null;
			$plan_id = (isset($data['plan_id'])) ? intval($data['plan_id']) : null;
			$status_sub_id = (isset($data['status_sub_id'])) ? intval($data['status_sub_id']) : null;
			$date_subscription = date('Y-m-d H:i:s');

			if (!is_null($name_subscription) && !is_null($payment_type_id) && !is_null($plan_id) && !is_null($status_sub_id)) {
				$planExists = $this->getPlanByID($plan_id);
				$paymentTypeExists = $this->getPaymentTypeByID($payment_type_id);
				$statusSubExists = $this->getStatusSubByID($status_sub_id);

				if (is_bool($planExists) && $planExists === false) {
					$message = array(
						'success' 	=> false,
						'code' 		=> 404,
						'message' 	=> 'Plan subscription not found',
					);
		
					echo json_encode($message);
					return false;	
				}

				if (is_bool($paymentTypeExists) && $paymentTypeExists === false) {
					$message = array(
						'success' => false,
						'code' => 404,
						'message' => 'Payment type not found',
					);
		
					echo json_encode($message);
					return false;	
				}

				if (is_bool($statusSubExists) && $statusSubExists === false) {
					$message = array(
						'success' => false,
						'code' => 404,
						'message' => 'Status subscription not found',
					);
		
					echo json_encode($message);
					return false;
				}

				$newSubscription = array(
					'nombre_subscriptor' 	=> $name_subscription,
					'email' 				=> $email_subscription,
					'tipo_cobro_id' 		=> $payment_type_id,
					'plan_id' 				=> $plan_id,
					'estado_sub_id' 		=> $status_sub_id,
					'fecha_subscripcion' 	=> $date_subscription,
				);
		
				$this->db->insert('subscripciones', $newSubscription);
		
				if ($this->db->affected_rows() > 0) {		
					$message = array(
						'success' 		=> true,
						'code' 			=> 200,
						'message' 		=> 'Subscription stored successfully',
						'subscriptor' 	=> $email_subscription
					);
		
					echo json_encode($message);
					return true;
				} else {
					$message = array(
						'success' 	=> false,
						'code' 		=> 404,
						'message' 	=> 'Subscription stored failed',
						'response' 	=> array()
					);
		
					echo json_encode($message);
					return false;
				}
			} else {
				$message = array(
					'success' 	=> false,
					'code' 		=> 404,
					'message' 	=> 'Params request validation failed',
				);
	
				echo json_encode($message);
				return false;	
			}
		}
	}

	public function getAllSubscriptions() {
		$result = $this->db->query("SELECT 
			subscripciones.id, 
			subscripciones.nombre_subscriptor,
			subscripciones.email,
			subscripciones.tipo_cobro_id,
			subscripciones.plan_id,
			subscripciones.estado_sub_id,
			subscripciones.fecha_subscripcion
			FROM subscripciones WHERE 
			nombre_subscriptor IS NOT NULL AND 
			email IS NOT NULL AND 
			tipo_cobro_id IS NOT NULL AND 
			plan_id IS NOT NULL AND 
			estado_sub_id IS NOT NULL AND 
			fecha_subscripcion IS NOT NULL  
			ORDER BY id DESC;")->result_array();

		if (!is_null($result) && count($result) > 0) {
			$message = array(
					'message' 	=> 'Total subscriptions',
					'status' 	=> 200,
					'response' 	=> $result
			);

			echo json_encode($message);
			return true;
		} else {
			echo false;
			return false;
		}
	}

	private function getSubscriptionsbyEmail(string $email) {
		try {
			if (strlen($email) < 1 || is_null($email) || empty($email)) {
				$message = array(
					'success' 	=> false,
					'code' 		=> 404,
					'message' 	=> 'Email address is not valid or is empty',
				);

				echo json_encode($message);
				return false;	
			}

			$query = "SELECT 
			subscripciones.id, 
			subscripciones.nombre_subscriptor,
			subscripciones.email,
			subscripciones.fecha_subscripcion,
            estados_subscripcion.estado
			FROM subscripciones 
            LEFT JOIN estados_subscripcion ON (estados_subscripcion.id = subscripciones.estado_sub_id)
			WHERE subscripciones.email = '".$email."' AND
			subscripciones.estado_sub_id = 1
			ORDER BY subscripciones.id DESC;";

			$result = $this->db->query($query)->result_array();

			if (!is_null($result) && count($result) > 0) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'code' 		=> $e->getCode(),
				'message' 	=> $e->getMessage()
			));
			return false;
		}	
	}

	private function getPlanByID(int $plan_id) {
		try {
			if (!is_int($plan_id) || is_null($plan_id)) {
				$message = array(
					'success' 	=> false,
					'code' 		=> 404,
					'message' 	=> 'Plan subscription is not valid or is empty',
				);

				echo json_encode($message);
				return false;	
			}

			$query = "SELECT 
			planes.id, 
			planes.nombre,
			planes.precio
			FROM planes 
			WHERE planes.id = ?
			ORDER BY planes.id DESC;";

			$result = $this->db->query($query, array($plan_id))->result_array();

			if (!is_null($result) && count($result) > 0) {
				return $result;
			} else {
				return false;
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'code' 		=> $e->getCode(),
				'message' 	=> $e->getMessage()
			));
			return false;
		}	
	}

	private function getPaymentTypeByID(int $payment_type_id) {
		try {
			if (!is_int($payment_type_id) || is_null($payment_type_id)) {
				$message = array(
					'success' 	=> false,
					'code' 		=> 404,
					'message' 	=> 'Payment type subscription is not valid or is empty',
				);

				echo json_encode($message);
				return false;	
			}

			$query = "SELECT 
			tipos_cobros.id, 
			tipos_cobros.tipo_cobro
			FROM tipos_cobros 
			WHERE tipos_cobros.id = ?
			ORDER BY tipos_cobros.id DESC;";

			$result = $this->db->query($query, array($payment_type_id))->result_array();

			if (!is_null($result) && count($result) > 0) {
				return $result;
			} else {
				return false;
			}
		} catch (Exception $e) {
			echo json_encode(array(
				'code' 		=> $e->getCode(),
				'message' 	=> $e->getMessage()
			));
			return false;
		}	
	}


	private function getStatusSubByID(int $status_sub_id) {
		try {
			if (!is_int($status_sub_id) || is_null($status_sub_id)) {
				$message = array(
					'success' 	=> false,
					'code' 		=> 404,
					'message' 	=> 'Payment type subscription is not valid or is empty',
				);

				echo json_encode($message);
				return false;	
			}

			$query = "SELECT 
			estados_subscripcion.id, 
			estados_subscripcion.estado
			FROM estados_subscripcion 
			WHERE estados_subscripcion.id = ?
			ORDER BY estados_subscripcion.id DESC;";

			$result = $this->db->query($query, array($status_sub_id))->result_array();

			if (!is_null($result) && count($result) > 0) {
				return $result;
			} else {
				return false;
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