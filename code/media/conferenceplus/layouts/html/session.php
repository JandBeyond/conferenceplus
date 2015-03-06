<?php
// No direct access
defined('_JEXEC') or die();

$params = JComponentHelper::getParams('com_conferenceplus');

$colour = $params->get('categorycolors','');
$map = [];

if (!empty($colour))
{
	$tmp = explode(',', $colour);

	foreach ($tmp AS $c)
	{
		list($num, $hex) = explode(':', $c);
		$map[$num] = $hex;
	}
}

$categoryname = '[' . $displayData->categoryname . ']';

if (array_key_exists($displayData->catid, $map))
{
	$categoryname = '<span class="label label-default" style="background-color:' . $map[$displayData->catid] . '">' .
						$displayData->categoryname . '</span>';
}
$speakerLink = '';
$speaker = 'NN';

if (! empty($displayData->assignedSpeakers))
{
	$speakerData = $displayData->assignedSpeakers[0];
	$speaker = $speakerData['firstname'] . ' ' . $speakerData['lastname'];
	$speakerLink = 'index.php?option=com_conferenceplus&view=speaker&id=' . $speakerData['conferenceplus_speaker_id'];
}

$Itemid = Conferenceplus\Route\Helper::getItemid();

if ( ! empty($displayData->Itemid))
{
	$Itemid = $displayData->Itemid;
}

$uri       = JUri::getInstance();
$returnurl = base64_encode($uri->toString(['path', 'query', 'fragment']));

?>
<div class="categoryname">
	<?php echo $categoryname; ?>
</div>

<div class="sessiontitle">
	<a href="index.php?option=com_conferenceplus&view=session&id=<?php
		echo $displayData->conferenceplus_session_id; ?>&Itemid=<?php
		echo $Itemid; ?>&return=<?php echo $returnurl; ?>">
		<?php echo $displayData->title; ?>
	</a>
</div>

<div class="sessionspeaker">
	<span class="glyphicon glyphicon-user"></span>
	<?php if ($speakerLink != '') : ?>
		<a href="<?php echo $speakerLink; ?>&Itemid=<?php
			echo $Itemid; ?>&return=<?php echo $returnurl; ?>">
	<?php endif; ?>
		<?php echo $speaker; ?>
	<?php if ($speakerLink != '') : ?>
		</a>
	<?php endif; ?>
</div>
