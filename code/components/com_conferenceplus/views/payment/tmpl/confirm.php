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

$title = JText::_('COM_CONFERENCEPLUS_CONFIRM_BUY_TICKET_TITLE');
$doc = JFactory::getDocument()->setTitle($title);
?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<h<?php echo $headerlevel; ?>><?php echo $title; ?></h<?php echo $headerlevel; ?>>

	<?php echo JText::_('COM_CONFERENCEPLUS_TICKET_CONFIRM_BUY_TICKET');?>
	
	<br />
	
	<a href="https://twitter.com/home?status=I%20just%20bought%20my%20ticket%20for%20JandBeyond%2029-31%20May%20in%20Prague.%20See%20you%20there!%20%23joomla%20%23jab15%20http://jandbeyond.org">Share on Twitter with your friends</a>

</div>
<!-- ************************** END: conferenceplus ************************** -->