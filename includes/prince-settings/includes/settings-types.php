<?php
/**
 * Functions used to build each option type.
 *
 * @package   Prince
 * @author    Prince Ahmed <israilahmed5@gmail.com>
 * @copyright Copyright (c) 2019, Prince Ahmed
 * @since     1.0.0
 */

/**
 * Builds the HTML for each of the available option types by calling those
 * function with call_user_func and passing the arguments to the second param.
 *
 * All fields are required!
 *
 * @param array $args The array of arguments are as follows:
 * @param string $type Type of option.
 * @param string $field_id The field ID.
 * @param string $field_name The field Name.
 * @param mixed $field_value The field value is a string or an array of values.
 * @param string $field_desc The field description.
 * @param string $field_std The standard value.
 * @param string $field_class Extra CSS classes.
 * @param array $field_choices The array of option choices.
 * @param array $field_settings The array of settings for a list item.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_display_by_type' ) ) {

	function prince_display_by_type( $args = array() ) {

		/* allow filters to be executed on the array */
		$args = apply_filters( 'prince_display_by_type', $args );

		/* build the function name */
		$function_name_by_type = str_replace( '-', '_', 'prince_type_' . $args['type'] );

		/* call the function & pass in arguments array */
		if ( function_exists( $function_name_by_type ) ) {
			call_user_func( $function_name_by_type, $args );
		} else {
			echo '<p>' . __( 'Sorry, this function does not exist', 'notification-plus' ) . '</p>';
		}

	}

}

/**
 * Background option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_background' ) ) {

	function prince_type_background( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;
		$block = $field_block ? true : false;

		/* If an attachment ID is stored here fetch its URL and replace the value */
		if ( isset( $field_value['background-image'] ) && wp_attachment_is_image( $field_value['background-image'] ) ) {

			$attachment_data = wp_get_attachment_image_src( $field_value['background-image'], 'original' );

			/* check for attachment data */
			if ( $attachment_data ) {

				$field_src = $attachment_data[0];

			}

		}

		/* format setting outer wrapper */
		echo '<div class="format-setting type-background ' .( $has_desc ? 'has-desc' : 'no-desc' ). '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_background_fields = apply_filters( 'prince_recognized_background_fields', array(
			'background-color',
			'background-repeat',
			'background-attachment',
			'background-position',
			'background-size',
			'background-image'
		), $field_id );

		echo '<div class="prince-background-group">';

		/* build background color */
		if ( in_array( 'background-color', $prince_recognized_background_fields ) ) {

			echo '<div class="prince-ui-colorpicker-input-wrap">';

			/* colorpicker JS */
			echo '<script>jQuery(document).ready(function($) { PRINCE.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			/* set background color */
			$background_color = isset( $field_value['background-color'] ) ? esc_attr( $field_value['background-color'] ) : '';

			/* input */
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-color]" id="' . $field_id . '-picker" value="' . $background_color . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';

		}

		/* build background repeat */
		if ( in_array( 'background-repeat', $prince_recognized_background_fields ) ) {

			$background_repeat = isset( $field_value['background-repeat'] ) ? esc_attr( $field_value['background-repeat'] ) : '';

			echo '<select name="' . esc_attr( $field_name ) . '[background-repeat]" id="' . esc_attr( $field_id ) . '-repeat" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . __( 'background-repeat', 'notification-plus' ) . '</option>';
			foreach ( prince_recognized_background_repeat( $field_id ) as $key => $value ) {

				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_repeat, $key, false ) . '>' . esc_attr( $value ) . '</option>';

			}

			echo '</select>';

		}

		/* build background attachment */
		if ( in_array( 'background-attachment', $prince_recognized_background_fields ) ) {

			$background_attachment = isset( $field_value['background-attachment'] ) ? esc_attr( $field_value['background-attachment'] ) : '';

			echo '<select name="' . esc_attr( $field_name ) . '[background-attachment]" id="' . esc_attr( $field_id ) . '-attachment" class="prince-ui-select ' . $field_class . '">';

			echo '<option value="">' . __( 'background-attachment', 'notification-plus' ) . '</option>';

			foreach ( prince_recognized_background_attachment( $field_id ) as $key => $value ) {

				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_attachment, $key, false ) . '>' . esc_attr( $value ) . '</option>';

			}

			echo '</select>';

		}

		/* build background position */
		if ( in_array( 'background-position', $prince_recognized_background_fields ) ) {

			$background_position = isset( $field_value['background-position'] ) ? esc_attr( $field_value['background-position'] ) : '';

			echo '<select name="' . esc_attr( $field_name ) . '[background-position]" id="' . esc_attr( $field_id ) . '-position" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . __( 'background-position', 'notification-plus' ) . '</option>';

			foreach ( prince_recognized_background_position( $field_id ) as $key => $value ) {

				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_position, $key, false ) . '>' . esc_attr( $value ) . '</option>';

			}

			echo '</select>';

		}

		/* Build background size  */
		if ( in_array( 'background-size', $prince_recognized_background_fields ) ) {

			/**
			 * Use this filter to create a select instead of an text input.
			 * Be sure to return the array in the correct format. Add an empty
			 * value to the first choice so the user can leave it blank.
			 *
			 * array(
			 * array(
			 * 'label' => 'background-size',
			 * 'value' => ''
			 * ),
			 * array(
			 * 'label' => 'cover',
			 * 'value' => 'cover'
			 * ),
			 * array(
			 * 'label' => 'contain',
			 * 'value' => 'contain'
			 * )
			 * )
			 *
			 */
			$choices = apply_filters( 'prince_type_background_size_choices', '', $field_id );

			if ( is_array( $choices ) && ! empty( $choices ) ) {

				/* build select */
				echo '<select name="' . esc_attr( $field_name ) . '[background-size]" id="' . esc_attr( $field_id ) . '-size" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

				foreach ( (array) $choices as $choice ) {
					if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
						echo '<option value="' . esc_attr( $choice['value'] ) . '"' . selected( ( isset( $field_value['background-size'] ) ? $field_value['background-size'] : '' ), $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
					}
				}

				echo '</select>';

			} else {

				echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-size]" id="' . esc_attr( $field_id ) . '-size" value="' . ( isset( $field_value['background-size'] ) ? esc_attr( $field_value['background-size'] ) : '' ) . '" class="widefat prince-background-size-input prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'background-size', 'notification-plus' ) . '" />';

			}

		}

		echo '</div>';

		/* build background image */
		if ( in_array( 'background-image', $prince_recognized_background_fields ) ) {

			echo '<div class="prince-ui-upload-parent">';

			/* input */
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-image]" id="' . esc_attr( $field_id ) . '" value="' . ( isset( $field_value['background-image'] ) ? esc_attr( $field_value['background-image'] ) : '' ) . '" class="widefat prince-ui-upload-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'background-image', 'notification-plus' ) . '" />';

			/* add media button */
			echo '<a href="javascript:void(0);" class="prince_upload_media prince-ui-button button button-primary light" rel="' . $post_id . '" title="' . __( 'Add Media', 'notification-plus' ) . '"><span class="icon dashicons dashicons-plus-alt"></span>' . __( 'Add Media', 'notification-plus' ) . '</a>';

			echo '</div>';

			/* media */
			if ( isset( $field_value['background-image'] ) && $field_value['background-image'] !== '' ) {

				/* replace image src */
				if ( isset( $field_src ) ) {
					$field_value['background-image'] = $field_src;
				}

				echo '<div class="prince-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';

				if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value['background-image'] ) ) {
					echo '<div class="prince-ui-image-wrap"><img src="' . esc_url( $field_value['background-image'] ) . '" alt="" /></div>';
				}

				echo '<a href="javascript:(void);" class="prince-ui-remove-media prince-ui-button button button-secondary light" title="' . __( 'Remove Media', 'notification-plus' ) . '"><span class="icon dashicons dashicons-trash"></span></a>';

				echo '</div>';

			}

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Border Option Type
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     The options arguments
 *
 * @return    string    The markup.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_border' ) ) {

	function prince_type_border( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-border ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_border_fields = apply_filters( 'prince_recognized_border_fields', array(
			'width',
			'unit',
			'style',
			'color'
		), $field_id );

		/* build border width */
		if ( in_array( 'width', $prince_recognized_border_fields ) ) {

			$width = isset( $field_value['width'] ) ? esc_attr( $field_value['width'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-sixth"><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'width', 'notification-plus' ) . '" /></div>';

		}

		/* build unit dropdown */
		if ( in_array( 'unit', $prince_recognized_border_fields ) ) {

			echo '<div class="prince-option-group prince-option-group--one-fourth">';

			echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . __( 'unit', 'notification-plus' ) . '</option>';

			foreach ( prince_recognized_border_unit_types( $field_id ) as $unit ) {
				echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
			}

			echo '</select>';

			echo '</div>';

		}

		/* build style dropdown */
		if ( in_array( 'style', $prince_recognized_border_fields ) ) {

			echo '<div class="prince-option-group prince-option-group--one-fourth">';

			echo '<select name="' . esc_attr( $field_name ) . '[style]" id="' . esc_attr( $field_id ) . '-style" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . __( 'style', 'notification-plus' ) . '</option>';

			foreach ( prince_recognized_border_style_types( $field_id ) as $key => $style ) {
				echo '<option value="' . esc_attr( $key ) . '"' . ( isset( $field_value['style'] ) ? selected( $field_value['style'], $key, false ) : '' ) . '>' . esc_attr( $style ) . '</option>';
			}

			echo '</select>';

			echo '</div>';

		}

		/* build color */
		if ( in_array( 'color', $prince_recognized_border_fields ) ) {

			echo '<div class="prince-ui-colorpicker-input-wrap">';

			/* colorpicker JS */
			echo '<script>jQuery(document).ready(function($) { PRINCE.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			/* set color */
			$color = isset( $field_value['color'] ) ? esc_attr( $field_value['color'] ) : '';

			/* input */
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . $field_id . '-picker" value="' . $color . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Box Shadow Option Type
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     The options arguments
 *
 * @return    string    The markup.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_box_shadow' ) ) {

	function prince_type_box_shadow( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-box-shadow ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_box_shadow_fields = apply_filters( 'prince_recognized_box_shadow_fields', array(
			'inset',
			'offset-x',
			'offset-y',
			'blur-radius',
			'spread-radius',
			'color'
		), $field_id );

		/* build inset */
		if ( in_array( 'inset', $prince_recognized_box_shadow_fields ) ) {

			echo '<div class="prince-option-group prince-option-group--checkbox"><p>';
			echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[inset]" id="' . esc_attr( $field_id ) . '-inset" value="inset" ' . ( isset( $field_value['inset'] ) ? checked( $field_value['inset'], 'inset', false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
			echo '<label for="' . esc_attr( $field_id ) . '-inset">inset</label>';
			echo '</p></div>';

		}

		/* build horizontal offset */
		if ( in_array( 'offset-x', $prince_recognized_box_shadow_fields ) ) {

			$offset_x = isset( $field_value['offset-x'] ) ? esc_attr( $field_value['offset-x'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-fifth"><span class="prince-icon-arrows-h prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[offset-x]" id="' . esc_attr( $field_id ) . '-offset-x" value="' . $offset_x . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'offset-x', 'notification-plus' ) . '" /></div>';

		}

		/* build vertical offset */
		if ( in_array( 'offset-y', $prince_recognized_box_shadow_fields ) ) {

			$offset_y = isset( $field_value['offset-y'] ) ? esc_attr( $field_value['offset-y'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-fifth"><span class="prince-icon-arrows-v prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[offset-y]" id="' . esc_attr( $field_id ) . '-offset-y" value="' . $offset_y . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'offset-y', 'notification-plus' ) . '" /></div>';

		}

		/* build blur-radius radius */
		if ( in_array( 'blur-radius', $prince_recognized_box_shadow_fields ) ) {

			$blur_radius = isset( $field_value['blur-radius'] ) ? esc_attr( $field_value['blur-radius'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-fifth"><span class="prince-icon-circle prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[blur-radius]" id="' . esc_attr( $field_id ) . '-blur-radius" value="' . $blur_radius . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'blur-radius', 'notification-plus' ) . '" /></div>';

		}

		/* build spread-radius radius */
		if ( in_array( 'spread-radius', $prince_recognized_box_shadow_fields ) ) {

			$spread_radius = isset( $field_value['spread-radius'] ) ? esc_attr( $field_value['spread-radius'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-fifth"><span class="prince-icon-arrows-alt prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[spread-radius]" id="' . esc_attr( $field_id ) . '-spread-radius" value="' . $spread_radius . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'spread-radius', 'notification-plus' ) . '" /></div>';

		}

		/* build color */
		if ( in_array( 'color', $prince_recognized_box_shadow_fields ) ) {

			echo '<div class="prince-ui-colorpicker-input-wrap">';

			/* colorpicker JS */
			echo '<script>jQuery(document).ready(function($) { PRINCE.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			/* set color */
			$color = isset( $field_value['color'] ) ? esc_attr( $field_value['color'] ) : '';

			/* input */
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . esc_attr( $field_id ) . '-picker" value="' . $color . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Category Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_category_checkbox' ) ) {

	function prince_type_category_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-category-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* get category array */
		$categories = get_categories( apply_filters( 'prince_type_category_checkbox_query', array( 'hide_empty' => false ), $field_id ) );

		/* build categories */
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $category->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '" value="' . esc_attr( $category->term_id ) . '" ' . ( isset( $field_value[ $category->term_id ] ) ? checked( $field_value[ $category->term_id ], $category->term_id, false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '">' . esc_attr( $category->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . __( 'No Categories Found', 'notification-plus' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Category Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_category_select' ) ) {

	function prince_type_category_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-category-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build category */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* get category array */
		$categories = get_categories( apply_filters( 'prince_type_category_select_query', array( 'hide_empty' => false ), $field_id ) );

		/* has cats */
		if ( ! empty( $categories ) ) {
			echo '<option value="">-- ' . __( 'Choose One', 'notification-plus' ) . ' --</option>';
			foreach ( $categories as $category ) {
				echo '<option value="' . esc_attr( $category->term_id ) . '"' . selected( $field_value, $category->term_id, false ) . '>' . esc_attr( $category->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Categories Found', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_checkbox' ) ) {

	function prince_type_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build checkbox */
		foreach ( (array) $field_choices as $key => $choice ) {
			if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '" ' . ( isset( $field_value[ $key ] ) ? checked( $field_value[ $key ], $choice['value'], false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label>';
				echo '</p>';
			}
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Colorpicker option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 * @updated   2.2.0
 */
if ( ! function_exists( 'prince_type_colorpicker' ) ) {

	function prince_type_colorpicker( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-colorpicker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build colorpicker */
		echo '<div class="prince-ui-colorpicker-input-wrap">';

		/* colorpicker JS */
		echo '<script>jQuery(document).ready(function($) { PRINCE.bind_colorpicker("' . esc_attr( $field_id ) . '"); });</script>';

		/* set the default color */
		$std = $field_std ? 'data-default-color="' . $field_std . '"' : '';

		/* input */
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" ' . $std . ' />';

		echo '</div>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Colorpicker Opacity option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_colorpicker_opacity' ) ) {

	function prince_type_colorpicker_opacity( $args = array() ) {

		$args['field_class'] = isset( $args['field_class'] ) ? $args['field_class'] . ' prince-colorpicker-opacity' : 'prince-colorpicker-opacity';
		prince_type_colorpicker( $args );

	}

}

/**
 * CSS option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_css' ) ) {

	function prince_type_css( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-css simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build textarea for CSS */
		echo '<textarea class="hidden" id="textarea_' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_name ) . '">' . esc_attr( $field_value ) . '</textarea>';

		/* build pre to convert it into ace editor later */
		echo '<pre class="prince-css-editor ' . esc_attr( $field_class ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</pre>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Custom Post Type Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_custom_post_type_checkbox' ) ) {

	function prince_type_custom_post_type_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-custom-post-type-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* setup the post types */
		$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

		/* query posts array */
		$my_posts = get_posts( apply_filters( 'prince_type_custom_post_type_checkbox_query', array(
			'post_type'      => $post_type,
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'any'
		), $field_id ) );

		/* has posts */
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			foreach ( $my_posts as $my_post ) {
				$post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[ $my_post->ID ] ) ? checked( $field_value[ $my_post->ID ], $my_post->ID, false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . $post_title . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . __( 'No Posts Found', 'notification-plus' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Custom Post Type Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_custom_post_type_select' ) ) {

	function prince_type_custom_post_type_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-custom-post-type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build category */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* setup the post types */
		$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

		/* query posts array */
		$my_posts = get_posts( apply_filters( 'prince_type_custom_post_type_select_query', array(
			'post_type'      => $post_type,
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'any'
		), $field_id ) );

		/* has posts */
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			echo '<option value="">-- ' . __( 'Choose One', 'notification-plus' ) . ' --</option>';
			foreach ( $my_posts as $my_post ) {
				$post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
				echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $field_value, $my_post->ID, false ) . '>' . $post_title . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Posts Found', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Date Picker option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_date_picker' ) ) {

	function prince_type_date_picker( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* filter date format */
		$date_format = apply_filters( 'prince_type_date_picker_date_format', 'yy-mm-dd', $field_id );

		/**
		 * Filter the addition of the readonly attribute.
		 *
		 * @param bool $is_readonly Whether to add the 'readonly' attribute. Default 'false'.
		 * @param string $field_id The field ID.
		 *
		 * @since     1.0.0
		 *
		 */
		$is_readonly = apply_filters( 'prince_type_date_picker_readonly', false, $field_id );

		/* format setting outer wrapper */
		echo '<div class="format-setting type-date-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* date picker JS */
		echo '<script>jQuery(document).ready(function($) { PRINCE.bind_date_picker("' . esc_attr( $field_id ) . '", "' . esc_attr( $date_format ) . '"); });</script>';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build date picker */
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '"' . ( $is_readonly == true ? ' readonly' : '' ) . ' />';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Date Time Picker option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_date_time_picker' ) ) {

	function prince_type_date_time_picker( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* filter date format */
		$date_format = apply_filters( 'prince_type_date_time_picker_date_format', 'yy-mm-dd', $field_id );

		/**
		 * Filter the addition of the readonly attribute.
		 *
		 * @param bool $is_readonly Whether to add the 'readonly' attribute. Default 'false'.
		 * @param string $field_id The field ID.
		 *
		 * @since     1.0.0
		 *
		 */
		$is_readonly = apply_filters( 'prince_type_date_time_picker_readonly', false, $field_id );

		/* format setting outer wrapper */
		echo '<div class="format-setting type-date-time-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* date time picker JS */
		echo '<script>jQuery(document).ready(function($) { PRINCE.bind_date_time_picker("' . esc_attr( $field_id ) . '", "' . esc_attr( $date_format ) . '"); });</script>';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build date time picker */
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '"' . ( $is_readonly == true ? ' readonly' : '' ) . ' />';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Dimension Option Type
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     The options arguments
 *
 * @return    string    The markup.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_dimension' ) ) {

	function prince_type_dimension( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-dimension ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_dimension_fields = apply_filters( 'prince_recognized_dimension_fields', array(
			'width',
			'height',
			'unit'
		), $field_id );

		/* build width dimension */
		if ( in_array( 'width', $prince_recognized_dimension_fields ) ) {

			$width = isset( $field_value['width'] ) ? esc_attr( $field_value['width'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-third"><span class="prince-icon-arrows-h prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'width', 'notification-plus' ) . '" /></div>';

		}

		/* build height dimension */
		if ( in_array( 'height', $prince_recognized_dimension_fields ) ) {

			$height = isset( $field_value['height'] ) ? esc_attr( $field_value['height'] ) : '';

			echo '<div class="prince-option-group prince-option-group--one-third"><span class="prince-icon-arrows-v prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[height]" id="' . esc_attr( $field_id ) . '-height" value="' . esc_attr( $height ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'height', 'notification-plus' ) . '" /></div>';

		}

		/* build unit dropdown */
		if ( in_array( 'unit', $prince_recognized_dimension_fields ) ) {

			echo '<div class="prince-option-group prince-option-group--one-third prince-option-group--is-last">';

			echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . __( 'unit', 'notification-plus' ) . '</option>';

			foreach ( prince_recognized_dimension_unit_types( $field_id ) as $unit ) {
				echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
			}

			echo '</select>';

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Gallery option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     The options arguments
 *
 * @return    string    The gallery metabox markup.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_gallery' ) ) {

	function prince_type_gallery( $args = array() ) {

		// Turns arguments array into variables
		extract( $args );

		// Verify a description
		$has_desc = $field_desc ? true : false;

		// Format setting outer wrapper
		echo '<div class="format-setting type-gallery ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		// Description
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		// Format setting inner wrapper
		echo '<div class="format-setting-inner">';

		// Setup the post type
		$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

		$field_value = trim( $field_value );

		// Saved values
		echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="prince-gallery-value ' . esc_attr( $field_class ) . '" />';

		// Search the string for the IDs
		preg_match( '/ids=\'(.*?)\'/', $field_value, $matches );

		// Turn the field value into an array of IDs
		if ( isset( $matches[1] ) ) {

			// Found the IDs in the shortcode
			$ids = explode( ',', $matches[1] );

		} else {

			// The string is only IDs
			$ids = ! empty( $field_value ) && $field_value != '' ? explode( ',', $field_value ) : array();

		}

		// Has attachment IDs
		if ( ! empty( $ids ) ) {

			echo '<ul class="prince-gallery-list">';

			foreach ( $ids as $id ) {

				if ( $id == '' ) {
					continue;
				}

				$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );

				echo '<li><img  src="' . $thumbnail[0] . '" width="75" height="75" /></li>';

			}

			echo '</ul>';

			echo '
          <div class="prince-gallery-buttons">
            <a href="#" class="prince-ui-button button button-secondary hug-left prince-gallery-delete">' . __( 'Delete Gallery', 'notification-plus' ) . '</a>
            <a href="#" class="prince-ui-button button button-primary right hug-right prince-gallery-edit">' . __( 'Edit Gallery', 'notification-plus' ) . '</a>
          </div>';

		} else {

			echo '
          <div class="prince-gallery-buttons">
            <a href="#" class="prince-ui-button button button-primary right hug-right prince-gallery-edit">' . __( 'Create Gallery', 'notification-plus' ) . '</a>
          </div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Playlist option type
 *
 * See @prince_display_by_type to see the full list of available arguments
 *
 * @param array the options arguments
 *
 * @return string the gallery metabox markup
 *
 * @access public
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'prince_type_playlist' ) ) {

	function prince_type_playlist( $args = [] ) {
		// Turns arguments array into variables
		extract( $args );

		// Check Playlist type from settings['type']
		$playlist_type = ! empty( $field_settings['type'] ) && 'video' == $field_settings['type'] ? 'video' : 'audio';

		// Verify a description
		$has_desc = $field_desc ? true : false;

		// Format setting outer wrapper
		echo '<div class="format-setting type-playlist ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		// Description
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		// Format setting inner wrapper
		echo '<div class="format-setting-inner">';

		// Setup the post type
		$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

		$field_value = trim( $field_value );

		// Saved values
		echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="prince-playlist-value ' . esc_attr( $field_class ) . '" />';

		// Search the string for the IDs
		preg_match( '/ids=\'(.*?)\'/', $field_value, $matches );

		// Turn the field value into an array of IDs
		if ( isset( $matches[1] ) ) {

			// Found the IDs in the shortcode
			$ids = explode( ',', $matches[1] );

		} else {

			// The string is only IDs
			$ids = ! empty( $field_value ) && $field_value != '' ? explode( ',', $field_value ) : array();

		}

		// Has attachment IDs
		if ( ! empty( $ids ) ) {

			echo '<ul class="prince-playlist-list attachments">';

			foreach ( $ids as $id ) {

				if ( $id == '' ) {
					continue;
				}

				?>

                <li class="attachment">
                    <div class="attachment-preview">
                        <div class="thumbnail">
                            <div class="centered">
                                <img src="<?php echo includes_url( 'images/media/' . $playlist_type . '.png' ); ?>" class="icon" draggable="false" alt="">
                            </div>
                            <div class="filename">
                                <div>
									<?php
									$file = get_attached_file( $id );
									echo esc_html( wp_basename( $file ) );
									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

				<?php
			}

			echo '</ul>';

			echo '
          <div class="prince-playlist-buttons">
            <a href="#" class="prince-ui-button button button-secondary hug-left prince-playlist-delete"  data-type="' . $playlist_type . '">' . __( 'Delete Playlist', 'notification-plus' ) . '</a>
            <a href="#" class="prince-ui-button button button-primary right hug-right prince-playlist-edit"  data-type="' . $playlist_type . '">' . __( 'Edit Playlist', 'notification-plus' ) . '</a>
          </div>';

		} else {
			echo '<div class="prince-playlist-buttons">
		            <a href="#" class="prince-ui-button button button-primary right hug-right prince-playlist-edit" data-type="' . $playlist_type . '">' . __( 'Create Playlist', 'notification-plus' ) . '</a>
		          </div>';

		}

		echo '</div>';

		echo '</div>';

	}
}

/**
 * Google Fonts option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_google_fonts' ) ) {

	function prince_type_google_fonts( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-google-font ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_google_fonts_fields = apply_filters( 'prince_recognized_google_font_fields', array(
			'variants',
			'subsets'
		), $field_id );

		// Set a default to show at least one item.
		if ( ! is_array( $field_value ) || empty( $field_value ) ) {
			$field_value = array(
				array(
					'family'   => '',
					'variants' => array(),
					'subsets'  => array()
				)
			);
		}

		foreach ( $field_value as $key => $value ) {

			echo '<div class="type-google-font-group">';

			/* build font family */
			$family = isset( $value['family'] ) ? $value['family'] : '';
			echo '<div class="prince-google-font-family">';
			echo '<a href="javascript:void(0);" class="js-remove-google-font prince-ui-button button button-secondary light" title="' . __( 'Remove Google Font', 'notification-plus' ) . '"><span class="icon dashicons dashicons-trash"/>' . __( 'Remove Google Font', 'notification-plus' ) . '</a>';
			echo '<select name="' . esc_attr( $field_name ) . '[' . $key . '][family]" id="' . esc_attr( $field_id ) . '-' . $key . '" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">' . __( '-- Choose One --', 'notification-plus' ) . '</option>';
			foreach ( prince_recognized_google_font_families( $field_id ) as $family_key => $family_value ) {
				echo '<option value="' . esc_attr( $family_key ) . '" ' . selected( $family, $family_key, false ) . '>' . esc_html( $family_value ) . '</option>';
			}
			echo '</select>';
			echo '</div>';

			/* build font variants */
			if ( in_array( 'variants', $prince_recognized_google_fonts_fields ) ) {
				$variants = isset( $value['variants'] ) ? $value['variants'] : array();
				echo '<div class="prince-google-font-variants" data-field-id-prefix="' . esc_attr( $field_id ) . '-' . $key . '-" data-field-name="' . esc_attr( $field_name ) . '[' . $key . '][variants]" data-field-class="prince-ui-checkbox ' . esc_attr( $field_class ) . '">';
				foreach ( prince_recognized_google_font_variants( $field_id, $family ) as $variant_key => $variant ) {
					echo '<p class="checkbox-wrap">';
					echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . $key . '][variants][]" id="' . esc_attr( $field_id ) . '-' . $key . '-' . $variant . '" value="' . esc_attr( $variant ) . '" ' . checked( in_array( $variant, $variants ), true, false ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
					echo '<label for="' . esc_attr( $field_id ) . '-' . $key . '-' . $variant . '">' . esc_html( $variant ) . '</label>';
					echo '</p>';
				}
				echo '</div>';
			}

			/* build font subsets */
			if ( in_array( 'subsets', $prince_recognized_google_fonts_fields ) ) {
				$subsets = isset( $value['subsets'] ) ? $value['subsets'] : array();
				echo '<div class="prince-google-font-subsets" data-field-id-prefix="' . esc_attr( $field_id ) . '-' . $key . '-" data-field-name="' . esc_attr( $field_name ) . '[' . $key . '][subsets]" data-field-class="prince-ui-checkbox ' . esc_attr( $field_class ) . '">';
				foreach ( prince_recognized_google_font_subsets( $field_id, $family ) as $subset_key => $subset ) {
					echo '<p class="checkbox-wrap">';
					echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . $key . '][subsets][]" id="' . esc_attr( $field_id ) . '-' . $key . '-' . $subset . '" value="' . esc_attr( $subset ) . '" ' . checked( in_array( $subset, $subsets ), true, false ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
					echo '<label for="' . esc_attr( $field_id ) . '-' . $key . '-' . $subset . '">' . esc_html( $subset ) . '</label>';
					echo '</p>';
				}
				echo '</div>';
			}

			echo '</div>';

		}

		echo '<div class="type-google-font-group-clone">';

		/* build font family */
		echo '<div class="prince-google-font-family">';
		echo '<a href="javascript:void(0);" class="js-remove-google-font prince-ui-button button button-secondary light" title="' . __( 'Remove Google Font', 'notification-plus' ) . '"><span class="icon dashicons dashicons-trash"/>' . __( 'Remove Google Font', 'notification-plus' ) . '</a>';
		echo '<select name="' . esc_attr( $field_name ) . '[%key%][family]" id="' . esc_attr( $field_id ) . '-%key%" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
		echo '<option value="">' . __( '-- Choose One --', 'notification-plus' ) . '</option>';
		foreach ( prince_recognized_google_font_families( $field_id ) as $family_key => $family_value ) {
			echo '<option value="' . esc_attr( $family_key ) . '">' . esc_html( $family_value ) . '</option>';
		}
		echo '</select>';
		echo '</div>';

		/* build font variants */
		if ( in_array( 'variants', $prince_recognized_google_fonts_fields ) ) {
			echo '<div class="prince-google-font-variants" data-field-id-prefix="' . esc_attr( $field_id ) . '-%key%-" data-field-name="' . esc_attr( $field_name ) . '[%key%][variants]" data-field-class="prince-ui-checkbox ' . esc_attr( $field_class ) . '">';
			echo '</div>';
		}

		/* build font subsets */
		if ( in_array( 'subsets', $prince_recognized_google_fonts_fields ) ) {
			echo '<div class="prince-google-font-subsets" data-field-id-prefix="' . esc_attr( $field_id ) . '-%key%-" data-field-name="' . esc_attr( $field_name ) . '[%key%][subsets]" data-field-class="prince-ui-checkbox ' . esc_attr( $field_class ) . '">';
			echo '</div>';
		}

		echo '</div>';

		echo '<a href="javascript:void(0);" class="js-add-google-font prince-ui-button button button-primary right hug-right" title="' . __( 'Add Google Font', 'notification-plus' ) . '">' . __( 'Add Google Font', 'notification-plus' ) . '</a>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * JavaScript option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_javascript' ) ) {

	function prince_type_javascript( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-javascript simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build textarea for CSS */
		echo '<textarea class="hidden" id="textarea_' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_name ) . '">' . esc_attr( $field_value ) . '</textarea>';

		/* build pre to convert it into ace editor later */
		echo '<pre class="prince-javascript-editor ' . esc_attr( $field_class ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</pre>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Link Color option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     The options arguments
 *
 * @return    string    The markup.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_link_color' ) ) {

	function prince_type_link_color( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-link-color ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_link_color_fields = apply_filters( 'prince_recognized_link_color_fields', array(
			'link'    => _x( 'Standard', 'color picker', 'notification-plus' ),
			'hover'   => _x( 'Hover', 'color picker', 'notification-plus' ),
			'active'  => _x( 'Active', 'color picker', 'notification-plus' ),
			'visited' => _x( 'Visited', 'color picker', 'notification-plus' ),
			'focus'   => _x( 'Focus', 'color picker', 'notification-plus' )
		), $field_id );

		/* build link color fields */
		foreach ( $prince_recognized_link_color_fields as $type => $label ) {

			if ( array_key_exists( $type, $prince_recognized_link_color_fields ) ) {

				echo '<div class="prince-ui-colorpicker-input-wrap">';

				echo '<label for="' . esc_attr( $field_id ) . '-picker-' . $type . '" class="prince-ui-colorpicker-label">' . esc_attr( $label ) . '</label>';

				/* colorpicker JS */
				echo '<script>jQuery(document).ready(function($) { PRINCE.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . $type . '"); });</script>';

				/* set color */
				$color = isset( $field_value[ $type ] ) ? esc_attr( $field_value[ $type ] ) : '';

				/* set default color */
				$std = isset( $field_std[ $type ] ) ? 'data-default-color="' . $field_std[ $type ] . '"' : '';

				/* input */
				echo '<input type="text" name="' . esc_attr( $field_name ) . '[' . $type . ']" id="' . esc_attr( $field_id ) . '-picker-' . $type . '" value="' . $color . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" ' . $std . ' />';

				echo '</div>';

			}

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * List Item option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_list_item' ) ) {

	function prince_type_list_item( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		// Default
		$sortable = true;

		// Check if the list can be sorted
		if ( ! empty( $field_class ) ) {
			$classes = explode( ' ', $field_class );
			if ( in_array( 'nprince-sortable', $classes ) ) {
				$sortable = false;
				str_replace( 'nprince-sortable', '', $field_class );
			}
		}

		/* format setting outer wrapper */
		echo '<div class="format-setting type-list-item ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* pass the settings array arround */
		echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . prince_encode( serialize( $field_settings ) ) . '" />';

		/**
		 * settings pages have array wrappers like 'prince'.
		 * So we need that value to create a proper array to save to.
		 * This is only for NON metabox settings.
		 */
		if ( ! isset( $get_option ) ) {
			$get_option = '';
		}

		/* build list items */
		echo '<ul class="prince-setting-wrap' . ( $sortable ? ' prince-sortable' : '' ) . '" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

		if ( is_array( $field_value ) && ! empty( $field_value ) ) {

			foreach ( $field_value as $key => $list_item ) {

				echo '<li class="ui-state-default list-list-item">';
				prince_list_item_view( $field_id, $key, $list_item, $post_id, $get_option, $field_settings, $type );
				echo '</li>';

			}

		}

		echo '</ul>';

		/* button */
		echo '<a href="javascript:void(0);" class="prince-list-item-add prince-ui-button button button-primary right hug-right" title="' . __( 'Add New', 'notification-plus' ) . '">' . __( 'Add New', 'notification-plus' ) . '</a>';

		/* description */
		$list_desc = $sortable ? __( 'You can re-order with drag & drop, the order will update after saving.', 'notification-plus' ) : '';
		echo '<div class="list-item-description">' . apply_filters( 'prince_list_item_description', $list_desc, $field_id ) . '</div>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Measurement option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_measurement' ) ) {

	function prince_type_measurement( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-measurement ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		echo '<div class="prince-ui-measurement-input-wrap">';

		echo '<input type="text" name="' . esc_attr( $field_name ) . '[0]" id="' . esc_attr( $field_id ) . '-0" value="' . ( isset( $field_value[0] ) ? esc_attr( $field_value[0] ) : '' ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" />';

		echo '</div>';

		/* build measurement */
		echo '<select name="' . esc_attr( $field_name ) . '[1]" id="' . esc_attr( $field_id ) . '-1" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

		echo '<option value="">' . __( 'unit', 'notification-plus' ) . '</option>';

		foreach ( prince_measurement_unit_types( $field_id ) as $unit ) {
			echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value[1] ) ? selected( $field_value[1], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Numeric Slider option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_numeric_slider' ) ) {

	function prince_type_numeric_slider( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		$_options = explode( ',', $field_min_max_step );
		$min      = isset( $_options[0] ) ? $_options[0] : 0;
		$max      = isset( $_options[1] ) ? $_options[1] : 100;
		$step     = isset( $_options[2] ) ? $_options[2] : 1;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-numeric-slider ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		echo '<div class="prince-numeric-slider-wrap">';

		echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-numeric-slider-hidden-input" value="' . esc_attr( $field_value ) . '" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '">';

		echo '<input type="text" class="prince-numeric-slider-helper-input widefat prince-ui-input ' . esc_attr( $field_class ) . '" value="' . esc_attr( $field_value ) . '" readonly>';

		echo '<div id="prince_numeric_slider_' . esc_attr( $field_id ) . '" class="prince-numeric-slider"></div>';

		echo '</div>';

		echo '</div>';

		echo '</div>';
	}

}

/**
 * On/Off option type
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     The options arguments
 *
 * @return    string    The gallery metabox markup.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_on_off' ) ) {

	function prince_type_on_off( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );


		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* Force only two choices, and allowing filtering on the choices value & label */
		$field_choices = array(
			array(
				/**
				 * Filter the value of the On button.
				 *
				 * @param string The On button value. Default 'on'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off value with one function.
				 *
				 * @since     1.0.0
				 *
				 */
				'value' => apply_filters( 'prince_on_off_switch_on_value', 'on', $field_id, 'on' ),
				/**
				 * Filter the label of the On button.
				 *
				 * @param string The On button label. Default 'On'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off label with one function.
				 *
				 * @since     1.0.0
				 *
				 */
				'label' => apply_filters( 'prince_on_off_switch_on_label', __( 'On', 'notification-plus' ), $field_id, 'on' )
			),
			array(
				/**
				 * Filter the value of the Off button.
				 *
				 * @param string The Off button value. Default 'off'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off value with one function.
				 *
				 * @since     1.0.0
				 *
				 */
				'value' => apply_filters( 'prince_on_off_switch_off_value', 'off', $field_id, 'off' ),
				/**
				 * Filter the label of the Off button.
				 *
				 * @param string The Off button label. Default 'Off'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off label with one function.
				 *
				 * @since     1.0.0
				 *
				 */
				'label' => apply_filters( 'prince_on_off_switch_off_label', __( 'Off', 'notification-plus' ), $field_id, 'off' )
			)
		);

		/**
		 * Filter the width of the On/Off switch.
		 *
		 * @param string The switch width. Default '100px'.
		 * @param string $field_id The field ID.
		 *
		 * @since     1.0.0
		 *
		 */
		$switch_width = apply_filters( 'prince_on_off_switch_width', '100px', $field_id );

		$disabled = ! empty( $field_class ) && $field_class == 'disabled' ? 'disabled' : '';

		echo '<div class="on-off-switch ' . $disabled . '"' . ( $switch_width != '100px' ? sprintf( ' style="width:%s"', $switch_width ) : '' ) . '>';

		/* build radio */
		foreach ( (array) $field_choices as $key => $choice ) {

			echo '
            <input type="radio" ' . $disabled . ' name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="radio prince-ui-radio ' . esc_attr( $field_class ) . '" />
            <label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" onclick="">' . esc_attr( $choice['label'] ) . '</label>';
		}

		echo '<span class="slide-button"></span>';

		echo '</div>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Page Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_page_checkbox' ) ) {

	function prince_type_page_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-page-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* query pages array */
		$my_posts = get_posts( apply_filters( 'prince_type_page_checkbox_query', array(
			'post_type'      => array( 'page' ),
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'any'
		), $field_id ) );

		/* has pages */
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			foreach ( $my_posts as $my_post ) {
				$post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[ $my_post->ID ] ) ? checked( $field_value[ $my_post->ID ], $my_post->ID, false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . $post_title . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . __( 'No Pages Found', 'notification-plus' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Page Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_page_select' ) ) {

	function prince_type_page_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-page-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build page select */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* query pages array */
		$my_posts = get_posts( apply_filters( 'prince_type_page_select_query', array(
			'post_type'      => array( 'page' ),
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'any'
		), $field_id ) );

		/* has pages */
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			echo '<option value="">-- ' . __( 'Choose One', 'notification-plus' ) . ' --</option>';
			foreach ( $my_posts as $my_post ) {
				$post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
				echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $field_value, $my_post->ID, false ) . '>' . $post_title . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Pages Found', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Post Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_post_checkbox' ) ) {

	function prince_type_post_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-post-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* query posts array */
		$my_posts = get_posts( apply_filters( 'prince_type_post_checkbox_query', array(
			'post_type'      => array( 'post' ),
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'any'
		), $field_id ) );

		/* has posts */
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			foreach ( $my_posts as $my_post ) {
				$post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[ $my_post->ID ] ) ? checked( $field_value[ $my_post->ID ], $my_post->ID, false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . $post_title . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . __( 'No Posts Found', 'notification-plus' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Post Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_post_select' ) ) {

	function prince_type_post_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-post-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build page select */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* query posts array */
		$my_posts = get_posts( apply_filters( 'prince_type_post_select_query', array(
			'post_type'      => array( 'post' ),
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'any'
		), $field_id ) );

		/* has posts */
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			echo '<option value="">-- ' . __( 'Choose One', 'notification-plus' ) . ' --</option>';
			foreach ( $my_posts as $my_post ) {
				$post_title = '' != $my_post->post_title ? $my_post->post_title : 'Untitled';
				echo '<option value="' . esc_attr( $my_post->ID ) . '"' . selected( $field_value, $my_post->ID, false ) . '>' . $post_title . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Posts Found', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Radio option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_radio' ) ) {

	function prince_type_radio( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build radio */
		foreach ( (array) $field_choices as $key => $choice ) {
			echo '<p><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="radio prince-ui-radio ' . esc_attr( $field_class ) . '" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Radio Images option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_radio_image' ) ) {

	function prince_type_radio_image( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-radio-image ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/**
		 * load the default filterable images if nothing
		 * has been set in the choices array.
		 */
		if ( empty( $field_choices ) ) {
			$field_choices = prince_radio_images( $field_id );
		}

		/* build radio image */
		foreach ( (array) $field_choices as $key => $choice ) {

			$src = $choice['src'];

			/* make radio image source filterable */
			$src = apply_filters( 'prince_type_radio_image_src', $src, $field_id );

			/**
			 * Filter the image attributes.
			 *
			 * @param string $attributes The image attributes.
			 * @param string $field_id The field ID.
			 * @param array $choice The choice.
			 *
			 * @since     1.0.0
			 *
			 */
			$attributes = apply_filters( 'prince_type_radio_image_attributes', '', $field_id, $choice );

			echo '<div class="prince-ui-radio-images">';
			echo '<p style="display:none"><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="prince-ui-radio prince-ui-images" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
			echo '<img ' . $attributes . ' src="' . esc_url( $src ) . '" alt="' . esc_attr( $choice['label'] ) . '" title="' . esc_attr( $choice['label'] ) . '" class="prince-ui-radio-image ' . esc_attr( $field_class ) . ( $field_value == $choice['value'] ? ' prince-ui-radio-image-selected' : '' ) . '" />';
			echo '</div>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_select' ) ) {

	function prince_type_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* filter choices array */
		$field_choices = apply_filters( 'prince_type_select_choices', $field_choices, $field_id );


		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build select */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
		foreach ( (array) $field_choices as $value => $label ) {
			echo '<option value="' . esc_attr( $value ) . '"' . selected( $field_value, $value, false ) . '>' . esc_attr( $label ) . '</option>';

		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Sidebar Select option type.
 *
 * This option type makes it possible for users to select a WordPress registered sidebar
 * to use on a specific area. By using the two provided filters, 'prince_recognized_sidebars',
 * and 'prince_recognized_sidebars_{$field_id}' we can be selective about which sidebars are
 * available on a specific content area.
 *
 * For example, if we create a WordPress theme that provides the ability to change the
 * Blog Sidebar and we don't want to have the footer sidebars available on this area,
 * we can unset those sidebars either manually or by using a regular expression if we
 * have a common name like footer-sidebar-$i.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_sidebar_select' ) ) {

	function prince_type_sidebar_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-sidebar-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build page select */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* get the registered sidebars */
		global $wp_registered_sidebars;

		$sidebars = array();
		foreach ( $wp_registered_sidebars as $id => $sidebar ) {
			$sidebars[ $id ] = $sidebar['name'];
		}

		/* filters to restrict which sidebars are allowed to be selected, for example we can restrict footer sidebars to be selectable on a blog page */
		$sidebars = apply_filters( 'prince_recognized_sidebars', $sidebars );
		$sidebars = apply_filters( 'prince_recognized_sidebars_' . $field_id, $sidebars );

		/* has sidebars */
		if ( count( $sidebars ) ) {
			echo '<option value="">-- ' . __( 'Choose Sidebar', 'notification-plus' ) . ' --</option>';
			foreach ( $sidebars as $id => $sidebar ) {
				echo '<option value="' . esc_attr( $id ) . '"' . selected( $field_value, $id, false ) . '>' . esc_attr( $sidebar ) . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Sidebars', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * List Item option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_slider' ) ) {

	function prince_type_slider( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-slider ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* pass the settings array arround */
		echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . prince_encode( serialize( $field_settings ) ) . '" />';

		/**
		 * settings pages have array wrappers like 'prince'.
		 * So we need that value to create a proper array to save to.
		 * This is only for NON metabox settings.
		 */
		if ( ! isset( $get_option ) ) {
			$get_option = '';
		}

		/* build list items */
		echo '<ul class="prince-setting-wrap prince-sortable" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

		if ( is_array( $field_value ) && ! empty( $field_value ) ) {

			foreach ( $field_value as $key => $list_item ) {

				echo '<li class="ui-state-default list-list-item">';
				prince_list_item_view( $field_id, $key, $list_item, $post_id, $get_option, $field_settings, $type );
				echo '</li>';

			}

		}

		echo '</ul>';

		/* button */
		echo '<a href="javascript:void(0);" class="prince-list-item-add prince-ui-button button button-primary right hug-right" title="' . __( 'Add New', 'notification-plus' ) . '">' . __( 'Add New', 'notification-plus' ) . '</a>';

		/* description */
		echo '<div class="list-item-description">' . __( 'You can re-order with drag & drop, the order will update after saving.', 'notification-plus' ) . '</div>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Social Links option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_social_links' ) ) {

	function prince_type_social_links( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* Load the default social links */
		if ( empty( $field_value ) && apply_filters( 'prince_type_social_links_load_defaults', true, $field_id ) ) {

			$field_value = apply_filters( 'prince_type_social_links_defaults', array(
				array(
					'name'  => __( 'Facebook', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Twitter', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Google+', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'LinkedIn', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Pinterest', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Youtube', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Dribbble', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Github', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Forrst', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Digg', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Delicious', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Tumblr', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Skype', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'SoundCloud', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Vimeo', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'Flickr', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				),
				array(
					'name'  => __( 'VK.com', 'notification-plus' ),
					'title' => '',
					'href'  => ''
				)
			), $field_id );

		}

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-social-list-item ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner type-social-links">';

		/* pass the settings array arround */
		echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . prince_encode( serialize( $field_settings ) ) . '" />';

		/**
		 * settings pages have array wrappers like 'prince'.
		 * So we need that value to create a proper array to save to.
		 * This is only for NON metabox settings.
		 */
		if ( ! isset( $get_option ) ) {
			$get_option = '';
		}

		/* build list items */
		echo '<ul class="prince-setting-wrap prince-sortable" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

		if ( is_array( $field_value ) && ! empty( $field_value ) ) {

			foreach ( $field_value as $key => $link ) {

				echo '<li class="ui-state-default list-list-item">';
				prince_social_links_view( $field_id, $key, $link, $post_id, $get_option, $field_settings, $type );
				echo '</li>';

			}

		}

		echo '</ul>';

		/* button */
		echo '<a href="javascript:void(0);" class="prince-social-links-add prince-ui-button button button-primary right hug-right" title="' . __( 'Add New', 'notification-plus' ) . '">' . __( 'Add New', 'notification-plus' ) . '</a>';

		/* description */
		echo '<div class="list-item-description">' . apply_filters( 'prince_social_links_description', __( 'You can re-order with drag & drop, the order will update after saving.', 'notification-plus' ), $field_id ) . '</div>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Spacing Option Type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_spacing' ) ) {

	function prince_type_spacing( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-spacing ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_spacing_fields = apply_filters( 'prince_recognized_spacing_fields', array(
			'top',
			'right',
			'bottom',
			'left',
			'unit'
		), $field_id );

		/* build top spacing */
		if ( in_array( 'top', $prince_recognized_spacing_fields ) ) {

			$top = isset( $field_value['top'] ) ? esc_attr( $field_value['top'] ) : '';

			echo '<div class="prince-option-group"><span class="prince-icon-arrow-up prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[top]" id="' . esc_attr( $field_id ) . '-top" value="' . $top . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'top', 'notification-plus' ) . '" /></div>';

		}

		/* build right spacing */
		if ( in_array( 'right', $prince_recognized_spacing_fields ) ) {

			$right = isset( $field_value['right'] ) ? esc_attr( $field_value['right'] ) : '';

			echo '<div class="prince-option-group"><span class="prince-icon-arrow-right prince-option-group--icon"></span></span><input type="text" name="' . esc_attr( $field_name ) . '[right]" id="' . esc_attr( $field_id ) . '-right" value="' . $right . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'right', 'notification-plus' ) . '" /></div>';

		}

		/* build bottom spacing */
		if ( in_array( 'bottom', $prince_recognized_spacing_fields ) ) {

			$bottom = isset( $field_value['bottom'] ) ? esc_attr( $field_value['bottom'] ) : '';

			echo '<div class="prince-option-group"><span class="prince-icon-arrow-down prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[bottom]" id="' . esc_attr( $field_id ) . '-bottom" value="' . $bottom . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'bottom', 'notification-plus' ) . '" /></div>';

		}

		/* build left spacing */
		if ( in_array( 'left', $prince_recognized_spacing_fields ) ) {

			$left = isset( $field_value['left'] ) ? esc_attr( $field_value['left'] ) : '';

			echo '<div class="prince-option-group"><span class="prince-icon-arrow-left prince-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[left]" id="' . esc_attr( $field_id ) . '-left" value="' . $left . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . __( 'left', 'notification-plus' ) . '" /></div>';

		}

		/* build unit dropdown */
		if ( in_array( 'unit', $prince_recognized_spacing_fields ) ) {

			echo '<div class="prince-option-group prince-option-group--is-last">';

			echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="prince-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . __( 'unit', 'notification-plus' ) . '</option>';

			foreach ( prince_recognized_spacing_unit_types( $field_id ) as $unit ) {
				echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
			}

			echo '</select>';

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Tab option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_tab' ) ) {

	function prince_type_tab( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* format setting outer wrapper */
		echo '<div class="format-setting type-tab">';

		echo '<br />';

		echo '</div>';

	}

}

/**
 * Tag Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_tag_checkbox' ) ) {

	function prince_type_tag_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-tag-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* get tags */
		$tags = get_tags( array( 'hide_empty' => false ) );

		/* has tags */
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $tag->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $tag->term_id ) . '" value="' . esc_attr( $tag->term_id ) . '" ' . ( isset( $field_value[ $tag->term_id ] ) ? checked( $field_value[ $tag->term_id ], $tag->term_id, false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $tag->term_id ) . '">' . esc_attr( $tag->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . __( 'No Tags Found', 'notification-plus' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Tag Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_tag_select' ) ) {

	function prince_type_tag_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-tag-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build tag select */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* get tags */
		$tags = get_tags( array( 'hide_empty' => false ) );

		/* has tags */
		if ( $tags ) {
			echo '<option value="">-- ' . __( 'Choose One', 'notification-plus' ) . ' --</option>';
			foreach ( $tags as $tag ) {
				echo '<option value="' . esc_attr( $tag->term_id ) . '"' . selected( $field_value, $tag->term_id, false ) . '>' . esc_attr( $tag->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Tags Found', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Taxonomy Checkbox option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_taxonomy_checkbox' ) ) {

	function prince_type_taxonomy_checkbox( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-taxonomy-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* setup the taxonomy */
		$taxonomy = isset( $field_taxonomy ) ? explode( ',', $field_taxonomy ) : array( 'category' );

		/* get taxonomies */
		$taxonomies = get_categories( apply_filters( 'prince_type_taxonomy_checkbox_query', array(
			'hide_empty' => false,
			'taxonomy'   => $taxonomy
		), $field_id ) );

		/* has tags */
		if ( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $taxonomy->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $taxonomy->term_id ) . '" value="' . esc_attr( $taxonomy->term_id ) . '" ' . ( isset( $field_value[ $taxonomy->term_id ] ) ? checked( $field_value[ $taxonomy->term_id ], $taxonomy->term_id, false ) : '' ) . ' class="prince-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $taxonomy->term_id ) . '">' . esc_attr( $taxonomy->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . __( 'No Taxonomies Found', 'notification-plus' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Taxonomy Select option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_taxonomy_select' ) ) {

	function prince_type_taxonomy_select( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-tag-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build tag select */
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="prince-ui-select ' . $field_class . '">';

		/* setup the taxonomy */
		$taxonomy = isset( $field_taxonomy ) ? explode( ',', $field_taxonomy ) : array( 'category' );

		/* get taxonomies */
		$taxonomies = get_categories( apply_filters( 'prince_type_taxonomy_select_query', array(
			'hide_empty' => false,
			'taxonomy'   => $taxonomy
		), $field_id ) );

		/* has tags */
		if ( $taxonomies ) {
			echo '<option value="">-- ' . __( 'Choose One', 'notification-plus' ) . ' --</option>';
			foreach ( $taxonomies as $taxonomy ) {
				echo '<option value="' . esc_attr( $taxonomy->term_id ) . '"' . selected( $field_value, $taxonomy->term_id, false ) . '>' . esc_attr( $taxonomy->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . __( 'No Taxonomies Found', 'notification-plus' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Text option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_text' ) ) {

	function prince_type_text( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		$field_attrs = isset( $field_attrs ) ? $field_attrs : '';

		/* build text input */
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat prince-ui-input ' . esc_attr( $field_class ) . '" ' . $field_attrs . ' />';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Number option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_number' ) ) {

	function prince_type_number( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		$_options = explode( ',', $field_min_max_step );
		$min      = isset( $_options[0] ) ? $_options[0] : 0;
		$max      = isset( $_options[1] ) ? $_options[1] : 100;

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		$field_attrs = isset( $field_attrs ) ? $field_attrs : '';

		/* build text input */
		echo '<input type="number" name="' . esc_attr( $field_name )
		     . '" id="' . esc_attr( $field_id )
		     . '" value="' . esc_attr( $field_value )
		     . '" class="widefat prince-ui-input ' . esc_attr( $field_class )
		     . '" min="' . esc_attr( $min )
		     . '" max="' . esc_attr( $max )
		     . '" ' . $field_attrs . ' />';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Function option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_function' ) ) {

	function prince_type_function( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		$params = ! empty( $field_settings['params'] ) ? $field_settings['params'] : [];

		call_user_func_array( esc_attr( $field_settings['function'] ), $params );

		if ( isset( $field_settings['input'] ) ) {
			printf(
				'<input type="hidden" name="%1$s" id="%2$s" value="%3$s" class="widefat prince-ui-input %4$s" %5$s />',
				esc_attr( $field_name ),
				esc_attr( $field_id ),
				esc_attr( $field_value ),
				esc_attr( $field_class ),
				$field_attrs
			);
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Textarea option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_textarea' ) ) {

	function prince_type_textarea( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-textarea ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . ' fill-area">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';


		/* build textarea */
		wp_editor(
			$field_value,
			esc_attr( $field_id ),
			array(
				'editor_class'  => esc_attr( $field_class ),
				'wpautop'       => apply_filters( 'prince_wpautop', true, $field_id ),
				'media_buttons' => apply_filters( 'prince_media_buttons', true, $field_id ),
				'textarea_name' => esc_attr( $field_name ),
				'textarea_rows' => esc_attr( $field_rows ),
				'tinymce'       => apply_filters( 'prince_tinymce', true, $field_id ),
				'quicktags'     => apply_filters( 'prince_quicktags', array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close' ), $field_id )
			)
		);

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Textarea Simple option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_textarea_simple' ) ) {

	function prince_type_textarea_simple( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-textarea simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* filter to allow wpautop */
		$wpautop = apply_filters( 'prince_wpautop', false, $field_id );

		/* wpautop $field_value */
		if ( $wpautop == true ) {
			$field_value = wpautop( $field_value );
		}

		/* build textarea simple */
		echo '<textarea class="textarea ' . esc_attr( $field_class ) . '" rows="' . esc_attr( $field_rows ) . '" cols="40" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</textarea>';

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Textblock option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_textblock' ) ) {

	function prince_type_textblock( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* format setting outer wrapper */
		echo '<div class="format-setting type-textblock wide-desc">';

		/* description */
		echo '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>';

		echo '</div>';

	}

}

/**
 * Textblock Titled option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_textblock_titled' ) ) {

	function prince_type_textblock_titled( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* format setting outer wrapper */
		echo '<div class="format-setting type-textblock titled wide-desc">';

		/* description */
		echo '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>';

		echo '</div>';

	}

}

/**
 * Typography option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_typography' ) ) {

	function prince_type_typography( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* format setting outer wrapper */
		echo '<div class="format-setting type-typography ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* allow fields to be filtered */
		$prince_recognized_typography_fields = apply_filters( 'prince_recognized_typography_fields', array(
			'font-color',
			'font-family',
			'font-size',
			'font-style',
			'font-variant',
			'font-weight',
			'letter-spacing',
			'line-height',
			'text-decoration',
			'text-transform'
		), $field_id );

		/* build font color */
		if ( in_array( 'font-color', $prince_recognized_typography_fields ) ) {

			/* build colorpicker */
			echo '<div class="prince-ui-colorpicker-input-wrap">';

			/* colorpicker JS */
			echo '<script>jQuery(document).ready(function($) { PRINCE.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			/* set background color */
			$background_color = isset( $field_value['font-color'] ) ? esc_attr( $field_value['font-color'] ) : '';

			/* input */
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[font-color]" id="' . esc_attr( $field_id ) . '-picker" value="' . esc_attr( $background_color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';

		}

		/* build font family */
		if ( in_array( 'font-family', $prince_recognized_typography_fields ) ) {
			$font_family = isset( $field_value['font-family'] ) ? $field_value['font-family'] : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-family]" id="' . esc_attr( $field_id ) . '-font-family" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-family</option>';
			foreach ( prince_recognized_font_families( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_family, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		/* build font size */
		if ( in_array( 'font-size', $prince_recognized_typography_fields ) ) {
			$font_size = isset( $field_value['font-size'] ) ? esc_attr( $field_value['font-size'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-size]" id="' . esc_attr( $field_id ) . '-font-size" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-size</option>';
			foreach ( prince_recognized_font_sizes( $field_id ) as $option ) {
				echo '<option value="' . esc_attr( $option ) . '" ' . selected( $font_size, $option, false ) . '>' . esc_attr( $option ) . '</option>';
			}
			echo '</select>';
		}

		/* build font style */
		if ( in_array( 'font-style', $prince_recognized_typography_fields ) ) {
			$font_style = isset( $field_value['font-style'] ) ? esc_attr( $field_value['font-style'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-style]" id="' . esc_attr( $field_id ) . '-font-style" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-style</option>';
			foreach ( prince_recognized_font_styles( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_style, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		/* build font variant */
		if ( in_array( 'font-variant', $prince_recognized_typography_fields ) ) {
			$font_variant = isset( $field_value['font-variant'] ) ? esc_attr( $field_value['font-variant'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-variant]" id="' . esc_attr( $field_id ) . '-font-variant" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-variant</option>';
			foreach ( prince_recognized_font_variants( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_variant, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		/* build font weight */
		if ( in_array( 'font-weight', $prince_recognized_typography_fields ) ) {
			$font_weight = isset( $field_value['font-weight'] ) ? esc_attr( $field_value['font-weight'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-weight]" id="' . esc_attr( $field_id ) . '-font-weight" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-weight</option>';
			foreach ( prince_recognized_font_weights( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_weight, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		/* build letter spacing */
		if ( in_array( 'letter-spacing', $prince_recognized_typography_fields ) ) {
			$letter_spacing = isset( $field_value['letter-spacing'] ) ? esc_attr( $field_value['letter-spacing'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[letter-spacing]" id="' . esc_attr( $field_id ) . '-letter-spacing" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">letter-spacing</option>';
			foreach ( prince_recognized_letter_spacing( $field_id ) as $option ) {
				echo '<option value="' . esc_attr( $option ) . '" ' . selected( $letter_spacing, $option, false ) . '>' . esc_attr( $option ) . '</option>';
			}
			echo '</select>';
		}

		/* build line height */
		if ( in_array( 'line-height', $prince_recognized_typography_fields ) ) {
			$line_height = isset( $field_value['line-height'] ) ? esc_attr( $field_value['line-height'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[line-height]" id="' . esc_attr( $field_id ) . '-line-height" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">line-height</option>';
			foreach ( prince_recognized_line_heights( $field_id ) as $option ) {
				echo '<option value="' . esc_attr( $option ) . '" ' . selected( $line_height, $option, false ) . '>' . esc_attr( $option ) . '</option>';
			}
			echo '</select>';
		}

		/* build text decoration */
		if ( in_array( 'text-decoration', $prince_recognized_typography_fields ) ) {
			$text_decoration = isset( $field_value['text-decoration'] ) ? esc_attr( $field_value['text-decoration'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[text-decoration]" id="' . esc_attr( $field_id ) . '-text-decoration" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">text-decoration</option>';
			foreach ( prince_recognized_text_decorations( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_decoration, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		/* build text transform */
		if ( in_array( 'text-transform', $prince_recognized_typography_fields ) ) {
			$text_transform = isset( $field_value['text-transform'] ) ? esc_attr( $field_value['text-transform'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[text-transform]" id="' . esc_attr( $field_id ) . '-text-transform" class="prince-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">text-transform</option>';
			foreach ( prince_recognized_text_transformations( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_transform, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Upload option type.
 *
 * See @prince_display_by_type to see the full list of available arguments.
 *
 * @param array     An array of arguments.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_type_upload' ) ) {

	function prince_type_upload( $args = array() ) {

		/* turns arguments array into variables */
		extract( $args );

		/* verify a description */
		$has_desc = $field_desc ? true : false;

		/* If an attachment ID is stored here fetch its URL and replace the value */
		if ( $field_value && wp_attachment_is_image( $field_value ) ) {

			$attachment_data = wp_get_attachment_image_src( $field_value, 'original' );

			/* check for attachment data */
			if ( $attachment_data ) {

				$field_src = $attachment_data[0];

			}

		}

		/* format setting outer wrapper */
		echo '<div class="format-setting type-upload ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' ' . ($field_block ? 'block' : '') . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

		/* format setting inner wrapper */
		echo '<div class="format-setting-inner">';

		/* build upload */
		echo '<div class="prince-ui-upload-parent">';

		/* input */
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat prince-ui-upload-input ' . esc_attr( $field_class ) . '" />';

		/* add media button */
		echo '<a href="javascript:void(0);" class="prince_upload_media prince-ui-button button button-primary light" rel="' . $post_id . '" title="' . __( 'Add Media', 'notification-plus' ) . '"><span class="icon dashicons dashicons-plus-alt"></span>' . __( 'Add Media', 'notification-plus' ) . '</a>';

		echo '</div>';

		/* media */
		if ( $field_value ) {

			echo '<div class="prince-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';

			/* replace image src */
			if ( isset( $field_src ) ) {
				$field_value = $field_src;
			}

			if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value ) ) {
				echo '<div class="prince-ui-image-wrap"><img src="' . esc_url( $field_value ) . '" alt="" /></div>';
			}

			echo '<a href="javascript:(void);" class="prince-ui-remove-media prince-ui-button button button-secondary light" title="' . __( 'Remove Media', 'notification-plus' ) . '"><span class="icon dashicons dashicons-trash"></span></a>';

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}