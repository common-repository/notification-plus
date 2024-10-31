<!--Conversion Who-->
<div class="notification-plus-form-group">
    <label for="settings_edd_who"><?php _e( 'Conversion Who:', 'notification-plus' ) ?></label>
    <input type="text" id="settings_edd_who" name="settings[edd_who]" value="<?php echo $woo_who; ?>"/>
    <p class="description">You can insert dynamic variables from your Order Data. Available Variables:
        <code>{full_name}</code>, <code>{first_name}</code>, <code>{last_name}</code>, <code>{product_name}</code>
    </p>
</div>

<!--Conversion Text-->
<div class="notification-plus-form-group">
    <label for="settings_edd_text"><?php _e( 'Conversion Text:', 'notification-plus' ) ?></label>
    <input type="text" id="settings_edd_text" name="settings[edd_text]" value="<?php echo $woo_text; ?>"/>
    <p class="description">You can insert dynamic variables from your Order Data. Available Variables:
        <code>{full_name}</code>, <code>{first_name}</code>, <code>{last_name}</code>, <code>{product_name}</code>
    </p>
</div>

<!--Product Type-->
<div class="notification-plus-form-group flex-row align-center">
    <label for="settings_edd_product_type" class="notification-plus-mr-10"><?php _e( 'Show Notification of:', 'notification-plus' ) ?></label>
    <select name="settings[edd_product_type]" class="settings_product_type" id="settings_edd_product_type">
        <option value="all" <?php selected( 'all', $edd_product_type ) ?>>All Products</option>
        <option value="product" <?php selected( 'product', $edd_product_type ) ?>>Specific Product
        </option>
    </select>
</div>

<div class="notification-plus-form-group <?php echo 'product' != $edd_product_type ? 'hidden' : ''; ?>">
    <label for="settings_edd_product"><?php _e( 'Select Product:', 'notification-plus' ) ?></label>
    <select name="settings[edd_product]" class="settings_product" id="settings_edd_product">
        <option value="">Select Product</option>
		<?php
		$products = get_posts( array(
			'post_type'   => 'download',
			'numberposts' => - 1,
			'post_status' => 'publish',
		) );

		if ( ! empty( $products ) ) {
			$products = wp_list_pluck( $products, 'post_title', 'ID' );
			foreach ( $products as $key => $item ) {
				printf( '<option value="%1$s" %3$s>%2$s</option>', $key, $item, selected( $edd_product, $key, false ) );
			}
		}

		?>
    </select>
    <p class="description"><?php _e( 'Select the specific product.', 'notification-plus' ) ?></p>
</div>

<!--Conversion Image Type-->
<div class="notification-plus-form-group flex-row align-center">
    <label for="settings_edd_image_type" class="notification-plus-mr-10"><?php _e( 'Image Type:', 'notification-plus' ) ?></label>
    <select name="settings[edd_image_type]" id="settings_edd_image_type" class="settings_image_type">
        <option value="none" <?php selected( 'none', $edd_image_type ) ?>>None</option>
        <option value="featured" <?php selected( 'featured', $edd_image_type ) ?>>Featured</option>
        <option value="custom" <?php selected( 'custom', $edd_image_type ) ?>>Custom</option>
    </select>
</div>

<div class="notification-plus-form-group <?php echo 'custom' != $edd_image_type ? 'hidden' : ''; ?>">
    <label for="settings_edd_image"><?php _e( 'Image', 'notification-plus' ) ?></label>

    <img src="<?php echo $edd_image; ?>" alt="<?php _e( 'Notification Image', 'notification-plus' ) ?>" class="notification-plus-image-preview <?php echo empty( $edd_image ) ? 'hidden' : ''; ?>">

    <div class="notification-plus-input-group">
        <input type="text" class="notification-plus-image settings_image" id="settings_edd_image" name="settings[edd_image]" value="<?php echo $edd_image; ?>"/>
        <button type="button" class="button button-primary notification-plus-choose-image notification-plus-ml-10 notification-plus-mr-10">
			<?php _e( 'Choose Image', 'notification-plus' ) ?>
        </button>
        <button type="button" class="button button-link-delete notification-plus-remove-image <?php echo empty( $edd_image ) ? 'hidden' : ''; ?>">
			<?php _e( 'Remove Image', 'notification-plus' ) ?>
        </button>
    </div>
    <p class="description"><?php _e( 'Leave empty for no image. Hint: icons8.com has a good library of small icons that you can
            use.', 'notification-plus' ) ?></p>
</div>

<!--URL Type-->
<div class="notification-plus-form-group flex-row align-center">
    <label for="settings_edd_url_type" class="notification-plus-mr-10"><?php _e( 'Notification URL Type:', 'notification-plus' ) ?></label>
    <select name="settings[edd_url_type]" id="settings_edd_url_type" class="settings_url_type">
        <option value="none" <?php selected( 'none', $edd_url_type ) ?>>None</option>
        <option value="product_page" <?php selected( 'product_page', $edd_url_type ) ?>>Product Page
        </option>
        <option value="custom" <?php selected( 'custom', $edd_url_type ) ?>>Custom</option>
    </select>
</div>

<div class="notification-plus-form-group <?php echo 'custom' != $edd_url_type ? 'hidden' : ''; ?>">
    <label for="settings_url"><?php _e( 'Notification URL', 'notification-plus' ) ?></label>
    <input type="text" class="settings_url" id="settings_edd_url" name="settings[edd_url]" value="<?php echo $url; ?>"/>
    <p class="description"><?php _e( 'The URL you want to user to go to after clicking the notification. Leave empty for no
            link.', 'notification-plus' ) ?></p>
</div>