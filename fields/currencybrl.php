<?php
/**
 * @package Checkout Lightbox with Pagar.me
 * @author Deividson Developer
 * @copyright 2017 Deividson (deividson.net)
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('JPATH_PLATFORM') or die;

JHtml::script("media/mod_checkout_lightbox_pagarme/js/functions.js");

class JFormFieldCurrencyBRL extends JFormFieldText
{
	protected $type = 'currencybrl';

	protected function getInput() {
        $html_field = parent::getInput();
        $search_attribute = 'type="text"';
        $replace_attribute = 'type="text" onkeypress="return vnumber(event);" ' .
        					 'onkeyup="formata_onkeyup(event, this, MASCARA_REAL, \'D\');"';

        $html_field = str_replace($search_attribute, $replace_attribute, $html_field);

        return $html_field;
    }
}
