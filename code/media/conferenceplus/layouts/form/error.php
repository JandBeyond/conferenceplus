<?php

// No direct access
defined('_JEXEC') or die;
?>
<div class="alert alert-error">
	<?php if (property_exists($displayData, 'pretext')) : ?>
		<strong><?php echo $displayData->pretext; ?></strong>
	<?php endif; ?>
	<?php if (!empty($displayData->errors)) : ?>
		<ul>
		<?php foreach ($displayData->errors as $error) : ?>
		<li>
			<?php if ($error instanceof Exception) : ?>
			<?php echo $error->getMessage(); ?>
		<?php else : ?>
			<?php echo $error; ?>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
