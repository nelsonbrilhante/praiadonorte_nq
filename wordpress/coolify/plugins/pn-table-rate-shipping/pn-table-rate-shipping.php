<?php
/**
 * Plugin Name: PN Table Rate Shipping
 * Description: Weight-based table rate shipping for Praia do Norte store.
 * Version: 1.0.0
 * Author: Praia do Norte
 * Requires Plugins: woocommerce
 *
 * @package PN_Table_Rate_Shipping
 */

defined('ABSPATH') || exit;

add_action('woocommerce_shipping_init', function () {

    class WC_Shipping_PN_Table_Rate extends WC_Shipping_Method {

        public function __construct($instance_id = 0) {
            $this->id                 = 'pn_table_rate';
            $this->instance_id        = absint($instance_id);
            $this->method_title       = 'PN Table Rate';
            $this->method_description = 'Envio por peso para Portugal.';
            $this->supports           = ['shipping-zones', 'instance-settings'];
            $this->title              = $this->get_option('title', 'Envio para Portugal');
            $this->tax_status         = $this->get_option('tax_status', 'none');

            $this->init_form_fields();
            $this->init_settings();

            add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);
        }

        public function init_form_fields() {
            $this->instance_form_fields = [
                'title' => [
                    'title'   => 'Title',
                    'type'    => 'text',
                    'default' => 'Envio para Portugal',
                ],
                'tax_status' => [
                    'title'   => 'Tax status',
                    'type'    => 'select',
                    'default' => 'none',
                    'options' => [
                        'taxable' => 'Taxable',
                        'none'    => 'None',
                    ],
                ],
            ];
        }

        public function calculate_shipping($package = []) {
            // Check for "sem-envio" shipping class — forces Local Pickup only
            $sem_envio = get_term_by('slug', 'sem-envio', 'product_shipping_class');
            if ($sem_envio) {
                foreach ($package['contents'] as $item) {
                    $product = $item['data'];
                    if ($product->get_shipping_class_id() === (int) $sem_envio->term_id) {
                        return; // No rate → only Local Pickup available
                    }
                }
            }

            // Sum cart weight
            $total_weight = 0;
            foreach ($package['contents'] as $item) {
                $product = $item['data'];
                $weight  = (float) $product->get_weight();
                $total_weight += $weight * $item['quantity'];
            }

            // Weight-based rate table
            $rates = [
                ['min' => 0,    'max' => 1.00,  'cost' => 6.80],
                ['min' => 1.01, 'max' => 2.00,  'cost' => 6.80],
                ['min' => 2.01, 'max' => 3.00,  'cost' => 9.45],
                ['min' => 3.01, 'max' => 4.00,  'cost' => 9.45],
                ['min' => 4.01, 'max' => 5.00,  'cost' => 9.45],
                ['min' => 5.01, 'max' => 10.00, 'cost' => 13.25],
            ];

            // Over 10 kg → no rate (only Local Pickup)
            if ($total_weight > 10.00) {
                return;
            }

            // Find matching tier
            $cost = null;
            foreach ($rates as $tier) {
                if ($total_weight >= $tier['min'] && $total_weight <= $tier['max']) {
                    $cost = $tier['cost'];
                    break;
                }
            }

            if ($cost === null) {
                return;
            }

            $this->add_rate([
                'id'    => $this->get_rate_id(),
                'label' => $this->title,
                'cost'  => $cost,
            ]);
        }
    }
});

add_filter('woocommerce_shipping_methods', function ($methods) {
    $methods['pn_table_rate'] = 'WC_Shipping_PN_Table_Rate';
    return $methods;
});
