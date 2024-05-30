<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://https://zeleri.com/
 * @since      1.0.0
 *
 * @package    Zeleri
 * @subpackage Zeleri/admin/partials
 */
?>

<?php 
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'wporg_messages', 'wporg_message', __( 'Configuracion guardada', 'wporg' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'wporg_messages' );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container p-5 my-5 border">
	<!-- <h1><?php //echo esc_html( get_admin_page_title() ); ?></h1> -->
	
 		<!-- Nav tabs -->
	<div class="row">
    
	
		<div class="col-md-3">
			<ul class="nav flex-column">
				<li class="nav-item">
					<a class="nav-link" href="#">Servicios</a>
        </li>
				<li class="nav-item">
					<a class="nav-link active" data-bs-toggle="pill" href="#home">Inicio</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="pill" href="#settings">Configuracion</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="pill" href="#transactions">Transacciones</a>
				</li>
			</ul>
		</div>


		<div class="col-md-9">
			<div class="tab-content">
				
				<div class="tab-pane fade show active" id="home">
					<div class="section-tab-home d-flex flex-column align-items-center">
						<img src="http://localhost/wordpress/wp-content/uploads/2023/10/Z-logo.png" alt="Logo Zeleri" />
						<h5>Te damos la bienvenida a Zeleri para WordPress</h5>
						<p>Con el plugin oficial de Zeleri para WordPress podrás realizar tus pagos, <br> de manera facil, rapida y segura.</p>
					</div>
				</div>

				<div class="tab-pane fade" id="settings">
					<form action='options.php' method='post'>
						<?php
							settings_fields( 'pluginPage' );
							do_settings_sections( 'pluginPage' );
							submit_button();
						?>
					</form>
				</div>

				<div class="tab-pane fade" id="transactions">
					<h3>Centro de ayuda</h3>
					<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
				</div>
				
			</div>
		</div>

	</div>

</div>
