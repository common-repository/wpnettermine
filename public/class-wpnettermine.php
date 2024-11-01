<?php
/**
 * WPNetTermine
 *
 * @package   WPNetTermine
 * @author    Marc Nilius <mail@marcnilius.de>
 * @license   GPL-2.0+
 * @link      http://www.marcnilius.de
 * @copyright 2014 Marc Nilius
 */

/**
 * WPNetTermine class. The shortcode implementation.
 *
 * @package WPNetTermine
 * @author  Marc Nilius <mail@marcnilius.de>
 */
class WPNetTermine {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @var     string
	 */
	const VERSION = '0.0.3';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wpnettermine';

	/**
	 * Instance of this class.
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// add shortcode
		add_shortcode( 'nettermine', array( $this, 'shortcode_nettermine' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
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
	 * Fired when the plugin is activated.
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 */
	private static function single_activate() {
		$settings = array(
			'objectid'		=> 0,
			'includetype'	=> 'html',
			'iframewidth'	=> 600,
			'iframeheight'	=> 200,
			'styles'		=> '',
		);
		add_option( 'wpnettermine_settings',  $settings);
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 */
	private static function single_deactivate() {
		delete_option('wpnettermine_settings');
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	private function get_nettermine_content() {
		$objectid = self::get_nettermine_option('objectid');
		$url = "http://www.nettermine.de/nettermine.asp?CheckBrowser=0&hausid=" . $objectid;

		$parameter = array();
		foreach ( $_POST as $key => $value ) {
			$parameter[mb_convert_encoding($key, "ISO-8859-15", "UTF-8")] = mb_convert_encoding($value, "ISO-8859-15", "UTF-8");
		}

		$data = http_build_query( $parameter );

		$headers = array(
			"Content-type: application/x-www-form-urlencoded",
			sprintf( 'Content-Length: %d', strlen( $data ) )
		);

		$options = array(
			'http' => array (
				'header' 	=> implode("\r\n", $headers),
				'method'	=> 'POST',
				'content'	=> $data,
			),
		);

		// check, if we have another URL in Post vars
		if ( isset ( $_POST['originalurl'] ) ) {
			$url = "http://www.nettermine.de/" . $_POST['originalurl'];
		}

		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		// error handling
		if ( $result == FALSE ) {
			return '<p class="error">Error loading NetTermine content</p>.';
		}

		// convert encoding
		$result = mb_convert_encoding($result, "UTF-8", "ISO-8859-15");

		// we only need the html within the body element
		preg_match( '/<body>(.*)<\/body>/si', $result, $matches );
		if ( count ($matches) > 1 ) {
			$result =  $matches[1];
		}
		else {
			$result = "";
		}

		// change form action
		preg_match( '/action="([^"]*)"/i', $result, $matches );
		if ( count ($matches) > 1 ) {
			$originalurl = $matches[1];
		}
		else {
			$originalurl = "";
		}

		$result = preg_replace( '/action="[^"]*"/i', 'action="' . $_SERVER['REQUEST_URI'] . '"', $result );

		// save old action to input hidden field
		$hiddenfield = '<input type="hidden" name="originalurl" value="' . $originalurl . '">';
		$result = preg_replace( '/<\/form>/', $hiddenfield . '</form>', $result );

		// set form id, for easier css access
		$result = preg_replace( '/<form /', '<form id="nettermine" ', $result );

		// get individual styles from plugin settings, if available
		$styles = self::get_nettermine_option('styles');

		if ( $styles ) {
			wp_add_inline_style( $this->plugin_slug . '-plugin-style', $styles );
		}

		return $result;
	}

	private function get_nettermine_iframe() {
		$objectid = self::get_nettermine_option('objectid');
		$iframewidth = self::get_nettermine_option('iframewidth');
		$iframeheight = self::get_nettermine_option('iframeheight');
		$url = "http://www.nettermine.de/nettermine.asp?CheckBrowser=0&hausid=" . $objectid;

		return '<iframe src="' . $url . '" width="' . $iframewidth . '" height="' . $iframeheight . '"></iframe>';
	}

	private static function get_nettermine_option( $setting ) {
        $settings = (array) get_option( 'wpnettermine_settings' );

        if ( isset ( $settings[$setting] ) ) {
        	return $settings[$setting];
        }
        else return false;
	}

	/**
	 * Paste shortcode content
	 */
	public function shortcode_nettermine() {
		$includetype = $this->get_nettermine_option('includetype');

		// add NetTermine as HTML
		if ( $includetype == 'html' ) {
			// add nettermine js & css files
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/nettermine.js', __FILE__ ), array( 'jquery' ), self::VERSION );
			wp_enqueue_style( $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/nettermine.css', __FILE__ ), false);

			// get HTML content and return
			return $this->get_nettermine_content();
		}
		// add nettermine as iframe
		else if ( $includetype == 'iframe' ) {
			return $this->get_nettermine_iframe();
		}
	}
}
