<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die;

$params 		= JComponentHelper::getParams('com_conferenceplus');
$headerlevel    = $params->get('headerlevel', 2);

$title = JText::_('COM_CONFERENCEPLUS_SUBMIT_AWARDNOMINATION_THANKYOU');
$doc = JFactory::getDocument()->setTitle($title);

?>
<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus">
	<?php echo "<h$headerlevel>" . $title . "</h$headerlevel>"; ?>
	<p>
	<?php echo JText::_('COM_CONFERENCEPLUS_SUBMIT_AWARDNOMINATION_THANKYOU_TEXT'); ?>
	</p>
</div>
<!-- ************************** END: conferenceplus ************************** -->
