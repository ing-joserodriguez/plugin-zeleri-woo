<?php 
	class Tabla_Transacciones_Zeleri extends WP_List_Table
	{
		
		//use Automattic\WooCommerce\Utilities\OrderUtil;

	    /**
	     * Prepare the items for the table to process
	     *
	     * @return Void
	     */
	    public function prepare_items() {
	        $columns = $this->get_columns();
	        $hidden = $this->get_hidden_columns();
	        $sortable = $this->get_sortable_columns();

	        $data = $this->table_data();
	        usort( $data, array( &$this, 'sort_data' ) );

	        $i = 0;
          foreach ($data AS $key) {
              if (isset($key['fecha'])) {
                  $data[$i]['fecha'] = date("d M, Y", strtotime($key['fecha']));
              }
              $i++;
          }

	        $perPage = 10;
	        $currentPage = $this->get_pagenum();
	        $totalItems = count($data);

	        $this->set_pagination_args( array(
	            'total_items' => $totalItems,
	            'per_page'    => $perPage
	        ) );

	        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

	        $this->_column_headers = array($columns, $hidden, $sortable);
	        $this->items = $data;
	    }

	    /**
	     * Override the parent columns method. Defines the columns to use in your listing table
	     *
	     * @return Array
	     */
	    public function get_columns() {
	        $columns = array(
	            'trx_id'         => 'ID trx',
	            'producto'       => 'Producto',
	            'order_woo'      => 'Orden WooCommerce',
	            'estado_woo'     => 'Estado interno',
	            'estado_zeleri'  => 'Estado Transaccion',
	            'orden_zeleri'   => 'Orden Compra Zeleri',
              'token'          => 'Token',
	            'monto'          => 'Monto',
              'fecha'          => 'Fecha creación',
              'fecha_zeleri'   => 'Fecha Transacción Zeleri',
              'error'          => 'Error',
              'detalle_error'  => 'Detalle de Error'
	        );

	        return $columns;
	    }

	    /**
	     * Define which columns are hidden
	     *
	     * @return Array
	     */
	    public function get_hidden_columns() {
	        return array("ID");
	    }

	    /**
	     * Define the sortable columns
	     *
	     * @return Array
	     */
	    public function get_sortable_columns() {
	    	$columns = array(
	    		'trx_id'       => array('trx_id', true),
          'order_woo'    => array('order_woo', true),
          'orden_zeleri' => array('orden_zeleri', true),
          'monto'        => array('monto', true),
          'fecha'        => array('fecha', true),
					'fecha_zeleri' => array('fecha_zeleri', true)
	    	);
	        return $columns;
	    }

	    /**
	     * Get the table data
	     *
	     * @return Array
	     */
	    private function table_data() {

				$str = ( isset($_GET['s']) ) ? $_GET['s'] : '';

				$args = array(
					'payment_method' => 'zeleri_woo_oficial_payment_gateways',
					'order' 		     => 'DESC', 
					'orderby' 			 => 'date',
					'search'      	 => $str,
    			'search_columns' => array('id', 'status', 'transaction_id', 'date_updated_gmt')
				);
				
				$orders = wc_get_orders( $args );

			  $data = array();

			  foreach ($orders as $key => $order) {

					$fecha = new DateTime( $order->get_date_created() );
					$fecha_zeleri = new DateTime( $order->get_meta('zeleri_payment_date') );

					$data[] = array(
            'trx_id'         => $order->get_order_number(),
            'producto'       => 'Producto_'.$key,
            'order_woo'      => $order->get_id(),
            'estado_woo'     => $order->get_status(),
            'estado_zeleri'  => $order->get_meta('zeleri_status'),
            'orden_zeleri'   => $order->get_transaction_id(),
            'token'          => '',
            'monto'          => wc_price($order->get_total()),
            'fecha'          => $fecha->format('d-m-Y'),
            'fecha_zeleri'   => $fecha_zeleri->format('d-m-Y'),
            'error'          => $order->get_meta('zeleri_error'),
            'detalle_error'  => $order->get_meta('zeleri_details_error')
          );
			  }



	        return $data;
	    }

	    /**
	     * Define what data to show on each column of the table
	     *
	     * @param  Array $item        Data
	     * @param  String $column_name - Current column name
	     *
	     * @return Mixed
	     */
	    public function column_default( $item, $column_name ) {
	        switch( $column_name ) {
	            case 'trx_id':
	            case 'producto':
	            case 'order_woo':
	            case 'estado_woo':
	            case 'estado_zeleri':
	            case 'orden_zeleri':
	            case 'token':
	            case 'monto':
              case 'fecha':
              case 'fecha_zeleri':
              case 'error':
              case 'detalle_error':
	                return $item[ $column_name ];

	            default:
	                return print_r( $item, true ) ;
	        }
	    }

	    /**
	     * Allows you to sort the data by the variables set in the $_GET
	     *
	     * @return Mixed
	     */
	    private function sort_data( $a, $b ) {
	        // Set defaults
	        $orderby = 'trx_id';
	        $order = 'desc';

	        // If orderby is set, use this as the sort column
	        if(!empty($_GET['orderby'])) {
	            $orderby = $_GET['orderby'];
	        }

	        // If order is set use this as the order
	        if(!empty($_GET['order'])) {
	            $order = $_GET['order'];
	        }

	        if($orderby == 'trx_id') {
	        	$_orderID1 = intval($this->get_order_id( $a[$orderby] ));
	        	$_orderID2 = intval($this->get_order_id( $b[$orderby] ));
	        	$result = ($_orderID1 > $_orderID2) ? +1 : -1;
	        }

	        if($orderby == 'fecha') {
	        	$_fecha1 = strtotime( $a[$orderby] );
	        	$_fecha2 = strtotime( $b[$orderby] );
	        	$result = ($_fecha1 > $_fecha2) ? +1 : -1;
	        }

          if($orderby == 'order_woo') {
            $_orderID1 = intval($this->get_order_id( $a[$orderby] ));
	        	$_orderID2 = intval($this->get_order_id( $b[$orderby] ));
	        	$result = ($_orderID1 > $_orderID2) ? +1 : -1;
          }

					if($orderby == 'orden_zeleri') {
            $_orderID1 = intval($this->get_order_id( $a[$orderby] ));
	        	$_orderID2 = intval($this->get_order_id( $b[$orderby] ));
	        	$result = ($_orderID1 > $_orderID2) ? +1 : -1;
          }

					if($orderby == 'monto') {
            $_amountID1 = intval($a[$orderby] );
	        	$_amountID2 = intval($b[$orderby] );
	        	$result = ($_amountID1 > $_amountID2) ? +1 : -1;
          }

					if($orderby == 'fecha_zeleri') {
	        	$_fecha1 = strtotime( $a[$orderby] );
	        	$_fecha2 = strtotime( $b[$orderby] );
	        	$result = ($_fecha1 > $_fecha2) ? +1 : -1;
	        }

	        if($order === 'asc') {
	            return $result;
	        }

	        return -$result;
	    }


	    /*public function get_bulk_actions() {
			$actions = array(
		    	'generar_multiples_ot' => 'Generar OT'
		  	);
		  	return $actions;
		}*/

		public function get_order_id( $str ) {
			$_str = strip_tags($str);
			$_str = trim($_str);
			$order_id = substr($_str, 1);
			return intval($order_id);
		}


		/*public function column_cb($item) {
			return sprintf(
		    	'<input type="checkbox" name="pedidos[]" value="%s" />', $item['ID']
		    );    
		}*/

	}