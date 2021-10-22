<?php

/**
 * Plugin Name:       adrianpajares.com - Custom Badge for WooCommerce (New and Sale)
 * Plugin URI:        https://adrianpajares.com/
 * Description:       Plugin to customize the WooCommerce Badge. Get new and sale badge together with a custom style. Shortcode: [ap_wc_badge]
 * Version:           1.0
 * Author:            adrianpajares.com
 * License:           MIT
*/

add_action( 'admin_init', 'ap_tools_settings_init_ap_wc_custom_badge' );


function ap_tools_settings_init_ap_wc_custom_badge(  ) { 

	register_setting( 'pluginPage', 'ap_tools_settings' );

	add_settings_field( 
		'ap_tools_text_field_1', 
		__( 'Badge "New" text', 'td_ap_tools' ), 
		'ap_tools_text_field_1_render', 
		'pluginPage', 
		'ap_tools_pluginPage_section' 
	);

	add_settings_field( 
		'ap_tools_text_field_2', 
		__( 'Badge "New" days', 'td_ap_tools' ), 
		'ap_tools_text_field_2_render', 
		'pluginPage', 
		'ap_tools_pluginPage_section' 
	);

	add_settings_field( 
		'ap_tools_text_field_3', 
		__( 'Badge "Sale" text', 'td_ap_tools' ), 
		'ap_tools_text_field_3_render', 
		'pluginPage', 
		'ap_tools_pluginPage_section' 
	);


}

function ap_tools_text_field_1_render(  ) { 

	$options = get_option( 'ap_tools_settings' );
	?>
	<input type='text' name='ap_tools_settings[ap_tools_text_field_1]' value='<?php echo $options['ap_tools_text_field_1']; ?>'>
	<?php

}


function ap_tools_text_field_2_render(  ) { 

	$options = get_option( 'ap_tools_settings' );
	?>
	<input type='number' name='ap_tools_settings[ap_tools_text_field_2]' value='<?php echo $options['ap_tools_text_field_2']; ?>'>
	<?php

}


function ap_tools_text_field_3_render(  ) { 

	$options = get_option( 'ap_tools_settings' );
	?>
	<input type='text' name='ap_tools_settings[ap_tools_text_field_3]' value='<?php echo $options['ap_tools_text_field_3']; ?>'>
	<?php

}


function ap_tools_settings_section_callback(  ) { 

	echo __( 'SECTION BADGE This section description', 'td_ap_tools' );

}


function ap_new_badge() {

    if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {

        return ;

    } else {

        global $product;
        $newness_days = 40; // Number of days the "NEW" badge is shown
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

add_shortcode('ap_wc_badge', 'ap_new_badge');