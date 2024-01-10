<?php

$config = array(
    'storeSubscriptions' => array(
        array(
            'field' => 'email_subscription',
            'label' => 'Email subscription',
            'rules' => 'required'
        ),
        array(
            'field' => 'name_subscription',
            'label' => 'Name subscription',
            'rules' => 'required'
        ),
        array(
            'field' => 'payment_type_id',
            'label' => 'Payment Type subscription',
            'rules' => 'required'
        ),
        array(
            'field' => 'plan_id',
            'label' => 'Plan Type subscription',
            'rules' => 'required'
        ),
        array(
            'field' => 'status_sub_id',
            'label' => 'Status subscription',
            'rules' => 'required'
        ),
        array(
            'field' => 'date_subscription',
            'label' => 'Date subscription ',
            'rules' => 'required'
        ),
    ),
);