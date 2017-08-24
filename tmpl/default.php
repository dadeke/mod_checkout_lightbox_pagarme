<?php
/**
 * @module Checkout Lightbox with Pagar.me
 * @copyright 2017 Deividson (deividson.net)
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
 
defined('_JEXEC') or die;

JText::script('MOD_LIGHTBOXPAGARME_ERROR_CHECKOUT');
JText::script('MOD_LIGHTBOXPAGARME_CHECKOUT_INFO');
JText::script('MOD_LIGHTBOXPAGARME_PAIDSUCCESS');
JText::script('MOD_LIGHTBOXPAGARME_BOLETO_LINK');

?>
<div id="message_div<?php echo $module->id; ?>" class="alert alert-danger hide"></div>
<div class="control-group">
	<div class="control-label">
		<h3><?php echo $product_name; ?></h3>
	</div>
</div>
<?php if($show_product_code == "1") { ?>
<div class="control-group">
	<div class="control-label">
		<label>
			<?php echo JText::_('MOD_LIGHTBOXPAGARME_SCODE_LABEL').$product_code; ?></label>
	</div>
</div>
<?php } ?>
<div class="control-group">
	<div class="control-label">
		<label><?php echo $product_description; ?></label>
	</div>
</div>
<?php if($show_quantity == "1") { ?>
<div class="control-group">
	<div class="control-label">
		<label>
			<?php echo JText::_('MOD_LIGHTBOXPAGARME_SQUANTITY_LABEL'); ?>
			<?php if(($allow_quantity_change == "1") || ($allow_quantity_change == "")) { ?>
			<input id="quantity<?php echo $module->id; ?>"
				type="text"
				class="quantity"
				value="<?php echo $quantity; ?>"
				onkeypress="return vnumber(event);"
				maxlength="3"
				required />
			<?php } else if($allow_quantity_change == "0") { ?>
			<?php echo $quantity; ?>
			<input id="quantity<?php echo $module->id; ?>"
				type="hidden"
				value="<?php echo $quantity; ?>" />
			<?php } ?>
		</label>
	</div>
</div>
<?php } else { ?>
<input id="quantity<?php echo $module->id; ?>"
	type="hidden"
	value="<?php echo $quantity; ?>" />
<?php } ?>
<div class="control-group">
	<div class="control-label">
		<label><?php echo JText::_('MOD_LIGHTBOXPAGARME_SBRL_LABEL').$amount; ?></label>
	</div>
</div>
<input id="buttonpay<?php echo $module->id; ?>"
	type="button"
	class="pagarme-checkout-btn"
	onclick="buttonpay<?php echo $module->id; ?>Clique();" />
<img id="loading_gif<?php echo $module->id; ?>"
	src="media/mod_checkout_lightbox_pagarme/images/loading.gif"
	alt="loading.gif"
	class="hide" />
<script type="text/javascript">
//<![CDATA 
function buttonpay<?php echo $module->id; ?>Clique() {
	var obj = {
		id: "<?php echo $module->id; ?>",
		menu_itemid: "<?php echo $menu_itemid; ?>",
		encryption_key: "<?php echo $encryption_key; ?>",
		payment_methods: "<?php echo $payment_methods; ?>",
		ui_color: "<?php echo $ui_color; ?>",
		max_installments: "<?php echo $max_installments; ?>",
		default_installment: "<?php echo $default_installment; ?>",
		interest_rate: <?php echo $interest_rate; ?>,
		free_installments: "<?php echo $free_installments; ?>",
		boleto_helper_text: "<?php echo $boleto_helper_text; ?>",
		boleto_discount_type: "<?php echo $boleto_discount_type; ?>",
		boleto_discount_amount: "<?php echo $boleto_discount_amount; ?>",
		boleto_discount_percentage: <?php echo $boleto_discount_percentage; ?>,
		product_name: "<?php echo $product_name; ?>",
		amount: <?php echo $amount_api; ?>
	};

	ProcessarPagarme(obj);
}
//]]
</script>