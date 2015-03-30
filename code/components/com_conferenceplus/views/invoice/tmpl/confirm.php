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

$title = JText::_('COM_CONFERENCEPLUS_CONFIRM_INVOICE_TITLE');
$doc = JFactory::getDocument()->setTitle($title);

?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<h<?php echo $headerlevel; ?>><?php echo $title; ?></h<?php echo $headerlevel; ?>>

	<?php echo JText::_('COM_CONFERENCEPLUS_CONFIRM_INVOICE_MESSAGE');?>

</div>
<!-- ************************** END: conferenceplus ************************** -->