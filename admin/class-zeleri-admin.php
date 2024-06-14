<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://zeleri.com/
 * @since      1.0.0
 *
 * @package    Zeleri
 * @subpackage Zeleri/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zeleri
 * @subpackage Zeleri/admin
 * @author     Zeleri <jose.rodriguez.externo@ionix.cl>
 */

class Zeleri_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zeleri_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zeleri_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zeleri-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'Bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );

		wp_enqueue_style( 'phosphor-icons', 'https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zeleri_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zeleri_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zeleri-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'Bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' );

	}

	public function index() {
		// CODE HERE
	}

	public function woocommerce_zeleri_payment_gateway_init() {
		if ( $this->woocommerce_is_active() ) {
			require_once( plugin_dir_path(dirname( __FILE__ )) . 'includes/class-zeleri-woo-oficial-payment-gateways.php' );
		}
	}

	public function add_zeleri_woo_oficial_payment_gateways( $methods ) {
		var_dump($methods);
		if ( $this->woocommerce_is_active() ) {
			$methods[] = 'Zeleri_Woo_Oficial_Payment_Gateways';
			return $methods;
		}
	}

	public function add_menu() {
		add_submenu_page(
			'woocommerce',
			'Zeleri', // Título de la página
			'Zeleri', // Literal de la opción
			'manage_options', // Dejadlo tal cual
			'zeleri', // Slug
			array( $this, 'zeleri_woo_oficial_settings_view' ), // Función que llama al pulsar
			100 // Para colocarlo en la ultima posicion del submenu
		);
	}

	public function zeleri_woo_oficial_settings_view() {
		wp_redirect('./admin.php?page=wc-settings&tab=checkout&section=zeleri_woo_oficial_payment_gateways');
    exit;
	}

	public function woocommerce_zeleri_admin_notices() {
		add_action( 'admin_notices', array($this , 'review_notice') );
	}
		
	public function review_notice() {
		if ( isset( $_GET['section'] ) && $_GET['section'] === 'zeleri_woo_oficial_payment_gateways' ) {
			echo '<div class="notice notice-info is-dismissible" id="zeleri-review-notice">
					<div class="zeleri-notice">
							<div class="img-logo-zeleri">
									<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . 'images/logo-zeleri.webp" height="30px" alt="Zeleri logo" />
							</div>
							<div class="zeleri-review-text">
									<p class="zeleri-review-title">Tu opinión es importante para nosotros</p>
									<p>¿Podrías tomarte un momento para dejarnos una reseña en el repositorio de WordPress?
											Solo te tomará un par de minutos y nos ayudará a seguir mejorando y llegar a más personas como tú.</p>
							</div>
							<a class="button button-primary zeleri-button-primary"
									href="https://wordpress.org/support/plugin/transbank-webpay-plus-rest/reviews/#new-post"
									target="_blank" rel="noopener"
							>Dejar reseña</a>
					</div>
			</div>';
		}
	}

	public function woocommerce_is_active() {
		$woocommerce_is_present = false;

		$all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
		if (stripos(implode($all_plugins), 'woocommerce.php')) {
				$woocommerce_is_present = true;
		}
		return $woocommerce_is_present;
	}


}
