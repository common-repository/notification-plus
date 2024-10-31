<!--Conversion Who-->
<div class="notification-plus-form-group">
    <label for="settings_woo_who"><?php _e( 'Conversion Who:', 'notification-plus' ) ?></label>
    <input type="text" id="settings_woo_who" name="settings[woo_who]" value="<?php echo $woo_who; ?>"/>
    <p class="description">You can insert dynamic variables from your Order Data. Available Variables:
        <code>{full_name}</code>, <code>{first_name}</code>, <code>{last_name}</code>, <code>{product_name}</code>
    </p>
</div>

<!--Conversion Text-->
<div class="notification-plus-form-group">
    <label for="settings_woo_text"><?php _e( 'Conversion Text:', 'notification-plus' ) ?></label>
    <input type="text" id="settings_woo_text" name="settings[woo_text]" value="<?php echo $woo_text; ?>"/>
    <p class="description">You can insert dynamic variables from your Order Data. Available Variables:
        <code>{full_name}</code>, <code>{first_name}</code>, <code>{last_name}</code>, <code>{product_name}</code>
    </p>
</div>

<!--Conversion Product Type-->
<div class="notification-plus-form-group flex-row align-center">
    <label for="settings_woo_product_type" class="notification-plus-mr-10"><?php _e( 'Show Notification of:', 'notification-plus' ) ?></label>
    <select name="settings[woo_product_type]" class="settings_product_type" id="settings_woo_product_type">
        <option value="all" <?php selected( 'all', $woo_product_type ) ?>>All Products</option>
        <option value="product" <?php selected( 'product', $woo_product_type ) ?>>Specific Product
        </option>
    </select>
</div>

<div class="notification-plus-form-group <?php echo 'product' != $woo_product_type ? 'hidden' : ''; ?>">
    <label for="settings_woo_product"><?php _e( 'Select Product:', 'notification-plus' ) ?></label>
    <select name="settings[woo_product]" class="settings_product" id="settings_woo_product">
        <option value="">Select Product</option>
		<?php

		$products = get_posts( array(
			'post_type'   => 'product',
			'numberposts' => - 1,
			'post_status' => 'publish',
		) );

		if ( ! empty( $products ) ) {
			$products = wp_list_pluck( $products, 'post_title', 'ID' );
			foreach ( $products as $key => $item ) {
				printf( '<option value="%1$s" %3$s>%2$s</option>', $key, $item, selected( $woo_product, $key, false ) );
			}
		}

		?>
    </select>
    <p class="description"><?php _e( 'Select the specific product.', 'notification-plus' ) ?></p>
</div>

<!--Image Type-->
<div class="notification-plus-form-group flex-row align-center">
    <label for="settings_woo_image_type" class="notification-plus-mr-10"><?php _e( 'Image Type:', 'notification-plus' ) ?></label>
    <select name="settings[woo_image_type]" id="settings_woo_image_type" class="settings_image_type">
        <option value="none" <?php selected( 'none', $woo_image_type ) ?>>None</option>
        <option value="featured" <?php selected( 'featured', $woo_image_type ) ?>>Featured</option>
        <option value="custom" <?php selected( 'custom', $woo_image_type ) ?>>Custom</option>
    </select>
</div>

<!--Custom Image-->
<div class="notification-plus-form-group <?php echo 'custom' != $woo_image_type ? 'hidden' : ''; ?>">
    <label for="settings_woo_image"><?php _e( 'Image', 'notification-plus' ) ?></label>

    <img src="<?php echo $image; ?>" alt="<?php _e( 'Notification Image', 'notification-plus' ) ?>" class="notification-plus-image-preview <?php echo empty( $image ) ? 'hidden' : ''; ?>">

    <div class="notification-plus-input-group">
        <input type="text" class="notification-plus-image settings_image" id="settings_woo_image" name="settings[woo_image]" value="<?php echo $image; ?>"/>
        <button type="button" class="button button-primary notification-plus-choose-image notification-plus-ml-10 notification-plus-mr-10">
			<?php _e( 'Choose Image', 'notification-plus' ) ?>
        </button>
        <button type="button" class="button button-link-delete notification-plus-remove-image <?php echo empty( $image ) ? 'hidden' : ''; ?>">
			<?php _e( 'Remove Image', 'notification-plus' ) ?>
        </button>
    </div>
    <p class="description"><?php _e( 'Leave empty for no image. Hint: icons8.com has a good library of small icons that you can
            use.', 'notification-plus' ) ?></p>
</div>

<!--URL Type-->
<div class="notification-plus-form-group flex-row align-center">
    <label for="settings_woo_url_type" class="notification-plus-mr-10"><?php _e( 'Notification URL Type:', 'notification-plus' ) ?></label>
    <select name="settings[woo_url_type]" id="settings_woo_url_type" class="settings_url_type">
        <option value="none" <?php selected( 'none', $woo_url_type ) ?>>None</option>
        <option value="product_page" <?php selected( 'product_page', $woo_url_type ) ?>>Product Page
        </option>
        <option value="custom" <?php selected( 'custom', $woo_url_type ) ?>>Custom</option>
    </select>
</div>

<!--Custom URL-->
<div class="notification-plus-form-group <?php echo 'custom' != $woo_url_type ? 'hidden' : ''; ?>">
    <label for="settings_woo_url"><?php _e( 'Notification URL', 'notification-plus' ) ?></label>
    <input type="text" class="settings_woo_url" id="settings_woo_url" name="settings[woo_url]" value="<?php echo $woo_url; ?>"/>
    <p class="description"><?php _e( 'The URL you want to user to go to after clicking the notification. Leave empty for no
            link.', 'notification-plus' ) ?></p>
</div>