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

$freeticket = $this->input->get('ft', 0) == 1;

if ($freeticket)
{
	$title = JText::_('COM_CONFERENCEPLUS_CONFIRM_GOT_TICKET_TITLE');
	$msg   = JText::_('COM_CONFERENCEPLUS_TICKET_CONFIRM_GOT_TICKET');
	$tweet = '<a target="_blank" href="https://twitter.com/home?status=I%20have%20gotton%20my%20ticket%20for%20JandBeyond%2029-31%20May%20in%20Prague.%20See%20you%20there!%20%23joomla%20%23jab15%20http://jandbeyond.org">'. JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TWEET') . '</a>';
}
else
{
	$title = JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TITLE');
	$msg   = JText::_('COM_CONFERENCEPLUS_TICKET_CONFIRM_BUY_TICKET');
	$tweet = '<a target="_blank" href="https://twitter.com/home?status=I%20just%20bought%20my%20ticket%20for%20JandBeyond%2029-31%20May%20in%20Prague.%20See%20you%20there!%20%23joomla%20%23jab15%20http://jandbeyond.org">'. JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TWEET') . '</a>';
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