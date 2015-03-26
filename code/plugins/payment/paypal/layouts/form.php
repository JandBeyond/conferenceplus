<?php defined('_JEXEC') or die(); ?>

<p><?php echo $displayData->title ?></p>

<form action="<?php echo $displayData->url; ?>"  method="post" id="paymentForm">
	<input type="hidden" name="cmd" value="<?php echo $displayData->cmd; ?>" />
	<input type="hidden" name="business" value="<?php echo $displayData->merchant; ?>" />
	<input type="hidden" name="return" value="<?php echo $displayData->success; ?>" />
	<input type="hidden" name="cancel_return" value="<?php echo $displayData->cancel; ?>" />
	<input type="hidden" name="notify_url" value="<?php echo $displayData->postback; ?>" />
	<input type="hidden" name="custom" value="<?php echo $displayData->custom; ?>" />

	<input type="hidden" name="item_number" value="<?php echo $displayData->item_number; ?>" />
	<input type="hidden" name="item_name" value="<?php echo $displayData->item_name; ?>" />
	<input type="hidden" name="currency_code" value="<?php echo $displayData->currency; ?>" />

	<input type="hidden" name="amount" value="<?php echo $displayData->net_amount; ?>" />
	<input type="hidden" name="tax" value="<?php echo $displayData->tax_amount; ?>" />


	<?php // Remove the following line if PayPal doing POST to your site causes a problem ?>
	<input type="hidden" name="rm" value="2">

	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="no_shipping" value="1" />

	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" id="paypalsubmit" />
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>
