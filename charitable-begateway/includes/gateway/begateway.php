<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('Charitable_Gateway_BeGateway')) {

  class Charitable_Gateway_BeGateway extends Charitable_Gateway
  {
    const ID = 'begateway';

    public function __construct()
    {
      $this->name = apply_filters('charitable_gateway_begateway_name', __('BeGateway', 'charitable-begateway'));

      $this->defaults = array(
        'label' => __('BeGateway', 'charitable-begateway'),
      );

      $this->supports = array(
        '1.3.0',
      );

      /**
      * Needed for backwards compatibility with Charitable < 1.3
      */
      $this->credit_card_form = false;
    }

    public static function get_gateway_id()
    {
      return self::ID;
    }

    public function gateway_settings($settings)
    {
      $settings['shop_id'] = array(
        'type' => 'text',
        'title' => __( 'Shop ID', 'charitable-begateway' ),
        'priority' => 8,
        'help' => __( 'Please enter your Shop Id', 'charitable-begateway' ),
        'required' => true,
        'default' => '361'
      );

      $settings['secret_key'] = array(
        'type' => 'text',
        'title' => __( 'Secret key', 'charitable-begateway' ),
        'priority' => 8,
        'help' => __( 'Please enter your Shop secret key', 'charitable-begateway' ),
        'required' => true,
        'default' => 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d'
      );

      $settings['public_key'] = array(
        'type' => 'text',
        'title' => __( 'Public key', 'charitable-begateway' ),
        'priority' => 8,
        'help' => __( 'Please enter your Shop public key', 'charitable-begateway' ),
        'required' => true,
        'default' => ''
      );

      $settings['domain_checkout'] = array(
        'type' => 'text',
        'title' => __( 'Payment page domain', 'charitable-begateway' ),
        'priority' => 8,
        'help' => __( 'Please enter payment page domain of your payment processor', 'charitable-begateway' ),
        'required' => true,
      );

      $settings['css'] = array(
        'type' => 'text',
        'title' => __( 'Widget CSS', 'charitable-begateway' ),
        'priority' => 8,
        'help' => __( 'Please enter CSS data to customize the payment widget', 'charitable-begateway' ),
        'required' => false,
        'default' => ''
      );

      return $settings;
    }

    public function get_keys()
    {
      $keys = [
        'title' => trim($this->get_value('title')),
        'shop_id' => trim($this->get_value('shop_id')),
        'secret_key' => trim($this->get_value('secret_key')),
        'public_key' => trim($this->get_value('public_key')),
        'domain_checkout' => trim($this->get_value('domain_checkout')),
        'css' => trim($this->get_value('css'))
      ];

      return $keys;
    }

    /**
     * Process the donation with BeGateway
     *
     * @param   Charitable_Donation $donation
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_donation($content, Charitable_Donation $donation ) {
      $gateway = new Charitable_Gateway_BeGateway();
      $campaign_donations = $donation->get_campaign_donations();

      foreach ($campaign_donations as $key => $value) {
        if (!empty($value->campaign_id)) {
          $post_id = $value->campaign_id;
          $campaign_name = $value->campaign_name;
          $post = get_post((int) $post_id);
          $campaign = new Charitable_Campaign($post);
          break;
        }
      }

      $donor = $donation->get_donor();
      $keys = $gateway->get_keys();

      \BeGateway\Settings::$checkoutBase = 'https://' . $keys['domain_checkout'];
      \BeGateway\Settings::$shopId = $keys['shop_id'];
      \BeGateway\Settings::$shopKey = $keys['secret_key'];
      \BeGateway\Settings::$shopPubKey = $keys['public_key'];

      $raw_description = sprintf( __( 'Campaign: %s', 'charitable-begateway' ), $campaign_name );
      $raw_description .= '. ';
      $raw_description .= sprintf( __( 'Donation: %d', 'charitable-begateway' ), $donation->ID );

      $token = new \BeGateway\GetPaymentToken;
      $token->money->setCurrency(charitable_get_currency());
      $token->money->setAmount($donation->get_total_donation_amount(true));

      $token->setDescription(mb_substr($raw_description, 0, 255));

      $token->setTrackingId(implode('|', array($donation->ID, $donation->get_donation_key())));
      $token->customer->setFirstName(trim($donor->get_donor_meta('first_name')));
      $token->customer->setLastName(trim($donor->get_donor_meta('last_name')));
      $token->customer->setCountry(trim($donor->get_donor_meta('country')));
      $token->customer->setCity(trim($donor->get_donor_meta('city')));
      $token->customer->setPhone(trim($donor->get_donor_meta('phone')));
      $token->customer->setZip(trim($donor->get_donor_meta('postcode')));
      $token->customer->setAddress(trim($donor->get_donor_meta('address')) . ' ' . trim($donor->get_donor_meta('address_2')));
      $token->customer->setEmail(trim($donor->get_donor_meta('email')));

      if (in_array(trim($donor->get_donor_meta('country')), array('US','CA'))) {
        $token->customer->setState(trim($donor->get_donor_meta('state')));
      }
      $token->setTestMode(charitable_get_option('test_mode') == 1);

      $return_url = charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $donation->ID ) );
      $cancel_url = charitable_get_permalink( 'donation_cancel_page', array( 'donation_id' => $donation->ID ) );
      $return_url  = str_replace( '&amp;', '&', $return_url );
      $cancel_url  = str_replace( '&amp;', '&', $cancel_url );
      $notify_url = charitable_get_ipn_url(Charitable_Gateway_BeGateway::ID);
      $notify_url = str_replace('0.0.0.0','webhook.begateway.com:8443', $notify_url);

      $token->setSuccessUrl($return_url);
      $token->setDeclineUrl($cancel_url);
      $token->setFailUrl($cancel_url);
      $token->setCancelUrl($cancel_url);
      $token->setNotificationUrl($notify_url);

      $lang = explode('_', get_locale());
      $lang = $lang[0];

      $token->setLanguage($lang);

      $response = $token->submit();

      $token->additional_data->setMeta(
        [
          'cms' => 'wp-charitable',
          'version'   => Charitable::VERSION
        ]
      );

      ob_start();
      echo $content;

      if(!$response->isSuccess()) {
        $error = sprintf(__( 'Error to get a payment token. Reason: %s', 'charitable-begateway'), $response->getMessage());
        echo ("<div class='charitable-notice charitable-form-errors'>");
        echo ("<div>" . $error . "</div>");
        echo ("<a href=" . $cancel_url . ">". __('Return back', 'charitable-begateway') . "</div>");
      } else {
?>
<script>
  this.start_begateway_payment = function () {
    var params = {
      checkout_url: "<?= \BeGateway\Settings::$checkoutBase; ?>",
      token: "<?= $response->getToken() ?>",
      style: {
        <?= $keys['css'] ?>
      },
      closeWidget: function(status) {
        if (status == null) {
          window.location.replace("<?= $cancel_url ?>");
        }
      }
    };
     new BeGateway(params).createWidget();
  };
  window.onload = start_begateway_payment();
 </script>
 <?php
      }
      $content = ob_get_clean();
      return $content;
    }

    public static function process_ipn() {
      $gateway = new Charitable_Gateway_BeGateway();
      $keys = $gateway->get_keys();

      \BeGateway\Settings::$shopId = $keys['shop_id'];
      \BeGateway\Settings::$shopKey = $keys['secret_key'];
      \BeGateway\Settings::$shopPubKey = $keys['public_key'];

      $webhook = new \BeGateway\Webhook;

      if (!$webhook->isAuthorized()) {
        die('Not authorized');
      }

      list($donation_id, $donation_key) = explode('|', $webhook->getTrackingId());

      $donation = charitable_get_donation((int) $donation_id);

      if (!$donation) {
        die('Donation not found');
      }

      if ($donation->get_donation_key() != $donation_key) {
        die('Donation key not matched');
      }

      $campaign_donations = $donation->get_campaign_donations();
      foreach ($campaign_donations as $key => $value) {
        if (!empty($value->campaign_id)) {
          $post_id = $value->campaign_id;
          $post = get_post((int) $post_id);
          $campaign = new Charitable_Campaign($post);
          break;
        }
      }

      /* If we have already processed this donation, stop here. */
      if ( 'charitable-completed' == get_post_status( $donation_id ) ) {
        die('Donation Processed Already');
      }

      /* Save the transation ID */
      $donation->set_gateway_transaction_id($webhook->getUid());

      if ($webhook->isSuccess()) {
        update_post_meta($donation_id, 'test_mode', $webhook->isTest());
        $donation->update_status('charitable-completed');
      } elseif ($webhook->isFailed()) {
        $message = sprintf('%s: %s', __('The donation has failed with the following reason', 'charitable-begateway'), $webhook->getMessage());
        $donation->update_donation_log($message);
        $donation->update_status('charitable-failed');
      }

      die('OK');
    }

    public static function add_fields($field)
    {
      $general_fields = array(
        'begateway_section_pages' => array(
          'title' => __('BeGateway Options', 'charitable-begateway'),
          'type' => 'heading',
          'priority' => 50,
        ),
        'begateway_rem_add' => array(
          'title' => __('Remove Address 1 & 2 Field', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Remove Address Field on Payment', 'charitable-begateway'),
          'priority' => 70,
        ),
        'begateway_rem_city' => array(
          'title' => __('Remove City Field', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Remove City Field on Payment', 'charitable-begateway'),
          'priority' => 80,
        ),
        'begateway_rem_state' => array(
          'title' => __('Remove State Field', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Remove State Field on Payment', 'charitable-begateway'),
          'priority' => 90,
        ),
        'begateway_rem_postcode' => array(
          'title' => __('Remove Postcode Field', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Remove Postcode Field on Payment', 'charitable-begateway'),
          'priority' => 100,
        ),
        'begateway_rem_country' => array(
          'title' => __('Remove Country Field', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Remove Country Field on Payment', 'charitable-begateway'),
          'priority' => 110,
        ),
        'begateway_mak_phone' => array(
          'title' => __('Phone Required', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Make Phone Fields Mandatory to be set', 'charitable-begateway'),
          'priority' => 120,
        ),
        'begateway_unr_email' => array(
          'title' => __('Unrequire Email', 'charitable-begateway'),
          'type' => 'checkbox',
          'help' => __('Make Email Fields Optional to be set. NOT RECOMMENDED', 'charitable-begateway'),
          'priority' => 130,
        ),
      );
      $field = array_merge($field, $general_fields);
      return $field;
    }

    public static function remove_unrequired_fields($fields)
    {

      $address = charitable_get_option('begateway_rem_add', false);
      $city = charitable_get_option('begateway_rem_city', false);
      $state = charitable_get_option('begateway_rem_state', false);
      $postcode = charitable_get_option('begatewayz_rem_postcode', false);
      $country = charitable_get_option('begateway_rem_country', false);
      $phone = charitable_get_option('begateway_mak_phone', false);
      $email = charitable_get_option('begateway_unr_email', false);

      if ($address) {
        unset($fields['address']);
        unset($fields['address_2']);
      }

      if ($city) {
        unset($fields['city']);
      }
      if ($state) {
        unset($fields['state']);
      }
      if ($postcode) {
        unset($fields['postcode']);
      }
      if ($country) {
        unset($fields['country']);
      }

      if ($phone) {
        $fields['phone']['required'] = true;
      }

      if ($email) {
        $fields['email']['required'] = false;
      }

      return $fields;
    }

    /**
     * Redirect the donation to the processing page.
     *
     * @param   int $donation_id
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function redirect_to_processing_legacy( $donation_id ) {
      wp_safe_redirect(
        charitable_get_permalink( 'donation_processing_page', array(
          'donation_id' => $donation_id,
        ) )
      );
      exit();
    }

    public static function charitable_enqueue_begateway_widget_script() {
      $gateway = new Charitable_Gateway_BeGateway();
      $keys = $gateway->get_keys();
      $url = explode('.', trim($keys['domain_checkout']));
      $url[0] = 'js';
      $url = 'https://' . implode('.', $url) . '/widget/be_gateway.js';

      wp_enqueue_script( 'charitable_begateway_widget_script', $url);
    }
  }
}
