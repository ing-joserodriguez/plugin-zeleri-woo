<?php
class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'zeleri_woo_oficial_payment_gateways';
        $this->method_title = 'Zeleri';
        $this->title = 'Zeleri';
        $this->has_fields = false;

        $options = get_option( 'zeleri_settings' );

        $this->enabled = ( isset($options['zeleri_checkbox_field_0']) ) ? $options['zeleri_checkbox_field_0'] : 'no';
        $this->method_description  = ( isset($options['zeleri_textarea_field_4']) ) ? $options['zeleri_textarea_field_4'] : '';

         // Initialize settings
         $this->init_form_fields();
         $this->init_settings();
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'zeleri_checkbox_field_0' => array(
                'title'   => __('Activar/Desactivar plugin:', 'zeleri'),
                'type'    => 'checkbox',
                'label'   => __('Enable Custom Payment Gateway', 'zeleri'),
                'default' => 'no',
            ),
            'zeleri_text_field_1' => array(
                'title'       => __('API Key (llave secreta) Produccion:', 'zeleri'),
                'type'        => 'text',
                'description' => __('Title displayed during checkout.', 'zeleri'),
                'default'     => __('Custom Payment', 'zeleri'),
                'desc_tip'    => true,
            ),
            'zeleri_text_field_2' => array(
                'title'       => __('Zeleri Key:', 'zeleri'),
                'type'        => 'text',
                'description' => __('Title displayed during checkout.', 'zeleri'),
                'default'     => __('Custom Payment', 'zeleri'),
                'desc_tip'    => true,
            ),
            'zeleri_textarea_field_4' => array(
                'title'       => __('Descripcion medio de pago:', 'zeleri'),
                'type'        => 'textarea',
                'description' => __('Description displayed during checkout.', 'zeleri'),
                'default'     => __('Pay with our custom payment gateway.', 'zeleri'),
                'desc_tip'    => true,
            ),
        );
    }
    

    public function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order( $order_id );
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
    }

    add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );

}