<?php
class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'zeleri_woo_oficial_payment_gateways';
        $this->method_title = 'Zeleri';
        $this->title = 'Zeleri';
        $this->has_fields = false;

        $options = get_option( 'zeleri_settings' );
        var_dump($options);

        $this->enabled = ( isset($options['zeleri_checkbox_field_0']) ) ? $options['zeleri_checkbox_field_0'] : 'no';
        $this->description = ( isset($options['zeleri_textarea_field_4']) ) ? $options['zeleri_textarea_field_4'] : 'Descripcion por defecto';
    }
  
    public function process_payment($order_id) {
    global $woocommerce;
    $order = new WC_Order( $order_id );
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
    }
}