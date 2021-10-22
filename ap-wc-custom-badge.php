<?php

/**
 * Plugin Name:       adrianpajares.com - Custom Badge for WooCommerce (New and Sale)
 * Plugin URI:        https://adrianpajares.com/
 * Description:       Plugin to customize the WooCommerce Badge. Get new and sale badge together with a custom style.
 * Version:           1.0
 * Author:            adrianpajares.com
 * License:           MIT
 */

function ap_new_badge() {

    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {


    } else {

        global $product;
        $newness_days = 40; // Number of days the badge is shown
        $created = strtotime( $product->get_date_created() );

        // Get product prices
        $regular_price = (float) $product->get_regular_price(); // Regular price
        $sale_price = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)
            
        
        if( $product->is_type( 'simple' ) ){

            $saving_percentage = round( 100 - ( $sale_price / $regular_price * 100 ), 0 ) . '%';
        
            if ( $saving_percentage > 0 ) {
                    echo '<span class="ap-badge-onsale">' . $saving_percentage . '</span>';
            }
            
            else {
            
            };
                
            if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
                    echo '<span class="ap-badge-new">' . esc_html__( 'New', 'woocommerce' ) . '</span>';
            }
                
            } elseif( $product->is_type( 'variable' ) ){

                    // Get all variations prices
                    $prices = $product->get_variation_prices( true );

                    // Get the prices (min and max)
                    $min_price = current( $prices['price'] );
                    $max_price = end( $prices['price'] );

                    // Get 2nd min price
                    $min_price2_arr = array_slice($prices['price'], 1, 1);
                    $min_price2 = $min_price2_arr[0];
                
                    $saving_percentage = round( 100 - ( $min_price / $max_price * 100 ), 0 ) . '%';
        
                if ( $saving_percentage > 0 ) {

                    echo '<span class="ap-badge-onsale">' . $saving_percentage . '</span>';
                }
            
                else {
            
            };
                
            if ( ( time() - ( 60 * 60 * 24 * $newness_days ) ) < $created ) {
                
                echo '<span class="ap-badge-new">' . esc_html__( 'New', 'woocommerce' ) . '</span>';

            }
                
        }

    }
        
      

}

add_shortcode('ap-badge', 'ap_new_badge');
