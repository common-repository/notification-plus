<?php

defined( 'ABSPATH' ) || exit;

class Notification_Plus_Notifications {

	/**
	 * Get notification config
	 *
	 * @param bool $type
	 * @param bool $post_id
	 *
	 * @return array|mixed
	 */
	public static function get_config( $type = false, $post_id = false ) {

		$settings = (object) get_post_meta( $post_id, '_settings', true );

		$default = [
			'url'                       => ! empty( $settings->url ) ? $settings->url : '',
			'trigger_on'                => ! empty( $settings->trigger_on ) ? $settings->trigger_on : 'all',
			'trigger_locations'         => ! empty( $settings->trigger_locations ) ? $settings->trigger_locations : [],
			'display_for'               => ! empty( $settings->display_for ) ? $settings->display_for : 'all',
			'display_trigger'           => ! empty( $settings->display_trigger ) ? $settings->display_trigger : 'delay',
			'display_trigger_value'     => ! empty( $settings->display_trigger_value ) ? $settings->display_trigger_value : 2,
			'display_once_per_session'  => ! empty( $settings->display_once_per_session ) ? $settings->display_once_per_session : false,
			'display_mobile'            => isset( $settings->display_mobile ) ? $settings->display_mobile : true,
			'display_duration'          => ! empty( $settings->display_duration ) ? $settings->display_duration : 5,
			'display_position'          => ! empty( $settings->display_position ) ? $settings->display_position : 'bottom_left',
			'display_close_button'      => isset( $settings->display_close_button ) ? $settings->display_close_button : true,
			'display_branding'          => isset( $settings->display_branding ) ? $settings->display_branding : true,
			'branding_name'             => ! empty( $settings->branding_name ) ? $settings->branding_name : '🔥 by Notification Plus',
			'branding_url'              => ! empty( $settings->branding_url ) ? $settings->branding_url : '#',
			'title_color'               => ! empty( $settings->title_color ) ? $settings->title_color : '#000',
			'description_color'         => ! empty( $settings->description_color ) ? $settings->description_color : '#000',
			'background_color'          => ! empty( $settings->background_color ) ? $settings->background_color : '#fff',
			'border_radius'             => ! empty( $settings->border_radius ) ? $settings->border_radius : 'rounded',
			'custom_post_page_ids'      => ! empty( $settings->custom_post_page_ids ) ? $settings->custom_post_page_ids : '',
			'button_url'                => ! empty( $settings->button_url ) ? $settings->button_url : '',
			'display_minimum_activity'  => ! empty( $settings->display_minimum_activity ) ? $settings->display_minimum_activity : 0,
			'pulse_background_color'    => ! empty( $settings->pulse_background_color ) ? $settings->pulse_background_color : '#17bf21',
			'enable_sound'              => isset( $settings->enable_sound ) ? $settings->enable_sound : false,
			'notification_sound'        => ! empty( $settings->notification_sound ) ? $settings->notification_sound : '',
			'sound_volume'              => ! empty( $settings->sound_volume ) ? $settings->sound_volume : 50,
			'show_agreement'            => isset( $settings->show_agreement ) ? $settings->show_agreement : false,
			'agreement_text'            => ! empty( $settings->agreement_text ) ? $settings->agreement_text : "I read & agree the Privacy Policy",
			'agreement_url'             => ! empty( $settings->agreement_url ) ? $settings->agreement_url : '',
			'number_color'              => ! empty( $settings->number_color ) ? $settings->number_color : '#fff',
			'number_background_color'   => ! empty( $settings->number_background_color ) ? $settings->number_background_color : '#000',
			'content_title_color'       => '#000',
			'content_description_color' => '#000',
			'button_background_color'   => ! empty( $settings->button_background_color ) ? $settings->button_background_color : '#272727',
			'button_color'              => ! empty( $settings->button_color ) ? $settings->button_color : '#fff',
			'time_color'                => ! empty( $settings->time_color ) ? $settings->time_color : '#fff',
			'time_background_color'     => ! empty( $settings->time_background_color ) ? $settings->time_background_color : '#000',
			'delay_between'             => ! empty( $settings->delay_between ) ? $settings->delay_between : '15',
		];

		$all = [
			'INFORMATIONAL' => array_merge( [
				"name" => __( 'Informational', 'notification-plus' ),
				"icon" => "fa fa-info-circle",

				'title'       => ! empty( $settings->title ) ? $settings->title : __( 'Flash Sale! 🔥', 'notification-plus' ),
				'description' => ! empty( $settings->description ) ? $settings->description : __( 'Limited sale until tonight, right now!', 'notification-plus' ),
				'image'       => ! empty( $settings->image ) ? $settings->image : 'https://img.icons8.com/nolan/2x/cutting-coupon.png',
			], $default ),

			'COUPON' => array_merge( [
				"name" => __( 'Coupon', 'notification-plus' ),
				"icon" => "fa fa-piggy-bank",

				'title'       => ! empty( $settings->title ) ? $settings->title : __( '35% OFF 🔥!', 'notification-plus' ),
				'description' => ! empty( $settings->description ) ? $settings->description : __( 'Limited summer sale coupon code!', 'notification-plus' ),
				'image'       => ! empty( $settings->image ) ? $settings->image : 'https://img.icons8.com/nolan/2x/cutting-coupon.png',
				'coupon_code' => ! empty( $settings->coupon_code ) ? $settings->coupon_code : __( 'COUPON20', 'notification-plus' ),
				'button_text' => ! empty( $settings->button_text ) ? $settings->button_text : __( 'Get Coupon', 'notification-plus' ),
				'footer_text' => ! empty( $settings->footer_text ) ? $settings->footer_text : __( 'No, I don\'t want to save money', 'notification-plus' ),

				'button_background_color' => ! empty( $settings->button_background_color ) ? $settings->button_background_color : '#000',
				'button_color'            => ! empty( $settings->button_color ) ? $settings->button_color : '#fff',
			], $default ),

			'LIVE_COUNTER' => array_merge( [
				"name" => __( 'Live Counter', 'notification-plus' ),
				"icon" => "fa fa-globe",

				'description'   => ! empty( $settings->description ) ? $settings->description : __( 'Active visitors now.', 'notification-plus' ),
				'last_activity' => ! empty( $settings->last_activity ) ? $settings->last_activity : 15,
				"image"         => ! empty( $settings->image ) ? $settings->image : "https://img.icons8.com/nolan/2x/cutting-coupon.png",
				"number"        => ! empty( $settings->number ) ? $settings->number : "15",
			], $default ),

			'EMAIL_COLLECTOR' => array_merge( [
				"name" => __( 'Email Collector', 'notification-plus' ),
				"icon" => "fa fa-envelope-open",

				'title'             => ! empty( $settings->title ) ? $settings->title : __( 'Sign up 🔥', 'notification-plus' ),
				'description'       => ! empty( $settings->description ) ? $settings->description : __( 'We do not send out spam emails & you can unsubscribe at any point.', 'notification-plus' ),
				'email_placeholder' => ! empty( $settings->email_placeholder ) ? $settings->email_placeholder : __( 'Your valid email', 'notification-plus' ),
				'button_text'       => ! empty( $settings->button_text ) ? $settings->button_text : __( 'Sign me up ✅', 'notification-plus' ),
			], $default ),

			'LATEST_CONVERSION' => array_merge( [
				"name" => __( 'Latest Conversion', 'notification-plus' ),
				"icon" => "fa fa-funnel-dollar",

				'display_trigger'       => ! empty( $settings->display_trigger ) ? $settings->display_trigger : 'delay',
				'display_trigger_value' => ! empty( $settings->display_trigger_value ) ? $settings->display_trigger_value : 2,
				'delay'                 => ! empty( $settings->display_trigger_value ) ? $settings->display_trigger_value : 2,

				'source' => ! empty( $settings->source ) ? $settings->source : 'custom',

				'who'      => ! empty( $settings->who ) ? $settings->who : __( 'A cool person just Purchased', 'notification-plus' ),
				'text'     => ! empty( $settings->text ) ? $settings->text : __( 'Notification Plus PRO', 'notification-plus' ),
				'woo_who'  => ! empty( $settings->woo_who ) ? $settings->woo_who : __( '{full_name} just Purchased', 'notification-plus' ),
				'woo_text' => ! empty( $settings->woo_text ) ? $settings->woo_text : __( '{product_name}', 'notification-plus' ),

				'image_type'     => ! empty( $settings->image_type ) ? $settings->image_type : 'featured',
				'woo_image_type' => ! empty( $settings->woo_image_type ) ? $settings->woo_image_type : 'featured',
				'edd_image_type' => ! empty( $settings->edd_image_type ) ? $settings->edd_image_type : 'featured',
				'image'          => ! empty( $settings->image ) ? $settings->image : 'https://img.icons8.com/color/2x/webhook.png',
				'woo_image'      => ! empty( $settings->woo_image ) ? $settings->woo_image : 'https://img.icons8.com/color/2x/webhook.png',
				'edd_image'      => ! empty( $settings->edd_image ) ? $settings->edd_image : 'https://img.icons8.com/color/2x/webhook.png',

				'product_type'     => ! empty( $settings->product_type ) ? $settings->product_type : 'all',
				'woo_product_type' => ! empty( $settings->woo_product_type ) ? $settings->woo_product_type : 'all',
				'edd_product_type' => ! empty( $settings->edd_product_type ) ? $settings->edd_product_type : 'all',
				'woo_product'      => ! empty( $settings->woo_product ) ? $settings->woo_product : '',
				'edd_product'      => ! empty( $settings->edd_product ) ? $settings->edd_product : '',

				'category' => ! empty( $settings->category ) ? $settings->category : '',

				'url_type'     => ! empty( $settings->url_type ) ? $settings->url_type : 'product_page',
				'woo_url_type' => ! empty( $settings->woo_url_type ) ? $settings->woo_url_type : 'product_page',
				'edd_url_type' => ! empty( $settings->edd_url_type ) ? $settings->edd_url_type : 'product_page',
				'woo_url'      => ! empty( $settings->woo_url ) ? $settings->woo_url : '',
				'edd_url'      => ! empty( $settings->edd_url ) ? $settings->edd_url : '',
				'url'          => ! empty( $settings->url ) ? $settings->url : '',

				'time'              => ! empty( $settings->time ) ? $settings->time : '',
				'conversions_count' => ! empty( $settings->conversions_count ) ? $settings->conversions_count : 1,
			], $default ),

			'CONVERSIONS_COUNTER' => array_merge( [
				"name"   => __( 'Conversions Counter', 'notification-plus' ),
				"icon"   => "fa fa-comment-dollar",
				'is_pro' => true,

				'title'         => ! empty( $settings->title ) ? $settings->title : __( 'People bought the product', 'notification-plus' ),
				'image'         => ! empty( $settings->image ) ? $settings->image : "https://img.icons8.com/officel/2x/return-purchase.png",
				'number'        => ! empty( $settings->number ) ? $settings->number : "5",
				'time'          => ! empty( $settings->time ) ? $settings->time : __( 'In the last %s hours', 'notification-plus' ),
				'last_activity' => ! empty( $settings->last_activity ) ? $settings->last_activity : 2,
			], $default ),

			'VIDEO' => array_merge( [
				"name" => __( 'Video', 'notification-plus' ),
				"icon" => "fa fa-video",

				'title'       => ! empty( $settings->title ) ? $settings->title : __( 'Demo of the product 🔥', 'notification-plus' ),
				'video'       => ! empty( $settings->video ) ? $settings->video : "https://www.youtube.com/?v=zWZa05uaiNA",
				'button_text' => ! empty( $settings->button_text ) ? $settings->button_text : __( 'Sign up 🙌', 'notification-plus' ),
			], $default ),

			'SOCIAL_SHARE' => array_merge( [
				"name" => __( 'Social Share', 'notification-plus' ),
				"icon" => "fa fa-share-alt",

				'title'          => ! empty( $settings->title ) ? $settings->title : __( 'Tell your friends 💻', 'notification-plus' ),
				'description'    => ! empty( $settings->description ) ? $settings->description : __( 'We appreciate all the shares to support us! Thank you!', 'notification-plus' ),
				'share_url'      => ! empty( $settings->share_url ) ? $settings->share_url : '',
				'share_facebook' => isset( $settings->share_facebook ) ? $settings->share_facebook : true,
				'share_twitter'  => isset( $settings->share_twitter ) ? $settings->share_twitter : true,
				'share_linkedin' => isset( $settings->share_linkedin ) ? $settings->share_linkedin : true,
			], $default ),

			'RANDOM_REVIEW' => array_merge( [
				"name"   => __( 'Random Review', 'notification-plus' ),
				"icon"   => "fa fa-random",
				'is_pro' => true,

				'title'       => ! empty( $settings->title ) ? $settings->title : __( 'John Doe', 'notification-plus' ),
				'description' => ! empty( $settings->description ) ? $settings->description : __( 'SocialProof is a 5 star product! 🔥', 'notification-plus' ),
				'image'       => ! empty( $settings->image ) ? $settings->image : "https://img.icons8.com/color/2x/person-male.png",
				'stars'       => ! empty( $settings->stars ) ? $settings->stars : 5,
			], $default ),

			'EMOJI_FEEDBACK' => array_merge( [
				"name" => __( 'Emoji Feedback', 'notification-plus' ),
				"icon" => "fa fa-smile-beam",

				'title'                  => ! empty( $settings->title ) ? $settings->title : __( 'Do you like our website?', 'notification-plus' ),
				'show_angry'             => isset( $settings->show_angry ) ? $settings->show_angry : true,
				'show_sad'               => isset( $settings->show_sad ) ? $settings->show_sad : true,
				'show_neutral'           => isset( $settings->show_neutral ) ? $settings->show_neutral : true,
				'show_happy'             => isset( $settings->show_happy ) ? $settings->show_happy : true,
				'show_excited'           => isset( $settings->show_excited ) ? $settings->show_excited : true,
				"feedback_emoji_angry"   => ! empty( $settings->feedback_emoji_angry ) ? $settings->feedback_emoji_angry : __( 'Angry', 'notification-plus' ),
				"feedback_emoji_sad"     => ! empty( $settings->feedback_emoji_sad ) ? $settings->feedback_emoji_sad : __( 'Sad', 'notification-plus' ),
				"feedback_emoji_neutral" => ! empty( $settings->feedback_emoji_neutral ) ? $settings->feedback_emoji_neutral : __( 'Neutral', 'notification-plus' ),
				"feedback_emoji_happy"   => ! empty( $settings->feedback_emoji_happy ) ? $settings->feedback_emoji_happy : __( 'Happy', 'notification-plus' ),
				"feedback_emoji_excited" => ! empty( $settings->feedback_emoji_excited ) ? $settings->feedback_emoji_excited : __( 'Excited', 'notification-plus' ),
			], $default ),

			'COOKIE_NOTIFICATION' => array_merge( [
				"name" => __( 'Cookie Notification', 'notification-plus' ),
				"icon" => "fa fa-cookie",

				'description'   => ! empty( $settings->description ) ? $settings->description : __( 'This website uses cookies to ensure you get the best experience on our website.', 'notification-plus' ),
				'button_text'   => ! empty( $settings->button_text ) ? $settings->button_text : __( 'Okay 🔥', 'notification-plus' ),
				'link_url'      => ! empty( $settings->link_url ) ? $settings->link_url : "",
				'link_url_text' => ! empty( $settings->link_url_text ) ? $settings->link_url_text : __( 'Learn More', 'notification-plus' ),
				'image'         => ! empty( $settings->image ) ? $settings->image : "https://img.icons8.com/plasticine/2x/cookie.png",
			], $default ),

			'SCORE_FEEDBACK' => array_merge( [
				"name" => __( 'Score Feedback', 'notification-plus' ),
				"icon" => "fa fa-sort-numeric-up",

				'title'            => ! empty( $settings->title ) ? $settings->title : __( 'How\'d you like our website?', 'notification-plus' ),
				'description'      => ! empty( $settings->description ) ? $settings->description : __( 'Rate from 1 to 5. 5 being excellent.', 'notification-plus' ),
				"feedback_score_1" => ! empty( $settings->feedback_score_1 ) ? $settings->feedback_score_1 : __( '1', 'notification-plus' ),
				"feedback_score_2" => ! empty( $settings->feedback_score_2 ) ? $settings->feedback_score_2 : __( '2', 'notification-plus' ),
				"feedback_score_3" => ! empty( $settings->feedback_score_3 ) ? $settings->feedback_score_3 : __( '3', 'notification-plus' ),
				"feedback_score_4" => ! empty( $settings->feedback_score_4 ) ? $settings->feedback_score_4 : __( '4', 'notification-plus' ),
				"feedback_score_5" => ! empty( $settings->feedback_score_5 ) ? $settings->feedback_score_5 : __( '5', 'notification-plus' ),
			], $default ),

			'REQUEST_COLLECTOR' => array_merge( [
				"name" => __( 'Request Collector', 'notification-plus' ),
				"icon" => "fa fa-user-plus",

				'title'               => ! empty( $settings->title ) ? $settings->title : __( 'John', 'notification-plus' ),
				'description'         => ! empty( $settings->description ) ? $settings->description : __( 'Support Team', 'notification-plus' ),
				'image'               => ! empty( $settings->image ) ? $settings->image : "https://img.icons8.com/cotton/2x/online-support.png",
				'content_title'       => ! empty( $settings->content_title ) ? $settings->content_title : __( 'Any questions?', 'notification-plus' ),
				'content_description' => ! empty( $settings->content_description ) ? $settings->content_description : __( 'Let us know and we will get back to you!', 'notification-plus' ),
				'input_placeholder'   => ! empty( $settings->input_placeholder ) ? $settings->input_placeholder : __( 'Valid Phone Number', 'notification-plus' ),
				'button_text'         => ! empty( $settings->button_text ) ? $settings->button_text : __( 'Call me back ⚡️', 'notification-plus' ),
			], $default ),

			'COUNTDOWN_COLLECTOR' => array_merge( [
				"name" => __( 'Countdown Collector', 'notification-plus' ),
				"icon" => "fa fa-clock",

				'title'             => ! empty( $settings->title ) ? $settings->title : __( 'Building a website 💻', 'notification-plus' ),
				'description'       => ! empty( $settings->description ) ? $settings->description : __( 'Free Webinar by us.', 'notification-plus' ),
				'content_title'     => ! empty( $settings->content_title ) ? $settings->content_title : __( 'Hurry up! Registrations are closing soon.', 'notification-plus' ),
				'input_placeholder' => ! empty( $settings->input_placeholder ) ? $settings->input_placeholder : __( 'Valid Email', 'notification-plus' ),
				'button_text'       => ! empty( $settings->button_text ) ? $settings->button_text : __( 'Sign up ✅️', 'notification-plus' ),
				'end_date'          => ! empty( $settings->end_date ) ? $settings->end_date : date( 'Y-m-d h:i A', strtotime( '+5 hours' ) ),
				"days"              => ! empty( $settings->days ) ? $settings->days : __( 'days', 'notification-plus' ),
				"hours"             => ! empty( $settings->hours ) ? $settings->hours : __( 'hours', 'notification-plus' ),
				"minutes"           => ! empty( $settings->minutes ) ? $settings->minutes : __( 'minutes', 'notification-plus' ),
				"seconds"           => ! empty( $settings->seconds ) ? $settings->seconds : __( 'seconds', 'notification-plus' ),
			], $default )

		];

		return $type ? $all[ $type ] : $all;
	}

	/**
	 * Get notification frontend view
	 *
	 * @param $post_id
	 * @param bool $shortcode
	 *
	 * @throws Exception
	 */
	public static function get_view( $post_id, $shortcode = false ) {

		$type         = get_post_meta( $post_id, '_notification_type', true );
		$notification = (object) self::get_config( $type, $post_id );

		$config = [
			'display_mobile'        => $notification->display_mobile,
			'display_trigger'       => $notification->display_trigger,
			'display_trigger_value' => $notification->display_trigger_value,
			'duration'              => $notification->display_duration == - 1 ? - 1 : $notification->display_duration * 1000,
			'url'                   => $notification->url,
			'close'                 => $notification->display_close_button,
			'once_per_session'      => $notification->display_once_per_session,
			'stop_on_focus'         => true,
			'notification_id'       => $post_id,
			'notification_type'     => $type,
			'enable_sound'          => $notification->enable_sound,
			'notification_sound'    => NOTIFICATION_PLUS_ASSETS . '/sounds/' . $notification->notification_sound . '.mp3',
			'sound_volume'          => $notification->sound_volume,
			'should_show'           => true,
		];

		if ( 'COUNTDOWN_COLLECTOR' == $type ) {
			$config['should_show'] = new \DateTime( $notification->end_date ) > new \DateTime();
		}

		$attr = [
			'data-notification-id' => $post_id,
		];

		$classes = [ 'notification-plus' ];

		if ( $shortcode ) {
			$classes[]              = 'shortcode';
			$config['shortcode']    = true;
			$config['position']     = null;
			$attr['data-shortcode'] = "notification_plus_$post_id";
		}

		if ( ! $shortcode ) {
			$classes[]             = "notification-plus-{$notification->display_position}";
			$attr['id']            = "notification_plus_$post_id";
			$attr['data-position'] = $notification->display_position;
		}

		$classes = implode( ' ', $classes );

		ob_start();

		if ( 'LATEST_CONVERSION' == $type ) {
			$delay    = $notification->delay;
			$duration = $notification->display_duration;
			$between  = $notification->delay_between;
			$limit    = $notification->conversions_count;

			//custom source notifications
			if ( 'custom' == $notification->source ) {
				$settings = get_post_meta( $post_id, '_settings', true );
				if ( ! empty( $settings['custom'] ) ) {

					$conversions = array_slice( $settings['custom'], 0, $limit );

					foreach ( $conversions as $i => $conversion ) {
						if ( 0 == $i ) {
							$config['display_trigger_value'] = $delay;
						} else {
							$config['display_trigger_value'] += ( $delay + $duration + $between );
						}

						$config['url']                = $conversion['url'];
						$config['notification_id']    = "{$post_id}_{$i}";
						$attr['data-notification-id'] = "{$post_id}_{$i}";
						$attr['id']                   = "notification_plus_{$post_id}_{$i}";
						$attr['data-config']          = json_encode( $config );

						$data_string = '';
						foreach ( $attr as $key => $value ) {
							$data_string .= sprintf( " %s='%s'", $key, $value );
						}

						$notification->who   = $conversion['who'];
						$notification->text  = $conversion['text'];
						$notification->image = $conversion['image'];
						$notification->time  = notification_plus_get_time( $conversion['time'] );

						printf( '<div class="%1$s" %2$s>', $classes, $data_string );
						include NOTIFICATION_PLUS_INCLUDES . '/notifications/' . strtolower( $type ) . '.php';
						printf( '</div>' );
					}
				}
			} elseif ( 'woocommerce' == $notification->source ) {

				if ( ! function_exists( 'wc_get_order' ) ) {
					return;
				}

				$orders = notification_plus_get_woo_orders( $notification );

				if ( empty( $orders ) ) {
					return;
				}

				foreach ( $orders as $i => $order_id ) {
					$order = wc_get_order( $order_id );
					$items = $order->get_items();

					if ( empty( $items ) ) {
						continue;
					}
					$item       = array_pop( $items );
					$product_id = $item->get_product_id();

					$full_name    = $order->get_formatted_billing_full_name();
					$first_name   = $order->get_billing_first_name();
					$last_name    = $order->get_billing_last_name();
					$product_name = $item->get_name();

					$rep_search = [
						'{full_name}',
						'{first_name}',
						'{last_name}',
						'{product_name}',
					];

					$replacement = [
						$full_name,
						$first_name,
						$last_name,
						$product_name,
					];

					$config['display_trigger_value'] += 0 == $i ? $delay : $delay + $between;

					$config['url']                = 'custom' != $notification->woo_url_type ? get_permalink( $product_id ) : $notification->woo_url;
					$config['notification_id']    = "{$post_id}_{$i}";
					$attr['data-notification-id'] = "{$post_id}_{$i}";
					$attr['id']                   = "notification_plus_{$post_id}_{$i}";
					$attr['data-config']          = json_encode( $config );

					$image = 'custom' != $notification->woo_image_type ? get_the_post_thumbnail_url( $product_id ) : $notification->woo_image;

					$data_string = '';
					foreach ( $attr as $key => $value ) {
						$data_string .= sprintf( " %s='%s'", $key, $value );
					}

					$notification->who   = str_replace( $rep_search, $replacement, $notification->woo_who );
					$notification->text  = str_replace( $rep_search, $replacement, $notification->woo_text );
					$notification->image = $image;
					$notification->time  = notification_plus_get_time( $order->order_date );

					printf( '<div class="%1$s" %2$s>', $classes, $data_string );
					include NOTIFICATION_PLUS_INCLUDES . '/notifications/' . strtolower( $type ) . '.php';
					printf( '</div>' );
				}

			} elseif ( 'edd' == $notification->source ) {

				$sales = notification_plus_get_edd_sales( $notification );

				if ( empty( $sales ) ) {
					return;
				}

				foreach ( $sales as $i => $sale ) {

					$first_name   = $sale->first_name;
					$last_name    = $sale->last_name;
					$full_name    = $first_name . ' ' . $last_name;
					$item         = $sale->downloads[0]['id'];
					$product_name = get_the_title( $item );

					$rep_search = [
						'{full_name}',
						'{first_name}',
						'{last_name}',
						'{product_name}',
					];

					$replacement = [
						$full_name,
						$first_name,
						$last_name,
						$product_name,
					];

					$config['display_trigger_value'] += 0 == $i ? $delay : $delay + $between;

					$config['url']                = 'custom' != $notification->edd_url_type ? get_permalink( $item ) : $notification->edd_url;
					$config['notification_id']    = "{$post_id}_{$i}";
					$attr['data-notification-id'] = "{$post_id}_{$i}";
					$attr['id']                   = "notification_plus_{$post_id}_{$i}";
					$attr['data-config']          = json_encode( $config );

					$image = 'custom' != $notification->edd_image_type ? get_the_post_thumbnail_url( $item ) : $notification->edd_image;

					$data_string = '';
					foreach ( $attr as $key => $value ) {
						$data_string .= sprintf( " %s='%s'", $key, $value );
					}

					$notification->who   = str_replace( $rep_search, $replacement, $notification->woo_who );
					$notification->text  = str_replace( $rep_search, $replacement, $notification->woo_text );
					$notification->image = $image;
					$notification->time  = notification_plus_get_time( $sale->date );


					printf( '<div class="%1$s" %2$s>', $classes, $data_string );
					include NOTIFICATION_PLUS_INCLUDES . '/notifications/' . strtolower( $type ) . '.php';
					printf( '</div>' );
				}

			}

		} else {
			$attr['data-config'] = json_encode( $config );

			$data_string = '';
			foreach ( $attr as $key => $value ) {
				$data_string .= sprintf( " %s='%s'", $key, $value );
			}

			printf( '<div class="%1$s" %2$s>', $classes, $data_string );
			include NOTIFICATION_PLUS_INCLUDES . '/notifications/' . strtolower( $type ) . '.php';
			printf( '</div>' );
		}

		echo ob_get_clean();

	}

	/**
	 * Get notification preview
	 *
	 * @param $type
	 * @param $post_id
	 */
	public static function preview( $type, $post_id ) {
		$notification = (object) self::get_config( $type, $post_id );

		ob_start();
		include NOTIFICATION_PLUS_INCLUDES . '/notifications/' . strtolower( $type ) . '.php';

		echo preg_replace( [ '/<form/', '/<\/form>/', '/required=\"required\"/' ], [
			'<div',
			'</div>',
			''
		], ob_get_clean() );
	}

	/**
	 * Check if the notification type supports data collection
	 *
	 * @param $type
	 *
	 * @return array
	 */
	public static function statistics_types( $type ) {

		$types = [ 'impression', 'hover' ];
		if ( in_array( $type, [
			'INFORMATIONAL',
			'COUPON',
			'LIVE_COUNTER',
			'VIDEO',
			'SOCIAL_SHARE',
			'COOKIE_NOTIFICATION'
		] ) ) {
			$types[] = 'click';
		} elseif ( in_array( $type, [ 'EMAIL_COLLECTOR', 'REQUEST_COLLECTOR', 'COUNTDOWN_COLLECTOR', ] ) ) {
			$types[] = 'submissions';
		}

		return $types;
	}

	/**
	 * Get notification setting fields based on type
	 *
	 * @param $type
	 * @param bool $field
	 * @param bool $post_id
	 *
	 * @return mixed
	 */
	public static function settings_fields( $type, $field = false, $post_id = false ) {
		$notification = self::get_config( $type, $post_id );

		$fields = require NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/fields/fields.php';

		return $field ? $fields->$field : $fields;
	}

	/**
	 * @param $type
	 *
	 * @return mixed
	 */
	public static function setting_tabs( $type ) {

		$default_display = [
			'trigger',
			'display_for',
			'display_trigger',
			'display_duration',
			'display_position',
			'display_once_per_session',
			'display_mobile',
			'display_close_button',
			'display_branding',
		];

		$fields = [
			'INFORMATIONAL' => [
				'content'   => [ 'title', 'description', 'image', 'url', ],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'border_radius',
					//'max_width',
				],
			],

			'COUPON' => [
				'content'   => [
					'title',
					'description',
					'image',
					'coupon_code',
					'button_url',
					'button_text',
					'footer_text',
				],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'button_background_color',
					'button_color',
					'border_radius',
					//'max_width',
				],
			],

			'LIVE_COUNTER' => [
				'content'   => [ 'description', 'last_activity', 'url', ],
				'display'   => $default_display,
				'customize' => [
					'number_color',
					'number_background_color',
					'description_color',
					'background_color',
					'pulse_background_color',
					'border_radius',
					//'max_width',
				],
			],

			'EMAIL_COLLECTOR' => [
				'content'   => [
					'title',
					'description',
					'email_placeholder',
					'button_text',
					'agreement',
				],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'button_background_color',
					'button_color',
					'border_radius',
					//'max_width',
				],
			],

			'CONVERSIONS_COUNTER' => [
				'content'   => [
					'title',
					'last_activity',
					'url'
				],
				'display'   => $default_display,
				'customize' => [
					'number_color',
					'number_background_color',
					'title_color',
					'background_color',
					'border_radius',
					//'max_width',
				],
			],

			'COOKIE_NOTIFICATION' => [
				'content'   => [ 'description', 'image', 'link_url', 'link_url_text', 'button_text' ],
				'display'   => $default_display,
				'customize' => [
					'description_color',
					'background_color',
					'button_background_color',
					'button_color',
					'border_radius',
					//'max_width',
				],
			],

			'COUNTDOWN_COLLECTOR' => [
				'content'   => [
					'title',
					'description',
					'content_title',
					'input_placeholder',
					'button_text',
					'end_date',
					'agreement',
				],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'content_title_color',
					'time_color',
					'time_background_color',
					'background_color',
					'button_background_color',
					'button_color',
					'border_radius',
					//'max_width',
				],
			],

			'EMOJI_FEEDBACK' => [
				'content'   => [ 'title', 'emoji' ],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'background_color',
					'border_radius',
				],
			],

			'LATEST_CONVERSION' => [
				'source'    => [ 'source' ],
				'content'   => [
					'latest_conversion_content',
					'conversions_count',
				],
				'display'   => [
					'trigger',
					'display_for',
					'delay_first',
					'display_duration',
					'delay_between',
					'display_position',
					'display_once_per_session',
					'display_mobile',
					'display_close_button',
					'display_branding',
				],
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'border_radius',
				],
			],

			'RANDOM_REVIEW' => [
				'content'   => [ 'url' ],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'border_radius',
					//'max_width',
				],
			],

			'REQUEST_COLLECTOR' => [
				'content'   => [
					'title',
					'description',
					'image',
					'content_title',
					'content_description',
					'input_placeholder',
					'button_text',
					'agreement',
				],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'content_title_color',
					'content_description_color',
					'background_color',
					'button_background_color',
					'button_color',
					'border_radius',
					//'max_width',
				],
			],

			'SCORE_FEEDBACK' => [
				'content'   => [ 'title', 'description', ],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'button_background_color',
					'button_color',
				],
			],

			'SOCIAL_SHARE' => [
				'content'   => [ 'title', 'description', 'share_url', ],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'description_color',
					'background_color',
					'border_radius',
				],
			],

			'VIDEO' => [
				'content'   => [ 'title', 'video', 'button_url', 'button_text', ],
				'display'   => $default_display,
				'customize' => [
					'title_color',
					'background_color',
					'button_background_color',
					'button_color',
					'border_radius',
				]
			],

		];

		$return          = $fields[ $type ];
		$return['sound'] = [ 'enable_sound' ];

		return $return;

	}

	/**
	 * Check if the notification can show by matching trigger location conditions
	 *
	 * @param $notification_id
	 *
	 * @return bool
	 */
	public static function can_show( $notification_id ) {

		$settings = get_post_meta( $notification_id, '_settings', true );

		//don't show on login/ reg pages
		if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
			return false;
		}

		//check display for
		if ( ! empty( $display_for = $settings['display_for'] ) ) {
			if ( 'logged_in' == $display_for && ! is_user_logged_in() ) {
				return false;
			}
			if ( 'logged_out' == $display_for && is_user_logged_in() ) {
				return false;
			}
		}

		//check trigger locations
		if ( empty( $settings['trigger_on'] ) ) {
			return true;
		}

		$trigger_on = $settings['trigger_on'];

		if ( $trigger_on == 'all' ) {
			return true;
		}

		if ( empty( $settings['trigger_locations'] ) ) {
			return true;
		}

		$locations = $settings['trigger_locations'];

		if ( in_array( 'is_custom', $locations ) ) {
			$ids = get_post_meta( $notification_id, '_settings', true )['custom_post_page_ids'];

			$ids = explode( ',', $ids );

			foreach ( $ids as $id ) {
				$id = trim( $id );

				if ( ! is_numeric( $id ) ) {
					continue;
				}

				if ( is_singular( $id ) ) {
					return true;
				}

			}

		}

		$fields = array(
			'is_front_page' => is_front_page(),
			'is_home'       => is_home(),
			'is_singular'   => is_singular(),
			'is_single'     => is_single(),
			'is_page'       => is_page(),
			'is_attachment' => is_attachment(),
			'is_search'     => is_search(),
			'is_404'        => is_404(),
			'is_archive'    => is_archive(),
			'is_category'   => is_category(),
		);

		foreach ( $locations as $location ) {
			if ( empty( $fields[ $location ] ) || ! $fields[ $location ] ) {
				continue;
			}

			return true;

		}

		return false;
	}

	/**
	 * Display frontend Notification
	 *
	 * @throws Exception
	 */
	public static function display_notifications() {
		$args = [
			'post_type'   => 'notification_plus',
			'post_status' => 'publish',
			'numberposts' => - 1,
		];

		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {
			$post_ids = wp_list_pluck( $posts, 'ID' );

			foreach ( $post_ids as $post_id ) {
				if ( ! self::can_show( $post_id ) ) {
					continue;
				}

				Notification_Plus_Notifications::get_view( $post_id );

			}

		}

	}

}


