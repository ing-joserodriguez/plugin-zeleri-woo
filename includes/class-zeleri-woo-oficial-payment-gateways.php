<?php
class Zeleri_Woo_Oficial_Payment_Gateways extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'zeleri_woo_oficial_payment_gateways';
        $this->method_title = 'Zeleri';
        $this->title = 'Zeleri';
        $this->has_fields = true;
        // Otros ajustes y configuraciones aquí
    }

    public function display_settings() {
      $settings_url = admin_url('admin.php?page=zeleri');
      wp_redirect($settings_url);
      exit;
    }

    public function init_form_fields() {
      $this->form_fields = array(
          'enabled' => array(
              'title' => 'Habilitar Zeleri',
              'type' => 'checkbox',
              'label' => 'Habilitar este método de pago',
              'default' => 'yes',
          ),
          // Otros campos aquí
      );
    }
  
  

    public function process_payment($order_id) {
        // Lógica para procesar el pago
        // Puedes redirigir al usuario a una página de confirmación o procesar el pago directamente
    }
}