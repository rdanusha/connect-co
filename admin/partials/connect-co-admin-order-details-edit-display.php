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
    <div class="connect-co-container" >
        <h3 style="margin: 2px"><?php echo __('Connect Co. Delivery Information', 'connect-co') ?></h3>
        <div class="">
            <div id="cc-success" class="connect-co-success" style="display: none"></div>
            <div id="cc-error" class="connect-co-error" style="display: none"></div>

            <?php if (!$config['delivery_city_availability']): ?>
                <div class="connect-co-error">
                    <?php
                    $message = 'Connect Co. delivery is not available for the city of shipping address. Please choose the nearest city.';
                    echo __($message, 'connect-co')
                    ?>
                </div>
            <?php endif; ?>
            <div class="connect-co-delivery-cost"><b>Delivery Cost: 12,000,000.00 LKR</b></div>
            <?php
            woocommerce_wp_select($config['pickup_locations']);

            woocommerce_wp_select($config['payment_types']);

            woocommerce_wp_text_input($config['package_weight']);

            woocommerce_wp_select($config['package_sizes']);

            woocommerce_wp_textarea_input($config['notes']);

            woocommerce_wp_select($config['cities']);

            woocommerce_wp_select($config['delivery_types']);
            ?>
            <br class="clear"/>
            <br class="clear"/>
            <input type="hidden" id="cc_order_id" name="cc_order_id" value="<?php echo $order->get_id(); ?>">
            <button type="button" id="connect-co-submit" class="button"
                <?php echo  (!$config['delivery_city_availability'])?'disabled':''; ?>>
                <?php echo __(' Submit to Connect Co.', 'connect-co') ?>
            </button>
        </div>
    </div>
    <script>
        (function ($) {
            $(function () {
                $("#connect-co-submit").click(function (e) {
                    e.preventDefault();
                    let confirmation = confirm("Submit order to Connect Co.?");
                    if (confirmation) {
                        let nonce = '<?php echo wp_create_nonce('submit_order_to_connect_co') ?>';
                        let cc_pickup_location = $('#cc_pickup_location option:selected').val();
                        let cc_payment_type = $('#cc_payment_type option:selected').val();
                        let cc_delivery_type = $('#cc_delivery_type option:selected').val();
                        let cc_package_weight = $('#cc_package_weight').val();
                        let cc_package_size = $('#cc_package_size option:selected').val();
                        let cc_notes = $('#cc_notes').val();
                        let cc_city = $('#cc_city option:selected').val();
                        let order_id = $('#cc_order_id').val();

                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>', // or example_ajax_obj.ajaxurl if using on frontend
                            type: 'post',
                            data: {
                                'action': 'submit_order_to_connect_co',
                                'ajax': true,
                                'nonce': nonce,
                                'cc_pickup_location': cc_pickup_location,
                                'cc_payment_type': cc_payment_type,
                                'cc_package_weight': cc_package_weight,
                                'cc_package_size': cc_package_size,
                                'cc_delivery_type': cc_delivery_type,
                                'cc_notes': cc_notes,
                                'cc_city': cc_city,
                                'order_id': order_id
                            },
                            dataType: "json",
                            beforeSend: function () {
                                $('#cc-error').fadeOut();
                                $('#cc-success').fadeOut();
                                $('#connect-co-submit').attr('disabled', true);
                            },
                            success: function (response) {
                                $('#connect-co-submit').attr('disabled', false);
                                if (response) {
                                    if (response.status == 'error') {
                                        $('#cc-error').html(response.message);
                                        $('#cc-error').fadeIn();
                                    }
                                    if (response.status == 'success') {
                                        $('#cc-success').text(response.message);
                                        $('#cc-success').fadeIn();
                                    }
                                } else {
                                    $('#cc-error').html('Something went wrong. Please try again.');
                                    $('#cc-error').fadeIn();
                                }
                            },
                            error: function (errorThrown) {
                                $('#cc-error').html('Something went wrong. Please try again.');
                                $('#cc-error').fadeIn();
                                $('#connect-co-submit').attr('disabled', false);
                                console.log(errorThrown);
                            }
                        });
                    }
                });

            });

        })(jQuery);

    </script>
<?php if (!$config['delivery_city_availability']): ?>
    <?php
    $class = 'error';
    $is_dismissible = '';
    include_once 'connect-co-admin-notices.php';
    ?>
<?php endif; ?>