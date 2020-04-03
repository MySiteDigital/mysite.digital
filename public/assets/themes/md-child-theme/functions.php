<?php

function enqueue_parent_styles() {
    wp_enqueue_style( 
        'parent-style', 
        get_template_directory_uri().'/style.css' 
    );
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );


function redirect_to_checkout( $url ) {
   $url = get_permalink( get_option( 'woocommerce_checkout_page_id' ) ); 
   return $url;
}
 
add_filter( 'woocommerce_add_to_cart_redirect', 'redirect_to_checkout' );

function update_payment_gateways( $available_gateways ) {
    if ( is_checkout() ) {
        foreach( WC()->cart->get_cart() as $cart_item ){
            $product_id = $cart_item[ 'product_id' ];
            $terms = wp_get_post_terms( $product_id, 'product_cat' );
            foreach ( $terms as $term ) $categories[] = $term->slug;
            if ( in_array( 'urgent-rates', $categories ) ) {
                unset( $available_gateways['bacs'] );
            }
        }
    }
    return $available_gateways;
}

add_filter( 'woocommerce_available_payment_gateways', 'update_payment_gateways', 999, 3 );


function quantity_input_min( $val ) {
    if( is_admin() ) {
        return 0.25;
    }
}
// Add min value to the quantity field (default = 1)
add_filter( 'woocommerce_quantity_input_min', 'quantity_input_min' );

function quantity_input_step( $val ) {
    if( is_admin() ) {
        return 0.25;
    }
}
// Add step value to the quantity field (default = 1)
add_filter( 'woocommerce_quantity_input_step', 'quantity_input_step' );

// Removes the WooCommerce filter, that is validating the quantity to be an int
remove_filter( 'woocommerce_stock_amount', 'intval' );
// Add a filter, that validates the quantity to be a float
add_filter( 'woocommerce_stock_amount', 'floatval' );


// Add unit price fix when showing the unit price on processed orders
function fix_order_amount_item_total( $price, $order, $item, $inc_tax = false, $round = true ) {
    $qty = ( ! empty( $item[ 'qty' ] ) && $item[ 'qty' ] != 0 ) ? $item[ 'qty']  : 1;
    if( $inc_tax ) {
        $price = ( $item[ 'line_total' ] + $item[ 'line_tax' ] ) / $qty;
    } else {
        $price = $item[ 'line_total' ] / $qty;
    }
    $price = $round ? round( $price, 2 ) : $price;
    return $price;
}

add_filter( 'woocommerce_order_amount_item_total', 'fix_order_amount_item_total', 10, 5 );   


