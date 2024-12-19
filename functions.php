<?php

/*
    Start: This function is used to reorder the menu items on the "My Account" page of WooCommerce.
    It defines a new order for the tabs displayed to users when they visit their account page.
*/

function george_reorder_my_account_menu( $menu_items ) {
    
    // Define new order for the menu items
    $newtaborder = array(
        'dashboard'          => __( 'Dashboard', 'woocommerce' ),       // Dashboard
        'orders'             => __( 'Orders', 'woocommerce' ),          // Orders
        'edit-account'       => __( 'Account', 'woocommerce' ),         // Account
        'edit-address'       => __( 'Addresses', 'woocommerce' ),       // Addresses
        'wc-smart-coupons'   => __( 'Coupons', 'woocommerce' ),         // Coupons
        'downloads'          => __( 'Downloads', 'woocommerce' ),       // Downloads
        'payment-methods'    => __( 'Payment Methods', 'woocommerce' ), // Payment Methods
        'customer-logout'    => __( 'Logout', 'woocommerce' ),          // Logout
    );
    
    // Return the newly ordered menu
    return $newtaborder;
}

// Apply the function through the 'woocommerce_account_menu_items' filter
add_filter( 'woocommerce_account_menu_items', 'george_reorder_my_account_menu' );

/*
    End: The function returns the newly ordered menu items, improving user experience on the account page.
*/
