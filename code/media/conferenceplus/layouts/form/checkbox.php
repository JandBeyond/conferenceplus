<?php

// No direct access
defined('_JEXEC') or die;
$showlabel = !(isset($displayData->showlabel) && $displayData->showlabel===false);

?>
<div class="form-group">
	<?php echo $displayData->input; ?>
	<?php if ($showlabel) : ?>
		<strong><?php echo $displayData->label; ?></strong>
	<?php endif; ?>
</div>

