<?php
// No direct access
defined('_JEXEC') or die;
$sidebar = JHtmlSidebar::render();
?>
<div id="conferenceplus">
<?php if (!empty( $sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $sidebar; ?>
	</div>
	<div id="j-main-container" class="span10 well">
<?php else : ?>
	<div id="j-main-container well">
<?php endif;?>
		<?php echo JText::_('COM_CONFERENCEPLUS_COMP');?>
		<?php echo JText::_('COM_CONFERENCEPLUS_COMP_DESC');?>

		<div class="cpanel" style="padding:20px">

			<?php echo JText::_('COM_CONFERENCEPLUS_COMP_SUPPORT');?>
			<?php echo JText::_('COM_CONFERENCEPLUS_COMP_DOCS');?>
			<?php echo JText::_('COM_CONFERENCEPLUS_COMP_FORUM');?>

			<?php //if (JFactory::getUser()->authorise('core.admin', 'com_socon')) : ?>
				<?php //echo LiveUpdate::getIcon(); ?>
			<?php //endif;?>

		</div>
		<div class="clearfix"> </div>

		<p>
			<?php echo JText::_('COM_CONFERENCEPLUS_COPYRIGHT') . ' | ' . JText::_('COM_CONFERENCEPLUS_LICENSE'); ?>
		</p>
	</div>
</div>