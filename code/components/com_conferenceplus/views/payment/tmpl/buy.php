<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

// No direct access
defined('_JEXEC') or die;

$displayData 	= new stdClass;
$params 		= JComponentHelper::getParams('com_conferenceplus');
$headerlevel    = $params->get('headerlevel', 2);
$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';

$title = JText::_('COM_CONFERENCEPLUS_BUY_TITLE');
$doc = JFactory::getDocument()->setTitle($title);

$Itemid = Conferenceplus\Route\Helper::getItemid('');

$tickettype = $this->item->ticketData->tickettype;
$ticket     = $this->item->ticketData->ticket;
$currency   = explode('|', $params->get('currency'))[0];

$processdata = $ticket->processdata;

?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<h<?php echo $headerlevel; ?>><?php echo $title; ?></h<?php echo $headerlevel; ?>>

	<?php echo JText::_('COM_CONFERENCEPLUS_BUY_PRETEXT');?>
	
	<div class="selectedticket">
		<?php echo JText::_('COM_CONFERENCEPLUS_BUY_TICKET_YOURSELECTION');?>
		<dl>
			<dt><?php echo JText::_('COM_CONFERENCEPLUS_TICKETTYPENAME');?></dt>
			<dd><?php echo $tickettype->productname;?></dd>
			<dt><?php echo JText::_('COM_CONFERENCEPLUS_TICKETTYPEDESCRIPTION');?></dt>
			<dd><?php echo $tickettype->description;?></dd>
			<dt><?php echo JText::_('COM_CONFERENCEPLUS_TICKETTYPEFEE');?></dt>
			<dd>
				<?php $displayData->fee = $tickettype->fee; ?>
				<?php $displayData->vat = $tickettype->vat; ?>
				<?php echo JLayoutHelper::render('html.fee', $displayData, $baseLayoutPath); ?>
			</dd>
			<dt><?php echo JText::_('COM_CONFERENCEPLUS_FIRSTNAME');?></dt>
			<dd><?php echo $ticket->firstname;?></dd>
			<dt><?php echo JText::_('COM_CONFERENCEPLUS_LASTNAME');?></dt>
			<dd><?php echo $ticket->lastname;?></dd>
			<dt><?php echo JText::_('COM_CONFERENCEPLUS_EMAIL');?></dt>
			<dd><?php echo $ticket->email;?></dd>

			<?php $fields = ['ask4gender', 'ask4tshirtsize', 'ask4food', 'ask4food0']; ?>

			<?php foreach ($fields as $field) : ?>
				<?php if (array_key_exists($field, $processdata) && !empty($processdata[$field])) :?>
					<dt><?php echo JText::_('COM_CONFERENCEPLUS_' . strtoupper($field));?></dt>
					<dd><?php echo JText::_($processdata[$field]);?></dd>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ($tickettype->fee != 0) : ?>
				<dt><?php echo JText::_('COM_CONFERENCEPLUS_INVOICEADDRESS');?></dt>
				<dd><?php echo nl2br($processdata['invoiceaddress']);?></dd>
			<?php endif; ?>
		</dl>
	</div>

	<?php if ($this->item->freeTicket) : ?>
		<form action="index.php?option=com_conferenceplus&Itemid=<?php echo $Itemid;?>" method="post" id="adminForm" role="form">

			<div class="form-actions">
				<input type="submit" value="<?php echo JText::_('COM_CONFERENCEPLUS_GETYOURFREETICKET');?>" class="btn btn-success" />
			</div>

			<input type="hidden" name="option" value="com_conferenceplus" />
			<input type="hidden" name="view" value="payment" />
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="layout" value="confirm" />
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
			<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />
		</form>

	<?php else : ?>

		<?php echo JText::_('COM_CONFERENCEPLUS_PAYMENTPROVIDERS'); ?>
		<div class="paymentproviders">
			<?php foreach ($this->item->paymentProviders as $provider) :?>
				<?php echo $provider; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
<!-- ************************** END: conferenceplus ************************** -->