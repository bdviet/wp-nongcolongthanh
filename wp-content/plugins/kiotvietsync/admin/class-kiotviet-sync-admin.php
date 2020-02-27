<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       opss.com.vn
 * @since      1.0.0
 *
 * @package    Kiotviet_Sync
 * @subpackage Kiotviet_Sync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kiotviet_Sync
 * @subpackage Kiotviet_Sync/admin
 * @author     opss <opss@citigo.com.vn>
 */
class Kiotviet_Sync_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */

    private $currentUrl;
    private $allowPage;
    private $appEnv;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->allowPage = ['toplevel_page_plugin-kiotviet-sync'];
        $this->appEnv = 'production';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {
        if (!in_array($hook, $this->allowPage)) {
            return true;
        }
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Kiotviet_Sync_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Kiotviet_Sync_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/kiotviet-sync-admin.css', array(), $this->version, 'all');
        if ($this->appEnv == 'production') {
            $cssFile = KIOTVIET_PLUGIN_URL . '/frontend/dist/static/css/kiotsync.min.css';
            wp_register_style('kiotvietsync_css', $cssFile, array(), time(), 'all');
            wp_enqueue_style('kiotvietsync_css');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        if (!in_array($hook, $this->allowPage)) {
            return true;
        }

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Kiotviet_Sync_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Kiotviet_Sync_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/kiotviet-sync-admin.js', array('jquery'), $this->version, false);

        if ($this->appEnv == 'dev') {
            wp_register_script('kiotvietsync_js', 'http://localhost:8081/app.js', array('jquery'), $this->version, true);
            wp_enqueue_script('kiotvietsync_js');
        } else if ($this->appEnv == 'production') {
            $jsFile = KIOTVIET_PLUGIN_URL . 'frontend/dist/static/js/kiotsync.min.js';
            wp_register_script('kiotvietsync_js', $jsFile, array('jquery'), time(), true);
            wp_enqueue_script('kiotvietsync_js');
        }

        wp_localize_script(
            'kiotvietsync_js',
            'wp_obj',
            array(  
                'ajaxurl' => admin_url('admin-ajax.php'), 
                'urlProduct' => menu_page_url('plugin-kiotviet-sync-product', 0),
                'fullPath' => $this->currentUrl,
            )
        );
    }

    public function add_plugin_admin_menu()
    {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */

        add_menu_page(
            'KiotViet Sync',
            'KiotViet Sync',
            'manage_options',
            'plugin-kiotviet-sync',
            array($this, 'display_plugin_setup_page'),
            '',
            '10'
        );

        add_submenu_page('plugin-kiotviet-sync', 'Thiết lập thông tin đồng bộ', 'Thiết lập thông tin đồng bộ', 'manage_options', 'plugin-kiotviet-sync', array($this, 'action_kiotvietsync_config'));
        add_submenu_page('plugin-kiotviet-sync', 'Danh sách sản phẩm đồng bộ', 'Danh sách sản phẩm đồng bộ', 'manage_options', 'plugin-kiotviet-sync-product', array($this, 'action_kiotvietsync_product'));
        add_submenu_page('plugin-kiotviet-sync', 'Danh sách đơn đặt hàng', 'Danh sách đơn đặt hàng', 'manage_options', 'plugin-kiotviet-sync-order', array($this, 'action_kiotvietsync_order'));
        add_submenu_page('plugin-kiotviet-sync', 'Lịch sử đồng bộ', 'Lịch sử đồng bộ', 'manage_options', 'plugin-kiotviet-sync-history', array($this, 'action_kiotvietsync_history'));

    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links($links)
    {
        /*
         *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
         */
        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page()
    {
        include_once 'partials/kiotviet-sync-admin-display.php';
    }

    public function action_kiotvietsync_config()
    {
        echo "<script type='text/javascript'>if(!window.location.hash.substr(1)){window.location.href = '" . $this->currentUrl . "#/config';}</script>";
    }

    public function action_kiotvietsync_product()
    {
        include_once KIOTVIET_PLUGIN_PATH . '/admin/list_table/product.php';

        $productList = new Kv_Products_List();

        echo '
        <style>
            .button-danger{
                color: #fff !important;
                background: #ff4e4e !important;
            }
            th#img {
                width: 75px !important;
            }
            th#sync {
                width: 135px;
                text-align: center;
            }

            .stock_status.instock {
                color: #7ad03a;
            }
            .stock_status.outofstock {
                color: #a44
            }

            .stock_status.onbackorder {
                color: #eaa600
            }
            img.woocommerce-placeholder.wp-post-image,
            img.attachment-woocommerce_thumbnail.size-woocommerce_thumbnail {
                width: 75px !important;
                height: auto;
                border: 1px solid #ccc;
                border-radius: 5px;
                overflow: hidden;
            }

        </style>
        <form method="get" action="admin.php">
                    <input type="hidden" name="page" value="plugin-kiotviet-sync-product">
                    <div class="wrap">
                            <h2>Danh sách sản phẩm</h2>';
        $productList->prepare_items();
        $productList->search_box('search', 'search_id');

        $productList->display();

        echo '</div></form>';

        echo "
            <script>
                jQuery(function(){
                    jQuery(document).on('click', '.product_sync', function(){
                        var jQuerythis = jQuery(this);
                        var status = jQuerythis.data('status');
                        var product_id = jQuerythis.data('product-id');
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data:{
                                status:status,
                                product_id: product_id,
                                action: 'kiotviet_sync_update_status',
                            },
                            dataType: 'json',
                            beforeSend: function(){
                                jQuerythis.attr('disabled', 'disabled');
                            },
                            success: function(resp){
                                if(resp.status === 'success'){
                                    if(resp.data){
                                        jQuerythis.closest('td').find('.product_sync').data('status', 0).removeClass('button-danger').addClass('button-primary').val('Đang đồng bộ').removeAttr('disabled');
                                    }else{
                                        jQuerythis.closest('td').find('.product_sync').data('status', 1).removeClass('button-primary').addClass('button-danger').val('Ngừng đồng bộ').removeAttr('disabled');
                                    }
                                }
                            }
                        });
                    });
                });
            </script>
        ";
    }

    public function action_kiotvietsync_history()
    {
        include_once KIOTVIET_PLUGIN_PATH . '/admin/list_table/log.php';

        $logsList = new Kv_Logs_List();

        echo '
            <style>
            /* The Modal (background) */
                .modal {
                    display: none; /* Hidden by default */
                    position: fixed; /* Stay in place */
                    z-index: 1; /* Sit on top */
                    padding-top: 100px; /* Location of the box */
                    left: 0;
                    top: 0;
                    width: 100%; /* Full width */
                    height: 100%; /* Full height */
                    overflow: auto; /* Enable scroll if needed */
                    background-color: rgb(0,0,0); /* Fallback color */
                    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                }
                
                /* Modal Content */
                .modal-content {
                    background-color: #fefefe;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 60%;
                    overflow: scroll;
                    height: 600px;
                }
                
                /* The Close Button */
                .close {
                    color: #aaaaaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                }
                
                .close:hover,
                .close:focus {
                    color: #000;
                    text-decoration: none;
                    cursor: pointer;
                }
                .detail-log {
                    word-break: break-all;
                    margin-bottom: 0px;
                }
            </style>
            <div id="view_log"></div>
            <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <p><pre class="detail-log"></pre></p>
            </div>

            </div>
            <form method="get" action="admin.php">
                <input type="hidden" name="page" value="plugin-kiotviet-sync-order">
                    <div class="wrap">
                            <h2>Lịch sử đồng bộ</h2>';
        $logsList->prepare_items();
        $logsList->display();

        echo '</div></form>';
        echo "
            <script>
                jQuery(function(){
                    jQuery('.view_log').click(function () {
                        jQuery('#myModal').css('display', 'block');
                        jQuery('.detail-log').text(JSON.stringify(jQuery(this).data('value'), null, 4));
                    });
                });

                jQuery(window).click(function(event){
                    var modal = document.getElementById('myModal');
                    if (event.target == modal) {
                        jQuery('#myModal').css('display', 'none');
                    }
                })
            </script>
            ";

    }

    public function action_kiotvietsync_order()
    {
        include_once KIOTVIET_PLUGIN_PATH . '/admin/list_table/order.php';

        $orderList = new Kv_Orders_List();

        echo '
            <style>
            .order-status {
                display: -webkit-inline-box;
                display: inline-flex;
                line-height: 2.5em;
                color: #777;
                background: #e5e5e5;
                border-radius: 4px;
                border-bottom: 1px solid rgba(0,0,0,.05);
                margin: -.25em 0;
                cursor: inherit!important;
                white-space: nowrap;
                max-width: 100%
            }

            .order-status.status-completed {
                background: #c8d7e1;
                color: #2e4453
            }

            .order-status.status-on-hold {
                background: #f8dda7;
                color: #94660c
            }

            .order-status.status-failed {
                background: #eba3a3;
                color: #761919
            }

            .order-status.status-processing {
                background: #c6e1c6;
                color: #5b841b
            }

            .order-status.status-trash {
                background: #eba3a3;
                color: #761919
            }

            .order-status>span {
                margin: 0 1em;
                overflow: hidden;
                text-overflow: ellipsis
            }
        </style>
                <form method="get" action="admin.php">
                    <input type="hidden" name="page" value="plugin-kiotviet-sync-order">
                    <div class="wrap">
                            <h2>Danh sách đơn đặt hàng</h2>';
        $orderList->prepare_items();
        $orderList->search_box('search', 'search_id');

        $orderList->display();

        echo '</div></form>';

        echo "
            <script>
                jQuery(function(){
                    jQuery('.re-sync-order').click(function(){
                        var jQuerythis = jQuery(this);
                        var orderId = jQuerythis.attr('data-id');
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data:{
                                order:orderId,
                                action: 'kiotviet_re_sync_order'
                            },
                            dataType: 'json',
                            beforeSend: function(){
                                jQuerythis.attr('disabled', 'disabled').val('Đang đồng bộ');
                            },
                            success: function(resp){
                                if(resp.status === 'error'){
                                    jQuerythis.closest('td').html(
                                        '<strong style=\"color:red\">Thất bại</strong> <br />' +
                                         '<small>' + resp.msg + '</small>'
                                        );
                                }else{
                                    jQuerythis.closest('td').html('<strong style=\"color:green\">Thành công</strong>');
                                }
                            }
                        });

                    });
                });
            </script>
        ";

    }

}
