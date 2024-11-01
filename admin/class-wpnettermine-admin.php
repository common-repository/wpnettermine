<?php
/**
 * WPNetTermine
 *
 * @package   WPNetTermine_Admin
 * @author    Marc Nilius <mail@marcnilius.de>
 * @license   GPL-2.0+
 * @link      http://www.marcnilius.de
 * @copyright 2014 Marc Nilius
 */

/**
 * WPNetTermine_Admin class. Admin settings for the plugin.
 *
 * @package WPNetTermine_Admin
 * @author  Marc Nilius <mail@marcnilius.de>
 */
class WPNetTermine_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = WPNetTermine::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// init plugin settings
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
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
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), WPNetTermine::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'WPNetTermine Settings', $this->plugin_slug ),
			__( 'WPNetTermine', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	public function register_plugin_settings() {
		register_setting( 'wpnettermine', 'wpnettermine_settings', array( $this, 'sanitize_settings' ) );
	}

	public function sanitize_settings( $input ) {
		if ( isset( $input['objectid'] ) ) {
			$input['objectid'] = absint( $input['objectid'] );
		}
		if ( isset( $input['iframewidth'] ) ) {
			$input['iframewidth'] = absint( $input['iframewidth'] );
		}
		if ( isset( $input['iframeheight'] ) ) {
			$input['iframeheight'] = absint( $input['iframeheight'] );
		}
		if ( isset( $input['includetype'] ) && $input['includetype'] != 'html' && $input['includetype'] != 'iframe' ) {
			$input['includetype'] = 'html';
		}
		if ( isset( $input['styles'] ) ) {
			$input['styles'] = preg_replace('/</i', '&lt;', $input['styles']);
		}

		return $input;
	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
}
