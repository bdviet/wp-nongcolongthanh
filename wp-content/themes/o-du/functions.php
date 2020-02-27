<?php

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
     unset($fields['billing']['billing_postcode']);
unset($fields['billing']['billing_country']);
 unset($fields['billing']['billing_address_2']);
 unset($fields['billing']['billing_company']);
 unset($fields['billing']['billing_address_1']);
     return $fields;
}

function tp_custom_checkout_fields( $fields ) {$fields['city']['label'] = 'Địa chỉ'; return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'tp_custom_checkout_fields' );

 ?>

