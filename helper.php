<?php
/**
 * @package Checkout Lightbox with Pagar.me
 * @author Deividson Developer
 * @copyright 2017 Deividson (deividson.net)
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class ModCheckoutLightboxPagarMeHelper
{
	/**
	 * Efetua a captura da transação na API do Pagar.me e retorna o resultado.
	 *
	 * @return array
	 */
	public static function efetuarCapturaAjax()
	{
		$session = JFactory::getSession();
		$jinput = JFactory::getApplication()->input;
		$module_id = $jinput->get('id', 0, 'INT');
		$quantity = $jinput->get('quantity', 0, 'INT');
		$token = $jinput->get('token', null);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('params'));
		$query->from($db->quoteName('#__modules'));
		$query->where($db->quoteName('id') . ' = '. strval($module_id));
		$db->setQuery($query);
		$result = json_decode($db->loadResult());

		if($quantity == 0) {
			$quantity = intval($result->quantity);
		}

		$result->amount = floatval(str_replace(',', '', $result->amount));	/* O Valor para API tem que ser sem separador decimal. */
		$content = array();
		$content['amount'] = strval($result->amount * $quantity);			/* Valor total. */
		$content['api_key'] = $result->api_key;								/* Chave de API. */
		$content['metadata']['product_code'] = $result->product_code;		/* Identificador do item. */

		$ch = curl_init("https://api.pagar.me/1/transactions/" . $token . "/capture");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if(!empty($error)) {
			throw new Exception(JText::_('MOD_LIGHTBOXPAGARME_ERROR_CHECKOUT').$error);
		}

		$result = json_decode($json);

		/* Retorna as mensagens de erro vindas da API. */
		if(property_exists($result, "errors")) {
			throw new Exception(JText::_('MOD_LIGHTBOXPAGARME_ERROR_CHECKOUT').$result->errors[0]->message);
		}

		$return['payment_method'] = $result->payment_method;
		$return['status'] = $result->status;
		if($result->payment_method == "credit_card") {
			return $return;
		}
		else if($result->payment_method == "boleto") {
			$return['boleto_url'] = $result->boleto_url;
			return $return;
		}
	}
}