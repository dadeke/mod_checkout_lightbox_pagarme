/**
 * @package Checkout Lightbox with Pagar.me
 * @author Deividson Developer
 * @copyright 2017 Deividson (deividson.net)
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

/* Funções utilitárias. */
function getKey(e) {
	return (window.event) ? event.keyCode : e.which;
}

function validaInteiro(letra) {
	return ((letra >= 48) && (letra <= 57)) || ((letra >= 96) && (letra <= 105));
}

function vnumber(e) {
	var tecla = getKey(e);
	if(tecla > 47 && tecla < 58) return true;
	else {
		if (tecla == 8 || tecla == 0) return true;
		else  return false;
	}
}

/* Funções de formatação. */
var MASCARA_CEP = '#####-###';
var MASCARA_INTEIRO = '###.###.###.###.###';
var MASCARA_REAL = MASCARA_INTEIRO + ',##';

function formata_onkeyup(evento, caixaTexto, mascara, alinhamento) {
	var retorno = '';
	var letra = getKey(evento);
	if(validaInteiro(letra) || letra == 8 || letra == 46) {
		retorno = formata(caixaTexto, mascara, alinhamento);
	}
	return retorno;
}

function formata(caixaTexto, mascara, alinhamento) {
	caixaTexto.value = formatacao(caixaTexto.value, mascara, alinhamento);
	return caixaTexto.value;
}

function formatacao(valor, mascara, alinhamento) {
	var retorno = "" + valor;
	retorno = retorno.replace(/\D/g,"");
	var tamanho = retorno.length;
	var aux = "";
	if(alinhamento == "D") {
		tamanhoMascara = mascara.length - 1;
		for(i = (tamanho-1); i >= 0; i--) {
			letra = retorno.charAt(i);
			if((letra >= "0") && (letra <= "9")) {
				aux = letra + aux;
				tamanhoAux = aux.length;
				letra = mascara.charAt(tamanhoMascara - tamanhoAux);
				tamanhoSemEspeciais = aux.replace(/\D/g,"").length;
				if((letra != "#") && (tamanho > tamanhoSemEspeciais)) {
					aux = letra + aux;
				}
			}
		}
	}
	else if(alinhamento == "E") {
		for(i = 0; i <= (tamanho-1); i++) {
			letra = retorno.charAt(i);
			if((letra >= "0") && (letra <= "9")) {
				aux += letra;
				letra = mascara.charAt(aux.length);
				if((letra != "#") && (tamanho > (i+1))) {
					aux += letra;
				}
			}
		}
	}
	if(aux != "") {
		retorno = aux;
	}
	return retorno;
}

/* Funções para o pagamento. */
function ProcessarPagarme(obj) {
	var message_div = jQuery('#message_div' + obj.id);
	message_div.html("");
	message_div.attr("class", "alert alert-danger hide");

	var quantity_str = jQuery('#quantity' + obj.id).val();
	var quantity = 1;
	if(jQuery.trim(quantity_str) != "") {
		quantity = parseInt(quantity_str, 10);
	}
	else {
		quantity_str = "1";
	}
	var amount = String(obj.amount * quantity);
	amount = amount.replace(".", "");

	var headerText = Joomla.JText._("MOD_LIGHTBOXPAGARME_CHECKOUT_INFO");
	headerText = headerText.replace("#product_name", obj.product_name);
	headerText = headerText.replace("#quantity", quantity_str);

	var params = {
		"customerData": "true",
		"createToken": "true",
		"amount": amount,
		"paymentMethods": obj.payment_methods,
		"headerText": headerText,
		"uiColor": obj.ui_color,
		"boletoHelperText": obj.boleto_helper_text,
		"maxInstallments": obj.max_installments,
		"defaultInstallment": obj.default_installment,
		"freeInstallments": obj.free_installments,
		"interestRate": obj.interest_rate
	};

	if((obj.boleto_discount_type == "fixed") && (obj.payment_methods.indexOf("boleto") !== -1)) {
		params['boletoDiscountAmount'] = obj.boleto_discount_amount;
	}
	else if((obj.boleto_discount_type == "percentage") && (obj.payment_methods.indexOf("boleto") !== -1)) {
		params['boletoDiscountPercentage'] = obj.boleto_discount_percentage;
	}

	var checkout = new PagarMeCheckout.Checkout({
		"encryption_key": obj.encryption_key,
		success: function(data) {
			// console.log(data);
			EfetuarCapturaPagarme(obj.id, quantity, data.token, obj.menu_itemid);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			// console.log(jqXHR);
			message_div.html(Joomla.JText._('MOD_LIGHTBOXPAGARME_ERROR_CHECKOUT') + ' ' + errorThrown);
			message_div.attr('class', 'alert alert-danger');
		}
	});
	checkout.open(params);
}

function EfetuarCapturaPagarme(id, quantity, token, menu_itemid) {
	var button_id = jQuery('#buttonpay' + id);
	var loading_gif = jQuery('#loading_gif' + id);
	var url = "index.php?option=com_ajax";

	button_id.prop("disabled", true);
	loading_gif.attr("class", "");

	var dataPost = {
		module: "checkout_lightbox_pagarme",
		format: "json",
		method: "efetuarcaptura",
		id: id,
		token: token,
		Itemid: menu_itemid
	};

	if(quantity) {
		dataPost.quantity = quantity;
	}

	jQuery.ajax({
		url: url,
		type: "post",
		dataType: "json",
		data: dataPost,
		success: function(result) {
			if(result.success) {
				// console.log(result);
				MostraResultadoPagarme(id, result.data);
			}
			else {
				var message_div = jQuery('#message_div' + id);
				message_div.html(result.message);
				message_div.attr("class", "alert alert-danger");
			}
		},
		complete: function() {
			loading_gif.attr("class", "hide");
			button_id.prop("disabled", false);
		}
	});
}

function MostraResultadoPagarme(id, data) {
	var message_div = jQuery('#message_div' + id);

	if((data.payment_method == "credit_card") && (data.status == "paid")) {
		message_div.html(Joomla.JText._("MOD_LIGHTBOXPAGARME_PAIDSUCCESS"));
		message_div.attr("class", "alert alert-success");
	}
	else if(data.payment_method == "boleto") {
		var message_boleto = Joomla.JText._("MOD_LIGHTBOXPAGARME_BOLETO_LINK");
		message_boleto = message_boleto.replace("#boleto_url", data.boleto_url);
		message_boleto = message_boleto.replace("#boleto_url", data.boleto_url); // Tem que mesmo ser duas vezes. AFf.
		message_div.html(message_boleto);
		message_div.attr("class", "alert alert-info");
	}
}