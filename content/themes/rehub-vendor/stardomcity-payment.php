<?php
class StardomCityPayment {

    function __construct() {
      $this->init();
    }

    public function init(){

      add_action('wp_enqueue_scripts', array($this, 'cfields_scripts'));
      add_filter('default_checkout_country', array($this, 'change_default_checkout_country'));
      add_filter('woocommerce_billing_fields', array($this, 'custom_billing_fields'));
      add_filter('woocommerce_checkout_fields', array($this, 'custom_checkout_fields'));
      add_filter('woocommerce_order_details_after_customer_details', array($this, 'custom_order_details_after_customer_details'), 10, 1);
      add_filter('woocommerce_email_customer_details_fields', array($this, 'custom_email_customer_details_fields'), 10, 3);

    }

    public function cfields_scripts() {
        wp_enqueue_script( 'checkout_script', get_stylesheet_directory_uri().'/js/cfields.js', array('jquery'), '1.0', true );
    }
    public function change_default_checkout_country() {
      return 'TR'; // country code
    }
    public function custom_billing_fields( $fields ) {

    	$fields['billing_country']['class'] = array('form-row-first');
    	$fields['billing_city']['class'] = array('form-row-last');
    	$fields['billing_city']['clear'] = true;

    	unset($fields['billing_postcode']);

    	$fields['billing_status'] = $this->create_billing_status();
    	// Individual fields
    	$fields['billing_tc_no'] = $this->create_billing_tc_no();
    	//Corporate fields
    	$fields['billing_company']['required'] = false;
    	$fields['billing_company']['class'] = array('form-row-wide', 'status-group2');
    	$fields['billing_tax_office_city'] = $this->create_billing_tax_office_city();
    	$fields['billing_tax_office'] = $this->create_billing_tax_office();
    	$fields['billing_tax_no'] = $this->create_billing_tax_no();

    	$fields['billing_address_1']['type'] = 'textarea';
    	unset($fields['billing_address_2']);

    	return $this->get_ordered_fields($fields);
    }
    public function custom_checkout_fields( $fields ) {

        $fields['billing']['billing_country']['class'] = array('form-row-first');

        $fields['billing']['billing_city']['class'] = array('form-row-last');
        $fields['billing']['billing_city']['clear'] = true;

        unset($fields['billing']['billing_postcode']);

        $fields['billing']['billing_status'] = $this->create_billing_status();

        // Individual fields
        $fields['billing']['billing_tc_no'] = $this->create_billing_tc_no();

        // Company Fields
        $fields['billing']['billing_company']['required'] = false;
        $fields['billing']['billing_company']['class'] = array('form-row-wide', 'status-group2');

        $fields['billing']['billing_tax_office_city'] = $this->create_billing_tax_office_city();
        $fields['billing']['billing_tax_office'] = $this->create_billing_tax_office();
        $fields['billing']['billing_tax_no'] = $this->create_billing_tax_no();


        $fields['billing']['billing_address_1']['type'] = 'textarea';
        unset($fields['billing']['billing_address_2']);

        $fields['billing'] = $this->get_ordered_fields($fields['billing']);

        return $fields;

    }


    public function custom_email_customer_details_fields($fields, $sent_to_admin, $order ) {

    	$billing_status = get_post_meta( $order->id, '_billing_status', true );

    	if($billing_status == 1){
        $billing_tc_no = get_post_meta( $order->id, '_billing_tc_no', true );

        $fields['billing_status']['label'] = __('Fatura Türü', '');
    		$fields['billing_status']['class'] = array('form-row-wide, status-select');
    		$fields['billing_status']['value'] = 'Bireysel Fatura';
    		// Individual fields
        $fields['billing_tc_no']['label'] = __('TC No', '');
    		$fields['billing_tc_no']['class'] = array('form-row-wide');
    		$fields['billing_tc_no']['value'] = $billing_tc_no;

    	} else if($billing_status == 2){
        $billing_company = get_post_meta( $order->id, '_billing_company', true );
        $billing_tax_office_city = get_post_meta( $order->id, '_billing_tax_office_city', true );
        $billing_tax_office = get_post_meta( $order->id, '_billing_tax_office', true );
        $billing_tax_no = get_post_meta( $order->id, '_billing_tax_no', true );

        $fields['billing_status']['label'] = __('Fatura Türü', '');
        $fields['billing_status']['class'] = array('form-row-wide, status-select');
        $fields['billing_status']['value'] = 'Kurumsal Fatura';
        // Company Fields
        $fields['billing_company']['class'] = array('form-row-wide');
        $fields['billing_company']['value'] = $billing_company;

        $fields['billing_tax_office_city']['label'] = __('Vergi Dairesi İl Seçiniz', '');
        $fields['billing_tax_office_city']['class'] = array('form-row-wide');
        $fields['billing_tax_office_city']['value'] = $billing_tax_office_city;

        $fields['billing_tax_office']['label'] = __('Vergi Dairesi Seçiniz', '');
        $fields['billing_tax_office']['class'] = array('form-row-wide');
        $fields['billing_tax_office']['value'] = $billing_tax_office;

        $fields['billing_tax_no']['label'] = __('Vergi Numarası', '');
        $fields['billing_tax_no']['class'] = array('form-row-wide');
        $fields['billing_tax_no']['value'] = $billing_tax_no;

    	}

    	return $fields;
    }
    public function custom_order_details_after_customer_details($order ) {
      if(!isset($order->billing_status)){
        return $order;
      }
      echo '<header><h2>Fatura Detayları</h2></header>';
      echo '<table class="shop_table customer_details">';
      echo '<tbody>';

      if($order->billing_status == 1){
        echo '<tr><th>'  .__('Fatura Türü', '') . '</th><td>' . 'Bireysel Fatura'  . '</td></tr>';
        if ( $order->billing_tc_no ) echo '<tr><th>'  .__('TC No', '') . '</th><td>' . $order->billing_tc_no . '</td></tr>';

      } else if($order->billing_status == 2){
        echo '<tr><th>'  .__('Fatura Türü', '') . '</th><td>' . 'Kurumsal Fatura' . '</td></tr>';

        if ( $order->billing_company ) echo '<tr><th>' . __('Şirket İsmi', '') . '</th><td>' . $order->billing_company . '</td></tr>';
        if ( $order->billing_tax_office_city ) echo '<tr><th>' . __('Vergi Dairesi İli ', '') . '</th><td>' . $order->billing_tax_office_city . '</td></tr>';
        if ( $order->billing_tax_office ) echo '<tr><th>' . __('Vergi Dairesi', '') . '</th><td>' . $order->billing_tax_office . '</td></tr>';
        if ( $order->billing_tax_no ) echo '<tr><th>' . __('Vergi Numarası', '') . '</th><td>' . $order->billing_tax_no . '</td></tr>';

        echo '</tbody>';
        echo '<table>';
      }
    }

    public function get_ordered_fields($fields){
      $fields_order = array(
    			'billing_first_name', 'billing_last_name',
    			'billing_email',      'billing_phone',
    			'billing_country',    'billing_city',
    			'billing_status',
    			'billing_tc_no',
    			'billing_company',
    			'billing_tax_office_city',
    			'billing_tax_office', 'billing_tax_no',
    			'billing_address_1'
    	);

    	foreach($fields_order as $field) {
    		$ordered_fields[$field] = $fields[$field];
    	}
    	return $ordered_fields;
    }

    public function create_billing_status(){
      return array(
        'type' => 'select',
        'class' => array('form-row-wide, status-select'),
        'required' => true,
        'label' => __('Fatura Türü', ''),
        'placeholder' =>  __('Fatura Türü Seçiniz', ''),
        'options' => array(
            '1' => __( 'Bireysel Fatura', '' ),
            '2' => __( 'Kurumsal Fatura', '' )
        )
      );
    }

    public function create_billing_tc_no(){
      return array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'required' => false,
        'label' => __('TC No', ''),
        'placeholder' => __('TC No Giriniz', '')
      );
    }

    public function create_billing_tax_office_city(){
      return array(
        'type' => 'text',
        'class' => array('form-row-wide'),
        'required' => false,
        'label' => __('Vergi Dairesi İl Giriniz', ''),
        'placeholder' => __('İl Giriniz', '')
      );
    }

    public function create_billing_tax_office(){
      return array(
        'type' => 'text',
        'class' => array('form-row-first'),
        'required' => false,
        'label' => __('Vergi Dairesi Giriniz', ''),
        'placeholder' => __('Vergi Dairesi', '')
      );
    }

    public function create_billing_tax_no(){
      return array(
        'type' => 'text',
        'class' => array('form-row-last'),
        'required' => false,
        'label' => __('Vergi Numarası Giriniz', ''),
        'placeholder' => __('Vergi Numarası', ''),
        'clear' => true
      );
    }
}
