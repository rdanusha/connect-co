<?php

/**
 * Provide a section to add delivery information
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Connect_Co
 * @subpackage Connect_Co/admin/partials
 */

?>
    <br class="clear"/>
    <br class="clear"/>
    <div class="connect-co-container">
        <h3 style="margin: 2px"><?php echo __('Connect Co. Delivery Information', 'connect-co') ?></h3>
        <div class="">
            <?php if ($config['is_submitted'] == '1') : ?>
                <div id="cc-info" class="connect-co-info">
                    Order submitted to the Connect Co.
                </div>
            <?php endif; ?>
            <div id="cc-success" class="connect-co-success" style="display: none"></div>
            <div id="cc-error" class="connect-co-error" style="display: none"></div>

            <?php if (!$config['delivery_city_availability'] && $config['is_submitted'] != '1'): ?>
                <div class="connect-co-error">
                    <?php
                    $message = 'Connect Co. delivery is not available for the city of shipping address. Please choose the nearest city.';
                    echo __($message, 'connect-co')
                    ?>
                </div>
            <?php endif; ?>
            <div class="connect-co-delivery-cost">Delivery cost:<br>
                <?php if ($config['is_submitted'] == '1'): ?>
                    <b><span><?php echo number_format($config['cc_delivery_charge'], 2) ?></span></b> LKR
                <?php else: ?>
                    <b><span id="cc-delivery-cost">0.00</span></b> LKR
                <?php endif; ?>
            </div>
            <?php if ($config['is_submitted'] == '1'): ?>
                <a href="javascript:void(0)"><span class="cc-show_details" id="cc-show_details">Show details</span></a>
            <?php endif; ?>
            <div class="cc-form-fields  <?php echo ($config['is_submitted'] == '1') ? 'cc-hide-records' : ''; ?>">
                <?php
                woocommerce_wp_select($config['pickup_locations']);
                woocommerce_wp_select($config['payment_types']);
                woocommerce_wp_text_input($config['package_weight']);
                woocommerce_wp_select($config['package_sizes']);
                woocommerce_wp_textarea_input($config['notes']);
                woocommerce_wp_select($config['cities']);
                woocommerce_wp_select($config['delivery_types']);
                ?>
                <?php $display_scheduled_date = ($config['is_submitted'] == '1' && $config['cc_scheduled_date'] !='') ? '' : 'style="display: none"'; ?>
                <div class="cc-delivery-date" <?php echo $display_scheduled_date; ?> >
                    <?php
                    woocommerce_wp_text_input($config['scheduled_date']);
                    ?>
                </div>
                <?php $display_time_window = ($config['is_submitted'] == '1' && $config['cc_time_window'] !='') ? '' : 'style="display: none"'; ?>
                <div class="cc-time-window"  <?php echo $display_time_window; ?> >
                    <?php
                    woocommerce_wp_select($config['time_window']);
                    ?>
                </div>
                <br class="clear"/>
                <br class="clear"/>
                <input type="hidden" id="cc_order_id" name="cc_order_id" value="<?php echo $order->get_id(); ?>">
                <input type="hidden" id="cc_delivery_charge" name="cc_delivery_charge">
                <button type="button" id="connect-co-submit" class="button"
                    <?php echo ($config['is_submitted'] == '1') ? 'disabled' : ''; ?>
                    <?php echo (!$config['delivery_city_availability']) ? 'disabled' : ''; ?>>
                    <?php echo __(' Submit to Connect Co.', 'connect-co') ?>
                </button>
            </div>
        </div>
    </div>
<?php if (!$config['delivery_city_availability'] && $config['is_submitted'] != '1'): ?>
    <?php
    $class = 'error';
    $is_dismissible = '';
    include_once 'connect-co-admin-notices.php';
    ?>
<?php endif; ?>