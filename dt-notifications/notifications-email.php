<?php

/**
 * Disciple_Tools_Notifications_Email
 *
 * @see     https://github.com/techcrunch/wp-async-task
 * @class   Disciple_Tools_Notifications_Email
 * @version 0.1.0
 * @since   0.1.0
 * @package Disciple_Tools
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Shared DT email function to be used throughout the DT system. It provides asynchonous mail delivery that does not halt page load.
 *
 * Example:
 * dt_send_email(
 *     'recipients@email.com',
 *     'subject line',
 *     'content of the message'
 * );
 *
 * @param $email
 * @param $subject
 * @param $message
 *
 * @return bool|\WP_Error
 */
function dt_send_email( $email, $subject, $message ) {
    // Check permission to send email
    if ( ! Disciple_Tools_Posts::can_access( 'contacts' ) ) {
        return new WP_Error( 'send_email_permission_error', 'You do not have the minimum permissions to send an email' );
    }

    // Sanitize
    $email = sanitize_email( $email );
    $subject = sanitize_text_field( $subject );
    $message = sanitize_textarea_field( $message );

    $subject = dt_get_option( "dt_email_base_subject" ) . ": " . $subject;
    // Send email
    try {
        $send_email = new Disciple_Tools_Notifications_Email();
        $send_email->launch(
            [
                'email'   => $email,
                'subject' => $subject,
                'message' => $message,
            ]
        );
    } catch ( Exception $e ) {
        return false;
    }

    return true;
}

/**
 * Shared DT email function, similar to dt_send_email, but intended for use for
 * emails that are related to a particular contact record.
 *
 * We want to keep the subject line for all updates related to a particular
 * contact the same. For contact 43, the subject line should always be the
 * same:
 *
 * Subject: Update on contact43
 *
 * That way, Gmail.com will group these emails in a single conversation
 * view. Ideally, we would use the `Message-ID` and `References` email
 * headers to make this more robust and more portable in other email
 * clients, but that would make this code more complex, as we probably
 * would have to store the Message-IDs for previous sent emails.
 *
 * This function also appends a link in the email body to the contact record.
 *
 * @param string $email
 * @param int    $post_id
 * @param string $message
 *
 * @return bool|\WP_Error
 */
function dt_send_email_about_post( string $email, int $post_id, string $message ) {
    $post_type = get_post_type( $post_id );
    $contact_url = home_url( '/' ) . $post_type . '/' . $post_id;
    $full_message = $message . "\r\n\r\n--\r\n" . __( 'Click here to view or reply', 'disciple_tools' ) . ": $contact_url";
    $post_label = Disciple_Tools_Posts::get_label_for_post_type( $post_type, true );

    return dt_send_email(
        $email,
        sprintf( esc_html_x( 'Update on %1$s #%2$s', 'ex: Update on Contact #323', 'disciple_tools' ), $post_label, $post_id ),
        $full_message
    );
}

/**
 * Class Disciple_Tools_Notifications_Email
 */
class Disciple_Tools_Notifications_Email extends Disciple_Tools_Async_Task
{
    protected $action = 'email_notification';

    /**
     * Prepare data for the asynchronous request
     *
     * @throws Exception If for any reason the request should not happen.
     *
     * @param array $data An array of data sent to the hook
     *
     * @return array
     */
    protected function prepare_data( $data ) {
        return $data;
    }

    /**
     * Send email
     */
    public function send_email() {
        /**
         * Nonce validation is done through a custom nonce process inside Disciple_Tools_Async_Task
         * to allow for asynchronous processing. This is a valid nonce but is not recognized by the WP standards checker.
         */
        // @phpcs:disable WordPress.CSRF.NonceVerification.NoNonceVerification
        $id = get_user_by( 'email', sanitize_email( $_POST[0]['email'] ) );
        if ( isset( $_POST['action'] ) ) {
//            ( metadata_exists( 'user', $id->ID, 'default_password_nag' ) || metadata_exists( 'user', $id->ID, 'session_tokens' )
            if ( sanitize_text_field( wp_unslash( $_POST['action'] ) ) == 'dt_async_email_notification' &&
                 isset( $_POST['_nonce'] ) && $this->verify_async_nonce( sanitize_key( wp_unslash( $_POST['_nonce'] ) ) ) ) {

                wp_mail( sanitize_email( $_POST[0]['email'] ), sanitize_text_field( wp_unslash( $_POST[0]['subject'] ) ), sanitize_textarea_field( wp_unslash( $_POST[0]['message'] ) ) );

            }
        }
        // phpcs:enable
    }

    /**
     * Run the async task action
     * Used when loading long running process with add_action
     * Not used when launching via the dt_send_email() function.
     */
    protected function run_action() {
        $email = sanitize_email( $_POST[0]['email'] );
        $subject = sanitize_text_field( $_POST[0]['subject'] );
        $message = sanitize_textarea_field( $_POST[0]['message'] );

        do_action( "dt_async_$this->action", $email, $subject, $message );

    }
}

/**
 * This hook function listens for the prepared async process on every page load.
 */
function dt_load_async_email() {
    if ( isset( $_POST['_wp_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wp_nonce'] ) ) ) && isset( $_POST['action'] ) && sanitize_key( wp_unslash( $_POST['action'] ) ) == 'dt_async_email_notification' ) {
        try {
            $send_email = new Disciple_Tools_Notifications_Email();
            $send_email->send_email();
        } catch ( Exception $e ) {
            dt_write_log( __METHOD__ . ': Failed to send email' );
            return new WP_Error( __METHOD__, 'Failed to send email with Async' );
        }
    }
}
add_action( 'init', 'dt_load_async_email' );



