<?php

/**
 * WooCommerce Functionality Customizations and Extensions
 * 
 * Description:
 * This file is a dynamic script designed to customize and extend the 
 * functionality of WooCommerce. Its purpose is to continuously improve 
 * and adapt features based on project requirements.
 *
 * Key Features:
 * - The file will be frequently updated with new features and improvements.
 * - Designed to provide flexibility and scalability for WooCommerce installations.
 * - Includes well-documented code for easy maintenance and understanding.
 *
 * Usage:
 * - Place this file in your WordPress theme's directory (`wp-content/themes/your-theme/functions.php`).
 * - Ensure WooCommerce is installed and activated on your WordPress site.
 * - Customize the code as per your requirements.
 *
 * Author: Anastasios Lamprianidis
 * Site: https://alamprianidis.gr/
 *
 * License: GPL-3.0+
 *
 * Copyright (C) 2024
 *
 * This program is distributed under the GNU General Public License, version 3 (GPL-3.0+).
 * See the full license at https://www.gnu.org/licenses/gpl-3.0.html
 */

/*
    Start: This function is used to reorder the menu items on the "My Account" page of WooCommerce.
    It defines a new order for the tabs displayed to users when they visit their account page.
*/

function custom_reorder_account_menu( $menu_items ) {
    
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
add_filter( 'woocommerce_account_menu_items', 'custom_reorder_account_menu' );

/*
    End: The function returns the newly ordered menu items, improving user experience on the account page.
*/


/*
    Start: This function adds the payment method used by the customer to the order email sent to the store administrators.
    Using a WooCommerce hook, the function displays the payment method to the admin each time a new order is created.
*/

if ( !function_exists( 'evolution_add_payment_method_to_admin_new_order' ) ) :

    /**
     * Adds the payment method to the admin's email
     * 
     * @hooked woocommerce_email_after_order_table()
     */
    function evolution_add_payment_method_to_admin_new_order( $order, $is_admin_email ) {

        // Check if the email is being sent to the admin
        if ( $is_admin_email ) {
            // Display the payment method in the admin email
            echo '<p><strong>Used Payment Method:</strong> ' . $order->payment_method_title . '</p>';
        }
    }

    // Attach the function to WooCommerce via the 'woocommerce_email_after_order_table' hook
    add_action( 'woocommerce_email_after_order_table', 'evolution_add_payment_method_to_admin_new_order', 15, 2 );

endif;

/*
    End: The function uses the 'woocommerce_email_after_order_table' hook to add the payment method 
    to the admin's email. This allows administrators to easily see which payment method 
    the customer used for their order.
*/

/*
    Start: This function hides the prices and the "Add to Cart" buttons for users who are not logged in.
    Only logged-in users can see the prices and add products to the cart. For non-logged-in users,
    a message with a link to log in is displayed.
*/

if ( !function_exists( 'evolution_hide_price_add_cart_not_logged_in' ) ) :

    /**
     * Hides the price and "Add to Cart" buttons for non-logged-in users
     */
    function evolution_hide_price_add_cart_not_logged_in() { 
    
        // Check if the user is not logged in
        if ( !is_user_logged_in() ) {

            // Removes the "Add to Cart" button from the product pages in the product loop
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
            
            // Removes the "Add to Cart" button from the single product page
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
            
            // Removes the price from the single product page
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
            
            // Removes the price from the product list
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );  
            
            // Adds a message encouraging non-logged-in users to log in
            add_action( 'woocommerce_single_product_summary', 'evolution_print_login_to_see', 31 );
            add_action( 'woocommerce_after_shop_loop_item', 'evolution_print_login_to_see', 11 );
        }
    }

    // Add the function to the 'init' hook to check the user's login status when WooCommerce loads
    add_action('init', 'evolution_hide_price_add_cart_not_logged_in');

    /**
     * Displays a message with a link to the login page
     */
    function evolution_print_login_to_see() {
        // Displays a message with a link to the login page
        echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . '">' . __('Log in to see the prices', 'theme_name') . '</a>';
    }

endif;

/*
    End: This function provides an easy way to restrict access to product prices and the "Add to Cart" button
    only for logged-in users. Non-logged-in users are encouraged to log in through a displayed message 
    with a link to the login page.
*/

/*
    Start: This function enables the Gutenberg editor for WooCommerce products.
    By default, WooCommerce uses the classic editor for products, but with this function, 
    you can allow the use of the Gutenberg editor for editing products.
*/

function activate_gutenberg_product( $can_edit, $post_type ) {

    // Check if the post type is 'product' (WooCommerce product)
    if ( $post_type == 'product' ) {
        $can_edit = true;  // Enables the use of the Gutenberg editor
    }
    
    return $can_edit;  // Returns the value of the $can_edit variable
}

add_filter( 'use_block_editor_for_post_type', 'activate_gutenberg_product', 10, 2 );

/*
    End: This function is hooked to the 'use_block_editor_for_post_type' filter, which determines whether 
    the Gutenberg editor is used for a specific post type. By adding this filter, the use of Gutenberg 
    is enabled for the "product" post type in WooCommerce.
*/

/*
    Start: This function automatically deletes the images associated with a WooCommerce product 
    when the product is deleted. Both the featured image and the gallery images are deleted.
    This is useful for keeping the file system clean and avoiding the storage of unnecessary images.
*/

add_action( 'before_delete_post', 'delete_product_images', 10, 1 );

function delete_product_images( $post_id )
{

    // Retrieve the product from WooCommerce using the post ID
    $product = wc_get_product( $post_id );

    // If the product is not found, terminate the function
    if ( !$product ) {
        return;
    }

    // Get the ID of the product's featured image
    $featured_image_id = $product->get_image_id();
    
    // Get the IDs of the product's gallery images
    $image_galleries_id = $product->get_gallery_image_ids();

    // Delete the featured image if it exists
    if( !empty( $featured_image_id ) ) {
        wp_delete_post( $featured_image_id );  // Delete the image from WordPress
    }

    // Delete the gallery images if they exist
    if( !empty( $image_galleries_id ) ) {
        foreach( $image_galleries_id as $single_image_id ) {
            wp_delete_post( $single_image_id );  // Delete each image from the gallery
        }
    }
}

/*
    End: This function is hooked to 'before_delete_post', so it executes before a post is deleted.
    The use of this function ensures that the images associated with products are automatically deleted when 
    the product is deleted, keeping the system free of unnecessary images.
*/

/*
    Start: This function adds a new column with product thumbnails in the "My Orders" table on the "My Account" page in WooCommerce.
    It moves the "Order Number" column and adds the new column before it, so the product thumbnails appear next to the order number.
*/

add_filter( 'woocommerce_my_account_my_orders_columns', 'filter_woocommerce_my_account_my_orders_columns', 10, 1 );
function filter_woocommerce_my_account_my_orders_columns( $columns ) {

    // Retain the "Order Number" column in the new layout
    $new_column = array( 'order-number' => $columns['order-number']);
    
    // Remove the original "Order Number" column
    unset($columns['order-number']);

    // Add a new column for product thumbnails
    $new_column['order-thumbnails'] = '';

    // Return the new column layout
    return array_merge($new_column, $columns);
}


add_action( 'woocommerce_my_account_my_orders_column_order-thumbnails', 'filter_woocommerce_my_account_my_orders_column_order', 10, 1 );
function filter_woocommerce_my_account_my_orders_column_order( $order ) {
    /*
    This function displays the product thumbnails in the "order-thumbnails" column we created. 
    For each product in the order, the function retrieves the product object and its thumbnail, 
    which is then displayed in the column.
    */

    // Loop through each product in the order
    foreach( $order->get_items() as $item ) {
        // Get the product object (WC_Product) from the order item
        $product   = $item->get_product(); 
        
        // Get the product's thumbnail with dimensions 36x36
        $thumbnail = $product->get_image(array( 36, 36)); 
        
        // Check if the product has an image and display the thumbnail
        if( $product->get_image_id() > 0 ) {
            echo $thumbnail . '&nbsp;' ;  // Display the thumbnail
        }
    }
}

/*
    End: The first function creates a new column for product thumbnails in the "My Orders" table on the "My Account" page, 
    while the second function fills this column with product images. 
    With this change, users can see thumbnails of the products they have ordered next to their order number.
*/

/*
    Start: This function automates the completion of orders that contain only virtual products.
    Typically, orders go through the "processing" stage first, but for virtual products, which do not require physical shipping,
    the order can be automatically marked as "Completed".
*/

function auto_complete_virtual_orders( $payment_complete_status, $order_id, $order ) {

    $current_status = $order->get_status();  // Retrieve the current status of the order

    // We want to update the status only if the order is in one of the allowed statuses
    $allowed_current_statuses = array( 'on-hold', 'pending', 'failed' );

    // Check if the payment status is "processing" and the current status is allowed
    if ( 'processing' === $payment_complete_status && in_array( $current_status, $allowed_current_statuses ) ) {

        $order_items = $order->get_items();  // Retrieve the items in the order

        // Create an array of the products in the order
        $order_products = array_filter( array_map( function( $item ) {
            // Retrieve the product associated with each order item
            return $item->get_product();
        }, $order_items ), function( $product ) {
            // Filter out anything that is not a product
            return !! $product;
        } );

        // Check if the order contains products
        if ( count( $order_products > 0 ) ) {
            // Check if all products in the order are "virtual"
            $is_virtual_order = array_reduce( $order_products, function( $virtual_order_so_far, $product ) {
                // If the product is virtual, continue checking
                return $virtual_order_so_far && $product->is_virtual();
            }, true );

            // If all products are virtual, update the order status to "Completed"
            if ( $is_virtual_order ) {
                $payment_complete_status = 'completed';
            }
        }
    }

    return $payment_complete_status;  // Return the updated payment status
}

/*
    End: This function simplifies the management of orders with virtual products by automating the completion process 
    when the conditions are met. This allows the order to be marked as "Completed" without manual intervention, 
    as long as the products are virtual and do not require shipping or physical delivery.
*/

/*
    Start: This function optimizes the loading of WooCommerce scripts and styles.
    It removes the WooCommerce styles and scripts from pages that are not related to WooCommerce 
    (such as product, cart, checkout, or account pages), thus improving the performance of the website.
*/

if ( !function_exists( 'evolution_manage_woocommerce_styles' ) ) :

    /**
     * Optimizes WooCommerce scripts
     * Removes WooCommerce styles and scripts from pages not related to WooCommerce
     */
    function evolution_manage_woocommerce_styles() {

        // First, check if WooCommerce is installed to avoid errors
        if ( function_exists( 'is_woocommerce' ) ) {

            // Removes the styles and scripts if the page is not a WooCommerce page (not product, cart, checkout, or account)
            if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {          
                wp_dequeue_style( 'woocommerce-layout' );        // Removes WooCommerce layout style
                wp_dequeue_style( 'woocommerce-smallscreen' );   // Removes small screen styles
                wp_dequeue_style( 'woocommerce-general' );       // Removes general WooCommerce styles
                wp_dequeue_style( 'evolution-woostyles' );       // Removes custom WooCommerce styles
                wp_dequeue_script( 'wc_price_slider' );          // Removes price slider script
                wp_dequeue_script( 'wc-single-product' );        // Removes single product page scripts
                wp_dequeue_script( 'wc-add-to-cart' );           // Removes add to cart scripts
                wp_dequeue_script( 'wc-cart-fragments' );        // Removes cart fragments scripts
                wp_dequeue_script( 'wc-checkout' );              // Removes checkout scripts
                wp_dequeue_script( 'wc-add-to-cart-variation' ); // Removes variation add to cart scripts
                wp_dequeue_script( 'wc-cart' );                  // Removes cart scripts
                wp_dequeue_script( 'wc-chosen' );                // Removes chosen.js library
                wp_dequeue_script( 'woocommerce' );              // Removes general WooCommerce script
                wp_dequeue_script( 'prettyPhoto' );              // Removes prettyPhoto gallery script
                wp_dequeue_script( 'prettyPhoto-init' );         // Removes prettyPhoto initialization script
                wp_dequeue_script( 'jquery-blockui' );           // Removes jQuery blockUI script
                wp_dequeue_script( 'jquery-placeholder' );       // Removes jQuery placeholder script
                wp_dequeue_script( 'fancybox' );                 // Removes fancybox script
                wp_dequeue_script( 'jqueryui' );                 // Removes jQuery UI script
            }
        }
    }

    // Add the function to the 'wp_enqueue_scripts' hook with a high priority (99) to ensure it runs later
    add_action( 'wp_enqueue_scripts', 'evolution_manage_woocommerce_styles', 99 );

endif;

/*
    End: This optimization prevents the loading of WooCommerce scripts and styles on pages 
    that do not need them, improving the page load speed on those pages.
    Especially on high-traffic websites, this can significantly improve performance.
*/

/*
    Start: This function hides prices and "Add to Cart" buttons for users who are not logged in.
    It removes the "Add to Cart" button and the product price from both the product pages and the shop page for non-logged-in users.
    Instead, a message with a login link is displayed, encouraging users to log in to see the prices and add products to the cart.
*/

if ( !function_exists( 'evolution_hide_price_add_cart_not_logged_in' ) ) :
 /**
  * Show prices only for registered and logged-in users
  */
function evolution_hide_price_add_cart_not_logged_in() { 
    
    // Check if the user is not logged in
    if ( !is_user_logged_in() ) {

        // Remove the "Add to Cart" button and price from product and shop pages
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );  
        
        // Add a message with login link
        add_action( 'woocommerce_single_product_summary', 'evolution_print_login_to_see', 31 );
        add_action( 'woocommerce_after_shop_loop_item', 'evolution_print_login_to_see', 11 );
    }
}

// Add function to init hook
add_action('init', 'evolution_hide_price_add_cart_not_logged_in');

/**
 * Displays a login link with a message
 */
function evolution_print_login_to_see() {
    echo '<p>' . 
        __('Please log in to see the prices.', 'theme_name') . 
        ' <a href="' . esc_url( get_permalink(wc_get_page_id('myaccount')) ) . '">' . 
        __('Log in here', 'theme_name') . 
        '</a></p>';
}
endif;

/*
    End: This function helps to hide product prices and the "Add to Cart" button for non-logged-in users. 
    It ensures that only logged-in users can view the prices and add products to their cart. If a user is not logged in, 
    a message with a link to the login page is shown. This can improve user experience by prompting users to log in 
    to access the store's features.
*/

?>
