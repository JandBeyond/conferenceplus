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


$tweetgot = $this->item->eventParams['tweetgot'];
$tweetbuy = $this->item->eventParams['tweetbuy'];

$freeticket = $this->input->get('ft', 0) == 1;

if ($freeticket)
{
	$title = JText::_('COM_CONFERENCEPLUS_CONFIRM_GOT_TICKET_TITLE');
	$msg   = JText::_('COM_CONFERENCEPLUS_TICKET_CONFIRM_GOT_TICKET');
	$tweet = '<a target="_blank" href="https://twitter.com/home?status=' . $tweetgot . '">' . JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TWEET') . '</a>';
}
else
{
	$title = JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TITLE');
	$msg   = JText::_('COM_CONFERENCEPLUS_TICKET_CONFIRM_BUY_TICKET');
	$tweet = '<a target="_blank" href="https://twitter.com/home?status=' . $tweetbuy . '">' . JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TWEET') . '</a>';
}

$doc = JFactory::getDocument()->setTitle($title);

?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<h<?php echo $headerlevel; ?>><?php echo $title; ?></h<?php echo $headerlevel; ?>>

	<?php echo $msg; ?>
	
	<br />
	
	<?php echo $tweet; ?>

</div>
<!-- ************************** END: conferenceplus ************************** -->