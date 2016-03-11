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

$title = $item->firstname . ' ' . $item->lastname;
JFactory::getDocument()->setTitle($title);

$return    = base64_decode($this->input->getBase64('return', ''));

$showImg = file_exists(JPATH_SITE . $base . '/' . $item->imagefile) && is_file(JPATH_SITE . $base . '/' . $item->imagefile);

$Itemid = Conferenceplus\Route\Helper::getItemid();
?>
<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item speaker">
	<h<?php echo $headerlevel; ?>><?php echo $title; ?></h<?php echo $headerlevel; ?>>

		<div class="row clearfix">
			<div class="col-md-4">
				<?php if ($showImg) : ?>
					<img class="speakerimage img-responsive img-thumbnail" src="<?php echo $base . '/' . $item->imagefile; ?>" />
				<?php else : ?>
					<img class="speakerimage img-responsive img-thumbnail" src="https://placeholdit.imgix.net/~text?txtsize=19&txt=200%C3%97200&w=200&h=200" />
				<?php endif; ?>
			</div>
			<div class="col-md-8">
				<?php echo nl2br($item->bio); ?>
			</div>
		</div>

	<?php if ( ! empty($item->assignedSessions)) : ?>
		<h<?php echo $shl1; ?>><?php echo JText::_('COM_CONFERENCEPLUS_SPEAKERS_SESSIONS'); ?></h<?php echo $shl1; ?>>

		<div class="row clearfix">
			<ul class="speakerssessions">
			<?php foreach ($item->assignedSessions as $session) : ?>
				<?php
					$link = 'index.php?option=com_conferenceplus&view=session&id=' . $session->conferenceplus_session_id
							. '&Itemid=' . $Itemid
							. '&return=' . $this->input->getBase64('return', '')
				?>
				<li>
					<strong><?php echo $session->dayname; ?>&nbsp;-&nbsp;<?php echo JText::_('COM_CONFERENCEPLUS_ROOM'); ?>&nbsp;<?php echo $session->roomname; ?></strong><br />
					<?php echo substr($session->stime, 0, 5); ?>&nbsp; - &nbsp;<?php echo substr($session->etime, 0, 5); ?>
					: <a href="<?php echo $link; ?>"><?php echo $session->title; ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ($return != '') : ?>
	<div class="backlink">
		<a href="<?php echo $return; ?>" class="btn btn-primary"><?php echo JText::_('COM_CONFERENCEPLUS_BACK_TO_PROGRAMME'); ?></a>
	</div>
	<?php endif; ?>
</div>

