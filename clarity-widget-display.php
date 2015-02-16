<?php
/**
 * Plugin Name: Clarity Widget Display
 * Plugin URI: http://matty.co.za/wordpress-plugins/clarity-widget-display/
 * Description: Howdy! Lets display your Clarity.fm blog widget on your website.
 * Version: 1.0.0
 * Author: Matty Cohen
 * Author URI: http://matty.co.za/
 * Requires at least: 4.1.0
 * Tested up to: 4.1.0
 *
 * Text Domain: clarity-widget-display
 * Domain Path: /languages/
 *
 * @package Clarity_Widget_Display
 * @category Core
 * @author Matty
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Clarity_Widget_Display to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Clarity_Widget_Display
 */
function Clarity_Widget_Display() {
	return Clarity_Widget_Display::instance();
} // End Clarity_Widget_Display()

Clarity_Widget_Display();

/**
 * Main Clarity_Widget_Display Class
 *
 * @class Clarity_Widget_Display
 * @version	1.0.0
 * @since 1.0.0
 * @package	Clarity_Widget_Display
 * @author Matty
 */
final class Clarity_Widget_Display {
	/**
	 * Clarity_Widget_Display The single instance of Clarity_Widget_Display.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct () {
		$this->token 			= 'clarity-widget-display';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';

		// Admin - Start
		require_once( 'classes/class-clarity-widget-display-settings.php' );
			$this->settings = Clarity_Widget_Display_Settings::instance();

		if ( is_admin() ) {
			require_once( 'classes/class-clarity-widget-display-admin.php' );
			$this->admin = Clarity_Widget_Display_Admin::instance();
		}
		// Admin - End
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'render_inline_css' ) );

		add_action( 'wp_footer', array( $this, 'display_clarity_blog_widget' ) );
	} // End __construct()

	/**
	 * Render the inline CSS for the widget.
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function render_inline_css () {
		echo '<style type="text/css">' . "\n" . '.clarity-fm-blog-widget { position: fixed; bottom: -10px; right: 2%; } @media only screen and (min-width : 375px) and (max-width : 667px) { body .clarity-fm-blog-widget { position: relative; margin-bottom: 0; text-align: center; } }' . "\n" . '</style>' . "\n";
	} // End render_inline_css()

	/**
	 * Display the stored clarity.fm widget code.
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function display_clarity_blog_widget () {
		$settings = $this->settings->get_settings();
		if ( isset( $settings['clarity-widget-code'] ) && '' != $settings['clarity-widget-code'] ) {
			echo '<div class="clarity-fm-blog-widget">' . $settings['clarity-widget-code'] . '</div>' . "\n";
		}
	} // End display_clarity_blog_widget()

	/**
	 * Main Clarity_Widget_Display Instance
	 *
	 * Ensures only one instance of Clarity_Widget_Display is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Clarity_Widget_Display()
	 * @return Main Clarity_Widget_Display instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'clarity-widget-display', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()
} // End Class
?>
