<?php

/**
 * Functions used only while viewing the admin UI.
 *
 * Limit loading these function only when needed
 * and not in the front end.
 *
 * @package   Prince
 * @author    Prince Ahmed <israilahmed5@gmail.com>
 * @copyright Copyright (c) 2019, Prince Ahmed
 * @since     1.0.0
 */

/**
 * Registers the Theme Option page
 *
 * @return    void
 *
 * @access    public
 * @uses      prince_register_settings()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_register_settings_page' ) ) {

	function prince_register_settings_page() {

		/* get the settings array */
		$get_settings = get_option( prince_settings_id() );

		/* sections array */
		$sections = isset( $get_settings['sections'] ) ? $get_settings['sections'] : array();

		/* settings array */
		$settings = isset( $get_settings['settings'] ) ? $get_settings['settings'] : array();

		/* contexual_help array */
		$contextual_help = isset( $get_settings['contextual_help'] ) ? $get_settings['contextual_help'] : array();

		/* build the Settings */
		if ( function_exists( 'prince_register_settings' ) ) {

			prince_register_settings( array(
					array(
						'id'    => prince_options_id(),
						'pages' => array(
							array(
								'id'              => 'prince_settings',
								'parent_slug'     => apply_filters( 'notification_plus_settings_parent_slug', 'themes.php' ),
								'page_title'      => apply_filters( 'notification_plus_settings_page_title', __( 'Settings', 'notification-plus' ) ),
								'menu_title'      => apply_filters( 'notification_plus_settings_menu_title', __( 'Settings', 'notification-plus' ) ),
								'capability'      => $caps = apply_filters( 'notification_plus_settings_capability', 'edit_theme_options' ),
								'menu_slug'       => apply_filters( 'notification_plus_settings_menu_slug', 'prince-options' ),
								'icon_url'        => apply_filters( 'notification_plus_settings_icon_url', null ),
								'position'        => apply_filters( 'notification_plus_settings_position', null ),
								'updated_message' => apply_filters( 'notification_plus_settings_updated_message', __( 'Settings updated.', 'notification-plus' ) ),
								'reset_message'   => apply_filters( 'notification_plus_settings_reset_message', __( 'Settings reset.', 'notification-plus' ) ),
								'button_text'     => apply_filters( 'notification_plus_settings_button_text', __( 'Save Changes', 'notification-plus' ) ),
								'contextual_help' => apply_filters( 'notification_plus_settings_contextual_help', $contextual_help ),
								'sections'        => apply_filters( 'notification_plus_settings_sections', $sections ),
								'settings'        => apply_filters( 'notification_plus_settings_settings', $settings )
							)
						)
					)
				) );

			// Filters the options.php to add the minimum user capabilities.
			add_filter( 'option_page_capability_' . prince_options_id(), function ( $caps ) {
				return $caps;
			}, 999 );

		}

	}

}

/**
 * Runs directly after the Settings are save.
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_after_settings_save' ) ) {

	function prince_after_settings_save() {

		$page    = isset( $_REQUEST['page'] ) ? esc_attr( $_REQUEST['page'] ) : '';
		$updated = isset( $_REQUEST['settings-updated'] ) && esc_html( $_REQUEST['settings-updated'] ) == 'true' ? true : false;

		/* only execute after the Settings are saved */
		if ( apply_filters( 'prince_settings_menu_slug', 'prince-settings' ) == $page && $updated ) {

			/* grab a copy of the Settings */
			$options = get_option( prince_options_id() );

			/* execute the action hook and pass the Settings to it */
			do_action( 'prince_after_settings_save', $options );

		}

	}

}

/**
 * Validate the options by type before saving.
 *
 * This function will run on only some of the option types
 * as all of them don't need to be validated, just the
 * ones users are going to input data into; because they
 * can't be trusted.
 *
 * @param mixed     Setting value
 * @param string    Setting type
 * @param string    Setting field ID
 * @param string    WPML field ID
 *
 * @return    mixed
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_validate_setting' ) ) {

	function prince_validate_setting( $input, $type, $field_id, $wmpl_id = '' ) {

		/* exit early if missing data */
		if ( ! $input || ! $type || ! $field_id ) {
			return $input;
		}

		$input = apply_filters( 'prince_validate_setting', $input, $type, $field_id );

		if ( 'background' == $type ) {

			$input['background-color'] = prince_validate_setting( $input['background-color'], 'colorpicker', $field_id );

			$input['background-image'] = prince_validate_setting( $input['background-image'], 'upload', $field_id );

			// Loop over array and check for values
			foreach ( (array) $input as $key => $value ) {
				if ( ! empty( $value ) ) {
					$has_value = true;
				}
			}

			// No value; set to empty
			if ( ! isset( $has_value ) ) {
				$input = '';
			}

		} else if ( 'border' == $type ) {

			// Loop over array and set errors or unset key from array.
			foreach ( $input as $key => $value ) {

				// Validate width
				if ( $key == 'width' && ! empty( $value ) && ! is_numeric( $value ) ) {

					$input[ $key ] = '0';

					add_settings_error( 'prince', 'invalid_border_width', sprintf( __( 'The %s input field for %s only allows numeric values.', 'notification-plus' ), '<code>width</code>', '<code>' . $field_id . '</code>' ), 'error' );

				}

				// Validate color
				if ( $key == 'color' && ! empty( $value ) ) {

					$input[ $key ] = prince_validate_setting( $value, 'colorpicker', $field_id );

				}

				// Unset keys with empty values.
				if ( empty( $value ) && strlen( $value ) == 0 ) {
					unset( $input[ $key ] );
				}

			}

			if ( empty( $input ) ) {
				$input = '';
			}

		} else if ( 'box-shadow' == $type ) {

			// Validate inset
			$input['inset'] = isset( $input['inset'] ) ? 'inset' : '';

			// Validate offset-x
			$input['offset-x'] = prince_validate_setting( $input['offset-x'], 'text', $field_id );

			// Validate offset-y
			$input['offset-y'] = prince_validate_setting( $input['offset-y'], 'text', $field_id );

			// Validate blur-radius
			$input['blur-radius'] = prince_validate_setting( $input['blur-radius'], 'text', $field_id );

			// Validate spread-radius
			$input['spread-radius'] = prince_validate_setting( $input['spread-radius'], 'text', $field_id );

			// Validate color
			$input['color'] = prince_validate_setting( $input['color'], 'colorpicker', $field_id );

			// Unset keys with empty values.
			foreach ( $input as $key => $value ) {
				if ( empty( $value ) && strlen( $value ) == 0 ) {
					unset( $input[ $key ] );
				}
			}

			// Set empty array to empty string.
			if ( empty( $input ) ) {
				$input = '';
			}

		} else if ( 'colorpicker' == $type ) {

			/* return empty & set error */
			if ( 0 === preg_match( '/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $input ) && 0 === preg_match( '/^rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]{1,4})\s*\)/i', $input ) ) {

				$input = '';

				add_settings_error( 'prince', 'invalid_hex', sprintf( __( 'The %s Colorpicker only allows valid hexadecimal or rgba values.', 'notification-plus' ), '<code>' . $field_id . '</code>' ), 'error' );

			}

		} else if ( 'colorpicker-opacity' == $type ) {

			// Not allowed
			if ( is_array( $input ) ) {
				$input = '';
			}

			// Validate color
			$input = prince_validate_setting( $input, 'colorpicker', $field_id );

		} else if ( in_array( $type, array( 'css', 'javascript', 'text', 'textarea', 'textarea-simple' ) ) ) {

			if ( ! current_user_can( 'unfiltered_html' ) && OT_ALLOW_UNFILTERED_HTML == false ) {

				$input = wp_kses_post( $input );

			}

		} else if ( 'dimension' == $type ) {

			// Loop over array and set error keys or unset key from array.
			foreach ( $input as $key => $value ) {
				if ( ! empty( $value ) && ! is_numeric( $value ) && $key !== 'unit' ) {
					$errors[] = $key;
				}
				if ( empty( $value ) && strlen( $value ) == 0 ) {
					unset( $input[ $key ] );
				}
			}

			/* return 0 & set error */
			if ( isset( $errors ) ) {

				foreach ( $errors as $error ) {

					$input[ $error ] = '0';

					add_settings_error( 'prince', 'invalid_dimension_' . $error, sprintf( __( 'The %s input field for %s only allows numeric values.', 'notification-plus' ), '<code>' . $error . '</code>', '<code>' . $field_id . '</code>' ), 'error' );

				}

			}

			if ( empty( $input ) ) {
				$input = '';
			}

		} else if ( 'google-fonts' == $type ) {

			unset( $input['%key%'] );

			// Loop over array and check for values
			if ( is_array( $input ) && ! empty( $input ) ) {
				$input = array_values( $input );
			}

			// No value; set to empty
			if ( empty( $input ) ) {
				$input = '';
			}

		} else if ( 'link-color' == $type ) {

			// Loop over array and check for values
			if ( is_array( $input ) && ! empty( $input ) ) {
				foreach ( $input as $key => $value ) {
					if ( ! empty( $value ) ) {
						$input[ $key ] = prince_validate_setting( $input[ $key ], 'colorpicker', $field_id . '-' . $key );
						$has_value     = true;
					}
				}
			}

			// No value; set to empty
			if ( ! isset( $has_value ) ) {
				$input = '';
			}

		} else if ( 'measurement' == $type ) {

			$input[0] = sanitize_text_field( $input[0] );

			// No value; set to empty
			if ( empty( $input[0] ) && strlen( $input[0] ) == 0 && empty( $input[1] ) ) {
				$input = '';
			}

		} else if ( 'spacing' == $type ) {

			// Loop over array and set error keys or unset key from array.
			foreach ( $input as $key => $value ) {
				if ( ! empty( $value ) && ! is_numeric( $value ) && $key !== 'unit' ) {
					$errors[] = $key;
				}
				if ( empty( $value ) && strlen( $value ) == 0 ) {
					unset( $input[ $key ] );
				}
			}

			/* return 0 & set error */
			if ( isset( $errors ) ) {

				foreach ( $errors as $error ) {

					$input[ $error ] = '0';

					add_settings_error( 'prince', 'invalid_spacing_' . $error, sprintf( __( 'The %s input field for %s only allows numeric values.', 'notification-plus' ), '<code>' . $error . '</code>', '<code>' . $field_id . '</code>' ), 'error' );

				}

			}

			if ( empty( $input ) ) {
				$input = '';
			}

		} else if ( 'typography' == $type && isset( $input['font-color'] ) ) {

			$input['font-color'] = prince_validate_setting( $input['font-color'], 'colorpicker', $field_id );

			// Loop over array and check for values
			foreach ( $input as $key => $value ) {
				if ( ! empty( $value ) ) {
					$has_value = true;
				}
			}

			// No value; set to empty
			if ( ! isset( $has_value ) ) {
				$input = '';
			}

		} else if ( 'upload' == $type ) {

			if ( filter_var( $input, FILTER_VALIDATE_INT ) === false ) {
				$input = esc_url_raw( $input );
			}

		} else if ( 'gallery' == $type ) {

			$input = trim( $input );

		} else if ( 'social-links' == $type ) {

			// Loop over array and check for values, plus sanitize the text field
			foreach ( (array) $input as $key => $value ) {
				if ( ! empty( $value ) && is_array( $value ) ) {
					foreach ( (array) $value as $item_key => $item_value ) {
						if ( ! empty( $item_value ) ) {
							$has_value                  = true;
							$input[ $key ][ $item_key ] = sanitize_text_field( $item_value );
						}
					}
				}
			}

			// No value; set to empty
			if ( ! isset( $has_value ) ) {
				$input = '';
			}

		}

		$input = apply_filters( 'prince_after_validate_setting', $input, $type, $field_id );

		return $input;

	}

}

/**
 * Setup the default admin styles
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_admin_styles' ) ) {

	function prince_admin_styles() {
		global $wp_styles, $post;

		/* execute styles before actions */
		do_action( 'prince_admin_styles_before' );

		/* load WP colorpicker */
		wp_enqueue_style( 'wp-color-picker' );

		/* load admin styles */
		wp_enqueue_style( 'prince-admin-css', notification_plus_settings_assets_url . '/css/admin.css', false, false );

		/* load the RTL stylesheet */
		$wp_styles->add_data( 'prince-admin-css', 'rtl', true );

		/* Remove styles added by the Easy Digital Downloads plugin */
		if ( isset( $post->post_type ) && $post->post_type == 'post' ) {
			wp_dequeue_style( 'jquery-ui-css' );
		}

		/**
		 * Filter the screen IDs used to dequeue `jquery-ui-css`.
		 *
		 * @param array $screen_ids An array of screen IDs.
		 *
		 * @since     1.0.0
		 *
		 */
		$screen_ids = apply_filters( 'prince_dequeue_jquery_ui_css_screen_ids', array(
			'toplevel_page_prince-settings',
			'prince_page_prince-documentation',
			'appearance_page_prince-settings'
		) );

		/* Remove styles added by the WP Review plugin and any custom pages added through filtering */
		if ( in_array( get_current_screen()->id, $screen_ids ) ) {
			wp_dequeue_style( 'plugin_name-admin-ui-css' );
			wp_dequeue_style( 'jquery-ui-css' );
		}

		/* execute styles after actions */
		do_action( 'prince_admin_styles_after' );

	}

}

/**
 * Setup the default admin scripts
 *
 * @return    void
 *
 * @access    public
 * @uses      wp_enqueue_script()     Add Prince scripts
 * @uses      wp_localize_script()    Used to include arbitrary Javascript data
 *
 * @uses      add_thickbox()          Include Thickbox for file uploads
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_admin_scripts' ) ) {

	function prince_admin_scripts() {

		/* execute scripts before actions */
		do_action( 'prince_admin_scripts_before' );

		if ( function_exists( 'wp_enqueue_media' ) ) {
			/* WP 3.5 Media Uploader */
			wp_enqueue_media();
		} else {
			/* Legacy Thickbox */
			add_thickbox();
		}

		/* load jQuery-ui slider */
		wp_enqueue_script( 'jquery-ui-slider' );

		/* load jQuery-ui datepicker */
		wp_enqueue_script( 'jquery-ui-datepicker' );

		/* load WP colorpicker */
		wp_enqueue_script( 'wp-color-picker' );

		/* load Ace Editor for CSS Editing */ //todo uncomment when need css & js editor
		//wp_enqueue_script( 'ace-editor', notification_plus_settings_assets_url.'/ace.min.js', null, '1.1.3' );

		/* load all the required scripts */
		wp_enqueue_script( 'prince', notification_plus_settings_assets_url . '/js/admin.js', array(
			'jquery',
			'jquery-ui-tabs',
			'jquery-ui-slider',
			'jquery-ui-datepicker',
			'wp-color-picker',
		), false );

		/* create localized JS array */
		$localized_array = array(
			'ajax'                  => admin_url( 'admin-ajax.php' ),
			'nonce'                 => wp_create_nonce( 'prince' ),
			'upload_text'           => apply_filters( 'prince_upload_text', __( 'Done', 'notification-plus' ) ),
			'remove_media_text'     => __( 'Remove Media', 'notification-plus' ),
			'reset_agree'           => __( 'Are you sure you want to reset back to the defaults?', 'notification-plus' ),
			'remove_no'             => __( 'You can\'t remove this! But you can edit the values.', 'notification-plus' ),
			'remove_agree'          => __( 'Are you sure you want to remove this?', 'notification-plus' ),
			'activate_layout_agree' => __( 'Are you sure you want to activate this layout?', 'notification-plus' ),
			'setting_limit'         => __( 'Sorry, you can\'t have settings three levels deep.', 'notification-plus' ),
			'delete'                => __( 'Delete Gallery', 'notification-plus' ),
			'deletePlaylist'        => __( 'Delete Playlist', 'notification-plus' ),
			'edit'                  => __( 'Edit Gallery', 'notification-plus' ),
			'editPlaylist'          => __( 'Edit Playlist', 'notification-plus' ),
			'create'                => __( 'Create Gallery', 'notification-plus' ),
			'createPlaylist'        => __( 'Create Playlist', 'notification-plus' ),
			'confirm'               => __( 'Are you sure you want to delete this Gallery?', 'notification-plus' ),
			'confirmPlaylist'       => __( 'Are you sure you want to delete this Playlist?', 'notification-plus' ),
			'date_current'          => __( 'Today', 'notification-plus' ),
			'date_time_current'     => __( 'Now', 'notification-plus' ),
			'date_close'            => __( 'Close', 'notification-plus' ),
			'replace'               => __( 'Featured Image', 'notification-plus' ),
			'with'                  => __( 'Image', 'notification-plus' )
		);

		/* localized script attached to 'prince' */
		wp_localize_script( 'prince', 'prince', $localized_array );

		/* execute scripts after actions */
		do_action( 'prince_admin_scripts_after' );

	}

}

/**
 * Returns the ID of a custom post type by post_title.
 *
 * @return      int
 *
 * @access      public
 * @uses        get_results()
 *
 * @since       2.0
 */
if ( ! function_exists( 'prince_get_media_post_ID' ) ) {

	function prince_get_media_post_ID() {

		// Option ID
		$option_id = 'prince_media_post_ID';

		// Get the media post ID
		$post_ID = get_option( $option_id, false );

		// Add $post_ID to the DB
		if ( $post_ID === false || empty( $post_ID ) ) {
			global $wpdb;

			// Get the media post ID
			$post_ID = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE `post_title` = 'Media' AND `post_type` = 'prince' AND `post_status` = 'private'" );

			// Add to the DB
			if ( $post_ID !== null ) {
				update_option( $option_id, $post_ID );
			}

		}

		return $post_ID;

	}

}

/**
 * Register custom post type & create the media post used to attach images.
 *
 * @return      void
 *
 * @access      public
 * @uses        get_results()
 *
 * @since       2.0
 */
if ( ! function_exists( 'prince_create_media_post' ) ) {

	function prince_create_media_post() {

		$regsiter_post_type = 'register_' . 'post_type';
		$regsiter_post_type( 'prince', array(
			'labels'              => array( 'name' => __( 'Option Tree', 'notification-plus' ) ),
			'public'              => false,
			'show_ui'             => false,
			'capability_type'     => 'post',
			'exclude_from_search' => true,
			'hierarchical'        => false,
			'rewrite'             => false,
			'supports'            => array( 'title', 'editor' ),
			'can_export'          => false,
			'show_in_nav_menus'   => false
		) );

		/* look for custom page */
		$post_id = prince_get_media_post_ID();

		/* no post exists */
		if ( $post_id == 0 ) {

			/* create post object */
			$_p                   = array();
			$_p['post_title']     = 'Media';
			$_p['post_name']      = 'media';
			$_p['post_status']    = 'private';
			$_p['post_type']      = 'prince';
			$_p['comment_status'] = 'closed';
			$_p['ping_status']    = 'closed';

			/* insert the post into the database */
			wp_insert_post( $_p );

		}

	}

}

/**
 * Setup default settings array.
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_default_settings' ) ) {

	function prince_default_settings() {
		global $wpdb;

		if ( ! get_option( prince_settings_id() ) ) {

			$section_count  = 0;
			$settings_count = 0;
			$settings       = array();
			$table_name     = $wpdb->prefix . 'prince';

			if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) ) == $table_name && $old_settings = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY item_sort ASC" ) ) {

				foreach ( $old_settings as $setting ) {

					/* heading is a section now */
					if ( $setting->item_type == 'heading' ) {

						/* add section to the sections array */
						$settings['sections'][ $section_count ]['id']    = $setting->item_id;
						$settings['sections'][ $section_count ]['title'] = $setting->item_title;

						/* save the last section id to use in creating settings */
						$section = $setting->item_id;

						/* increment the section count */
						$section_count ++;

					} else {

						/* add setting to the settings array */
						$settings['settings'][ $settings_count ]['id']      = $setting->item_id;
						$settings['settings'][ $settings_count ]['label']   = $setting->item_title;
						$settings['settings'][ $settings_count ]['desc']    = $setting->item_desc;
						$settings['settings'][ $settings_count ]['section'] = $section;
						$settings['settings'][ $settings_count ]['type']    = prince_map_old_option_types( $setting->item_type );
						$settings['settings'][ $settings_count ]['std']     = '';
						$settings['settings'][ $settings_count ]['class']   = '';

						/* textarea rows */
						$rows = '';
						if ( in_array( $settings['settings'][ $settings_count ]['type'], array(
							'css',
							'javascript',
							'textarea'
						) ) ) {
							if ( (int) $setting->item_options > 0 ) {
								$rows = (int) $setting->item_options;
							} else {
								$rows = 15;
							}
						}
						$settings['settings'][ $settings_count ]['rows'] = $rows;

						/* post type */
						$post_type = '';
						if ( in_array( $settings['settings'][ $settings_count ]['type'], array(
							'custom-post-type-select',
							'custom-post-type-checkbox'
						) ) ) {
							if ( '' != $setting->item_options ) {
								$post_type = $setting->item_options;
							} else {
								$post_type = 'post';
							}
						}
						$settings['settings'][ $settings_count ]['post_type'] = $post_type;

						/* choices */
						$choices = array();
						if ( in_array( $settings['settings'][ $settings_count ]['type'], array(
							'checkbox',
							'radio',
							'select'
						) ) ) {
							if ( '' != $setting->item_options ) {
								$choices = prince_convert_string_to_array( $setting->item_options );
							}
						}
						$settings['settings'][ $settings_count ]['choices'] = $choices;

						$settings_count ++;
					}

				}

				/* make sure each setting has a section just incase */
				if ( isset( $settings['sections'] ) && isset( $settings['settings'] ) ) {
					foreach ( $settings['settings'] as $k => $setting ) {
						if ( '' == $setting['section'] ) {
							$settings['settings'][ $k ]['section'] = $settings['sections'][0]['id'];
						}
					}
				}

			}

			/* if array if not properly formed create fallback settings array */
			if ( ! isset( $settings['sections'] ) || ! isset( $settings['settings'] ) ) {

				$settings = array(
					'sections' => array(
						array(
							'id'    => 'general',
							'title' => __( 'General', 'notification-plus' )
						)
					),
					'settings' => array(
						array(
							'id'        => 'sample_text',
							'label'     => __( 'Sample Text Field Label', 'notification-plus' ),
							'desc'      => __( 'Description for the sample text field.', 'notification-plus' ),
							'section'   => 'general',
							'type'      => 'text',
							'std'       => '',
							'class'     => '',
							'rows'      => '',
							'post_type' => '',
							'choices'   => array()
						)
					)
				);

			}

			/* update the settings array */
			update_option( prince_settings_id(), $settings );

			/* get option tree array */
			$options = get_option( prince_options_id() );

			/* validate options */
			if ( is_array( $options ) ) {

				foreach ( $settings['settings'] as $setting ) {

					if ( isset( $options[ $setting['id'] ] ) ) {

						$content = prince_stripslashes( $options[ $setting['id'] ] );

						$options[ $setting['id'] ] = prince_validate_setting( $content, $setting['type'], $setting['id'] );

					}

				}

				/* execute the action hook and pass the Settings to it */
				do_action( 'prince_before_settings_save', $options );

				/* update the option tree array */
				update_option( prince_options_id(), $options );

			}

		}

	}

}

/**
 * Helper function to update the CSS option type after save.
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_save_css' ) ) {

	function prince_save_css( $options ) {

		/* grab a copy of the settings */
		$settings = get_option( prince_settings_id() );

		/* has settings */
		if ( isset( $settings['settings'] ) ) {

			/* loop through sections and insert CSS when needed */
			foreach ( $settings['settings'] as $k => $setting ) {

				/* is the CSS option type */
				if ( isset( $setting['type'] ) && 'css' == $setting['type'] ) {

					/* insert CSS into dynamic.css */
					if ( isset( $options[ $setting['id'] ] ) && '' !== $options[ $setting['id'] ] ) {

						prince_insert_css_with_markers( $setting['id'], $options[ $setting['id'] ] );

						/* remove old CSS from dynamic.css */
					} else {

						prince_remove_old_css( $setting['id'] );

					}

				}

			}

		}

	}

}

/**
 * Save settings array before the screen is displayed.
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_save_settings' ) ) {

	function prince_save_settings() {

		/* check and verify import settings nonce */
		if ( isset( $_POST['prince_settings_nonce'] ) && wp_verify_nonce( $_POST['prince_settings_nonce'], 'prince_settings_form' ) ) {

			/* settings value */
			$settings = isset( $_POST[ prince_settings_id() ] ) ? esc_attr( $_POST[ prince_settings_id() ] ) : '';

			/* validate sections */
			if ( isset( $settings['sections'] ) ) {

				/* fix numeric keys since drag & drop will change them */
				$settings['sections'] = array_values( $settings['sections'] );

				/* loop through sections */
				foreach ( $settings['sections'] as $k => $section ) {

					/* remove from array if missing values */
					if ( ( ! isset( $section['title'] ) && ! isset( $section['id'] ) ) || ( '' == $section['title'] && '' == $section['id'] ) ) {

						unset( $settings['sections'][ $k ] );

					} else {

						/* validate label */
						if ( '' != $section['title'] ) {

							$settings['sections'][ $k ]['title'] = wp_kses_post( $section['title'] );

						}

						/* missing title set to unfiltered ID */
						if ( ! isset( $section['title'] ) || '' == $section['title'] ) {

							$settings['sections'][ $k ]['title'] = wp_kses_post( $section['id'] );

							/* missing ID set to title */
						} else if ( ! isset( $section['id'] ) || '' == $section['id'] ) {

							$section['id'] = wp_kses_post( $section['title'] );

						}

						/* sanitize ID once everything has been checked first */
						$settings['sections'][ $k ]['id'] = prince_sanitize_option_id( wp_kses_post( $section['id'] ) );

					}

				}

				$settings['sections'] = prince_stripslashes( $settings['sections'] );

			}

			/* validate settings by looping over array as many times as it takes */
			if ( isset( $settings['settings'] ) ) {

				$settings['settings'] = prince_validate_settings_array( $settings['settings'] );

			}

			/* validate contextual_help */
			if ( isset( $settings['contextual_help']['content'] ) ) {

				/* fix numeric keys since drag & drop will change them */
				$settings['contextual_help']['content'] = array_values( $settings['contextual_help']['content'] );

				/* loop through content */
				foreach ( $settings['contextual_help']['content'] as $k => $content ) {

					/* remove from array if missing values */
					if ( ( ! isset( $content['title'] ) && ! isset( $content['id'] ) ) || ( '' == $content['title'] && '' == $content['id'] ) ) {

						unset( $settings['contextual_help']['content'][ $k ] );

					} else {

						/* validate label */
						if ( '' != $content['title'] ) {

							$settings['contextual_help']['content'][ $k ]['title'] = wp_kses_post( $content['title'] );

						}

						/* missing title set to unfiltered ID */
						if ( ! isset( $content['title'] ) || '' == $content['title'] ) {

							$settings['contextual_help']['content'][ $k ]['title'] = wp_kses_post( $content['id'] );

							/* missing ID set to title */
						} else if ( ! isset( $content['id'] ) || '' == $content['id'] ) {

							$content['id'] = wp_kses_post( $content['title'] );

						}

						/* sanitize ID once everything has been checked first */
						$settings['contextual_help']['content'][ $k ]['id'] = prince_sanitize_option_id( wp_kses_post( $content['id'] ) );

					}

					/* validate textarea description */
					if ( isset( $content['content'] ) ) {

						$settings['contextual_help']['content'][ $k ]['content'] = wp_kses_post( $content['content'] );

					}

				}

			}

			/* validate contextual_help sidebar */
			if ( isset( $settings['contextual_help']['sidebar'] ) ) {

				$settings['contextual_help']['sidebar'] = wp_kses_post( $settings['contextual_help']['sidebar'] );

			}

			$settings['contextual_help'] = prince_stripslashes( $settings['contextual_help'] );

			/* default message */
			$message = 'failed';

			/* is array: save & show success message */
			if ( is_array( $settings ) ) {

				/* WPML unregister ID's that have been removed */
				if ( function_exists( 'icl_unregister_string' ) ) {

					$current = get_option( prince_settings_id() );
					$options = get_option( prince_options_id() );

					if ( isset( $current['settings'] ) ) {

						/* Empty ID array */
						$new_ids = array();

						/* Build the WPML IDs array */
						foreach ( $settings['settings'] as $setting ) {

							if ( $setting['id'] ) {

								$new_ids[] = $setting['id'];

							}

						}

						/* Remove missing IDs from WPML */
						foreach ( $current['settings'] as $current_setting ) {

							if ( ! in_array( $current_setting['id'], $new_ids ) ) {

								if ( ! empty( $options[ $current_setting['id'] ] ) && in_array( $current_setting['type'], array(
										'list-item',
										'slider'
									) ) ) {

									foreach ( $options[ $current_setting['id'] ] as $key => $value ) {

										foreach ( $value as $ckey => $cvalue ) {

											prince_wpml_unregister_string( $current_setting['id'] . '_' . $ckey . '_' . $key );

										}

									}

								} else if ( ! empty( $options[ $current_setting['id'] ] ) && $current_setting['type'] == 'social-icons' ) {

									foreach ( $options[ $current_setting['id'] ] as $key => $value ) {

										foreach ( $value as $ckey => $cvalue ) {

											prince_wpml_unregister_string( $current_setting['id'] . '_' . $ckey . '_' . $key );

										}

									}

								} else {

									prince_wpml_unregister_string( $current_setting['id'] );

								}

							}

						}

					}

				}

				update_option( prince_settings_id(), $settings );
				$message = 'success';

			}

			/* redirect */
			wp_redirect( esc_url_raw( add_query_arg( array(
				'action'  => 'save-settings',
				'message' => $message
			), esc_html( $_POST['_wp_http_referer'] ) ) ) );
			exit;

		}

		return false;

	}

}

/**
 * Unregister WPML strings based on settings changing.
 *
 * @param array $settings The array of settings.
 *
 * @access public
 * @since  2.7.0
 */
if ( ! function_exists( 'prince_wpml_unregister' ) ) {

	function prince_wpml_unregister( $settings = array() ) {

		// WPML unregister ID's that have been removed.
		if ( function_exists( 'icl_unregister_string' ) ) {

			$current = get_option( prince_settings_id() );
			$options = get_option( prince_options_id() );

			if ( isset( $current['settings'] ) ) {

				// Empty ID array.
				$new_ids = array();

				// Build the WPML IDs array.
				foreach ( $settings['settings'] as $setting ) {
					if ( $setting['id'] ) {
						$new_ids[] = $setting['id'];
					}
				}

				// Remove missing IDs from WPML.
				foreach ( $current['settings'] as $current_setting ) {
					if ( ! in_array( $current_setting['id'], $new_ids, true ) ) {
						if ( ! empty( $options[ $current_setting['id'] ] ) && in_array( $current_setting['type'], array(
								'list-item',
								'slider'
							), true ) ) {
							foreach ( $options[ $current_setting['id'] ] as $key => $value ) {
								foreach ( $value as $ckey => $cvalue ) {
									prince_wpml_unregister_string( $current_setting['id'] . '_' . $ckey . '_' . $key );
								}
							}
						} elseif ( ! empty( $options[ $current_setting['id'] ] ) && 'social-icons' === $current_setting['type'] ) {
							foreach ( $options[ $current_setting['id'] ] as $key => $value ) {
								foreach ( $value as $ckey => $cvalue ) {
									prince_wpml_unregister_string( $current_setting['id'] . '_' . $ckey . '_' . $key );
								}
							}
						} else {
							prince_wpml_unregister_string( $current_setting['id'] );
						}
					}
				}
			}
		}
	}
}

/**
 * Validate the settings array before save.
 *
 * This function will loop over the settings array as many
 * times as it takes to validate every sub setting.
 *
 * @param array $settings The array of settings.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_validate_settings_array' ) ) {

	function prince_validate_settings_array( $settings = array() ) {

		/* validate settings */
		if ( count( $settings ) > 0 ) {

			/* fix numeric keys since drag & drop will change them */
			$settings = array_values( $settings );

			/* loop through settings */
			foreach ( $settings as $k => $setting ) {


				/* remove from array if missing values */
				if ( ( ! isset( $setting['label'] ) && ! isset( $setting['id'] ) ) || ( '' == $setting['label'] && '' == $setting['id'] ) ) {

					unset( $settings[ $k ] );

				} else {

					/* validate label */
					if ( '' != $setting['label'] ) {

						$settings[ $k ]['label'] = wp_kses_post( $setting['label'] );

					}

					/* missing label set to unfiltered ID */
					if ( ! isset( $setting['label'] ) || '' == $setting['label'] ) {

						$settings[ $k ]['label'] = $setting['id'];

						/* missing ID set to label */
					} else if ( ! isset( $setting['id'] ) || '' == $setting['id'] ) {

						$setting['id'] = wp_kses_post( $setting['label'] );

					}

					/* sanitize ID once everything has been checked first */
					$settings[ $k ]['id'] = prince_sanitize_option_id( wp_kses_post( $setting['id'] ) );

				}

				/* validate description */
				if ( '' != $setting['desc'] ) {

					$settings[ $k ]['desc'] = wp_kses_post( $setting['desc'] );

				}

				/* validate choices */
				if ( isset( $setting['choices'] ) ) {

					/* loop through choices */
					foreach ( $setting['choices'] as $ck => $choice ) {

						/* remove from array if missing values */
						if ( ( ! isset( $choice['label'] ) && ! isset( $choice['value'] ) ) || ( '' == $choice['label'] && '' == $choice['value'] ) ) {

							unset( $setting['choices'][ $ck ] );

						} else {

							/* missing label set to unfiltered ID */
							if ( ! isset( $choice['label'] ) || '' == $choice['label'] ) {

								$setting['choices'][ $ck ]['label'] = wp_kses_post( $choice['value'] );

								/* missing value set to label */
							} else if ( ! isset( $choice['value'] ) || '' == $choice['value'] ) {

								$setting['choices'][ $ck ]['value'] = prince_sanitize_option_id( wp_kses_post( $choice['label'] ) );

							}

						}

					}

					/* update keys and push new array values */
					$settings[ $k ]['choices'] = array_values( $setting['choices'] );

				}

				/* validate sub settings */
				if ( isset( $setting['settings'] ) ) {

					$settings[ $k ]['settings'] = prince_validate_settings_array( $setting['settings'] );

				}

			}

		}

		/* return array but strip those damn slashes out first!!! */

		return prince_stripslashes( $settings );

	}

}

/**
 * Helper function to display alert messages.
 *
 * @param array     Page array
 *
 * @return    mixed
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_alert_message' ) ) {

	function prince_alert_message( $page = array() ) {

		if ( empty( $page ) ) {
			return false;
		}

		$before = apply_filters( 'prince_before_page_messages', '', $page );

		if ( $before ) {
			return $before;
		}

		$action  = isset( $_REQUEST['action'] ) ? esc_attr( $_REQUEST['action'] ) : '';
		$message = isset( $_REQUEST['message'] ) ? esc_attr( $_REQUEST['message'] ) : '';
		$updated = isset( $_REQUEST['settings-updated'] ) ? esc_attr( $_REQUEST['settings-updated'] ) : '';

		if ( $action == 'save-settings' ) {

			if ( $message == 'success' ) {

				return '<div id="message" class="updated fade below-h2"><p>' . __( 'Settings updated.', 'notification-plus' ) . '</p></div>';

			} else if ( $message == 'failed' ) {

				return '<div id="message" class="error fade below-h2"><p>' . __( 'Settings could not be saved.', 'notification-plus' ) . '</p></div>';

			}

		} else if ( $action == 'import-xml' || $action == 'import-settings' ) {

			if ( $message == 'success' ) {

				return '<div id="message" class="updated fade below-h2"><p>' . __( 'Settings Imported.', 'notification-plus' ) . '</p></div>';

			} else if ( $message == 'failed' ) {

				return '<div id="message" class="error fade below-h2"><p>' . __( 'Settings could not be imported.', 'notification-plus' ) . '</p></div>';

			}
		} else if ( $action == 'import-data' ) {

			if ( $message == 'success' ) {

				return '<div id="message" class="updated fade below-h2"><p>' . __( 'Data Imported.', 'notification-plus' ) . '</p></div>';

			} else if ( $message == 'failed' ) {

				return '<div id="message" class="error fade below-h2"><p>' . __( 'Data could not be imported.', 'notification-plus' ) . '</p></div>';

			}

		} else if ( $action == 'import-layouts' ) {

			if ( $message == 'success' ) {

				return '<div id="message" class="updated fade below-h2"><p>' . __( 'Layouts Imported.', 'notification-plus' ) . '</p></div>';

			} else if ( $message == 'failed' ) {

				return '<div id="message" class="error fade below-h2"><p>' . __( 'Layouts could not be imported.', 'notification-plus' ) . '</p></div>';

			}

		} else if ( $action == 'save-layouts' ) {

			if ( $message == 'success' ) {

				return '<div id="message" class="updated fade below-h2"><p>' . __( 'Layouts Updated.', 'notification-plus' ) . '</p></div>';

			} else if ( $message == 'failed' ) {

				return '<div id="message" class="error fade below-h2"><p>' . __( 'Layouts could not be updated.', 'notification-plus' ) . '</p></div>';

			} else if ( $message == 'deleted' ) {

				return '<div id="message" class="updated fade below-h2"><p>' . __( 'Layouts have been deleted.', 'notification-plus' ) . '</p></div>';

			}

		} else if ( $updated == 'layout' ) {

			return '<div id="message" class="updated fade below-h2"><p>' . __( 'Layout activated.', 'notification-plus' ) . '</p></div>';

		} else if ( $action == 'reset' ) {

			return '<div id="message" class="updated fade below-h2"><p>' . $page['reset_message'] . '</p></div>';

		}

		do_action( 'prince_custom_page_messages', $page );

		if ( $updated == 'true' ) {

			return '<div id="message" class="updated fade below-h2"><p>' . $page['updated_message'] . '</p></div>';

		}

		return false;

	}

}

/**
 * Setup the default option types.
 *
 * The returned option types are filterable so you can add your own.
 * This is not a task for a beginner as you'll need to add the function
 * that displays the option to the user and validate the saved data.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_option_types_array' ) ) {

	function prince_option_types_array() {

		return apply_filters( 'prince_option_types_array', array(
			'background'                => __( 'Background', 'notification-plus' ),
			'border'                    => __( 'Border', 'notification-plus' ),
			'box-shadow'                => __( 'Box Shadow', 'notification-plus' ),
			'category-checkbox'         => __( 'Category Checkbox', 'notification-plus' ),
			'category-select'           => __( 'Category Select', 'notification-plus' ),
			'checkbox'                  => __( 'Checkbox', 'notification-plus' ),
			'colorpicker'               => __( 'Colorpicker', 'notification-plus' ),
			'colorpicker-opacity'       => __( 'Colorpicker Opacity', 'notification-plus' ),
			'css'                       => __( 'CSS', 'notification-plus' ),
			'custom-post-type-checkbox' => __( 'Custom Post Type Checkbox', 'notification-plus' ),
			'custom-post-type-select'   => __( 'Custom Post Type Select', 'notification-plus' ),
			'date-picker'               => __( 'Date Picker', 'notification-plus' ),
			'date-time-picker'          => __( 'Date Time Picker', 'notification-plus' ),
			'dimension'                 => __( 'Dimension', 'notification-plus' ),
			'gallery'                   => __( 'Gallery', 'notification-plus' ),
			'google-fonts'              => __( 'Google Fonts', 'notification-plus' ),
			'javascript'                => __( 'JavaScript', 'notification-plus' ),
			'link-color'                => __( 'Link Color', 'notification-plus' ),
			'list-item'                 => __( 'List Item', 'notification-plus' ),
			'measurement'               => __( 'Measurement', 'notification-plus' ),
			'numeric-slider'            => __( 'Numeric Slider', 'notification-plus' ),
			'on-off'                    => __( 'On/Off', 'notification-plus' ),
			'page-checkbox'             => __( 'Page Checkbox', 'notification-plus' ),
			'page-select'               => __( 'Page Select', 'notification-plus' ),
			'post-checkbox'             => __( 'Post Checkbox', 'notification-plus' ),
			'post-select'               => __( 'Post Select', 'notification-plus' ),
			'radio'                     => __( 'Radio', 'notification-plus' ),
			'radio-image'               => __( 'Radio Image', 'notification-plus' ),
			'select'                    => __( 'Select', 'notification-plus' ),
			'sidebar-select'            => __( 'Sidebar Select', 'notification-plus' ),
			'slider'                    => __( 'Slider', 'notification-plus' ),
			'social-links'              => __( 'Social Links', 'notification-plus' ),
			'spacing'                   => __( 'Spacing', 'notification-plus' ),
			'tab'                       => __( 'Tab', 'notification-plus' ),
			'tag-checkbox'              => __( 'Tag Checkbox', 'notification-plus' ),
			'tag-select'                => __( 'Tag Select', 'notification-plus' ),
			'taxonomy-checkbox'         => __( 'Taxonomy Checkbox', 'notification-plus' ),
			'taxonomy-select'           => __( 'Taxonomy Select', 'notification-plus' ),
			'text'                      => __( 'Text', 'notification-plus' ),
			'textarea'                  => __( 'Textarea', 'notification-plus' ),
			'textarea-simple'           => __( 'Textarea Simple', 'notification-plus' ),
			'textblock'                 => __( 'Textblock', 'notification-plus' ),
			'textblock-titled'          => __( 'Textblock Titled', 'notification-plus' ),
			'typography'                => __( 'Typography', 'notification-plus' ),
			'upload'                    => __( 'Upload', 'notification-plus' )
		) );

	}
}

/**
 * Filters the typography font-family to add Google fonts dynamically.
 *
 * @param array $families An array of all recognized font families.
 * @param string $field_id ID of the feild being filtered.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */

if ( ! function_exists( 'prince_google_font_stack' ) ) {
	function prince_google_font_stack( $families, $field_id ) {

		$prince_google_fonts     = get_theme_mod( 'prince_google_fonts', array() );
		$prince_set_google_fonts = get_theme_mod( 'prince_set_google_fonts', array() );

		if ( ! empty( $prince_set_google_fonts ) ) {
			foreach ( $prince_set_google_fonts as $id => $sets ) {
				foreach ( $sets as $value ) {
					$family = isset( $value['family'] ) ? $value['family'] : '';
					if ( $family && isset( $prince_google_fonts[ $family ] ) ) {
						$spaces              = explode( ' ', $prince_google_fonts[ $family ]['family'] );
						$font_stack          = count( $spaces ) > 1 ? '"' . $prince_google_fonts[ $family ]['family'] . '"' : $prince_google_fonts[ $family ]['family'];
						$families[ $family ] = apply_filters( 'prince_google_font_stack', $font_stack, $family, $field_id );
					}
				}
			}
		}

		return $families;
	}
}

add_filter( 'prince_recognized_font_families', 'prince_google_font_stack', 1, 2 );

/**
 * Recognized font families
 *
 * Returns an array of all recognized font families.
 * Keys are intended to be stored in the database
 * while values are ready for display in html.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_font_families' ) ) {

	function prince_recognized_font_families( $field_id = '' ) {

		$families = array(
			'arial'     => 'Arial',
			'georgia'   => 'Georgia',
			'helvetica' => 'Helvetica',
			'palatino'  => 'Palatino',
			'tahoma'    => 'Tahoma',
			'times'     => '"Times New Roman", sans-serif',
			'trebuchet' => 'Trebuchet',
			'verdana'   => 'Verdana'
		);

		return apply_filters( 'prince_recognized_font_families', $families, $field_id );

	}

}

/**
 * Recognized font sizes
 *
 * Returns an array of all recognized font sizes.
 *
 * @param string $field_id ID that's passed to the filters.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0.12
 */
if ( ! function_exists( 'prince_recognized_font_sizes' ) ) {

	function prince_recognized_font_sizes( $field_id ) {

		$range = prince_range( apply_filters( 'prince_font_size_low_range', 0, $field_id ), apply_filters( 'prince_font_size_high_range', 150, $field_id ), apply_filters( 'prince_font_size_range_interval', 1, $field_id ) );

		$unit = apply_filters( 'prince_font_size_unit_type', 'px', $field_id );

		foreach ( $range as $k => $v ) {
			$range[ $k ] = $v . $unit;
		}

		return apply_filters( 'prince_recognized_font_sizes', $range, $field_id );
	}

}

/**
 * Recognized font styles
 *
 * Returns an array of all recognized font styles.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_font_styles' ) ) {

	function prince_recognized_font_styles( $field_id = '' ) {

		return apply_filters( 'prince_recognized_font_styles', array(
			'normal'  => 'Normal',
			'italic'  => 'Italic',
			'oblique' => 'Oblique',
			'inherit' => 'Inherit'
		), $field_id );

	}

}

/**
 * Recognized font variants
 *
 * Returns an array of all recognized font variants.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_font_variants' ) ) {

	function prince_recognized_font_variants( $field_id = '' ) {

		return apply_filters( 'prince_recognized_font_variants', array(
			'normal'     => 'Normal',
			'small-caps' => 'Small Caps',
			'inherit'    => 'Inherit'
		), $field_id );

	}

}

/**
 * Recognized font weights
 *
 * Returns an array of all recognized font weights.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_font_weights' ) ) {

	function prince_recognized_font_weights( $field_id = '' ) {

		return apply_filters( 'prince_recognized_font_weights', array(
			'normal'  => 'Normal',
			'bold'    => 'Bold',
			'bolder'  => 'Bolder',
			'lighter' => 'Lighter',
			'100'     => '100',
			'200'     => '200',
			'300'     => '300',
			'400'     => '400',
			'500'     => '500',
			'600'     => '600',
			'700'     => '700',
			'800'     => '800',
			'900'     => '900',
			'inherit' => 'Inherit'
		), $field_id );

	}

}

/**
 * Recognized letter spacing
 *
 * Returns an array of all recognized line heights.
 *
 * @param string $field_id ID that's passed to the filters.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0.12
 */
if ( ! function_exists( 'prince_recognized_letter_spacing' ) ) {

	function prince_recognized_letter_spacing( $field_id ) {

		$range = prince_range( apply_filters( 'prince_letter_spacing_low_range', - 0.1, $field_id ), apply_filters( 'prince_letter_spacing_high_range', 0.1, $field_id ), apply_filters( 'prince_letter_spacing_range_interval', 0.01, $field_id ) );

		$unit = apply_filters( 'prince_letter_spacing_unit_type', 'em', $field_id );

		foreach ( $range as $k => $v ) {
			$range[ $k ] = $v . $unit;
		}

		return apply_filters( 'prince_recognized_letter_spacing', $range, $field_id );
	}

}

/**
 * Recognized line heights
 *
 * Returns an array of all recognized line heights.
 *
 * @param string $field_id ID that's passed to the filters.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0.12
 */
if ( ! function_exists( 'prince_recognized_line_heights' ) ) {

	function prince_recognized_line_heights( $field_id ) {

		$range = prince_range( apply_filters( 'prince_line_height_low_range', 0, $field_id ), apply_filters( 'prince_line_height_high_range', 150, $field_id ), apply_filters( 'prince_line_height_range_interval', 1, $field_id ) );

		$unit = apply_filters( 'prince_line_height_unit_type', 'px', $field_id );

		foreach ( $range as $k => $v ) {
			$range[ $k ] = $v . $unit;
		}

		return apply_filters( 'prince_recognized_line_heights', $range, $field_id );
	}

}

/**
 * Recognized text decorations
 *
 * Returns an array of all recognized text decorations.
 * Keys are intended to be stored in the database
 * while values are ready for display in html.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0.10
 */
if ( ! function_exists( 'prince_recognized_text_decorations' ) ) {

	function prince_recognized_text_decorations( $field_id = '' ) {

		return apply_filters( 'prince_recognized_text_decorations', array(
			'blink'        => 'Blink',
			'inherit'      => 'Inherit',
			'line-through' => 'Line Through',
			'none'         => 'None',
			'overline'     => 'Overline',
			'underline'    => 'Underline'
		), $field_id );

	}

}

/**
 * Recognized text transformations
 *
 * Returns an array of all recognized text transformations.
 * Keys are intended to be stored in the database
 * while values are ready for display in html.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0.10
 */
if ( ! function_exists( 'prince_recognized_text_transformations' ) ) {

	function prince_recognized_text_transformations( $field_id = '' ) {

		return apply_filters( 'prince_recognized_text_transformations', array(
			'capitalize' => 'Capitalize',
			'inherit'    => 'Inherit',
			'lowercase'  => 'Lowercase',
			'none'       => 'None',
			'uppercase'  => 'Uppercase'
		), $field_id );

	}

}

/**
 * Recognized background repeat
 *
 * Returns an array of all recognized background repeat values.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_background_repeat' ) ) {

	function prince_recognized_background_repeat( $field_id = '' ) {

		return apply_filters( 'prince_recognized_background_repeat', array(
			'no-repeat' => 'No Repeat',
			'repeat'    => 'Repeat All',
			'repeat-x'  => 'Repeat Horizontally',
			'repeat-y'  => 'Repeat Vertically',
			'inherit'   => 'Inherit'
		), $field_id );

	}

}

/**
 * Recognized background attachment
 *
 * Returns an array of all recognized background attachment values.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_background_attachment' ) ) {

	function prince_recognized_background_attachment( $field_id = '' ) {

		return apply_filters( 'prince_recognized_background_attachment', array(
			"fixed"   => "Fixed",
			"scroll"  => "Scroll",
			"inherit" => "Inherit"
		), $field_id );

	}

}

/**
 * Recognized background position
 *
 * Returns an array of all recognized background position values.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_recognized_background_position' ) ) {

	function prince_recognized_background_position( $field_id = '' ) {

		return apply_filters( 'prince_recognized_background_position', array(
			"left top"      => "Left Top",
			"left center"   => "Left Center",
			"left bottom"   => "Left Bottom",
			"center top"    => "Center Top",
			"center center" => "Center Center",
			"center bottom" => "Center Bottom",
			"right top"     => "Right Top",
			"right center"  => "Right Center",
			"right bottom"  => "Right Bottom"
		), $field_id );

	}

}

/**
 * Border Styles
 *
 * Returns an array of all available style types.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_border_style_types' ) ) {

	function prince_recognized_border_style_types( $field_id = '' ) {

		return apply_filters( 'prince_recognized_border_style_types', array(
			'hidden' => 'Hidden',
			'dashed' => 'Dashed',
			'solid'  => 'Solid',
			'double' => 'Double',
			'groove' => 'Groove',
			'ridge'  => 'Ridge',
			'inset'  => 'Inset',
			'outset' => 'Outset',
		), $field_id );

	}

}

/**
 * Border Units
 *
 * Returns an array of all available unit types.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_border_unit_types' ) ) {

	function prince_recognized_border_unit_types( $field_id = '' ) {

		return apply_filters( 'prince_recognized_border_unit_types', array(
			'px' => 'px',
			'%'  => '%',
			'em' => 'em',
			'pt' => 'pt'
		), $field_id );

	}

}

/**
 * Dimension Units
 *
 * Returns an array of all available unit types.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_dimension_unit_types' ) ) {

	function prince_recognized_dimension_unit_types( $field_id = '' ) {

		return apply_filters( 'prince_recognized_dimension_unit_types', array(
			'px' => 'px',
			'%'  => '%',
			'em' => 'em',
			'pt' => 'pt'
		), $field_id );

	}

}

/**
 * Spacing Units
 *
 * Returns an array of all available unit types.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_spacing_unit_types' ) ) {

	function prince_recognized_spacing_unit_types( $field_id = '' ) {

		return apply_filters( 'prince_recognized_spacing_unit_types', array(
			'px' => 'px',
			'%'  => '%',
			'em' => 'em',
			'pt' => 'pt'
		), $field_id );

	}

}

/**
 * Recognized Google font families
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_google_font_families' ) ) {

	function prince_recognized_google_font_families( $field_id ) {

		$families            = array();
		$prince_google_fonts = get_theme_mod( 'prince_google_fonts', array() );

		// Forces an array rebuild when we sitch themes
		if ( empty( $prince_google_fonts ) ) {
			$prince_google_fonts = prince_fetch_google_fonts( true, true );
		}

		foreach ( (array) $prince_google_fonts as $key => $item ) {

			if ( isset( $item['family'] ) ) {

				$families[ $key ] = $item['family'];

			}

		}

		return apply_filters( 'prince_recognized_google_font_families', $families, $field_id );

	}

}

/**
 * Recognized Google font variants
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_google_font_variants' ) ) {

	function prince_recognized_google_font_variants( $field_id, $family ) {

		$variants            = array();
		$prince_google_fonts = get_theme_mod( 'prince_google_fonts', array() );

		if ( isset( $prince_google_fonts[ $family ]['variants'] ) ) {

			$variants = $prince_google_fonts[ $family ]['variants'];

		}

		return apply_filters( 'prince_recognized_google_font_variants', $variants, $field_id, $family );

	}

}

/**
 * Recognized Google font subsets
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_recognized_google_font_subsets' ) ) {

	function prince_recognized_google_font_subsets( $field_id, $family ) {

		$subsets             = array();
		$prince_google_fonts = get_theme_mod( 'prince_google_fonts', array() );

		if ( isset( $prince_google_fonts[ $family ]['subsets'] ) ) {

			$subsets = $prince_google_fonts[ $family ]['subsets'];

		}

		return apply_filters( 'prince_recognized_google_font_subsets', $subsets, $field_id, $family );

	}

}

/**
 * Measurement Units
 *
 * Returns an array of all available unit types.
 * Renamed in version 2.0 to avoid name collisions.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_measurement_unit_types' ) ) {

	function prince_measurement_unit_types( $field_id = '' ) {

		return apply_filters( 'prince_measurement_unit_types', array(
			'px' => 'px',
			'%'  => '%',
			'em' => 'em',
			'pt' => 'pt'
		), $field_id );

	}

}

/**
 * Radio Images default array.
 *
 * Returns an array of all available radio images.
 * You can filter this function to change the images
 * on a per option basis.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_radio_images' ) ) {

	function prince_radio_images( $field_id = '' ) {

		return apply_filters( 'prince_radio_images', array(
			array(
				'value' => 'left-sidebar',
				'label' => __( 'Left Sidebar', 'notification-plus' ),
				'src'   => notification_plus_settings_assets_url . 'princeleft-sidebar.png'
			),
			array(
				'value' => 'right-sidebar',
				'label' => __( 'Right Sidebar', 'notification-plus' ),
				'src'   => notification_plus_settings_assets_url . 'princeright-sidebar.png'
			),
			array(
				'value' => 'full-width',
				'label' => __( 'Full Width (no sidebar)', 'notification-plus' ),
				'src'   => notification_plus_settings_assets_url . 'princefull-width.png'
			),
			array(
				'value' => 'dual-sidebar',
				'label' => __( 'Dual Sidebar', 'notification-plus' ),
				'src'   => notification_plus_settings_assets_url . 'princedual-sidebar.png'
			),
			array(
				'value' => 'left-dual-sidebar',
				'label' => __( 'Left Dual Sidebar', 'notification-plus' ),
				'src'   => notification_plus_settings_assets_url . 'princeleft-dual-sidebar.png'
			),
			array(
				'value' => 'right-dual-sidebar',
				'label' => __( 'Right Dual Sidebar', 'notification-plus' ),
				'src'   => notification_plus_settings_assets_url . 'princeright-dual-sidebar.png'
			)
		), $field_id );

	}

}

/**
 * Default List Item Settings array.
 *
 * Returns an array of the default list item settings.
 * You can filter this function to change the settings
 * on a per option basis.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_list_item_settings' ) ) {

	function prince_list_item_settings( $id ) {

		$settings = apply_filters( 'prince_list_item_settings', array(
			array(
				'id'        => 'image',
				'label'     => __( 'Image', 'notification-plus' ),
				'desc'      => '',
				'std'       => '',
				'type'      => 'upload',
				'rows'      => '',
				'class'     => '',
				'post_type' => '',
				'choices'   => array()
			),
			array(
				'id'        => 'link',
				'label'     => __( 'Link', 'notification-plus' ),
				'desc'      => '',
				'std'       => '',
				'type'      => 'text',
				'rows'      => '',
				'class'     => '',
				'post_type' => '',
				'choices'   => array()
			),
			array(
				'id'        => 'description',
				'label'     => __( 'Description', 'notification-plus' ),
				'desc'      => '',
				'std'       => '',
				'type'      => 'textarea-simple',
				'rows'      => 10,
				'class'     => '',
				'post_type' => '',
				'choices'   => array()
			)
		), $id );

		return $settings;

	}

}

/**
 * Default Slider Settings array.
 *
 * Returns an array of the default slider settings.
 * You can filter this function to change the settings
 * on a per option basis.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_slider_settings' ) ) {

	function prince_slider_settings( $id ) {

		$settings = apply_filters( 'image_slider_fields', array(
			array(
				'name'  => 'image',
				'type'  => 'image',
				'label' => __( 'Image', 'notification-plus' ),
				'class' => ''
			),
			array(
				'name'  => 'link',
				'type'  => 'text',
				'label' => __( 'Link', 'notification-plus' ),
				'class' => ''
			),
			array(
				'name'  => 'description',
				'type'  => 'textarea',
				'label' => __( 'Description', 'notification-plus' ),
				'class' => ''
			)
		), $id );

		/* fix the array keys, values, and just get it 2.0 ready */
		foreach ( $settings as $_k => $setting ) {

			foreach ( $setting as $s_key => $s_value ) {

				if ( 'name' == $s_key ) {

					$settings[ $_k ]['id'] = $s_value;
					unset( $settings[ $_k ]['name'] );

				} else if ( 'type' == $s_key ) {

					if ( 'input' == $s_value ) {

						$settings[ $_k ]['type'] = 'text';

					} else if ( 'textarea' == $s_value ) {

						$settings[ $_k ]['type'] = 'textarea-simple';

					} else if ( 'image' == $s_value ) {

						$settings[ $_k ]['type'] = 'upload';

					}

				}

			}

		}

		return $settings;

	}

}

/**
 * Default Social Links Settings array.
 *
 * Returns an array of the default social links settings.
 * You can filter this function to change the settings
 * on a per option basis.
 *
 * @return    array
 *
 * @access    public
 * @uses      apply_filters()
 *
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_social_links_settings' ) ) {

	function prince_social_links_settings( $id ) {

		$settings = apply_filters( 'prince_social_links_settings', array(
			array(
				'id'    => 'name',
				'label' => __( 'Name', 'notification-plus' ),
				'desc'  => sprintf( __( 'Enter the name/ title that will be shown in the title attribute of the link. %s', 'notification-plus' ), '<br><code>Example: Website, Facebook, Twitter etc</code>' ),
				'std'   => '',
				'type'  => 'text',
				'class' => 'prince-setting-title'
			),

			array(
				'id'    => 'href',
				'label' => 'Link',
				'desc'  => sprintf( __( 'Enter a link to the profile or page on the social website. Remember to add the %s part to the front of the link.', 'notification-plus' ), '<code>http://</code>' ),
				'type'  => 'text',
			)
		), $id );

		return $settings;

	}

}

/**
 * Inserts CSS with field_id markers.
 *
 * Inserts CSS into a dynamic.css file, placing it between
 * BEGIN and END field_id markers. Replaces existing marked info,
 * but still retains surrounding data.
 *
 * @param string $field_id The CSS option field ID.
 * @param array $options The current prince array.
 *
 * @return    bool    True on write success, false on failure.
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.5.3
 */
if ( ! function_exists( 'prince_insert_css_with_markers' ) ) {

	function prince_insert_css_with_markers( $field_id = '', $insertion = '', $meta = false ) {

		/* missing $field_id or $insertion exit early */
		if ( '' == $field_id || '' == $insertion ) {
			return;
		}

		/* path to the dynamic.css file */
		$filepath = get_stylesheet_directory() . '/dynamic.css';
		if ( is_multisite() ) {
			$multisite_filepath = get_stylesheet_directory() . '/dynamic-' . get_current_blog_id() . '.css';
			if ( file_exists( $multisite_filepath ) ) {
				$filepath = $multisite_filepath;
			}
		}

		/* allow filter on path */
		$filepath = apply_filters( 'css_option_file_path', $filepath, $field_id );

		/* grab a copy of the paths array */
		$prince_css_file_paths = get_option( 'prince_css_file_paths', array() );
		if ( is_multisite() ) {
			$prince_css_file_paths = get_blog_option( get_current_blog_id(), 'prince_css_file_paths', $prince_css_file_paths );
		}

		/* set the path for this field */
		$prince_css_file_paths[ $field_id ] = $filepath;

		/* update the paths */
		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), 'prince_css_file_paths', $prince_css_file_paths );
		} else {
			update_option( 'prince_css_file_paths', $prince_css_file_paths );
		}

		/* insert CSS into file */
		if ( file_exists( $filepath ) ) {

			$insertion = prince_normalize_css( $insertion );
			$regex     = "/{{([a-zA-Z0-9\_\-\#\|\=]+)}}/";
			$marker    = $field_id;

			/* Match custom CSS */
			preg_match_all( $regex, $insertion, $matches );

			/* Loop through CSS */
			foreach ( $matches[0] as $option ) {

				$value        = '';
				$option_array = explode( '|', str_replace( array( '{{', '}}' ), '', $option ) );
				$option_id    = isset( $option_array[0] ) ? $option_array[0] : '';
				$option_key   = isset( $option_array[1] ) ? $option_array[1] : '';
				$option_type  = prince_get_option_type_by_id( $option_id );
				$fallback     = '';

				// Get the meta array value
				if ( $meta ) {
					global $post;

					$value = get_post_meta( $post->ID, $option_id, true );

					// Get the options array value
				} else {

					$options = get_option( prince_options_id() );

					if ( isset( $options[ $option_id ] ) ) {

						$value = $options[ $option_id ];

					}

				}

				// This in an array of values
				if ( is_array( $value ) ) {

					if ( empty( $option_key ) ) {

						// Measurement
						if ( $option_type == 'measurement' ) {
							$unit = ! empty( $value[1] ) ? $value[1] : 'px';

							// Set $value with measurement properties
							if ( isset( $value[0] ) && strlen( $value[0] ) > 0 ) {
								$value = $value[0] . $unit;
							}

							// Border
						} else if ( $option_type == 'border' ) {
							$border = array();

							$unit = ! empty( $value['unit'] ) ? $value['unit'] : 'px';

							if ( isset( $value['width'] ) && strlen( $value['width'] ) > 0 ) {
								$border[] = $value['width'] . $unit;
							}

							if ( ! empty( $value['style'] ) ) {
								$border[] = $value['style'];
							}

							if ( ! empty( $value['color'] ) ) {
								$border[] = $value['color'];
							}

							/* set $value with border properties or empty string */
							$value = ! empty( $border ) ? implode( ' ', $border ) : '';

							// Box Shadow
						} else if ( $option_type == 'box-shadow' ) {

							/* set $value with box-shadow properties or empty string */
							$value = ! empty( $value ) ? implode( ' ', $value ) : '';

							// Dimension
						} else if ( $option_type == 'dimension' ) {
							$dimension = array();

							$unit = ! empty( $value['unit'] ) ? $value['unit'] : 'px';

							if ( isset( $value['width'] ) && strlen( $value['width'] ) > 0 ) {
								$dimension[] = $value['width'] . $unit;
							}

							if ( isset( $value['height'] ) && strlen( $value['height'] ) > 0 ) {
								$dimension[] = $value['height'] . $unit;
							}

							// Set $value with dimension properties or empty string
							$value = ! empty( $dimension ) ? implode( ' ', $dimension ) : '';

							// Spacing
						} else if ( $option_type == 'spacing' ) {
							$spacing = array();

							$unit = ! empty( $value['unit'] ) ? $value['unit'] : 'px';

							if ( isset( $value['top'] ) && strlen( $value['top'] ) > 0 ) {
								$spacing[] = $value['top'] . $unit;
							}

							if ( isset( $value['right'] ) && strlen( $value['right'] ) > 0 ) {
								$spacing[] = $value['right'] . $unit;
							}

							if ( isset( $value['bottom'] ) && strlen( $value['bottom'] ) > 0 ) {
								$spacing[] = $value['bottom'] . $unit;
							}

							if ( isset( $value['left'] ) && strlen( $value['left'] ) > 0 ) {
								$spacing[] = $value['left'] . $unit;
							}

							// Set $value with spacing properties or empty string
							$value = ! empty( $spacing ) ? implode( ' ', $spacing ) : '';

							// Typography
						} else if ( $option_type == 'typography' ) {
							$font = array();

							if ( ! empty( $value['font-color'] ) ) {
								$font[] = "color: " . $value['font-color'] . ";";
							}

							if ( ! empty( $value['font-family'] ) ) {
								foreach ( prince_recognized_font_families( $marker ) as $key => $v ) {
									if ( $key == $value['font-family'] ) {
										$font[] = "font-family: " . $v . ";";
									}
								}
							}

							if ( ! empty( $value['font-size'] ) ) {
								$font[] = "font-size: " . $value['font-size'] . ";";
							}

							if ( ! empty( $value['font-style'] ) ) {
								$font[] = "font-style: " . $value['font-style'] . ";";
							}

							if ( ! empty( $value['font-variant'] ) ) {
								$font[] = "font-variant: " . $value['font-variant'] . ";";
							}

							if ( ! empty( $value['font-weight'] ) ) {
								$font[] = "font-weight: " . $value['font-weight'] . ";";
							}

							if ( ! empty( $value['letter-spacing'] ) ) {
								$font[] = "letter-spacing: " . $value['letter-spacing'] . ";";
							}

							if ( ! empty( $value['line-height'] ) ) {
								$font[] = "line-height: " . $value['line-height'] . ";";
							}

							if ( ! empty( $value['text-decoration'] ) ) {
								$font[] = "text-decoration: " . $value['text-decoration'] . ";";
							}

							if ( ! empty( $value['text-transform'] ) ) {
								$font[] = "text-transform: " . $value['text-transform'] . ";";
							}

							// Set $value with font properties or empty string
							$value = ! empty( $font ) ? implode( "\n", $font ) : '';

							// Background
						} else if ( $option_type == 'background' ) {
							$bg = array();

							if ( ! empty( $value['background-color'] ) ) {
								$bg[] = $value['background-color'];
							}

							if ( ! empty( $value['background-image'] ) ) {

								// If an attachment ID is stored here fetch its URL and replace the value
								if ( wp_attachment_is_image( $value['background-image'] ) ) {

									$attachment_data = wp_get_attachment_image_src( $value['background-image'], 'original' );

									// Check for attachment data
									if ( $attachment_data ) {

										$value['background-image'] = $attachment_data[0];

									}

								}

								$bg[] = 'url("' . $value['background-image'] . '")';

							}

							if ( ! empty( $value['background-repeat'] ) ) {
								$bg[] = $value['background-repeat'];
							}

							if ( ! empty( $value['background-attachment'] ) ) {
								$bg[] = $value['background-attachment'];
							}

							if ( ! empty( $value['background-position'] ) ) {
								$bg[] = $value['background-position'];
							}

							if ( ! empty( $value['background-size'] ) ) {
								$size = $value['background-size'];
							}

							// Set $value with background properties or empty string
							$value = ! empty( $bg ) ? 'background: ' . implode( " ", $bg ) . ';' : '';

							if ( isset( $size ) ) {
								if ( ! empty( $bg ) ) {
									$value .= apply_filters( 'prince_insert_css_with_markers_bg_size_white_space', "\n\x20\x20", $option_id );
								}
								$value .= "background-size: $size;";
							}

						}

					} else {

						$value = $value[ $option_key ];

					}

				}

				// If an attachment ID is stored here fetch its URL and replace the value
				if ( $option_type == 'upload' && wp_attachment_is_image( $value ) ) {

					$attachment_data = wp_get_attachment_image_src( $value, 'original' );

					// Check for attachment data
					if ( $attachment_data ) {

						$value = $attachment_data[0];

					}

				}

				// Attempt to fallback when `$value` is empty
				if ( empty( $value ) ) {

					// We're trying to access a single array key
					if ( ! empty( $option_key ) ) {

						// Link Color `inherit`
						if ( $option_type == 'link-color' ) {
							$fallback = 'inherit';
						}

					} else {

						// Border
						if ( $option_type == 'border' ) {
							$fallback = 'inherit';
						}

						// Box Shadow
						if ( $option_type == 'box-shadow' ) {
							$fallback = 'none';
						}

						// Colorpicker
						if ( $option_type == 'colorpicker' ) {
							$fallback = 'inherit';
						}

						// Colorpicker Opacity
						if ( $option_type == 'colorpicker-opacity' ) {
							$fallback = 'inherit';
						}

					}

					/**
					 * Filter the `dynamic.css` fallback value.
					 *
					 * @param string $fallback The default CSS fallback value.
					 * @param string $option_id The option ID.
					 * @param string $option_type The option type.
					 * @param string $option_key The option array key.
					 *
					 * @since     1.0.0
					 *
					 */
					$fallback = apply_filters( 'prince_insert_css_with_markers_fallback', $fallback, $option_id, $option_type, $option_key );

				}

				// Let's fallback!
				if ( ! empty( $fallback ) ) {
					$value = $fallback;
				}

				// Filter the CSS
				$value = apply_filters( 'prince_insert_css_with_markers_value', $value, $option_id );

				// Insert CSS, even if the value is empty
				$insertion = stripslashes( str_replace( $option, $value, $insertion ) );

			}

			// Can't write to the file so we error out
			if ( ! is_writable( $filepath ) ) {
				add_settings_error( 'prince', 'dynamic_css', sprintf( __( 'Unable to write to file %s.', 'notification-plus' ), '<code>' . $filepath . '</code>' ), 'error' );

				return false;
			}

			// Create array from the lines of code
			$markerdata = explode( "\n", implode( '', file( $filepath ) ) );

			// Can't write to the file return false
			if ( ! $f = prince_file_open( $filepath, 'w' ) ) {
				return false;
			}

			$searching = true;
			$foundit   = false;

			// Has array of lines
			if ( ! empty( $markerdata ) ) {

				// Foreach line of code
				foreach ( $markerdata as $n => $markerline ) {

					// Found begining of marker, set $searching to false
					if ( $markerline == "/* BEGIN {$marker} */" ) {
						$searching = false;
					}

					// Keep searching each line of CSS
					if ( $searching == true ) {
						if ( $n + 1 < count( $markerdata ) ) {
							prince_file_write( $f, "{$markerline}\n" );
						} else {
							prince_file_write( $f, "{$markerline}" );
						}
					}

					// Found end marker write code
					if ( $markerline == "/* END {$marker} */" ) {
						prince_file_write( $f, "/* BEGIN {$marker} */\n" );
						prince_file_write( $f, "{$insertion}\n" );
						prince_file_write( $f, "/* END {$marker} */\n" );
						$searching = true;
						$foundit   = true;
					}

				}

			}

			// Nothing inserted, write code. DO IT, DO IT!
			if ( ! $foundit ) {
				prince_file_write( $f, "/* BEGIN {$marker} */\n" );
				prince_file_write( $f, "{$insertion}\n" );
				prince_file_write( $f, "/* END {$marker} */\n" );
			}

			// Close file
			prince_file_close( $f );

			return true;
		}

		return false;

	}

}

/**
 * Remove old CSS.
 *
 * Removes CSS when the textarea is empty, but still retains surrounding styles.
 *
 * @param string $field_id The CSS option field ID.
 *
 * @return    bool    True on write success, false on failure.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_remove_old_css' ) ) {

	function prince_remove_old_css( $field_id = '' ) {

		/* missing $field_id string */
		if ( '' == $field_id ) {
			return false;
		}

		/* path to the dynamic.css file */
		$filepath = get_stylesheet_directory() . '/dynamic.css';

		/* allow filter on path */
		$filepath = apply_filters( 'css_option_file_path', $filepath, $field_id );

		/* remove CSS from file */
		if ( is_writeable( $filepath ) ) {

			/* get each line in the file */
			$markerdata = explode( "\n", implode( '', file( $filepath ) ) );

			/* can't write to the file return false */
			if ( ! $f = prince_file_open( $filepath, 'w' ) ) {
				return false;
			}

			$searching = true;

			/* has array of lines */
			if ( ! empty( $markerdata ) ) {

				/* foreach line of code */
				foreach ( $markerdata as $n => $markerline ) {

					/* found begining of marker, set $searching to false  */
					if ( $markerline == "/* BEGIN {$field_id} */" ) {
						$searching = false;
					}

					/* $searching is true, keep rewrite each line of CSS  */
					if ( $searching == true ) {
						if ( $n + 1 < count( $markerdata ) ) {
							prince_file_write( $f, "{$markerline}\n" );
						} else {
							prince_file_write( $f, "{$markerline}" );
						}
					}

					/* found end marker delete old CSS */
					if ( $markerline == "/* END {$field_id} */" ) {
						prince_file_write( $f, "" );
						$searching = true;
					}

				}

			}

			/* close file */
			prince_file_close( $f );

			return true;

		}

		return false;

	}

}

/**
 * Normalize CSS
 *
 * Normalize & Convert all line-endings to UNIX format.
 *
 * @param string $css
 *
 * @return    string
 *
 * @access    public
 * @since     1.1.8
 * @updated   2.0
 */
if ( ! function_exists( 'prince_normalize_css' ) ) {

	function prince_normalize_css( $css ) {

		/* Normalize & Convert */
		$css = str_replace( "\r\n", "\n", $css );
		$css = str_replace( "\r", "\n", $css );

		/* Don't allow out-of-control blank lines */
		$css = preg_replace( "/\n{2,}/", "\n\n", $css );

		return $css;
	}

}

/**
 * Helper function to loop over the option types.
 *
 * @param array $type The current option type.
 *
 * @return   string
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_loop_through_option_types' ) ) {

	function prince_loop_through_option_types( $type = '', $child = false ) {

		$content = '';
		$types   = prince_option_types_array();

		if ( $child ) {
			unset( $types['list-item'] );
		}

		foreach ( $types as $key => $value ) {
			$content .= '<option value="' . $key . '" ' . selected( $type, $key, false ) . '>' . $value . '</option>';
		}

		return $content;

	}

}

/**
 * Helper function to loop over choices.
 *
 * @param string $name The form element name.
 * @param array $choices The array of choices.
 *
 * @return   string
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_loop_through_choices' ) ) {

	function prince_loop_through_choices( $name, $choices = array() ) {

		$content = '';

		foreach ( (array) $choices as $key => $choice ) {
			$content .= '<li class="ui-state-default list-choice">' . prince_choices_view( $name, $key, $choice ) . '</li>';
		}

		return $content;
	}

}

/**
 * Helper function to loop over sub settings.
 *
 * @param string $name The form element name.
 * @param array $settings The array of settings.
 *
 * @return   string
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_loop_through_sub_settings' ) ) {

	function prince_loop_through_sub_settings( $name, $settings = array() ) {

		$content = '';

		foreach ( $settings as $key => $setting ) {
			$content .= '<li class="ui-state-default list-sub-setting">' . prince_settings_view( $name, $key, $setting ) . '</li>';
		}

		return $content;
	}

}

/**
 * Helper function to display sections.
 *
 * This function is used in AJAX to add a new section
 * and when section have already been added and saved.
 *
 * @param int $key The array key for the current element.
 * @param array    An array of values for the current section.
 *
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_sections_view' ) ) {

	function prince_sections_view( $name, $key, $section = array() ) {

		return '
    <div class="prince-setting is-section">
      <div class="open">' . ( isset( $section['title'] ) ? esc_attr( $section['title'] ) : 'Section ' . ( $key + 1 ) ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="prince-setting-edit prince-ui-button button left-item" title="' . __( 'edit', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-edit"></span>' . __( 'Edit', 'notification-plus' ) . '
        </a>
        <a href="javascript:void(0);" class="prince-setting-remove prince-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-trash"></span>' . __( 'Delete', 'notification-plus' ) . '
        </a>
      </div>
      <div class="prince-setting-body">
        <div class="format-settings">
          <div class="format-setting type-text">
            <div class="description">' . __( '<strong>Section Title</strong>: Displayed as a menu item on the Settings page.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][title]" value="' . ( isset( $section['title'] ) ? esc_attr( $section['title'] ) : '' ) . '" class="widefat prince-ui-input prince-setting-title section-title" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text">
            <div class="description">' . __( '<strong>Section ID</strong>: A unique lower case alphanumeric string, underscores allowed.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][id]" value="' . ( isset( $section['id'] ) ? esc_attr( $section['id'] ) : '' ) . '" class="widefat prince-ui-input section-id" autocomplete="off" />
            </div>
          </div>
        </div>
      </div>
    </div>';

	}

}

/**
 * Helper function to display settings.
 *
 * This function is used in AJAX to add a new setting
 * and when settings have already been added and saved.
 *
 * @param int $key The array key for the current element.
 * @param array    An array of values for the current section.
 *
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_settings_view' ) ) {

	function prince_settings_view( $name, $key, $setting = array() ) {

		$child    = ( strpos( $name, '][settings]' ) !== false ) ? true : false;
		$type     = isset( $setting['type'] ) ? $setting['type'] : '';
		$std      = isset( $setting['std'] ) ? $setting['std'] : '';
		$operator = isset( $setting['operator'] ) ? esc_attr( $setting['operator'] ) : 'and';

		// Serialize the standard value just incase
		if ( is_array( $std ) ) {
			$std = maybe_serialize( $std );
		}

		if ( in_array( $type, array( 'css', 'javascript', 'textarea', 'textarea-simple' ) ) ) {
			$std_form_element = '<textarea class="textarea" rows="10" cols="40" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][std]">' . esc_html( $std ) . '</textarea>';
		} else {
			$std_form_element = '<input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][std]" value="' . esc_attr( $std ) . '" class="widefat prince-ui-input" autocomplete="off" />';
		}

		return '
    <div class="prince-setting">
      <div class="open">' . ( isset( $setting['label'] ) ? esc_attr( $setting['label'] ) : 'Setting ' . ( $key + 1 ) ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="prince-setting-edit prince-ui-button button left-item" title="' . __( 'Edit', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-edit"></span>' . __( 'Edit', 'notification-plus' ) . '
        </a>
        <a href="javascript:void(0);" class="prince-setting-remove prince-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-trash"></span>' . __( 'Delete', 'notification-plus' ) . '
        </a>
      </div>
      <div class="prince-setting-body">
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Label</strong>: Displayed as the label of a form element on the Settings page.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][label]" value="' . ( isset( $setting['label'] ) ? esc_attr( $setting['label'] ) : '' ) . '" class="widefat prince-ui-input prince-setting-title" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>ID</strong>: A unique lower case alphanumeric string, underscores allowed.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][id]" value="' . ( isset( $setting['id'] ) ? esc_attr( $setting['id'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-select wide-desc">
            <div class="description">' . __( '<strong>Type</strong>: Choose one of the available option types from the dropdown.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <select name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][type]" value="' . esc_attr( $type ) . '" class="prince-ui-select">
              ' . prince_loop_through_option_types( $type, $child ) . '                     
               
              </select>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-textarea wide-desc">
            <div class="description">' . __( '<strong>Description</strong>: Enter a detailed description for the users to read on the Settings page, HTML is allowed. This is also where you enter content for both the Textblock & Textblock Titled option types.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <textarea class="textarea" rows="10" cols="40" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][desc]">' . ( isset( $setting['desc'] ) ? esc_html( $setting['desc'] ) : '' ) . '</textarea>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-textblock wide-desc">
            <div class="description">' . __( '<strong>Choices</strong>: This will only affect the following option types: Checkbox, Radio, Select & Done.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <ul class="prince-setting-wrap prince-sortable" data-name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . ']">
                ' . ( isset( $setting['choices'] ) ? prince_loop_through_choices( $name . '[' . $key . ']', $setting['choices'] ) : '' ) . '
              </ul>
              <a href="javascript:void(0);" class="prince-choice-add prince-ui-button button hug-left">' . __( 'Add Choice', 'notification-plus' ) . '</a>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-textblock wide-desc">
            <div class="description">' . __( '<strong>Settings</strong>: This will only affect the List Item option type.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <ul class="prince-setting-wrap prince-sortable" data-name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . ']">
                ' . ( isset( $setting['settings'] ) ? prince_loop_through_sub_settings( $name . '[' . $key . '][settings]', $setting['settings'] ) : '' ) . '
              </ul>
              <a href="javascript:void(0);" class="prince-list-item-setting-add prince-ui-button button hug-left">' . __( 'Add Setting', 'notification-plus' ) . '</a>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Standard</strong>: Setting the standard value for your option only works for some option types. Read the <code>Prince->Documentation</code> for more information on which ones.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              ' . $std_form_element . '
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Rows</strong>: Enter a numeric value for the number of rows in your textarea. This will only affect the following option types: CSS, Textarea, & Textarea Simple.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][rows]" value="' . ( isset( $setting['rows'] ) ? esc_attr( $setting['rows'] ) : '' ) . '" class="widefat prince-ui-input" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Post Type</strong>: Add a comma separated list of post type like \'post,page\'. This will only affect the following option types: Custom Post Type Checkbox, & Custom Post Type Select.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][post_type]" value="' . ( isset( $setting['post_type'] ) ? esc_attr( $setting['post_type'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Taxonomy</strong>: Add a comma separated list of any registered taxonomy like \'category,post_tag\'. This will only affect the following option types: Taxonomy Checkbox, & Taxonomy Select.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][taxonomy]" value="' . ( isset( $setting['taxonomy'] ) ? esc_attr( $setting['taxonomy'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Min, Max, & Step</strong>: Add a comma separated list of options in the following format <code>0,100,1</code> (slide from <code>0-100</code> in intervals of <code>1</code>). The three values represent the minimum, maximum, and step options and will only affect the Numeric Slider option type.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][min_max_step]" value="' . ( isset( $setting['min_max_step'] ) ? esc_attr( $setting['min_max_step'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>CSS Class</strong>: Add and optional class to this option type.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][class]" value="' . ( isset( $setting['class'] ) ? esc_attr( $setting['class'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . sprintf( __( '<strong>Condition</strong>: Add a comma separated list (no spaces) of conditions in which the field will be visible, leave this setting empty to always show the field. In these examples, <code>value</code> is a placeholder for your condition, which can be in the form of %s.', 'notification-plus' ), '<code>field_id:is(value)</code>, <code>field_id:not(value)</code>, <code>field_id:contains(value)</code>, <code>field_id:less_than(value)</code>, <code>field_id:less_than_or_equal_to(value)</code>, <code>field_id:greater_than(value)</code>, or <code>field_id:greater_than_or_equal_to(value)</code>' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][condition]" value="' . ( isset( $setting['condition'] ) ? esc_attr( $setting['condition'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-select wide-desc">
            <div class="description">' . __( '<strong>Operator</strong>: Choose the logical operator to compute the result of the conditions.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <select name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][operator]" value="' . $operator . '" class="prince-ui-select">
                <option value="and" ' . selected( $operator, 'and', false ) . '>' . __( 'and', 'notification-plus' ) . '</option>
                <option value="or" ' . selected( $operator, 'or', false ) . '>' . __( 'or', 'notification-plus' ) . '</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    ' . ( ! $child ? '<input type="hidden" class="hidden-section" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][section]" value="' . ( isset( $setting['section'] ) ? esc_attr( $setting['section'] ) : '' ) . '" />' : '' );

	}

}

/**
 * Helper function to display setting choices.
 *
 * This function is used in AJAX to add a new choice
 * and when choices have already been added and saved.
 *
 * @param string $name The form element name.
 * @param array $key The array key for the current element.
 * @param array    An array of values for the current choice.
 *
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_choices_view' ) ) {

	function prince_choices_view( $name, $key, $choice = array() ) {

		return '
    <div class="prince-setting">
      <div class="open">' . ( isset( $choice['label'] ) ? esc_attr( $choice['label'] ) : 'Choice ' . ( $key + 1 ) ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="prince-setting-edit prince-ui-button button left-item" title="' . __( 'Edit', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-edit"></span>' . __( 'Edit', 'notification-plus' ) . '
        </a>
        <a href="javascript:void(0);" class="prince-setting-remove prince-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-trash"></span>' . __( 'Delete', 'notification-plus' ) . '
        </a>
      </div>
      <div class="prince-setting-body">
        <div class="format-settings">
          <div class="format-setting-label">
            <h5>' . __( 'Label', 'notification-plus' ) . '</h5>
          </div>
          <div class="format-setting type-text wide-desc">
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[choices][' . esc_attr( $key ) . '][label]" value="' . ( isset( $choice['label'] ) ? esc_attr( $choice['label'] ) : '' ) . '" class="widefat prince-ui-input prince-setting-title" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting-label">
            <h5>' . __( 'Value', 'notification-plus' ) . '</h5>
          </div>
          <div class="format-setting type-text wide-desc">
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[choices][' . esc_attr( $key ) . '][value]" value="' . ( isset( $choice['value'] ) ? esc_attr( $choice['value'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting-label">
            <h5>' . __( 'Image Source (Radio Image only)', 'notification-plus' ) . '</h5>
          </div>
          <div class="format-setting type-text wide-desc">
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[choices][' . esc_attr( $key ) . '][src]" value="' . ( isset( $choice['src'] ) ? esc_attr( $choice['src'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
    </div>';

	}

}

/**
 * Helper function to display sections.
 *
 * This function is used in AJAX to add a new section
 * and when section have already been added and saved.
 *
 * @param int $key The array key for the current element.
 * @param array    An array of values for the current section.
 *
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_contextual_help_view' ) ) {

	function prince_contextual_help_view( $name, $key, $content = array() ) {

		return '
    <div class="prince-setting">
      <div class="open">' . ( isset( $content['title'] ) ? esc_attr( $content['title'] ) : 'Content ' . ( $key + 1 ) ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="prince-setting-edit prince-ui-button button left-item" title="' . __( 'Edit', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-edit"></span>' . __( 'Edit', 'notification-plus' ) . '
        </a>
        <a href="javascript:void(0);" class="prince-setting-remove prince-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-trash"></span>' . __( 'Delete', 'notification-plus' ) . '
        </a>
      </div>
      <div class="prince-setting-body">
        <div class="format-settings">
          <div class="format-setting type-text no-desc">
            <div class="description">' . __( '<strong>Title</strong>: Displayed as a contextual help menu item on the Settings page.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][title]" value="' . ( isset( $content['title'] ) ? esc_attr( $content['title'] ) : '' ) . '" class="widefat prince-ui-input prince-setting-title" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text no-desc">
            <div class="description">' . __( '<strong>ID</strong>: A unique lower case alphanumeric string, underscores allowed.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][id]" value="' . ( isset( $content['id'] ) ? esc_attr( $content['id'] ) : '' ) . '" class="widefat prince-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-textarea no-desc">
            <div class="description">' . __( '<strong>Content</strong>: Enter the HTML content about this contextual help item displayed on the Theme Option page for end users to read.', 'notification-plus' ) . '</div>
            <div class="format-setting-inner">
              <textarea class="textarea" rows="15" cols="40" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][content]">' . ( isset( $content['content'] ) ? esc_html( $content['content'] ) : '' ) . '</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>';

	}

}

/**
 * Helper function to display list items.
 *
 * This function is used in AJAX to add a new list items
 * and when they have already been added and saved.
 *
 * @param string $name The form field name.
 * @param int $key The array key for the current element.
 * @param array     An array of values for the current list item.
 *
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'prince_list_item_view' ) ) {

	function prince_list_item_view( $name, $key, $list_item = array(), $post_id = 0, $get_option = '', $settings = array(), $type = '' ) {

		/* required title setting */
		$required_setting = array(
			array(
				'id'        => 'title',
				'label'     => __( 'Title', 'notification-plus' ),
				'desc'      => '',
				'std'       => '',
				'type'      => 'text',
				'rows'      => '',
				'class'     => 'prince-setting-title',
				'post_type' => '',
				'choices'   => array()
			)
		);

		/* load the old filterable slider settings */
		if ( 'slider' == $type ) {

			$settings = prince_slider_settings( $name );

		}

		/* if no settings array load the filterable list item settings */
		if ( empty( $settings ) ) {

			$settings = prince_list_item_settings( $name );

		}

		/* merge the two settings array */
		$settings = array_merge( $required_setting, $settings );

		echo '
    <div class="prince-setting">
      <div class="open">' . ( isset( $list_item['title'] ) ? esc_attr( $list_item['title'] ) : '' ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="prince-setting-edit prince-ui-button button left-item" title="' . __( 'Edit', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-edit"></span>' . __( 'Edit', 'notification-plus' ) . '
        </a>
        <a href="javascript:void(0);" class="prince-setting-remove prince-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-trash"></span>' . __( 'Delete', 'notification-plus' ) . '
        </a>
      </div>
      <div class="prince-setting-body">';

		foreach ( $settings as $field ) {

			// Set field value
			$field_value = isset( $list_item[ $field['id'] ] ) ? $list_item[ $field['id'] ] : '';

			/* set default to standard value */
			if ( isset( $field['std'] ) ) {
				$field_value = prince_filter_std_value( $field_value, $field['std'] );
			}

			// filter the title label and description
			if ( $field['id'] == 'title' ) {

				// filter the label
				$field['label'] = apply_filters( 'prince_list_item_title_label', $field['label'], $name );

				// filter the description
				$field['desc'] = apply_filters( 'prince_list_item_title_desc', $field['desc'], $name );

			}

			/* make life easier */
			$_field_name = $get_option ? $get_option . '[' . $name . ']' : $name;

			/* build the arguments array */
			$_args = array(
				'type'               => $field['type'],
				'field_id'           => $name . '_' . $field['id'] . '_' . $key,
				'field_name'         => $_field_name . '[' . $key . '][' . $field['id'] . ']',
				'field_value'        => $field_value,
				'field_desc'         => isset( $field['desc'] ) ? $field['desc'] : '',
				'field_std'          => isset( $field['std'] ) ? $field['std'] : '',
				'field_block'        => isset( $field['block'] ) ? true : false,
				'field_rows'         => isset( $field['rows'] ) ? $field['rows'] : 10,
				'field_post_type'    => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
				'field_taxonomy'     => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
				'field_min_max_step' => isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
				'field_class'        => isset( $field['class'] ) ? $field['class'] : '',
				'field_condition'    => isset( $field['condition'] ) ? $field['condition'] : '',
				'field_operator'     => isset( $field['operator'] ) ? $field['operator'] : 'and',
				'field_choices'      => isset( $field['choices'] ) && ! empty( $field['choices'] ) ? $field['choices'] : array(),
				'field_settings'     => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
				'post_id'            => $post_id,
				'get_option'         => $get_option
			);

			$conditions = '';

			/* setup the conditions */
			if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {

				/* doing magic on the conditions so they work in a list item */
				$conditionals = explode( ',', $field['condition'] );
				foreach ( $conditionals as $condition ) {
					$parts = explode( ':', $condition );
					if ( isset( $parts[0] ) ) {
						$field['condition'] = str_replace( $condition, $name . '_' . $parts[0] . '_' . $key . ':' . $parts[1], $field['condition'] );
					}
				}

				$conditions = ' data-condition="' . $field['condition'] . '"';
				$conditions .= isset( $field['operator'] ) && in_array( $field['operator'], array(
					'and',
					'AND',
					'or',
					'OR'
				) ) ? ' data-operator="' . $field['operator'] . '"' : '';

			}

			// Build the setting CSS class
			if ( ! empty( $_args['field_class'] ) ) {

				$classes = explode( ' ', $_args['field_class'] );

				foreach ( $classes as $_key => $value ) {

					$classes[ $_key ] = $value . '-wrap';

				}

				$class = 'format-settings ' . implode( ' ', $classes );

			} else {

				$class = 'format-settings';

			}

			/* option label */
			echo '<div id="setting_' . $_args['field_id'] . '" class="' . $class . '"' . $conditions . '>';

			/* don't show title with textblocks */
			if ( $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
				echo '<div class="format-setting-label">';
				echo '<h3 class="label">' . esc_attr( $field['label'] ) . '</h3>';
				echo '</div>';
			}

			/* only allow simple textarea inside a list-item due to known DOM issues with wp_editor() */
			if ( apply_filters( 'prince_override_forced_textarea_simple', false, $field['id'] ) == false && $_args['type'] == 'textarea' ) {
				$_args['type'] = 'textarea-simple';
			}

			/* option body, list-item is not allowed inside another list-item */
			if ( $_args['type'] !== 'list-item' && $_args['type'] !== 'slider' ) {
				echo prince_display_by_type( $_args );
			}

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Helper function to display social links.
 *
 * This function is used in AJAX to add a new list items
 * and when they have already been added and saved.
 *
 * @param string $name The form field name.
 * @param int $key The array key for the current element.
 * @param array     An array of values for the current list item.
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_social_links_view' ) ) {

	function prince_social_links_view( $name, $key, $list_item = array(), $post_id = 0, $get_option = '', $settings = array(), $type = '' ) {

		/* if no settings array load the filterable social links settings */
		if ( empty( $settings ) ) {

			$settings = prince_social_links_settings( $name );

		}

		echo '
    <div class="prince-setting">
      <div class="open">' . ( isset( $list_item['name'] ) ? esc_attr( $list_item['name'] ) : '' ) . '</div>
      <div class="button-section">
        <a href="javascript:void(0);" class="prince-setting-edit prince-ui-button button left-item" title="' . __( 'Edit', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-edit"></span>' . __( 'Edit', 'notification-plus' ) . '
        </a>
        <a href="javascript:void(0);" class="prince-setting-remove prince-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'notification-plus' ) . '">
          <span class="icon dashicons dashicons-trash"></span>' . __( 'Delete', 'notification-plus' ) . '
        </a>
      </div>
      <div class="prince-setting-body">';

		foreach ( $settings as $field ) {

			// Set field value
			$field_value = isset( $list_item[ $field['id'] ] ) ? $list_item[ $field['id'] ] : '';

			/* set default to standard value */
			if ( isset( $field['std'] ) ) {
				$field_value = prince_filter_std_value( $field_value, $field['std'] );
			}

			/* make life easier */
			$_field_name = $get_option ? $get_option . '[' . $name . ']' : $name;

			/* build the arguments array */
			$_args = array(
				'type'               => $field['type'],
				'field_id'           => $name . '_' . $field['id'] . '_' . $key,
				'field_name'         => $_field_name . '[' . $key . '][' . $field['id'] . ']',
				'field_value'        => $field_value,
				'field_desc'         => isset( $field['desc'] ) ? $field['desc'] : '',
				'field_std'          => isset( $field['std'] ) ? $field['std'] : '',
				'field_rows'         => isset( $field['rows'] ) ? $field['rows'] : 10,
				'field_post_type'    => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
				'field_taxonomy'     => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
				'field_min_max_step' => isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
				'field_class'        => isset( $field['class'] ) ? $field['class'] : '',
				'field_condition'    => isset( $field['condition'] ) ? $field['condition'] : '',
				'field_operator'     => isset( $field['operator'] ) ? $field['operator'] : 'and',
				'field_choices'      => isset( $field['choices'] ) && ! empty( $field['choices'] ) ? $field['choices'] : array(),
				'field_settings'     => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
				'post_id'            => $post_id,
				'get_option'         => $get_option
			);

			$conditions = '';

			/* setup the conditions */
			if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {

				/* doing magic on the conditions so they work in a list item */
				$conditionals = explode( ',', $field['condition'] );
				foreach ( $conditionals as $condition ) {
					$parts = explode( ':', $condition );
					if ( isset( $parts[0] ) ) {
						$field['condition'] = str_replace( $condition, $name . '_' . $parts[0] . '_' . $key . ':' . $parts[1], $field['condition'] );
					}
				}

				$conditions = ' data-condition="' . $field['condition'] . '"';
				$conditions .= isset( $field['operator'] ) && in_array( $field['operator'], array(
					'and',
					'AND',
					'or',
					'OR'
				) ) ? ' data-operator="' . $field['operator'] . '"' : '';

			}

			/* option label */
			echo '<div id="setting_' . $_args['field_id'] . '" class="format-settings"' . $conditions . '>';

			/* don't show title with textblocks */
			if ( $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
				echo '<div class="format-setting-label">';
				echo '<h3 class="label">' . esc_attr( $field['label'] ) . '</h3>';
				echo '</div>';
			}

			/* only allow simple textarea inside a list-item due to known DOM issues with wp_editor() */
			if ( $_args['type'] == 'textarea' ) {
				$_args['type'] = 'textarea-simple';
			}

			/* option body, list-item is not allowed inside another list-item */
			if ( $_args['type'] !== 'list-item' && $_args['type'] !== 'slider' && $_args['type'] !== 'social-links' ) {
				echo prince_display_by_type( $_args );
			}

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';

	}

}

/**
 * Helper function to validate option ID's
 *
 * @param string $input The string to sanitize.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_sanitize_option_id' ) ) {

	function prince_sanitize_option_id( $input ) {

		return preg_replace( '/[^a-z0-9]/', '_', trim( strtolower( $input ) ) );

	}

}

/**
 * Convert choices string to array
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_convert_string_to_array' ) ) {

	function prince_convert_string_to_array( $input ) {

		if ( '' !== $input ) {

			/* empty choices array */
			$choices = array();

			/* exlode the string into an array */
			foreach ( explode( ',', $input ) as $k => $choice ) {

				/* if ":" is splitting the string go deeper */
				if ( preg_match( '/\|/', $choice ) ) {
					$split                  = explode( '|', $choice );
					$choices[ $k ]['value'] = trim( $split[0] );
					$choices[ $k ]['label'] = trim( $split[1] );

					/* if radio image there are three values */
					if ( isset( $split[2] ) ) {
						$choices[ $k ]['src'] = trim( $split[2] );
					}

				} else {
					$choices[ $k ]['value'] = trim( $choice );
					$choices[ $k ]['label'] = trim( $choice );
				}

			}

			/* return a formated choices array */

			return $choices;

		}

		return false;

	}
}

/**
 * Custom stripslashes from single value or array.
 *
 * @param mixed $input
 *
 * @return      mixed
 *
 * @access      public
 * @since       2.0
 */
if ( ! function_exists( 'prince_stripslashes' ) ) {

	function prince_stripslashes( $input ) {

		if ( is_array( $input ) ) {

			foreach ( $input as &$val ) {

				if ( is_array( $val ) ) {

					$val = prince_stripslashes( $val );

				} else {

					$val = stripslashes( trim( $val ) );

				}

			}

		} else {

			$input = stripslashes( trim( $input ) );

		}

		return $input;

	}

}

/**
 * Returns an array of elements from start to limit, inclusive.
 *
 * Occasionally zero will be some impossibly large number to
 * the "E" power when creating a range from negative to positive.
 * This function attempts to fix that by setting that number back to "0".
 *
 * @param string $start First value of the sequence.
 * @param string $limit The sequence is ended upon reaching the limit value.
 * @param string $step If a step value is given, it will be used as the increment
 *                      between elements in the sequence. step should be given as a
 *                      positive number. If not specified, step will default to 1.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0.12
 */
if ( ! function_exists( 'prince_range' ) ) {
	function prince_range( $start, $limit, $step = 1 ) {

		if ( $step < 0 ) {
			$step = 1;
		}

		$range = range( $start, $limit, $step );

		foreach ( $range as $k => $v ) {
			if ( strpos( $v, 'E' ) ) {
				$range[ $k ] = 0;
			}
		}

		return $range;
	}
}

/**
 * Helper function to return encoded strings
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0.13
 */
if ( ! function_exists( 'prince_encode' ) ) {
	function prince_encode( $value ) {

		$func = 'base64' . '_encode';

		return $func( $value );

	}
}

/**
 * Helper function to return decoded strings
 *
 * @return    string
 *
 * @access    public
 * @since     1.0.0.13
 */
if ( ! function_exists( 'prince_decode' ) ) {
	function prince_decode( $value ) {

		$func = 'base64' . '_decode';

		return $func( $value );

	}
}

/**
 * Helper function to filter standard option values.
 *
 * @param mixed $value Saved string or array value
 * @param mixed $std Standard string or array value
 *
 * @return    mixed     String or array
 *
 * @access    public
 * @since     1.0.0.15
 */
if ( ! function_exists( 'prince_filter_std_value' ) ) {
	function prince_filter_std_value( $value = '', $std = '' ) {

		$std = maybe_unserialize( $std );

		if ( is_array( $value ) && is_array( $std ) ) {

			foreach ( $value as $k => $v ) {

				if ( '' == $value[ $k ] && isset( $std[ $k ] ) ) {

					$value[ $k ] = $std[ $k ];

				}

			}

		} else if ( '' == $value && ! empty( $std ) ) {

			$value = $std;

		}

		return $value;

	}
}

/**
 * Helper function to set the Google fonts array.
 *
 * @param string $id The option ID.
 * @param bool $value The option value
 *
 * @return    void
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_set_google_fonts' ) ) {
	function prince_set_google_fonts( $id = '', $value = '' ) {

		$prince_set_google_fonts = get_theme_mod( 'prince_set_google_fonts', array() );

		if ( is_array( $value ) && ! empty( $value ) ) {
			$prince_set_google_fonts[ $id ] = $value;
		} else if ( isset( $prince_set_google_fonts[ $id ] ) ) {
			unset( $prince_set_google_fonts[ $id ] );
		}

		set_theme_mod( 'prince_set_google_fonts', $prince_set_google_fonts );

	}
}

/**
 * Helper function to remove unused options from the Google fonts array.
 *
 * @param array $options The array of saved options.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_update_google_fonts_after_save' ) ) {
	function prince_update_google_fonts_after_save( $options ) {

		$prince_set_google_fonts = get_theme_mod( 'prince_set_google_fonts', array() );

		foreach ( $prince_set_google_fonts as $key => $set ) {
			if ( ! isset( $options[ $key ] ) ) {
				unset( $prince_set_google_fonts[ $key ] );
			}
		}
		set_theme_mod( 'prince_set_google_fonts', $prince_set_google_fonts );

	}
}
add_action( 'prince_after_settings_save', 'prince_update_google_fonts_after_save', 1 );

/**
 * Helper function to fetch the Google fonts array.
 *
 * @param bool $normalize Whether or not to return a normalized array. Default 'true'.
 * @param bool $force_rebuild Whether or not to force the array to be rebuilt. Default 'false'.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_fetch_google_fonts' ) ) {
	function prince_fetch_google_fonts( $normalize = true, $force_rebuild = false ) {

		/* Google Fonts cache key */
		$prince_google_fonts_cache_key = apply_filters( 'prince_google_fonts_cache_key', 'prince_google_fonts_cache' );

		/* get the fonts from cache */
		$prince_google_fonts = apply_filters( 'prince_google_fonts_cache', get_transient( $prince_google_fonts_cache_key ) );

		if ( $force_rebuild || ! is_array( $prince_google_fonts ) || empty( $prince_google_fonts ) ) {

			$prince_google_fonts = array();

			/* API url and key */
			$prince_google_fonts_api_url = apply_filters( 'prince_google_fonts_api_url', 'https://www.googleapis.com/webfonts/v1/webfonts' );
			$prince_google_fonts_api_key = apply_filters( 'prince_google_fonts_api_key', 'AIzaSyC2pkIzFbbtOiODhKGsF-JPKBsbRStSNgc' );

			/* API arguments */
			$prince_google_fonts_fields = apply_filters( 'prince_google_fonts_fields', array(
				'family',
				'variants',
				'subsets'
			) );
			$prince_google_fonts_sort   = apply_filters( 'prince_google_fonts_sort', 'alpha' );

			/* Initiate API request */
			$prince_google_fonts_query_args = array(
				'key'    => $prince_google_fonts_api_key,
				'fields' => 'items(' . implode( ',', $prince_google_fonts_fields ) . ')',
				'sort'   => $prince_google_fonts_sort
			);


			/* Build and make the request */
			$prince_google_fonts_query    = esc_url_raw( add_query_arg( $prince_google_fonts_query_args, $prince_google_fonts_api_url ) );
			$prince_google_fonts_response = wp_safe_remote_get( $prince_google_fonts_query, array(
				'sslverify' => false,
				'timeout'   => 15
			) );

			/* continue if we got a valid response */
			if ( 200 == wp_remote_retrieve_response_code( $prince_google_fonts_response ) ) {

				if ( $response_body = wp_remote_retrieve_body( $prince_google_fonts_response ) ) {

					/* JSON decode the response body and cache the result */
					$prince_google_fonts_data = json_decode( trim( $response_body ), true );

					if ( is_array( $prince_google_fonts_data ) && isset( $prince_google_fonts_data['items'] ) ) {

						$prince_google_fonts = $prince_google_fonts_data['items'];

						// Normalize the array key
						$prince_google_fonts_tmp = array();
						foreach ( $prince_google_fonts as $key => $value ) {
							$id                             = remove_accents( $value['family'] );
							$id                             = strtolower( $id );
							$id                             = preg_replace( '/[^a-z0-9_\-]/', '', $id );
							$prince_google_fonts_tmp[ $id ] = $value;
						}

						$prince_google_fonts = $prince_google_fonts_tmp;
						set_theme_mod( 'prince_google_fonts', $prince_google_fonts );
						set_transient( $prince_google_fonts_cache_key, $prince_google_fonts, WEEK_IN_SECONDS );

					}

				}

			}

		}

		return $normalize ? prince_normalize_google_fonts( $prince_google_fonts ) : $prince_google_fonts;

	}
}

/**
 * Helper function to normalize the Google fonts array.
 *
 * @param array $google_fonts An array of fonts to nrmalize.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_normalize_google_fonts' ) ) {
	function prince_normalize_google_fonts( $google_fonts ) {

		$prince_normalized_google_fonts = array();

		if ( is_array( $google_fonts ) && ! empty( $google_fonts ) ) {

			foreach ( $google_fonts as $google_font ) {

				if ( isset( $google_font['family'] ) ) {

					$id = str_replace( ' ', '+', $google_font['family'] );

					$prince_normalized_google_fonts[ $id ] = array(
						'family' => $google_font['family']
					);

					if ( isset( $google_font['variants'] ) ) {

						$prince_normalized_google_fonts[ $id ]['variants'] = $google_font['variants'];

					}

					if ( isset( $google_font['subsets'] ) ) {

						$prince_normalized_google_fonts[ $id ]['subsets'] = $google_font['subsets'];

					}

				}

			}

		}

		return $prince_normalized_google_fonts;

	}
}

/**
 * Helper function to register a WPML string.
 *
 * @param string $id The string ID.
 * @param string $value The string value.
 *
 * @access public
 * @since  2.1
 */
if ( ! function_exists( 'prince_wpml_register_string' ) ) {

	function prince_wpml_register_string( $id, $value ) {
		if ( function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'Theme Options', $id, $value );
		}
	}
}

/**
 * Helper function to unregister a WPML string.
 *
 * @param string $id The string ID.
 *
 * @access public
 * @since  2.1
 */
if ( ! function_exists( 'prince_wpml_unregister_string' ) ) {

	function prince_wpml_unregister_string( $id ) {
		if ( function_exists( 'icl_unregister_string' ) ) {
			icl_unregister_string( 'Theme Options', $id );
		}
	}
}

/**
 * Returns an array with the post format gallery meta box.
 *
 * @param mixed $pages Excepts a comma separated string or array of
 *                      post_types and is what tells the metabox where to
 *                      display. Default 'post'.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_meta_box_post_format_gallery' ) ) {
	function prince_meta_box_post_format_gallery( $pages = 'post' ) {

		if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'gallery', current( get_theme_support( 'post-formats' ) ) ) ) {
			return false;
		}

		if ( is_string( $pages ) ) {
			$pages = explode( ',', $pages );
		}

		return apply_filters( 'prince_meta_box_post_format_gallery', array(
			'id'       => 'prince-post-format-gallery',
			'title'    => __( 'Gallery', 'notification-plus' ),
			'desc'     => '',
			'pages'    => $pages,
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => array(
				array(
					'id'    => '_format_gallery',
					'label' => '',
					'desc'  => '',
					'std'   => '',
					'type'  => 'gallery',
					'class' => 'prince-gallery-shortcode'
				)
			)
		), $pages );

	}
}

/**
 * Returns an array with the post format link metabox.
 *
 * @param mixed $pages Excepts a comma separated string or array of
 *                      post_types and is what tells the metabox where to
 *                      display. Default 'post'.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_meta_box_post_format_link' ) ) {
	function prince_meta_box_post_format_link( $pages = 'post' ) {

		if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'link', current( get_theme_support( 'post-formats' ) ) ) ) {
			return false;
		}

		if ( is_string( $pages ) ) {
			$pages = explode( ',', $pages );
		}

		return apply_filters( 'prince_meta_box_post_format_link', array(
			'id'       => 'prince-post-format-link',
			'title'    => __( 'Link', 'notification-plus' ),
			'desc'     => '',
			'pages'    => $pages,
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => array(
				array(
					'id'    => '_format_link_url',
					'label' => '',
					'desc'  => __( 'Link URL', 'notification-plus' ),
					'std'   => '',
					'type'  => 'text'
				),
				array(
					'id'    => '_format_link_title',
					'label' => '',
					'desc'  => __( 'Link Title', 'notification-plus' ),
					'std'   => '',
					'type'  => 'text'
				)
			)
		), $pages );

	}
}

/**
 * Returns an array with the post format quote metabox.
 *
 * @param mixed $pages Excepts a comma separated string or array of
 *                      post_types and is what tells the metabox where to
 *                      display. Default 'post'.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_meta_box_post_format_quote' ) ) {
	function prince_meta_box_post_format_quote( $pages = 'post' ) {

		if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'quote', current( get_theme_support( 'post-formats' ) ) ) ) {
			return false;
		}

		if ( is_string( $pages ) ) {
			$pages = explode( ',', $pages );
		}

		return apply_filters( 'prince_meta_box_post_format_quote', array(
			'id'       => 'prince-post-format-quote',
			'title'    => __( 'Quote', 'notification-plus' ),
			'desc'     => '',
			'pages'    => $pages,
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => array(
				array(
					'id'    => '_format_quote_source_name',
					'label' => '',
					'desc'  => __( 'Source Name (ex. author, singer, actor)', 'notification-plus' ),
					'std'   => '',
					'type'  => 'text'
				),
				array(
					'id'    => '_format_quote_source_url',
					'label' => '',
					'desc'  => __( 'Source URL', 'notification-plus' ),
					'std'   => '',
					'type'  => 'text'
				),
				array(
					'id'    => '_format_quote_source_title',
					'label' => '',
					'desc'  => __( 'Source Title (ex. book, song, movie)', 'notification-plus' ),
					'std'   => '',
					'type'  => 'text'
				),
				array(
					'id'    => '_format_quote_source_date',
					'label' => '',
					'desc'  => __( 'Source Date', 'notification-plus' ),
					'std'   => '',
					'type'  => 'text'
				)
			)
		), $pages );

	}
}

/**
 * Returns an array with the post format video metabox.
 *
 * @param mixed $pages Excepts a comma separated string or array of
 *                      post_types and is what tells the metabox where to
 *                      display. Default 'post'.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_meta_box_post_format_video' ) ) {
	function prince_meta_box_post_format_video( $pages = 'post' ) {

		if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'video', current( get_theme_support( 'post-formats' ) ) ) ) {
			return false;
		}

		if ( is_string( $pages ) ) {
			$pages = explode( ',', $pages );
		}

		return apply_filters( 'prince_meta_box_post_format_video', array(
			'id'       => 'prince-post-format-video',
			'title'    => __( 'Video', 'notification-plus' ),
			'desc'     => '',
			'pages'    => $pages,
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => array(
				array(
					'id'    => '_format_video_embed',
					'label' => '',
					'desc'  => sprintf( __( 'Embed video from services like Youtube, Vimeo, or Hulu. You can find a list of supported oEmbed sites in the %1$s. Alternatively, you could use the built-in %2$s shortcode.', 'notification-plus' ), '<a href="http://codex.wordpress.org/Embeds" target="_blank">' . __( 'Wordpress Codex', 'notification-plus' ) . '</a>', '<code>[video]</code>' ),
					'std'   => '',
					'type'  => 'textarea'
				)
			)
		), $pages );

	}
}

/**
 * Returns an array with the post format audio metabox.
 *
 * @param mixed $pages Excepts a comma separated string or array of
 *                      post_types and is what tells the metabox where to
 *                      display. Default 'post'.
 *
 * @return    array
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_meta_box_post_format_audio' ) ) {
	function prince_meta_box_post_format_audio( $pages = 'post' ) {

		if ( ! current_theme_supports( 'post-formats' ) || ! in_array( 'audio', current( get_theme_support( 'post-formats' ) ) ) ) {
			return false;
		}

		if ( is_string( $pages ) ) {
			$pages = explode( ',', $pages );
		}

		return apply_filters( 'prince_meta_box_post_format_audio', array(
			'id'       => 'prince-post-format-audio',
			'title'    => __( 'Audio', 'notification-plus' ),
			'desc'     => '',
			'pages'    => $pages,
			'context'  => 'side',
			'priority' => 'low',
			'fields'   => array(
				array(
					'id'    => '_format_audio_embed',
					'label' => '',
					'desc'  => sprintf( __( 'Embed audio from services like SoundCloud and Rdio. You can find a list of supported oEmbed sites in the %1$s. Alternatively, you could use the built-in %2$s shortcode.', 'notification-plus' ), '<a href="http://codex.wordpress.org/Embeds" target="_blank">' . __( 'Wordpress Codex', 'notification-plus' ) . '</a>', '<code>[audio]</code>' ),
					'std'   => '',
					'type'  => 'textarea'
				)
			)
		), $pages );

	}
}

/**
 * Returns the option type by ID.
 *
 * @param string $option_id The option ID
 *
 * @return    string    $settings_id The settings array ID
 * @return    string    The option type.
 *
 * @access    public
 * @since     1.0.0
 */
if ( ! function_exists( 'prince_get_option_type_by_id' ) ) {

	function prince_get_option_type_by_id( $option_id, $settings_id = '' ) {

		if ( empty( $settings_id ) ) {

			$settings_id = prince_settings_id();

		}

		$settings = get_option( $settings_id, array() );

		if ( isset( $settings['settings'] ) ) {

			foreach ( $settings['settings'] as $value ) {

				if ( $option_id == $value['id'] && isset( $value['type'] ) ) {

					return $value['type'];

				}

			}

		}

		return false;

	}

}

/**
 * This method instantiates the meta box class & builds the UI.
 *
 * @param array    Array of arguments to create a meta box
 *
 * @return   void
 *
 * @access   public
 * @uses     MetaBox()
 *
 * @since    2.0
 */
if ( ! function_exists( 'prince_register_meta_box' ) ) {

	function prince_register_meta_box( $args ) {
		if ( ! $args ) {
			return;
		}

		$prince_meta_box = new Prince_Settings_MetaBox( $args );
	}

}