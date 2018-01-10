<?php
/*
  Plugin Name: AG Form Data
  Plugin URI: http://cesltd.com
  Description: AG form Data plugin is a generic form plugin that will store data submitted through order form and donation form. When we enable the plugin it creates required table on your wordpress database
  Version: 1.0
  Author: Anil Kumar M
  Author URI:  http://cestltd.com
 */

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Customers_List extends WP_List_Table {

    /** Class constructor */
    public function __construct() {

        parent::__construct(array(
            'singular' => __('Customer', 'sp'), //singular name of the listed records
            'plural' => __('Customers', 'sp'), //plural name of the listed records
            'ajax' => false //does this table support ajax?
        ));
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_customers($per_page = 2, $page_number = 1) {

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}form_data";

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
//    public static function delete_customer($id) {
//        global $wpdb;
//
//        $wpdb->delete(
//                "{$wpdb->prefix}form_data", [ 'ID' => $id], [ '%d']
//        );
//    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}form_data";

        return $wpdb->get_var($sql);
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        _e('No Data avaliable.', 'sp');
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name) {

        switch ($column_name) {
            case 'type':
                $displayText = ucfirst($item[$column_name]);
                break;
            case 'data':
                $displayText = unserialize($item[$column_name]);

                if ($item['type'] === "donation") {
                    $displayText = "<b>Name:</b> " . $displayText['first_name'] . $displayText['last_name'] . "<br/>" .
                            "<b>Phone:</b> " . $displayText['phone'] . "<br/>" .
                            "<b>Email:</b> " . $displayText['email'] . "<br/>" .
                            "<b>Address:</b> " . $displayText['address'] . "<br/>" . $displayText['city'] . "<br/>" . $displayText['state']
                            . "<br/>" . $displayText['postalcode']
                            . "<br/><b>Mode of payment:</b>" . $displayText['payment_mode']
                            . "<br/><b>TransactionId:</b>" . $displayText['transactionId']
                            . "<br/><b>Amount:</b>" . $displayText['amount'];
                } else {
                    $displayText = "<b>Name:</b> " . $displayText['first_name'] . $displayText['last_name'] . "<br/>" .
                            "<b>Phone:</b> " . $displayText['phone'] . "<br/>" .
                            "<b>Email:</b> " . $displayText['email'] . "<br/>" .
                            "<b>Address:</b> " . $displayText['address'] . "<br/>" . $displayText['city'] . "<br/>" . $displayText['state']
                            . "<br/>" . $displayText['postalcode']
                            . "<br/><b>Mode of payment:</b>" . $displayText['paymenttype']
                            . "<br/><b>TransactionId:</b>" . $displayText['transactionId']
                            . "<br/><b>Amount:</b>" . $displayText['amount'] .
                            "<br/><b> Acknowledgement Data</b><br/><b>Name:</b> " . $displayText['ack_name'] . "<br/>" .
                            "<b>Phone:</b> " . $displayText['ack_phone'] . "<br/>" .
                            "<b>Email:</b> " . $displayText['ack_email'] . "<br/>" .
                            "<b>Address:</b> " . $displayText['ack_address'] . "<br/>" . $displayText['ack_city'] . "<br/>" . $displayText['ack-state']
                            . "<br/>" . $displayText['ack_postalcode'];
                }


                break;
            case 'created':
                $displayText = "";
                if ($item['type'] === "brick") {
                    $displayText = unserialize($item['data']);
                    $displayText = "<b>Order Type:</b> " . $displayText['order_type'] . "<br/>" .
                            "<b>Line1:</b> " . $displayText['line1'] . "<br/>" .
                            "<b>Line2:</b> " . $displayText['line2'] . "<br/>" .
                            "<b>Line3:</b> " . $displayText['line3'] . "<br/>" .
                            "<b>Line4:</b> " . $displayText['line4'] . "<br/>" .
                            "<b>Line5:</b> " . $displayText['line5'] . "<br/>" .
                            "<b>Line6: </b>" . $displayText['line6'] . "<br/>"
                    ;
                }
                break;
            case 'status':
                $displayText = $item[$column_name];
                $delete_nonce = wp_create_nonce('sp_delete_customer');
                if ($item[$column_name] == 'pending') {
                    $displayText = sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s"><input type="button" name="mark" value="Mark for complete" /></a>', esc_attr($_REQUEST['page']), 'edit', absint($item['id']), $delete_nonce);
                }

                break;

            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
        return $displayText;
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_name($item) {

        $delete_nonce = wp_create_nonce('sp_delete_customer');

        $title = '<strong>' . $item['name'] . '</strong>';

//        $actions = [
//            'delete' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['ID']), $delete_nonce)
//        ];

        return $title . $this->row_actions($actions);
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {

        $columns = array(
            'type' => __('Type For', ''),
            'data' => __('Form Data'),
            'created' => __('Order Data'),
            'status' => __('Status'),
        );

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'type' => array('type', true)
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'bulk-delete' => 'Delete'
        );
        $actions = array();

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();
        /** Process bulk action */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('customers_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ));

        $this->items = self::get_customers($per_page, $current_page);
    }

    public function process_bulk_action() {
        global $wpdb;
        //To edit the record status
        if ('edit' === $this->current_action()) {
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'sp_delete_customer')) {
                die('Go get a life script kiddies');
            }

            $wpdb->update('wp_form_data', array('status' => 'completed'), array('id' => $_REQUEST["customer"]), array("%s"), array("%d"));
        }

        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'sp_delete_customer')) {
                die('Go get a life script kiddies');
            } else {
                self::delete_customer(absint($_GET['customer']));

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                wp_redirect(esc_url_raw(add_query_arg()));
                exit;
            }
        }

        // If the delete bulk action is triggered
        if (( isset($_POST['action']) && $_POST['action'] == 'bulk-delete' ) || ( isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql($_POST['bulk-delete']);

            // loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                self::delete_customer($id);
            }

            
            wp_redirect(esc_url_raw(add_query_arg()));
            exit;
        }
    }

}

class SP_Plugin {

    static $instance;
    // customer WP_List_Table object
    public $customers_obj;
    public $orders_obj;

    // class constructor
    public function __construct() {
        add_filter('set-screen-option', array( __CLASS__, 'set_screen'), 10, 3);
        add_action('admin_menu', array ($this, 'plugin_menu'));
    }

    public static function set_screen($status, $option, $value) {
        return $value;
    }

    public function plugin_menu() {

        $hook = add_menu_page(
                'Donation Form', 'Form Data', 'manage_options', 'donationform', array( $this, 'plugin_settings_page')
        );

        add_action("load-$hook", array( $this, 'screen_option'));
    }

    /**
     * Plugin settings page
     */
    public function plugin_settings_page() {
        ?>
        <div class="wrap">
            <h2>Form Data</h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
        <?php
        $this->customers_obj->prepare_items();
        $this->customers_obj->display();
        ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
        <?php
    }

    /**
     * Screen options
     */
    public function screen_option() {

        $option = 'per_page';
        $args = array(
            'label' => 'Records',
            'default' => 5,
            'option' => 'customers_per_page'
        );

        add_screen_option($option, $args);

        $this->customers_obj = new Customers_List();
    }

    /** Singleton instance */
    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}

//Initiate the plugin object
add_action('plugins_loaded', function () {
    SP_Plugin::get_instance();
});

//Create the table at the time of activation of plugin
register_activation_hook(__FILE__, 'form_plugin_create_db');

function form_plugin_create_db() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'form_data';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  data text,
  type enum('donation','brick') DEFAULT NULL,
  status enum('pending','completed') DEFAULT 'pending',
  created datetime DEFAULT CURRENT_TIMESTAMP,
 
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

//To handel form actions
include_once 'form_handler.php';

//To Add the paypal APP details
include_once 'paypal_settings.php';


