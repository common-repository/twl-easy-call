<?php

if (!class_exists('Twilio_Ajax')) {

    class Twilio_Ajax {

        public function __construct() {
            //dfault button
            add_action('wp_ajax_make_the_call_guest', array($this, 'make_the_call_callback'));
            add_action('wp_ajax_nopriv_make_the_call_guest', array($this, 'make_the_call_callback'));
        }

        public function make_the_call_callback() {
            global $wpdb;
            
            $customerNumber = sanitize_text_field($_POST['user']);
            $agentNumber = sanitize_text_field($_POST['agent']);

            if ($customerNumber && $agentNumber) {
                    $configs = array(
                        'TWILIO_ACCOUNT_SID' => get_option('tw_setting_twilio_account_sid'),
                        'TWILIO_AUTH_TOKEN' => get_option('tw_setting_twilio_auth_token'),
                        'TWILIO_NUMBER' => get_option('tw_setting_twilio_number')
                    );

                    // Set URL for outbound call   
                    $url = TWILIO_PLUGIN_URL . 'lib/outbound.php?agentNumber=' . $agentNumber;

                    // Create authenticated REST client using account credentials in
                    $client = new Services_Twilio(
                            $configs['TWILIO_ACCOUNT_SID'], $configs['TWILIO_AUTH_TOKEN']
                    );

                    try {
                        // Place an outbound call
                        $call = $client->account->calls->create(
                                $configs['TWILIO_NUMBER'], // A Twilio number in your account
                                $customerNumber, // The visitor's phone number
                                $url
                        );
                        echo "Done"; //Call comming!
                    } catch (Exception $e) {
                        // Failed calls will throw
                        echo esc_attr($e->getMessage());
                    }
                
            }

            wp_die();
        }

    }

    $ajax = new Twilio_Ajax();
}
