<?php
/**
 * Front-end forms: [axd_support_form] and [axd_contact_form].
 *
 * Submissions go to admin-post.php, are validated, protected against spam
 * (nonce, honeypot, minimum fill time, per-IP rate limit), stored, emailed,
 * and then redirected back to the page with a one-time success token.
 *
 * @package AXD_Support_Desk
 */

defined( 'ABSPATH' ) || exit;

/**
 * Forms.
 */
class AXD_SD_Forms {

	const MIN_SECONDS = 3;
	const RATE_LIMIT  = 6; // Submissions per IP per window.
	const RATE_WINDOW = 600; // Seconds.

	/**
	 * Hooks.
	 */
	public static function init() {
		add_shortcode( 'axd_support_form', array( __CLASS__, 'support_form' ) );
		add_shortcode( 'axd_contact_form', array( __CLASS__, 'contact_form' ) );
		foreach ( array( 'axd_sd_support', 'axd_sd_contact' ) as $action ) {
			add_action( 'admin_post_nopriv_' . $action, array( __CLASS__, 'handle' ) );
			add_action( 'admin_post_' . $action, array( __CLASS__, 'handle' ) );
		}
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	/**
	 * Register the form stylesheet (enqueued only where a form renders).
	 */
	public static function register_assets() {
		wp_register_style( 'axd-sd-forms', AXD_SD_URL . 'assets/forms.css', array(), AXD_SD_VERSION );
	}

	/* ------------------------------------------------------------------ */

	/**
	 * Support case form.
	 *
	 * @return string
	 */
	public static function support_form() {
		wp_enqueue_style( 'axd-sd-forms' );
		$done = self::success( 'support' );
		if ( $done ) {
			$phone = AXD_SD_Settings::get( 'phone' );
			return '<div class="axd-sd-done" role="status" tabindex="-1">'
				. '<span class="axd-sd-ok" aria-hidden="true"></span>'
				. '<h3>' . esc_html__( 'Your support case has been submitted.', 'axd-support-desk' ) . '</h3>'
				. '<div class="axd-sd-case"><span>' . esc_html__( 'Your case number', 'axd-support-desk' ) . '</span><strong>' . esc_html( $done ) . '</strong></div>'
				. '<p>' . esc_html__( 'We’ve emailed you a confirmation with this case number. You’ll get an email update each time your case is updated.', 'axd-support-desk' )
				. ( $phone ? ' ' . sprintf( /* translators: %s: phone */ esc_html__( 'For urgent issues, call %s.', 'axd-support-desk' ), esc_html( $phone ) ) : '' ) . '</p>'
				. '<p><a class="axd-sd-link" href="' . esc_url( remove_query_arg( 'axd_done' ) ) . '#case">' . esc_html__( 'Open another case', 'axd-support-desk' ) . '</a></p>'
				. '</div>';
		}

		$err     = self::error_notice();
		$clients = AXD_SD_Settings::client_ids();

		ob_start();
		echo $err; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in error_notice().
		?>
		<form class="axd-sd-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php self::hidden_fields( 'axd_sd_support' ); ?>
			<p class="axd-sd-req-note"><?php echo wp_kses_post( __( 'Fields marked <span class="axd-sd-req">*</span> are required.', 'axd-support-desk' ) ); ?></p>
			<div class="axd-sd-grid">
				<p class="axd-sd-field">
					<label for="axd-s-name"><?php esc_html_e( 'Full name', 'axd-support-desk' ); ?> <span class="axd-sd-req">*</span></label>
					<input id="axd-s-name" name="name" type="text" autocomplete="name" required maxlength="120">
				</p>
				<p class="axd-sd-field">
					<label for="axd-s-client"><?php esc_html_e( 'Client ID', 'axd-support-desk' ); ?></label>
					<?php if ( $clients ) : ?>
						<select id="axd-s-client" name="client">
							<option value=""><?php esc_html_e( 'Select your Client ID', 'axd-support-desk' ); ?></option>
							<?php foreach ( array_keys( $clients ) as $code ) : ?>
								<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $code ); ?></option>
							<?php endforeach; ?>
							<option value="UNKNOWN"><?php esc_html_e( 'I don’t know my Client ID', 'axd-support-desk' ); ?></option>
						</select>
					<?php else : ?>
						<input id="axd-s-client" name="client" type="text" maxlength="40" placeholder="<?php esc_attr_e( 'If you have one', 'axd-support-desk' ); ?>">
					<?php endif; ?>
				</p>
				<p class="axd-sd-field">
					<label for="axd-s-email"><?php esc_html_e( 'Email address', 'axd-support-desk' ); ?> <span class="axd-sd-req">*</span></label>
					<input id="axd-s-email" name="email" type="email" autocomplete="email" required maxlength="190">
				</p>
				<p class="axd-sd-field">
					<label for="axd-s-phone"><?php esc_html_e( 'Phone number', 'axd-support-desk' ); ?></label>
					<input id="axd-s-phone" name="phone" type="tel" autocomplete="tel" maxlength="40">
				</p>
				<fieldset class="axd-sd-field axd-sd-full">
					<legend><?php esc_html_e( 'Priority', 'axd-support-desk' ); ?></legend>
					<div class="axd-sd-segs">
						<?php foreach ( AXD_SD_Tickets::priorities() as $key => $label ) : ?>
							<label class="axd-sd-seg is-<?php echo esc_attr( $key ); ?>">
								<input type="radio" name="priority" value="<?php echo esc_attr( $key ); ?>" <?php checked( 'normal', $key ); ?>>
								<span><?php echo esc_html( $label ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<p class="axd-sd-field axd-sd-full">
					<label for="axd-s-desc"><?php esc_html_e( 'Describe your request', 'axd-support-desk' ); ?> <span class="axd-sd-req">*</span></label>
					<textarea id="axd-s-desc" name="description" required maxlength="5000" placeholder="<?php esc_attr_e( 'What’s happening, what you were doing, and any error messages you see.', 'axd-support-desk' ); ?>"></textarea>
				</p>
				<div class="axd-sd-foot axd-sd-full">
					<p class="axd-sd-note"><?php esc_html_e( 'Your case number will be shown and emailed to you after you submit.', 'axd-support-desk' ); ?></p>
					<button type="submit" class="axd-sd-btn"><?php esc_html_e( 'Submit Support Case', 'axd-support-desk' ); ?></button>
				</div>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Contact / consultation form.
	 *
	 * @return string
	 */
	public static function contact_form() {
		wp_enqueue_style( 'axd-sd-forms' );
		if ( self::success( 'contact' ) ) {
			return '<div class="axd-sd-done" role="status" tabindex="-1">'
				. '<span class="axd-sd-ok" aria-hidden="true"></span>'
				. '<h3>' . esc_html__( 'Thank you. Your message is on its way.', 'axd-support-desk' ) . '</h3>'
				. '<p>' . esc_html__( 'We’ll review your message and follow up by email or phone.', 'axd-support-desk' ) . '</p>'
				. '<p><a class="axd-sd-link" href="' . esc_url( remove_query_arg( 'axd_done' ) ) . '#contact-form">' . esc_html__( 'Send another message', 'axd-support-desk' ) . '</a></p>'
				. '</div>';
		}

		$topics = self::topics();
		ob_start();
		echo self::error_notice(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in error_notice().
		?>
		<form class="axd-sd-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php self::hidden_fields( 'axd_sd_contact' ); ?>
			<p class="axd-sd-req-note"><?php echo wp_kses_post( __( 'Fields marked <span class="axd-sd-req">*</span> are required.', 'axd-support-desk' ) ); ?></p>
			<div class="axd-sd-grid">
				<p class="axd-sd-field">
					<label for="axd-c-name"><?php esc_html_e( 'Full name', 'axd-support-desk' ); ?> <span class="axd-sd-req">*</span></label>
					<input id="axd-c-name" name="name" type="text" autocomplete="name" required maxlength="120">
				</p>
				<p class="axd-sd-field">
					<label for="axd-c-org"><?php esc_html_e( 'Organization', 'axd-support-desk' ); ?></label>
					<input id="axd-c-org" name="organization" type="text" autocomplete="organization" maxlength="160">
				</p>
				<p class="axd-sd-field">
					<label for="axd-c-email"><?php esc_html_e( 'Email address', 'axd-support-desk' ); ?> <span class="axd-sd-req">*</span></label>
					<input id="axd-c-email" name="email" type="email" autocomplete="email" required maxlength="190">
				</p>
				<p class="axd-sd-field">
					<label for="axd-c-phone"><?php esc_html_e( 'Phone number', 'axd-support-desk' ); ?></label>
					<input id="axd-c-phone" name="phone" type="tel" autocomplete="tel" maxlength="40">
				</p>
				<p class="axd-sd-field axd-sd-full">
					<label for="axd-c-topic"><?php esc_html_e( 'How can we help?', 'axd-support-desk' ); ?></label>
					<select id="axd-c-topic" name="topic">
						<option value=""><?php esc_html_e( 'Select a topic', 'axd-support-desk' ); ?></option>
						<?php foreach ( $topics as $topic ) : ?>
							<option><?php echo esc_html( $topic ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<p class="axd-sd-field axd-sd-full">
					<label for="axd-c-msg"><?php esc_html_e( 'Message', 'axd-support-desk' ); ?> <span class="axd-sd-req">*</span></label>
					<textarea id="axd-c-msg" name="message" required maxlength="5000" placeholder="<?php esc_attr_e( 'Tell us about your project, challenge, or idea.', 'axd-support-desk' ); ?>"></textarea>
				</p>
				<label class="axd-sd-check axd-sd-full"><input type="checkbox" name="consult" value="1"> <?php esc_html_e( 'I’d like to schedule a consultation.', 'axd-support-desk' ); ?></label>
				<div class="axd-sd-foot axd-sd-full">
					<p class="axd-sd-note"><?php echo wp_kses_post( sprintf( /* translators: %s: privacy URL */ __( 'We’ll only use your information to respond to your inquiry. <a href="%s">Privacy Policy</a>', 'axd-support-desk' ), esc_url( home_url( '/privacy-policy/' ) ) ) ); ?></p>
					<button type="submit" class="axd-sd-btn"><?php esc_html_e( 'Send Message', 'axd-support-desk' ); ?></button>
				</div>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Contact topics.
	 *
	 * @return array
	 */
	private static function topics() {
		return array(
			__( 'Data Analytics & Dashboards', 'axd-support-desk' ),
			__( 'Custom Applications & AI', 'axd-support-desk' ),
			__( 'Website Development', 'axd-support-desk' ),
			__( 'IT Support & Consulting', 'axd-support-desk' ),
			__( 'Nonprofit, healthcare, or legal technology', 'axd-support-desk' ),
			__( 'Something else', 'axd-support-desk' ),
		);
	}

	/**
	 * Hidden security fields.
	 *
	 * @param string $action Form action.
	 */
	private static function hidden_fields( $action ) {
		?>
		<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
		<input type="hidden" name="axd_return" value="<?php echo esc_url( get_permalink() ); ?>">
		<input type="hidden" name="axd_ts" value="<?php echo esc_attr( (string) time() ); ?>">
		<?php wp_nonce_field( $action, 'axd_nonce' ); ?>
		<p class="axd-sd-hp" aria-hidden="true">
			<label for="axd-website-<?php echo esc_attr( $action ); ?>"><?php esc_html_e( 'Leave this field empty', 'axd-support-desk' ); ?></label>
			<input id="axd-website-<?php echo esc_attr( $action ); ?>" type="text" name="axd_website" value="" tabindex="-1" autocomplete="off">
		</p>
		<?php
	}

	/* ------------------------------------------------------------------ */

	/**
	 * Handle a submission.
	 */
	public static function handle() {
		$action = isset( $_POST['action'] ) ? sanitize_key( wp_unslash( $_POST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified below.
		$return = isset( $_POST['axd_return'] ) ? esc_url_raw( wp_unslash( $_POST['axd_return'] ) ) : home_url( '/' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$return = wp_validate_redirect( $return, home_url( '/' ) );
		$anchor = 'axd_sd_support' === $action ? '#case' : '#contact-form';

		$fail = static function ( $code ) use ( $return, $anchor ) {
			wp_safe_redirect( add_query_arg( 'axd_err', $code, $return ) . $anchor );
			exit;
		};

		if ( ! isset( $_POST['axd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['axd_nonce'] ) ), $action ) ) {
			$fail( 'expired' );
		}
		// Honeypot and minimum fill time: silently treat bots as successful.
		$ts = isset( $_POST['axd_ts'] ) ? (int) $_POST['axd_ts'] : 0;
		if ( ! empty( $_POST['axd_website'] ) || ( time() - $ts ) < self::MIN_SECONDS ) {
			wp_safe_redirect( $return . $anchor );
			exit;
		}
		if ( ! self::rate_ok() ) {
			$fail( 'rate' );
		}

		$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		if ( '' === $name || ! is_email( $email ) ) {
			$fail( 'fields' );
		}

		if ( 'axd_sd_support' === $action ) {
			$desc     = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';
			$priority = isset( $_POST['priority'] ) ? sanitize_key( wp_unslash( $_POST['priority'] ) ) : 'normal';
			$client   = isset( $_POST['client'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_POST['client'] ) ) ) : '';
			if ( '' === trim( $desc ) ) {
				$fail( 'fields' );
			}
			if ( ! isset( AXD_SD_Tickets::priorities()[ $priority ] ) ) {
				$priority = 'normal';
			}
			$known = AXD_SD_Settings::client_ids();
			if ( $known && '' !== $client && 'UNKNOWN' !== $client && ! isset( $known[ $client ] ) ) {
				$client = '';
			}
			$id = AXD_SD_Tickets::create(
				array(
					'name'        => $name,
					'email'       => $email,
					'phone'       => $phone,
					'client'      => 'UNKNOWN' === $client ? __( 'Unknown', 'axd-support-desk' ) : substr( $client, 0, 40 ),
					'priority'    => $priority,
					'description' => $desc,
				)
			);
			if ( is_wp_error( $id ) ) {
				$fail( 'server' );
			}
			AXD_SD_Mailer::ticket_created( $id );
			$token = self::token( 'support', AXD_SD_Tickets::meta( $id, 'case' ) );
		} else {
			$data = array(
				'name'         => $name,
				'email'        => $email,
				'phone'        => $phone,
				'organization' => isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : '',
				'topic'        => isset( $_POST['topic'] ) ? sanitize_text_field( wp_unslash( $_POST['topic'] ) ) : '',
				'message'      => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
				'consult'      => ! empty( $_POST['consult'] ),
			);
			if ( '' === trim( $data['message'] ) ) {
				$fail( 'fields' );
			}
			if ( ! in_array( $data['topic'], self::topics(), true ) ) {
				$data['topic'] = '';
			}
			$id = AXD_SD_Tickets::create_inquiry( $data );
			if ( is_wp_error( $id ) ) {
				$fail( 'server' );
			}
			AXD_SD_Mailer::inquiry_created( $id, $data );
			$token = self::token( 'contact', '1' );
		}

		wp_safe_redirect( add_query_arg( 'axd_done', $token, remove_query_arg( 'axd_err', $return ) ) . $anchor );
		exit;
	}

	/**
	 * Per-IP rate limit (IP is hashed, never stored in plain text).
	 *
	 * @return bool
	 */
	private static function rate_ok() {
		$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$key   = 'axd_sd_rl_' . substr( wp_hash( $ip ), 0, 20 );
		$count = (int) get_transient( $key );
		if ( $count >= self::RATE_LIMIT ) {
			return false;
		}
		set_transient( $key, $count + 1, self::RATE_WINDOW );
		return true;
	}

	/**
	 * One-time success token (so the case number isn't guessable from the URL).
	 *
	 * @param string $form  support|contact.
	 * @param string $value Value to show.
	 * @return string
	 */
	private static function token( $form, $value ) {
		$token = wp_generate_password( 20, false );
		set_transient( 'axd_sd_done_' . $token, array( $form, $value ), 30 * MINUTE_IN_SECONDS );
		return $token;
	}

	/**
	 * Read a success token for this form.
	 *
	 * @param string $form support|contact.
	 * @return string Value, or '' when none.
	 */
	private static function success( $form ) {
		if ( empty( $_GET['axd_done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display.
			return '';
		}
		$token = preg_replace( '/[^A-Za-z0-9]/', '', wp_unslash( $_GET['axd_done'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$data  = get_transient( 'axd_sd_done_' . $token );
		return ( is_array( $data ) && $data[0] === $form ) ? (string) $data[1] : '';
	}

	/**
	 * Error message for ?axd_err=.
	 *
	 * @return string
	 */
	private static function error_notice() {
		$code = isset( $_GET['axd_err'] ) ? sanitize_key( wp_unslash( $_GET['axd_err'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$msgs = array(
			'fields'  => __( 'Please fill in the required fields with a valid email address.', 'axd-support-desk' ),
			'expired' => __( 'Your session expired. Please submit the form again.', 'axd-support-desk' ),
			'rate'    => __( 'Too many submissions. Please wait a few minutes or email us directly.', 'axd-support-desk' ),
			'server'  => __( 'Something went wrong on our side. Please email us directly.', 'axd-support-desk' ),
		);
		if ( ! isset( $msgs[ $code ] ) ) {
			return '';
		}
		return '<p class="axd-sd-error" role="alert">' . esc_html( $msgs[ $code ] ) . '</p>';
	}
}
