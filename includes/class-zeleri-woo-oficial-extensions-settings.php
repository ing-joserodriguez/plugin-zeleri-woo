<?php

class Zeleri_Woo_Oficial_Extensions_Settings extends WC_Settings_API {
  function __construct() {
    $this->init_form_fields();
  }

  function init_form_fields() {
    $this->form_fields = array(
        'enabled' => array(
            'title'   => __('Enable/Disable', 'zeleri'),
            'type'    => 'checkbox',
            'label'   => __('Enable Custom Payment Gateway', 'zeleri'),
            'default' => 'yes',
        ),
        'title' => array(
            'title'       => __('Title', 'zeleri'),
            'type'        => 'text',
            'description' => __('Title displayed during checkout.', 'zeleri'),
            'default'     => __('Custom Payment', 'zeleri'),
            'desc_tip'    => true,
        ),
        'description' => array(
            'title'       => __('Description', 'zeleri'),
            'type'        => 'textarea',
            'description' => __('Description displayed during checkout.', 'zeleri'),
            'default'     => __('Pay with our custom payment gateway.', 'zeleri'),
            'desc_tip'    => true,
        ),
    );
  }
}

	