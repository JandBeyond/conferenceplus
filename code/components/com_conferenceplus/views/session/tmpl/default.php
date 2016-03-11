<?php
 /**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2015 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

$params 		= JComponentHelper::getParams('com_conferenceplus');
$headerlevel    = $params->get('headerlevel', 1);
$shl1 			= $headerlevel + 1;
$shl11 			= $headerlevel + 2;
$shl111			= $headerlevel + 3;

$item = $this->item;
$base = JUri::base(true);
JFactory::getDocument()->setTitle($item->title);

$return    = base64_decode($this->input->getBase64('return', ''));
?>
<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item session">
	<h<?php echo $headerlevel; ?>><?php echo $item->title; ?></h<?php echo $headerlevel; ?>>
	<div class="sessiondescription">
		<?php echo nl2br($item->description); ?>
	</div>

	<?php if (! empty($item->assignedSpeakers)) : ?>
		<?php foreach ($item->assignedSpeakers AS $speaker) : ?>
			<h<?php echo $shl1; ?> class="speakertitle">
				<?php echo JText::_('COM_CONFERENCEPLUS_SPEAKER'); ?>:
				<span>
					<?php echo $speaker['firstname'] . ' ' . $speaker['lastname']; ?>
				</span>
			</h<?php echo $shl1; ?>>

		<div class="row clearfix">
			<div class="col-md-4">
				<?php if (file_exists(JPATH_SITE . $base . '/' .$speaker['imagefile']) && is_file(JPATH_SITE . $base . '/' .$speaker['imagefile'])) : ?>
					<img class="speakerimage img-responsive img-thumbnail" src="<?php echo $base . '/' .$speaker['imagefile']; ?>"/>
				<?php else : ?>
					<img class="speakerimage img-responsive img-thumbnail" src="https://placeholdit.imgix.net/~text?txtsize=19&txt=200%C3%97200&w=200&h=200" />
				<?php endif; ?>
			</div>
			<div class="col-md-8">
				<?php echo nl2br($speaker['bio']); ?>
			</div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if ($return != '') : ?>
	<div class="backlink">
		<a href="<?php echo $return; ?>" class="btn btn-primary"><?php echo JText::_('COM_CONFERENCEPLUS_BACK_TO_PROGRAMME'); ?></a>
	</div>
	<?php endif; ?>
</div>

