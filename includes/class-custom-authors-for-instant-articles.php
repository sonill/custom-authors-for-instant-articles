<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.themebeez.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Compare_Products {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Addonify_Compare_Products_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ADDONIFY_COMPARE_PRODUCTS_VERSION' ) ) {
			$this->version = ADDONIFY_COMPARE_PRODUCTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'addonify-compare-products';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Addonify_Compare_Products_Loader. Orchestrates the hooks of the plugin.
	 * - Addonify_Compare_Products_i18n. Defines internationalization functionality.
	 * - Addonify_Compare_Products_Admin. Defines all hooks for the admin area.
	 * - Addonify_Compare_Products_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-addonify-compare-products-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-addonify-compare-products-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-addonify-compare-products-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-addonify-compare-products-public.php';

		$this->loader = new Addonify_Compare_Products_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Addonify_Compare_Products_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Addonify_Compare_Products_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Addonify_Compare_Products_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		// admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_callback', 20 );

		// custom link in plugins.php page in wp-admin
		$this->loader->add_action( 'plugin_action_links', $plugin_admin, 'custom_plugin_link_callback', 10, 2 );

		// show settings page ui 
		$this->loader->add_action("admin_init", $plugin_admin, 'settings_page_ui' );
		
		//show notice if woocommerce is not active
		$this->loader->add_action('admin_init', $plugin_admin, 'addonify_cp_show_woocommerce_not_active_notice_callback' );

		// show admin notices after form submission
		$this->loader->add_action('admin_notices', $plugin_admin, 'addonify_cp_form_submission_notification_callback' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Addonify_Compare_Products_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		// add "Compare" button after add to cart button
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_compare_products_btn_after_add_to_cart_btn_callback', 20 );


		// add "Compare" button before add to cart button
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_compare_products_btn_before_add_to_cart_btn_callback' );


		// add "Compare Button" button aside image
		$this->loader->add_action( 'woocommerce_shop_loop_item_title', $plugin_public, 'show_compare_products_btn_aside_image_callback' );

		
		// image overlay container 
		$this->loader->add_action( 'woocommerce_before_shop_loop_item', $plugin_public, 'addonify_overlay_container_start_callback', 10 );
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'addonify_overlay_container_end_callback', 10 );
		

		// add custom markup into footer
		$this->loader->add_action( 'wp_footer', $plugin_public, 'add_markup_into_footer_callback' );


		// add custom styles into header
		$this->loader->add_action( 'wp_head', $plugin_public, 'generate_custom_styles_callback' );


		// ajax callback

		// get product thumbnails
		$this->loader->add_action( 'wp_ajax_get_products_thumbnails', $plugin_public, 'get_products_thumbnails_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_products_thumbnails', $plugin_public, 'get_products_thumbnails_callback' );

		// search items
		$this->loader->add_action( 'wp_ajax_search_items', $plugin_public, 'search_items_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_search_items', $plugin_public, 'search_items_callback' );

		// get compare contents
		$this->loader->add_action( 'wp_ajax_get_compare_contents', $plugin_public, 'get_compare_contents_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_compare_contents', $plugin_public, 'get_compare_contents_callback' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Addonify_Compare_Products_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
