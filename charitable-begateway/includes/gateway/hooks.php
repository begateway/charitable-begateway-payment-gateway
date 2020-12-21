<?php
add_filter('charitable_process_donation_begateway', array('Charitable_Gateway_BeGateway', 'redirect_to_processing'), 10, 3);
add_filter('charitable_donation_form_user_fields', array('Charitable_Gateway_BeGateway', 'remove_unrequired_fields'));
add_action('charitable_donation_receipt_page', array('Charitable_Gateway_BeGateway', 'process_response'));
add_action('init', array('Charitable_Gateway_BeGateway', 'listener'));
add_filter('charitable_settings_tab_fields_general', array('Charitable_Gateway_BeGateway', 'add_fields'), 6);
