<?php

$currency = explode('|', $this->params->get('currency'))[0];
$Itemid = Conferenceplus\Route\Helper::getItemid();
$uri = JUri::base() . "index.php?option=com_conferenceplus&view=ticket&layout=buy&Itemid=$Itemid&tickettype=";

?>

<?php if (0 != count($this->items)) : ?>

	<?php foreach($this->items as $item) :?>
		<div class="ticket">
			<p class="ticketname">
				<?php echo $item->name; ?>
			</p>
			<p class="ticketdesc">
				<?php echo $item->description; ?>
			</p>
			<p class="ticketfee">
				<?php echo number_format($item->fee/100, 0, ',', ''); ?> <?php echo $currency; ?>
			</p>

			<a class="btn btn-primary" href="<?php echo $uri . $item->conferenceplus_tickettype_id; ?>"><span>Buy</span></a>
		</div>
	<?php endforeach; ?>

<?php else : ?>

	<p>We are sorry but the ticket shop is closed</p>

<?php endif; ?>
