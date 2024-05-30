<?php
class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'zeleri_woo_oficial_payment_gateways';
        $this->method_title = 'Zeleri';
        $this->title = 'Zeleri';
        $this->has_fields = false;
    }

    public function admin_options() {
      echo '<h2>' . esc_html( $this->get_method_title() );
      wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=zeleri' ) );
      echo '</h2>';
      echo wp_kses_post( wpautop( $this->get_method_description() ) );
      parent::admin_options();
    }
  
    public function process_payment($order_id) {
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
    }
}