<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('Twilio_Short_Codes')) {

    class Twilio_Short_Codes {

        public function __construct() {
            //add guest short code
            add_shortcode('wpc2c', array($this, 'twilio_shortcode_func'));
        }

        public function twilio_shortcode_func($attr) {
            ob_start();
            //check for twilio settings
            if (isset($attr['number']) && get_option('tw_setting_twilio_number') && get_option('tw_setting_twilio_auth_token') && get_option('tw_setting_twilio_account_sid')):

                //agent phone number
                $agent_phone = $attr['number'];

                $button_label = 'Click To Call';
                if (isset($attr['label']))
                    $button_label = $attr['label'];
                ?>
                <input type="text" class="twilio_call_number"/>
                <button data-agent="<?php echo esc_attr($agent_phone); ?>" type="button" class="twilio_call_button"><?php echo esc_attr($button_label); ?></button>                              
                <?php
            endif;
            return ob_get_clean();
        }

    }

    $twilio_shortcode = new Twilio_Short_Codes();
}
