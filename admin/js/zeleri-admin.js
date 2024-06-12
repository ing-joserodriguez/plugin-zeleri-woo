(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $(function () {
    const URLactual = jQuery(location).attr("href");
    const url = new URL(URLactual);
    const params = url.searchParams;

    //Valido que estoy dentro de la seccion del plugin de zeleri
    if (params.get("section") == "zeleri_woo_oficial_payment_gateways") {
      //Oculto el boton de "Guardar cambios" por defecto y muestro el del formulario de configuracion
      $("#wpbody-content .submit").hide();
      $("#wpbody-content .zeleri-button-submit").show();

      //Valido si la variable "tab_pane" viene en la url
      if (params.get("tab_pane")) {
        //Si existe, se simulamuestra la seccion en donde se estaba anteriormente
        const IDTabActive = "#" + params.get("tab_pane");
        $('.nav-tabs a[href="' + IDTabActive + '"]').trigger("click");
      }

      //Valido si la variable "tab_pane" viene en la url
      //if (params.get("tab_pane") == "tabZeleriTransacciones") {
      //Si existe, se simulamuestra la seccion en donde se estaba anteriormente
      //$('.nav-tabs a[href="#tabZeleriTransacciones"]').trigger("click");
      //}

      //Valido si la variable "tab_pane" viene en la url
      //if (params.get("tab_pane") == "tabZeleriConfiguracion") {
      //Si existe, se simulamuestra la seccion en donde se estaba anteriormente
      //$('.nav-tabs a[href="#tabZeleriConfiguracion"]').trigger("click");
      //}
    }

    //Hace que el enlace de la seccion de inicio despliegue la seccion de las configuraciones
    $("#irZeleriConfiguracion").on("click", function () {
      $('.nav-tabs a[href="#tabZeleriConfiguracion"]').trigger("click");
    });
  });
})(jQuery);
