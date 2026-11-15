<?php
/**
 * Branded HTML emails for tickets and inquiries.
 *
 * @package AXD_Support_Desk
 */

defined( 'ABSPATH' ) || exit;

/**
 * Email sender.
 */
class AXD_SD_Mailer {

	/**
	 * Send an HTML email.
	 *
	 * @param string $to       Recipient.
	 * @param string $subject  Subject.
	 * @param string $heading  Heading inside the email.
	 * @param string $body     HTML body (already escaped).
	 * @param string $reply_to Optional Reply-To address.
	 * @return bool
	 */
	public static function send( $to, $subject, $heading, $body, $reply_to = '' ) {
		$notify  = AXD_SD_Settings::get( 'notify_email' );
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'Reply-To: ' . ( $reply_to ? $reply_to : $notify ),
		);
		$from_name = AXD_SD_Settings::get( 'from_name' );
		$set_name  = static function () use ( $from_name ) {
			return $from_name;
		};
		add_filter( 'wp_mail_from_name', $set_name );
		$sent = wp_mail( $to, $subject, self::wrap( $heading, $body ), $headers );
		remove_filter( 'wp_mail_from_name', $set_name );
		return $sent;
	}

	/**
	 * Email layout.
	 *
	 * @param string $heading Heading.
	 * @param string $body    Body HTML.
	 * @return string
	 */
	private static function wrap( $heading, $body ) {
		$phone = AXD_SD_Settings::get( 'phone' );
		return '<!doctype html><html><body style="margin:0;background:#F5F7FB;font-family:Arial,Helvetica,sans-serif;color:#0B1530">'
			. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F5F7FB;padding:32px 12px"><tr><td align="center">'
			. '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden">'
			. '<tr><td style="background:linear-gradient(110deg,#081A3B,#1450D8);background-color:#0B2A5E;padding:22px 32px;color:#ffffff;font-size:20px;font-weight:bold;letter-spacing:.5px">AXD MEDIA</td></tr>'
			. '<tr><td style="padding:32px">'
			. '<h1 style="margin:0 0 16px;font-size:22px;line-height:1.3;color:#0B1530">' . esc_html( $heading ) . '</h1>'
			. '<div style="font-size:15px;line-height:1.6;color:#2B3552">' . $body . '</div>'
			. '</td></tr>'
			. '<tr><td style="padding:20px 32px;background:#F5F7FB;font-size:12px;line-height:1.5;color:#4A5573">'
			. esc_html__( 'AXD Media Support', 'axd-support-desk' ) . ( $phone ? ' · ' . esc_html( $phone ) : '' ) . '<br>'
			. esc_html__( 'Reply to this email to reach our team. Please keep the case number in the subject line.', 'axd-support-desk' )
			. '</td></tr></table></td></tr></table></body></html>';
	}

	/**
	 * Case number box.
	 *
	 * @param string $case Case number.
	 * @return string
	 */
	private static function case_box( $case ) {
		return '<p style="margin:20px 0;padding:14px 18px;border:1.5px dashed #B9C6DE;border-radius:12px;background:#F5F7FB">'
			. '<span style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#4A5573">' . esc_html__( 'Your case number', 'axd-support-desk' ) . '</span><br>'
			. '<strong style="font-size:22px;color:#0A5BEF">' . esc_html( $case ) . '</strong></p>';
	}

	/**
	 * Format plain text as safe HTML paragraphs.
	 *
	 * @param string $text Text.
	 * @return string
	 */
	public static function text_html( $text ) {
		return wpautop( esc_html( $text ) );
	}

	/**
	 * New ticket: confirmation to client + alert to AXD.
	 *
	 * @param int $id Ticket.
	 */
	public static function ticket_created( $id ) {
		$t        = 'AXD_SD_Tickets';
		$case     = $t::meta( $id, 'case' );
		$name     = $t::meta( $id, 'name' );
		$email    = $t::meta( $id, 'email' );
		$msgs     = $t::messages( $id );
		$desc     = $msgs ? $msgs[0]->body : '';
		$priority = $t::priorities()[ $t::meta( $id, 'priority' ) ] ?? '';

		// Client confirmation.
		$body  = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'axd-support-desk' ), esc_html( $name ) ) . '</p>';
		$body .= '<p>' . esc_html__( 'Thanks for contacting AXD Media support. We’ve received your request and a technician will follow up shortly.', 'axd-support-desk' ) . '</p>';
		$body .= self::case_box( $case );
		$body .= '<p style="color:#4A5573"><strong>' . esc_html__( 'Your request:', 'axd-support-desk' ) . '</strong></p>' . self::text_html( $desc );
		$body .= '<p>' . esc_html__( 'You’ll receive an email each time your case is updated. For urgent issues, please call us.', 'axd-support-desk' ) . '</p>';
		self::send( $email, sprintf( '[%s] %s', $case, __( 'We received your support request', 'axd-support-desk' ) ), __( 'We received your request', 'axd-support-desk' ), $body );

		// Internal alert.
		$rows = array(
			__( 'Case', 'axd-support-desk' )      => $case,
			__( 'Name', 'axd-support-desk' )      => $name,
			__( 'Email', 'axd-support-desk' )     => $email,
			__( 'Phone', 'axd-support-desk' )     => $t::meta( $id, 'phone' ),
			__( 'Client ID', 'axd-support-desk' ) => $t::meta( $id, 'client' ),
			__( 'Priority', 'axd-support-desk' )  => $priority,
		);
		$body  = self::table( $rows ) . self::text_html( $desc );
		$body .= '<p><a href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=edit' ) ) . '" style="display:inline-block;background:#0A5BEF;color:#ffffff;padding:12px 20px;border-radius:999px;text-decoration:none;font-weight:bold">' . esc_html__( 'Open ticket', 'axd-support-desk' ) . '</a></p>';
		self::send( AXD_SD_Settings::get( 'notify_email' ), sprintf( '[%s] %s: %s', $case, $priority, __( 'New support ticket', 'axd-support-desk' ) ), __( 'New support ticket', 'axd-support-desk' ), $body, $email );
	}

	/**
	 * Reply and/or status change email to the client.
	 *
	 * @param int    $id         Ticket.
	 * @param string $reply      Reply text ('' for none).
	 * @param string $new_status Status key if it changed ('' otherwise).
	 * @return bool
	 */
	public static function ticket_updated( $id, $reply, $new_status ) {
		$t      = 'AXD_SD_Tickets';
		$case   = $t::meta( $id, 'case' );
		$name   = $t::meta( $id, 'name' );
		$status = $new_status ? ( $t::statuses()[ $new_status ] ?? $new_status ) : '';

		$body = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'axd-support-desk' ), esc_html( $name ) ) . '</p>';
		if ( $reply ) {
			$body .= '<p>' . esc_html__( 'There’s an update on your support case:', 'axd-support-desk' ) . '</p>';
			$body .= '<div style="border-left:3px solid #6A2EE8;padding:4px 0 4px 16px;margin:16px 0">' . self::text_html( $reply ) . '</div>';
		}
		if ( $status ) {
			$body .= '<p>' . sprintf(
				/* translators: 1: case number, 2: status */
				esc_html__( 'Your case %1$s is now: %2$s', 'axd-support-desk' ),
				'<strong>' . esc_html( $case ) . '</strong>',
				'<strong style="color:#0A5BEF">' . esc_html( $status ) . '</strong>'
			) . '</p>';
		}
		if ( 'waiting' === $new_status ) {
			$body .= '<p>' . esc_html__( 'We need a little more information from you. Simply reply to this email.', 'axd-support-desk' ) . '</p>';
		}
		$subject = $status
			? sprintf( '[%s] %s: %s', $case, __( 'Status update', 'axd-support-desk' ), $status )
			: sprintf( '[%s] %s', $case, __( 'New reply from AXD Media', 'axd-support-desk' ) );
		return self::send( $t::meta( $id, 'email' ), $subject, __( 'Your case has been updated', 'axd-support-desk' ), $body );
	}

	/**
	 * Contact inquiry alert to AXD.
	 *
	 * @param int   $id   Inquiry.
	 * @param array $data Form data.
	 */
	public static function inquiry_created( $id, $data ) {
		$rows = array(
			__( 'Name', 'axd-support-desk' )         => $data['name'],
			__( 'Organization', 'axd-support-desk' ) => $data['organization'],
			__( 'Email', 'axd-support-desk' )        => $data['email'],
			__( 'Phone', 'axd-support-desk' )        => $data['phone'],
			__( 'Topic', 'axd-support-desk' )        => $data['topic'],
			__( 'Consultation', 'axd-support-desk' ) => $data['consult'] ? __( 'Yes, wants to schedule', 'axd-support-desk' ) : __( 'No', 'axd-support-desk' ),
		);
		$body  = self::table( $rows ) . self::text_html( $data['message'] );
		$body .= '<p><a href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=edit' ) ) . '">' . esc_html__( 'View in WordPress', 'axd-support-desk' ) . '</a></p>';
		self::send( AXD_SD_Settings::get( 'notify_email' ), sprintf( '%s: %s', __( 'New website inquiry', 'axd-support-desk' ), $data['name'] ), __( 'New website inquiry', 'axd-support-desk' ), $body, $data['email'] );

		$body  = '<p>' . sprintf( /* translators: %s: name */ esc_html__( 'Hi %s,', 'axd-support-desk' ), esc_html( $data['name'] ) ) . '</p>';
		$body .= '<p>' . esc_html__( 'Thank you for reaching out to AXD Media. We’ve received your message and will follow up by email or phone shortly.', 'axd-support-desk' ) . '</p>';
		self::send( $data['email'], __( 'Thanks for contacting AXD Media', 'axd-support-desk' ), __( 'We received your message', 'axd-support-desk' ), $body );
	}

	/**
	 * Simple label/value table.
	 *
	 * @param array $rows Label => value.
	 * @return string
	 */
	private static function table( $rows ) {
		$html = '<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;margin:0 0 16px;font-size:14px">';
		foreach ( $rows as $label => $value ) {
			if ( '' === (string) $value ) {
				continue;
			}
			$html .= '<tr><td style="padding:6px 12px 6px 0;color:#4A5573;width:120px;vertical-align:top">' . esc_html( $label ) . '</td><td style="padding:6px 0;font-weight:bold">' . esc_html( $value ) . '</td></tr>';
		}
		return $html . '</table>';
	}
}
