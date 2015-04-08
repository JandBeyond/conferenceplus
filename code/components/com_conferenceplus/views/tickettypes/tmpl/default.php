<?php

$displayData 	= new stdClass;
$params 		= JComponentHelper::getParams('com_conferenceplus');

$headerlevel    = $params->get('headerlevel', 2);

$title = JTExt::_('COM_CONFERENCEPLUS_SELECT_TICKET_TITLE');

$doc = JFactory::getDocument()->setTitle($title);

$Itemid = Conferenceplus\Route\Helper::getItemid();

$currency = explode('|', $this->params->get('currency'))[0];
$Itemid = Conferenceplus\Route\Helper::getItemid();
$uri = JUri::base() . "index.php?option=com_conferenceplus&view=ticket&layout=prebuy&Itemid=$Itemid&tickettype=";

?>

<div class="conferenceplus tickettypes">

<?php if (0 != count($this->items)) : ?>

	<?php echo "<h$headerlevel>" . $title . "</h$headerlevel>"; ?>

	<div class="clearfix">
	<?php foreach($this->items as $item) :?>
		<a class="ticket<?php echo $item->partnerticket == "1" ? ' partner' : '';?><?php echo ' ttid' . $item->conferenceplus_tickettype_id;?>" href="<?php echo $uri . $item->conferenceplus_tickettype_id; ?>">
			<div class="ticket">
				<div class="ticketicon"></div>
				<p class="ticketname">
					<?php echo $item->name; ?>
				</p>
				<p class="ticketdesc">
					<?php echo $item->description; ?>
				</p>
				<p class="ticketfee">
					<?php echo $currency; ?> <?php echo number_format($item->fee/100, 0, ',', ''); ?>
				</p>
				<p class="call2action">
					<?php echo JTExt::_('COM_CONFERENCEPLUS_SELECT_TICKET'); ?>
				</p>
			</div>
		</a>
	<?php endforeach; ?>
	</div>
		<?php echo JTExt::_('COM_CONFERENCEPLUS_BUY_TICKET_BEFORETICKETS') ?>

	<?php echo JTExt::_('COM_CONFERENCEPLUS_BUY_TICKET_AFTERTICKETS') ?>


<?php else : ?>

	<?php echo JText::_('COM_CONFERENCEPLUS_SHOP_CLOSED'); ?>

<?php endif; ?>

</div>