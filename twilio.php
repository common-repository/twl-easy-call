<?php
/*
  Plugin Name: Easy Call With Twilio
  Description: Click-to-call converts your website's users into engaged customers by creating an easy way for your customers to contact your sales and support teams right on your website. Your users can input a phone number and receive a phone call connecting to any destination phone number you like.
  Version: 1.1.0
  Author: zonnix
  Author URI: http://zonnix.net
  License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
define('TWILIO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TWILIO_PLUGIN_PATH', plugin_dir_path(__FILE__));

if (!class_exists('Twilio')) {

    class Twilio {

        public function __construct() {
            //create settings page
            add_action('admin_menu', array($this, 'twilio_settings_page'));
            //short codes
            require_once ('includes/shortcodes.php');
            //ajax functions
            require_once ('includes/ajax.php');
            //front end script
            add_action('wp_enqueue_scripts', array($this, 'twilio_script'));
            //admin style
            add_action('admin_enqueue_scripts', array($this, 'twilio_settings_style'));
            //user phone confirm
            //add_action('wp_footer', array($this, 'user_phone_confirm_func'));

            require_once ('lib/Twilio/Twilio.php');
        }


        /**
         * admin style
         */
        public function twilio_settings_style() {
            wp_enqueue_style('twilio-settings-style', TWILIO_PLUGIN_URL . 'css/admin.css');
        }

        /**
         * front end script
         */
        public function twilio_script() {
            ?>
            <script>
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
            </script>
            <?php
            wp_enqueue_style('twilio-css', TWILIO_PLUGIN_URL . 'css/frontend.css');
            wp_enqueue_style('twilio-phone-css', TWILIO_PLUGIN_URL . 'css/intlTelInput.css');
            wp_enqueue_script('twilio-phone', TWILIO_PLUGIN_URL . 'js/intlTelInput.min.js', array('jquery'));
            wp_enqueue_script('twilio-util', TWILIO_PLUGIN_URL . 'js/utils.js');
            wp_enqueue_script('twilio-script', TWILIO_PLUGIN_URL . 'js/twilio-script.js');
        }

        /**
         * settings page
         */
        public function twilio_settings_page() {
            add_menu_page('Easy Call With Twilio', 'Easy Call With Twilio', 'manage_options', 'twilio', array($this, 'twilio_settings_page_func'), 'dashicons-phone');
        }

        public function twilio_settings_page_func() {
            ?>
            <div class="twilio_settings" style="margin-top: 20px;">
                <img src="<?php echo TWILIO_PLUGIN_URL; ?>img/twilio.png"/>
                <br>
                <div style="background-color: #fff;padding: 15px;">
            <div >
    <h3><b>NOW!</b> Twilio Easy Call Pro version is available!</h3>
    <p class="about-description">Enjoy with a lot of features to control your website. Buttons customisation, Add Welcome messages, recorded messages, integration with Woo-Commerce, logs, black list and more ... </p>
    <div class="welcome-panel-column-container">
    <div class="welcome-panel-column">
        <a class="button button-primary button-hero load-customize hide-if-no-customize" href="http://codecanyon.net/item/twilio-easy-call-pro/13892822">Get Your Premium Version</a>
        <br><br>
    </div>
    </div>
    </div>
        </div>
                <br>
                <table class="update-nag">
                    <tbody>
                        <tr>
                            <td><b>Short Code: </b> [wpc2c label='Click To Call' number='+01234567892']</td>
                        </tr>
                    </tbody>
                </table>

                <hr/>
                <h3>Settings</h3>
                <?php
                //save settings
                if (isset($_POST['setting_twilio_number'])) {
                    update_option('tw_setting_twilio_number', sanitize_text_field($_POST['setting_twilio_number']));
                    update_option('tw_setting_twilio_account_sid', sanitize_text_field($_POST['setting_twilio_account_sid']));
                    update_option('tw_setting_twilio_auth_token', sanitize_text_field($_POST['setting_twilio_auth_token']));
                    ?>
                    <div class="updated below-h2" id="message"><p>Settings updated successfully.</p></div>
                    <?php
                }
                ?>
                <form method="post" class="form-table">
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Twilio Account SID</strong></td>
                                <td>
                                    <input value="<?php echo esc_attr(get_option('tw_setting_twilio_account_sid')); ?>" name="setting_twilio_account_sid" type="text"/>
                                    <p class="description">Your Account SID you can get it from: <a target="_blank" href='https://www.twilio.com/user/account/'>https://www.twilio.com/user/account/</a></p>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Twilio Auth Token</strong></td>
                                <td>
                                    <input value="<?php echo esc_attr(get_option('tw_setting_twilio_auth_token')); ?>" name="setting_twilio_auth_token" type="text"/>
                                    <p class="description">Your Account Auth Token you can get it from: <a target="_blank" href='https://www.twilio.com/user/account/'>https://www.twilio.com/user/account/</a></p>
                                </td>
                            </tr>   
                            <tr>
                                <td><strong>Twilio Number</strong></td>
                                <td>
                                    <input value="<?php echo esc_attr(get_option('tw_setting_twilio_number')); ?>" name="setting_twilio_number" type="text"/>
                                    <p class="description">Twilio Phone Number (ie. +14695572832)</p>
                                </td>
                            </tr>  
                        </tbody>
                    </table>       
                    <p class="submit">
                        <input type="submit" value="Save Changes" class="button button-primary">
                    </p>
                </form>
                <hr/>
            </div>
            <p>
                If you like <strong>Easy Call With Twilio</strong> please leave us a <a href="https://wordpress.org/support/view/plugin-reviews/twl-easy-call#postform" target="_blank" data-rated="Thanks :)">★★★★★</a> rating. A huge thank you from Zonnix in advance!
            </p>
            <div class="clear">
</div>
            <?php
        }
    }
    $twilio = new Twilio();
}
