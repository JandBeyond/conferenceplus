<?php

// No direct access
defined('_JEXEC') or die;
$showlabel = !(isset($displayData->showlabel) && $displayData->showlabel===false);

$divclassopen  = '<div>';
$divclassclose = '</div>';
if (isset($displayData->divclass))
{
	$divclassopen = '<div class="'.$displayData->divclass.'">';
}

	?>
	<?php echo $divclassopen; ?>
		<?php if ($showlabel) : ?>
			<strong><?php echo $displayData->label; ?></strong>
		<?php endif; ?>
		<?php echo $displayData->input; ?>
	<?php echo $divclassclose; ?>
