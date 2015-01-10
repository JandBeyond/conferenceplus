<?php

defined('_JEXEC') or die;

$lists = [];

$messages = JFactory::getApplication()->getMessageQueue();

if (is_array($messages) && !empty($messages))
{
	foreach ($messages as $msg)
	{
		if (isset($msg['type']) && isset($msg['message']))
		{
			$lists[$msg['type']][] = $msg['message'];
		}
	}

}

$msgList = $lists;

?>
<div id="system-message-container">
	<?php if (is_array($msgList) && !empty($msgList)) : ?>
		<div id="system-message">
			<?php foreach ($msgList as $type => $msgs) : ?>
				<div class="alert alert-<?php echo $type; ?>">
					<?php // This requires JS so we should add it trough JS. Progressive enhancement and stuff. ?>
					<a class="close" data-dismiss="alert">×</a>

					<?php if (!empty($msgs)) : ?>
						<h4 class="alert-heading"><?php echo JText::_($type); ?></h4>
						<div>
							<ul>
							<?php foreach ($msgs as $msg) : ?>
								<?php echo $msg; ?>
							<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>