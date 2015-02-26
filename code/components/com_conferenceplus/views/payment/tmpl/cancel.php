<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

// No direct access
defined('_JEXEC') or die;

$displayData 	= new stdClass;
$params 		= JComponentHelper::getParams('com_conferenceplus');

$headerlevel    = $params->get('headerlevel', 2);

$title = JText::_('COM_CONFERENCEPLUS_CANCEL_BUY_TICKET_TITLE');
$doc = JFactory::getDocument()->setTitle($title);
$Itemid = Conferenceplus\Route\Helper::getItemid('');
$url = JUri::base() . 'index.php?option=com_conferenceplus&view=payment&layout=buy&Itemid=' . $Itemid;

?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<h<?php echo $headerlevel; ?>><?php echo $title; ?></h<?php echo $headerlevel; ?>>

	<?php echo JText::_('COM_CONFERENCEPLUS_TICKET_CANCEL_BUY_TICKET');?>

	<a class="btn btn-warning" href="<?php echo $url; ?>">
		<?php echo JText::_('COM_CONFERENCEPLUS_TICKET_CANCEL_BUY_CHECKDATA');?>
	</a>

</div>
<!-- ************************** END: conferenceplus ************************** -->