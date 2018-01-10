<?php
// create custom paypal settings 
add_action('admin_menu', 'form_plugin_create_menu');

function form_plugin_create_menu() {

    //create new top-level menu
    add_options_page('Paypal Settings', 'Paypal Settings', 'administrator', __FILE__, 'form_plugin_settings_page', plugins_url('/images/icon.png', __FILE__));

    //call register settings function
    add_action('admin_init', 'register_form_plugin_settings');
}

function register_form_plugin_settings() {
    //register our settings
    register_setting('paypal-settings-group', 'paypal_status');
    register_setting('paypal-settings-group', 'paypal_username');
    register_setting('paypal-settings-group', 'paypal_password');
    register_setting('paypal-settings-group', 'paypal_signature');
    register_setting('paypal-settings-group', 'paypal_url');
}

function form_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h2>Paypal Settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('paypal-settings-group'); ?>
            <?php do_settings_sections('paypal-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Paypal Status</th>
                    <td>
                        <?php echo fields_status(); ?>
                </tr>
                <tr valign="top">
                    <th scope="row">Paypal UserName</th>
                    <td><input type="text" class="regular-text" name="paypal_username" value="<?php echo esc_attr(get_option('paypal_username')); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Paypal Password</th>
                    <td><input type="text" class="regular-text"name="paypal_password" value="<?php echo esc_attr(get_option('paypal_password')); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Paypal Signature.</th>
                    <td><input type="text" class="regular-text" name="paypal_signature" value="<?php echo esc_attr(get_option('paypal_signature')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Paypal Url.</th>
                    <td><input type="text" class="regular-text"name="paypal_url" value="<?php echo esc_attr(get_option('paypal_url')); ?>" /></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php
}

function fields_status() {
    $value = get_option('paypal_status', '');
    $status = '';
    $inactiveStatus = '';
    if ($value == '1') {
        $status = "checked";
    } else {
        $inactiveStatus = "checked";
    }
    echo'  <input type="radio" name="paypal_status" value="1" ' . $status . '> Active
          <input type="radio" name="paypal_status" value="0" ' . $inactiveStatus . '> Inactive';
}
?>