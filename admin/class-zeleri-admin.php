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

		wp_enqueue_style( 'Bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );

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

		wp_enqueue_script( 'Bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' );

	}

	public function add_menu() {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(
			"Zeleri", // Título de la página
			"Zeleri", // Literal de la opción
			"manage_options", // Dejadlo tal cual
			'zeleri', // Slug
			array( $this, 'index' ), // Función que llama al pulsar
			plugins_url( 'zeleri/admin/images/logo-zeleri.webp' ) // Icono del menú
		);
	}
		
	public function index() {
		include 'partials/zeleri-admin-display.php';
	}

	public function zeleri_settings_init() {

		register_setting( 
			'pluginPage', 
			'zeleri_settings',
			array( $this, 'zeleri_validate_plugin_settings' )
		);
	
		add_settings_section(
			'zeleri_pluginPage_section', 
			__( 'Configuracion inicial del plugin para procesar pagos', 'zeleri' ), 
			array( $this, 'zeleri_settings_section_callback' ),
			'pluginPage'
		);
	
		add_settings_field( 
			'zeleri_select_field_0', 
			__( 'Ambiente:', 'zeleri' ), 
			array( $this, 'zeleri_select_field_0_render' ), 
			'pluginPage', 
			'zeleri_pluginPage_section',
			array(
				'label_for'         => 'zeleri_text_field_0',
				'class'             => 'zeleri-field-setting',
			)
		);
	
		add_settings_field( 
			'zeleri_text_field_1', 
			__( 'Llave Publica:', 'zeleri' ), 
			array( $this, 'zeleri_text_field_1_render' ), 
			'pluginPage', 
			'zeleri_pluginPage_section',
			array(
				'label_for'         => 'zeleri_text_field_1',
				'class'             => 'zeleri-field-setting',
			)
		);
	
		add_settings_field( 
			'zeleri_text_field_2', 
			__( 'Llave Privada:', 'zeleri' ), 
			array( $this, 'zeleri_text_field_2_render' ), 
			'pluginPage', 
			'zeleri_pluginPage_section',
			array(
				'label_for' => 'zeleri_text_field_2',
				'class'     => 'zeleri-field-setting'
			)
		);

	}

	public function zeleri_settings_section_callback() { 
	
		echo __( 'Tokens de conexion', 'zeleri' );
	
	}

	public function zeleri_validate_plugin_settings( $input ) {
		$output['zeleri_select_field_0'] = absint( $input['zeleri_select_field_0'] );
		$output['zeleri_text_field_1']   = sanitize_text_field( $input['zeleri_text_field_1'] );
		$output['zeleri_text_field_2']   = sanitize_text_field( $input['zeleri_text_field_2'] );
		// ...
		return $output;
	}
	
	public function zeleri_select_field_0_render($args) { 
	
		$options = get_option( 'zeleri_settings' );
		?>

		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="zeleri_settings[<?php echo esc_attr( $args['label_for'] ); ?>]">
			
			<option value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 1, false ) ) : ( '' ); ?>>
				<?php esc_html_e( 'Desarrollo', 'zeleri' ); ?>
			</option>
			<option value="2" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 2, false ) ) : ( '' ); ?>>
				<?php esc_html_e( 'Produccion', 'zeleri' ); ?>
			</option>

		</select>

	
		<?php
	}
	
	public function zeleri_text_field_1_render($args) { 
	
		$options = get_option( 'zeleri_settings' );
		?>
		<input type='text' id="<?php echo esc_attr( $args['label_for'] ); ?>" name='zeleri_settings[<?php echo esc_attr( $args['label_for'] ); ?>]' value='<?php echo isset( $options[ $args['label_for'] ] ) ? esc_attr( $options[$args['label_for']] ) : ''; ?>'>
		<?php
	
	}
	
	public function zeleri_text_field_2_render($args) { 
	
		$options = get_option( 'zeleri_settings' );
		?>
		<input type='text' id="<?php echo esc_attr( $args['label_for'] ); ?>" name='zeleri_settings[<?php echo esc_attr( $args['label_for'] ); ?>]' value='<?php echo isset( $options[ $args['label_for'] ] ) ? esc_attr( $options[$args['label_for']] ) : ''; ?>'>
		<?php
	
	}

}
