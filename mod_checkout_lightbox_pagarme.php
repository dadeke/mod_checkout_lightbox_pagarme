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
$product_code = $params->get('product_code');
$product_name = $params->get('product_name');
$product_description = $params->get('product_description');
$show_quantity = $params->get('show_quantity');
$quantity = $params->get('quantity');
$amount = $params->get('amount');

$amount = floatval(str_replace(',', '.', $amount));
$amount = $amount * intval($quantity);
$amount_api = number_format($amount, 2, '', ''); /* O Valor para API tem que ser sem separador decimal. */
$amount_brl = number_format($amount, 2, ',', '.');
$amount = number_format($amount, 2, '.', '');

$app = JFactory::getApplication();
$menu_itemid = $app->getMenu()->getActive()->id;

require JModuleHelper::getLayoutPath('mod_checkout_lightbox_pagarme', $params->get('layout', 'default'));
?>