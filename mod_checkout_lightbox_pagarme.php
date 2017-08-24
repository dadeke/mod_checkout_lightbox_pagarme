<?php
/**
 * @package Checkout Lightbox with Pagar.me
 * @author Deividson Developer
 * @copyright 2017 Deividson (deividson.net)
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
 
defined('_JEXEC') or die;

JHtml::stylesheet("media/mod_checkout_lightbox_pagarme/css/styles.css");
JHtml::script("https://assets.pagar.me/checkout/checkout.js");
JHtml::script("media/mod_checkout_lightbox_pagarme/js/functions.js");

$encryption_key = $params->get('encryption_key');
$payment_methods = $params->get('payment_methods');
$ui_color = $params->get('ui_color');
$max_installments = $params->get('max_installments');
$default_installment = $params->get('default_installment');
$interest_rate = $params->get('interest_rate');
$free_installments = $params->get('free_installments');
$boleto_helper_text = $params->get('boleto_helper_text');
$boleto_discount_type = $params->get('boleto_discount_type');
$boleto_discount_amount = $params->get('boleto_discount_amount');
$boleto_discount_percentage = $params->get('boleto_discount_percentage');
$show_product_code = $params->get('show_product_code');
$product_code = $params->get('product_code');
$product_name = $params->get('product_name');
$product_description = $params->get('product_description');
$show_quantity = $params->get('show_quantity');
$allow_quantity_change = $params->get('allow_quantity_change', '1');
$quantity = $params->get('quantity');
$amount = $params->get('amount');
$amount_api = str_replace(array('.', ','), '', $amount); /* O valor para API deve ser sem separador de milhar e de decimal. */

$app = JFactory::getApplication();
$menu_itemid = $app->getMenu()->getActive()->id;

require JModuleHelper::getLayoutPath('mod_checkout_lightbox_pagarme', $params->get('layout', 'default'));
?>