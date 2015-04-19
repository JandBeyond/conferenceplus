<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

// No direct access
defined('_JEXEC') or die;

$displayData 	= new stdClass;
$form 			= $this->form;
$params 		= JComponentHelper::getParams('com_conferenceplus');
$keys 			= array_keys($form->getFieldset());

$headerlevel    = $params->get('headerlevel', 2);

$title = JText::_('COM_CONFERENCEPLUS_PREBUY_TITLE');
JFactory::getDocument()->setTitle($title);

$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';

$Itemid = Conferenceplus\Route\Helper::getItemid('');

$currency = explode('|', $params->get('currency'))[0];

$uri       = JUri::getInstance();
$returnurl = base64_encode($uri->toString(['path', 'query', 'fragment']));

$showMessages = ! empty(JFactory::getApplication()->getMessageQueue());

$validCouponAvailable = $this->item->couponAvailable;

$fields = array('firstname', 'lastname', 'email', 'ask4gender', 'ask4tshirtsize', 'ask4food', 'ask4food0', 'invoiceaddress');

?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<?php
		echo "<h$headerlevel>" . $title . "</h$headerlevel>";
		echo JText::_('COM_CONFERENCEPLUS_PREBUY_TICKET');
	?>
	<form action="index.php?option=com_conferenceplus&view=ticket&task=save&layout=buy&Itemid=<?php echo $Itemid;?>" method="post" id="adminForm" role="form">

		<div class="selectedticket">
			<?php echo JText::_('COM_CONFERENCEPLUS_PREBUY_TICKET_YOURSELECTION');?>
			<dl>
				<dt><?php echo JText::_('COM_CONFERENCEPLUS_TICKETTYPENAME');?></dt>
				<dd><?php echo $this->item->ticketType->productname;?></dd>
				<dt><?php echo JText::_('COM_CONFERENCEPLUS_TICKETTYPEDESCRIPTION');?></dt>
				<dd><?php echo $this->item->ticketType->description;?></dd>
				<dt><?php echo JText::_('COM_CONFERENCEPLUS_TICKETTYPEFEE');?></dt>
				<dd id="fee">
					<?php $displayData->fee = $this->item->ticketType->fee; ?>
					<?php $displayData->vat = $this->item->ticketType->vat; ?>
					<?php echo JLayoutHelper::render('html.fee', $displayData, $baseLayoutPath); ?>
				</dd>
			</dl>
			<?php if ($validCouponAvailable) : ?>
				<?php $displayData->couponCode = $this->item->couponCode; ?>
				<?php $displayData->resultCouponCheck = $this->item->resultCouponCheck; ?>
				<?php $displayData->tickettypeId = $this->item->ticketType->conferenceplus_tickettype_id; ?>
				<?php echo JLayoutHelper::render('form.coupon', $displayData, $baseLayoutPath); ?>
			<?php endif; ?>
		</div>

		<?php echo JText::_('COM_CONFERENCEPLUS_PREBUY_TICKET_PRETEXT');?>

		<?php if ($showMessages) : ?>
			<?php echo JLayoutHelper::render('html.messages', '', $baseLayoutPath); ?>
		<?php endif; ?>

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row">
				<?php foreach($fields AS $f) : ?>

					<?php if (in_array($f, $keys)) : ?>
						<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'PREINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
						<?php $displayData->label = $form->getLabel($f); ?>
						<?php $displayData->input = $form->getInput($f); ?>
						<?php echo JLayoutHelper::render('form.formelement', $displayData, $baseLayoutPath); ?>
						<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'POSTINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
					<?php endif; ?>

				<?php endforeach; ?>

				<div class="form-actions">
					<input type="submit" value="<?php echo JText::_('COM_CONFERENCEPLUS_SEND');?>" class="btn btn-danger" />
				</div>

				<input type="hidden" name="option" value="com_conferenceplus" />
				<input type="hidden" name="view" value="ticket" />
				<input type="hidden" name="task" value="save" />
				<input type="hidden" name="layout" value="buy" />
				<input type="hidden" name="returnurl" value="<?php echo $returnurl; ?>" />
				<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

			</div>
		</div>
	</form>

</div>
<!-- ************************** END: conferenceplus ************************** -->