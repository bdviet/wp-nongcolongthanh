<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       opss.com.vn
 * @since      1.0.0
 *
 * @package    Kiotviet_Sync
 * @subpackage Kiotviet_Sync/includes
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
 * @package    Kiotviet_Sync
 * @subpackage Kiotviet_Sync/includes
 * @author     opss <opss@citigo.com.vn>
 */
class Kiotviet_Sync
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Kiotviet_Sync_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
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
    public function __construct()
    {
        if (defined('KIOTVIET_PLUGIN_VERSION')) {
            $this->version = KIOTVIET_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'kiotviet-sync';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        add_filter('parent_file', 'active_order_menu');
        function active_order_menu($file)
        {
            global $plugin_page, $submenu_file;
            if (array_key_exists('plugin', $_GET) && $_GET['plugin'] == 'kiotviet-sync-order') {
                $plugin_page = 'plugin-kiotviet-sync-order';
                $submenu_file = $plugin_page;
            } elseif (array_key_exists('plugin', $_GET) && $_GET['plugin'] == 'kiotviet-sync-product') {
                $plugin_page = 'plugin-kiotviet-sync-order';
                $submenu_file = $plugin_page;
            }
            return $file;
        }
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Kiotviet_Sync_Loader. Orchestrates the hooks of the plugin.
     * - Kiotviet_Sync_i18n. Defines internationalization functionality.
     * - Kiotviet_Sync_Admin. Defines all hooks for the admin area.
     * - Kiotviet_Sync_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-kiotviet-sync-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-kiotviet-sync-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-kiotviet-sync-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-kiotviet-sync-public.php';


        // Include
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-auth.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-categories.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-products.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-pricebooks.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-branchs.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-config.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-webhook.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-log.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/class-kiotviet-sync-services-order.php';

        //  Load public hook actions
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/public_actions/WebHookAction.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/public_actions/OrderHookAction.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tgm-plugin-activation.php';

        //  Load helper
        require_once plugin_dir_path(dirname(__FILE__)) . 'helpers/KiotvietSyncHelper.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'helpers/KiotvietWcProduct.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'helpers/KiotvietWcCategory.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'helpers/KiotvietWcAttribute.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'helpers/functions.php';

        $this->loader = new Kiotviet_Sync_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Kiotviet_Sync_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Kiotviet_Sync_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */

    private function hook_auth()
    {
        $auth_service = new Kiotviet_Sync_Service_Auth;
        $this->loader->add_action('wp_ajax_kiotviet_sync_service_auth', $auth_service, 'doRequest');
        $this->loader->add_action('wp_ajax_kiotviet_sync_get_token', $auth_service, 'getAccessToken');
        $this->loader->add_action('wp_ajax_kiotviet_sync_save_config_retailer', $auth_service, 'saveConfigRetailer');
    }

    private function hook_config()
    {
        $config_service = new Kiotviet_Sync_Service_Config;
        $this->loader->add_action('wp_ajax_kiotviet_sync_get_config', $config_service, 'getConfig');
        $this->loader->add_action('wp_ajax_kiotviet_sync_save_config', $config_service, 'saveConfig');
        $this->loader->add_action('wp_ajax_kiotviet_sync_remove_config', $config_service, 'removeConfig');
    }

    private function hook_web_hook()
    {
        $webhook_service = new Kiotviet_Sync_Service_Webhook;
        $this->loader->add_action('wp_ajax_kiotviet_sync_remove_webhook', $webhook_service, 'removeWebhook');
        $this->loader->add_action('wp_ajax_kiotviet_sync_register_webhook', $webhook_service, 'registerWebhook');
    }

    private function hook_log()
    {
        $log_service = new Kiotviet_Sync_Service_Log;
        $this->loader->add_action('wp_ajax_kiotviet_sync_remove_log', $log_service, 'removeLog');
    }

    private function hook_order()
    {
        $order_service = new Kiotviet_Sync_Service_Order;
        $this->loader->add_action('wp_ajax_kiotviet_re_sync_order', $order_service, 'reSyncOrder');
    }

    private function hook_product()
    {
        $product_service = new Kiotviet_Sync_Service_Product;
        $this->loader->add_action('wp_ajax_kiotviet_sync_add_product', $product_service, 'add');
        $this->loader->add_action('wp_ajax_kiotviet_sync_update_product', $product_service, 'update');
        $this->loader->add_action('wp_ajax_kiotviet_sync_get_product_map', $product_service, 'getProductMap');
        $this->loader->add_action('wp_ajax_kiotviet_sync_delete_product', $product_service, 'delete');
        $this->loader->add_action('wp_ajax_kiotviet_sync_update_status', $product_service, 'updateStatus');
        $this->loader->add_action('wp_ajax_kiotviet_sync_update_product_price', $product_service, 'updatePrice');
        $this->loader->add_action('wp_ajax_kiotviet_sync_update_product_stock', $product_service, 'updateStock');
    }

    private function hook_category()
    {
        $category_service = new Kiotviet_Sync_Service_Category;
        $this->loader->add_action('wp_ajax_kiotviet_sync_add_category', $category_service, 'add');
        $this->loader->add_action('wp_ajax_kiotviet_sync_delete_sync_category', $category_service, 'deleteSync');
        $this->loader->add_action('wp_ajax_kiotviet_sync_delete_category', $category_service, 'delete');
        $this->loader->add_action('wp_ajax_kiotviet_sync_update_category', $category_service, 'update');
    }

    private function hook_price_book()
    {
        $pricebook_service = new Kiotviet_Sync_Service_PriceBook;
        $this->loader->add_action('wp_ajax_kiotviet_sync_get_pricebook', $pricebook_service, 'get');
        $this->loader->add_action('wp_ajax_kiotviet_sync_save_pricebook', $pricebook_service, 'save');
    }

    private function hook_branch()
    {
        $branch_service = new Kiotviet_Sync_Service_Branch;
        $this->loader->add_action('wp_ajax_kiotviet_sync_get_branch', $branch_service, 'get');
        $this->loader->add_action('wp_ajax_kiotviet_sync_save_branch', $branch_service, 'save');
    }

    private function define_admin_hooks()
    {
        $plugin_admin = new Kiotviet_Sync_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add menu item
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        $this->hook_auth();
        $this->hook_config();
        $this->hook_web_hook();
        $this->hook_log();
        $this->hook_order();
        $this->hook_product();
        $this->hook_category();
        $this->hook_price_book();
        $this->hook_branch();

        // Add hook delete product
        $this->loader->add_action( 'before_delete_post', $this, 'delete_product' );

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        $this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

        //  Require plugin
        $this->loader->add_action('tgmpa_register', $this, 'kiotviet_register_required_plugins');
    }

    public function delete_product($post_id)
    {
        global $wpdb;
        $productDeletes = [];
        $retailer = kiotviet_sync_get_data('retailer', "");
        $wcProduct = wc_get_product($post_id);

        if(!empty($wcProduct)){
            $productSync = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}kiotviet_sync_products WHERE `product_id` = " . $post_id . " AND `retailer` = '" . $retailer . "'", ARRAY_A);
            if($wcProduct->get_type() == "simple"){
                if($productSync){
                    $productDeletes[] = $productSync['id'];
                }
            }

            if($wcProduct->get_type() == 'variable'){
                if($productSync){
                    $productDeletes[] = $productSync['id'];
                    $args = array(
                        'post_parent' => $post_id,
                        'post_type'   => 'product_variation',
                        'post_status' => 'any, trash, auto-draft',
                        'orderby'     => array( 'menu_order' => 'ASC', 'ID' => 'ASC' ),
                        'numberposts' => -1,
                    );
                    $productChildren = get_posts( $args );
                    $productchildrenIds = [];
                    foreach($productChildren as $product){
                        $productchildrenIds[] = $product->ID;
                    }

                    $productChildrenSync = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}kiotviet_sync_products WHERE `product_id` IN (" . implode(",", $productchildrenIds) . ")" . " AND `retailer` = '" . $retailer . "'");
                    foreach($productChildrenSync as $children){
                        $productDeletes[] = $children->id;
                    }
                }
            }

            foreach($productDeletes as $productDelete){
                $delete = [
                    "id" => $productDelete,
                ];
                $wpdb->delete($wpdb->prefix . "kiotviet_sync_products", $delete);
            }
        }
    }

    public function kiotviet_register_required_plugins()
    {
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(
            array(
                'name' => 'WooCommerce',
                'slug' => 'woocommerce',
                'required' => true,
            ),
        );

        $config = array(
            'id' => 'kiotviet',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu' => 'tgmpa-install-plugins', // Menu slug.
            'parent_slug' => 'plugins.php',            // Parent menu slug.
            'capability' => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices' => true,                    // Show admin notices or not.
            'dismissable' => false,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message' => '',                      // Message to output right before the plugins table.
            'strings' => array(
                'page_title' => __('Các plugin yêu cầu', 'kiotviet'),
                'menu_title' => __('Cài đặt plugin', 'kiotviet'),
                'notice_can_activate_required' => _n_noop(
                    'Bạn cần kích hoạt các plugin sau để sử dụng chức năng đồng bộ sản phẩm của KiotViet: %1$s.',
                    'Bạn cần kích hoạt các plugin sau để sử dụng chức năng đồng bộ sản phẩm của KiotViet: %1$s.',
                    'kiotviet'
                ),
                'notice_can_install_required' => _n_noop(
                    'Bạn cần cài đặt các plugin sau để có thể sử dụng chức năng đồng bộ của KiotViet: %1$s.',
                    'Bạn cần cài đặt các plugin sau để có thể sử dụng chức năng đồng bộ của KiotViet: %1$s.',
                    'kiotviet'
                ),
            )
            /*
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'kiotviet' ),
                'menu_title'                      => __( 'Install Plugins', 'kiotviet' ),
                /* translators: %s: plugin name. * /
                'installing'                      => __( 'Installing Plugin: %s', 'kiotviet' ),
                /* translators: %s: plugin name. * /
                'updating'                        => __( 'Updating Plugin: %s', 'kiotviet' ),
                'oops'                            => __( 'Something went wrong with the plugin API.', 'kiotviet' ),
                'notice_can_install_required'     => _n_noop(
                    'This theme requires the following plugin: %1$s.',
                    'This theme requires the following plugins: %1$s.',
                    'kiotviet'
                ),
                'notice_can_install_recommended'  => _n_noop(
                    /* translators: 1: plugin name(s). * /
                    'This theme recommends the following plugin: %1$s.',
                    'This theme recommends the following plugins: %1$s.',
                    'kiotviet'
                ),
                'notice_ask_to_update'            => _n_noop(
                    /* translators: 1: plugin name(s). * /
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'kiotviet'
                ),
                'notice_ask_to_update_maybe'      => _n_noop(
                    /* translators: 1: plugin name(s). * /
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'kiotviet'
                ),
                'notice_can_activate_required'    => _n_noop(
                    /* translators: 1: plugin name(s). * /
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'kiotviet'
                ),
                'notice_can_activate_recommended' => _n_noop(
                    /* translators: 1: plugin name(s). * /
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'kiotviet'
                ),
                'install_link'                    => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'kiotviet'
                ),
                'update_link' 					  => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'kiotviet'
                ),
                'activate_link'                   => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'kiotviet'
                ),
                'return'                          => __( 'Return to Required Plugins Installer', 'kiotviet' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'kiotviet' ),
                'activated_successfully'          => __( 'The following plugin was activated successfully:', 'kiotviet' ),
                /* translators: 1: plugin name. * /
                'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'kiotviet' ),
                /* translators: 1: plugin name. * /
                'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'kiotviet' ),
                /* translators: 1: dashboard link. * /
                'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'kiotviet' ),
                'dismiss'                         => __( 'Dismiss this notice', 'kiotviet' ),
                'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'kiotviet' ),
                'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'kiotviet' ),

                'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
            ),
            */
        );

        tgmpa($plugins, $config);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        //  Register route for webhook
        $publicApi = new WebHookAction();
        $this->loader->add_action('rest_api_init', $publicApi, 'register_api_route');

        $orderHookAction = new OrderHookAction();
        $this->loader->add_action('woocommerce_checkout_order_processed', $orderHookAction, 'order_processed');
        $this->loader->add_action('woocommerce_thankyou', $orderHookAction, 'update_stock_order');
        $plugin_public = new Kiotviet_Sync_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Kiotviet_Sync_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
