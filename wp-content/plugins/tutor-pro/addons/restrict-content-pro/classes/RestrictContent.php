<?php
/**
 * Tutor Course attachments Main Class
 */

namespace TUTOR_RC;

use TUTOR\Tutor_Base;

class RestrictContent extends Tutor_Base {

	public function __construct() {
		parent::__construct();

		add_filter( 'tutor_course_loop_price', array( $this, 'tutor_course_add_to_cart' ) );
		add_filter( 'tutor/course/single/entry-box/free', array( $this, 'tutor_course_add_to_cart' ) );
		add_action( 'tutor_lesson_load_before', array( $this, 'check_subscription' ) );
	}

	public function check_subscription() {
		global $post, $wpdb;
		$monetize_by = get_tutor_option( 'monetize_by' );

		if ( $monetize_by == 'restrict-content-pro' ) {
			$has_membership_access = false;
			$course_id             = tutor_utils()->get_course_id_by_content( get_the_ID() );
			$user_id               = get_current_user_id();

			if ( tutor_utils()->is_enrolled( $course_id ) ) {
				if ( function_exists( 'rcp_user_can_access' ) ) {
					if ( rcp_user_can_access( $user_id, $course_id ) ) {
						$has_membership_access = true;
					}
				}
				if ( ! $has_membership_access ) {
					$wpdb->query( "UPDATE {$wpdb->posts} SET post_status = 'expired' WHERE post_type = 'tutor_enrolled' AND post_parent = {$course_id} AND post_author = {$user_id}" );
				}
			}
		}
	}

	public function tutor_course_add_to_cart( $html ) {
		global $current_user, $wpdb, $post;

		$monetize_by = get_tutor_option( 'monetize_by' );

		if ( $monetize_by !== 'restrict-content-pro' ) {
			return $html;
		}

		if ( function_exists( 'rcp_user_can_access' ) ) {
			$has_membership_access = false;

			if ( rcp_user_can_access( get_current_user_id(), $post->ID ) ) {
				$has_membership_access = true;
			}

			if ( is_user_logged_in() ) {
				if ( $has_membership_access ) {
					return $html;
				} else {
					ob_start();
					?>
					<div class="tutor-restrict-content-message-wrapper tutor-d-flex tutor-justify-content-center tutor- tutor-align-items-center tutor-flex-column">
						<div style="text-align: center;">
							<span class="tutor-color-black">
								<?php echo esc_html( apply_filters( 'tutor_restrict_content_msg', rcp_get_restricted_content_message() ) ); ?>
							</span>
						</div>
						<div class="list-item-button">
							<a class="tutor-btn tutor-btn-md tutor-btn-full tutor-membership-btn tutor-mt-4" href="<?php echo esc_url( rcp_get_registration_page_url() ); ?>">
								<?php echo esc_html( 'Get Membership', 'tutor-pro' ); ?>
							</a>
						</div>
					</div>
					<?php
					return apply_filters( 'tutor_restrict_content_html', ob_get_clean() );
				}
			}
		}

		return $html;
	}

	public function tutor_course_price( $html ) {
		$monetize_by = get_tutor_option( 'monetize_by' );

		if ( $monetize_by === 'restrict-content-pro' ) {
			return '';
		}

		return $html;
	}

}
