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
<div class="container p-5 my-5">
	<!--<div class="container"> -->
			<div class="row">
				<div class="col-md-3">
					<ul class="nav nav-tabs flex-column">
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#inicio">Inicio <i class="ph-bold ph-caret-right"></i></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#transacciones">Transacciones <i class="ph-bold ph-caret-right"></i></a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#configuracion">Configuración <i class="ph-bold ph-caret-right"></i></a>
				</li>
					</ul>
				</div>
				<div class="col-md-9">
					<div class="tab-content">
						<div id="inicio" class="tab-pane fade">
							<p>Texto de prueba para la sección "Inicio".</p>
						</div>

						<div id="transacciones" class="tab-pane fade">
							<p>Texto de prueba para la sección "Transacciones".</p>
						</div>

						<div id="configuracion" class="tab-pane fade show active">
							<form action='options.php' method='post'>
								<?php
									settings_fields( 'pluginPage' );
									do_settings_sections( 'pluginPage' );
									submit_button();
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	<!--</div>-->
</div>

