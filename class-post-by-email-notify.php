<?php
/**
 * Post By Email Notify.
 *
 * @package   Post_By_Email_Notify
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-2.0+
 * @link      http://github.com/barryceelen/post-by-email-notify
 * @copyright 2013 Barry Ceelen
 *
 */

/**
 * Plugin class.
 *
 * @package Post_By_Email_Notify
 * @author  Barry Ceelen <b@rryceelen.com>
 */
class Post_By_Email_Notify {

	/**
	 * Plugin version.
	 *
	 * @since   0.0.1
	 *
	 * @var     string
	 */
	const VERSION = '0.0.1';

	/**
	 * Unique identifier.
	 *
	 * @since    0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'post-by-email-notify';

	/**
	 * Instance of this class.
	 *
	 * @since    0.0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 *
	 * @since     0.0.1
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		// Send notification to post author if a post is created.
		add_action( 'publish_phone', array( $this, 'notify_author' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.0.1
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Notify the author when a post is created via email
	 *
	 * @since  0.0.1
	 *
	 * @param    int    $post_ID    ID of the post
	 * @return   void
	 */
	function notify_author( $post_ID ) {

		// Only send notification if the original sender's mail address matches an existing user
		$userdata = get_user_by( 'email', get_post_meta( $post_ID, 'original_author', true ) );
		if ( empty( $userdata ) ) {
			return;
		}

		$post_title = get_the_title( $post_ID );

		/**
		 * Filter the email recipient(s)
		 *
		 * @param array $recipients Array of email adresses the notification will be sent to
		 * @param int   Post author ID.
		 */
		$recipients = apply_filters(
			'post_by_email_notify_recipients',
			array( $userdata->user_email ),
			$userdata->ID
		);

		$subject = '[' . esc_attr( get_bloginfo( 'name' ) ) . '] ' . _x( 'Post By Email', 'Notification email subject.', $this->plugin_slug ) . ': ' . $post_title;

		/**
		 * Filter the email subject.
		 *
		 * @param string $subject   The subject of the email.
		 * @param int    $author_id Post author ID
		 */
		$subject = apply_filters(
			'post_by_email_notify_subject',
			$subject,
			$userdata->ID
		);

		// TODO Include more details in message like post_status, taxonomies etc.
		$body = '';
		$body[] = sprintf( '"%1$s" was created.', $post_title );
		$body[] = "\r\n\r\n";
		$body[] = get_permalink( $post_ID );

		/**
		 * Filter the message.
		 *
		 * @param array $message The message content.
		 * @param int $post_ID
		 * @param int Post author ID
		 */
		$body = apply_filters(
			'post_by_email_notify_message',
			$body,
			$post_ID,
			$userdata->ID
		);
		$body = join( $body );

		/**
		 * Filter the email headers.
		 */
		$headers = '';
		$headers = apply_filters( $this->plugin_slug . '_headers', $headers );

		foreach ( $recipients as $recipient ) {
			wp_mail( $recipient, $subject, $body, $headers );
		}

	}
}
