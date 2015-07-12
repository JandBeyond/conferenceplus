<?php

$displayData 	= new stdClass;
$form 			= $this->form;
$errors 		= $form->getErrors();
$params 		= JComponentHelper::getParams('com_conferenceplus');
$keys 			= array_keys($form->getFieldset());
$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';
$headerlevel    = $params->get('headerlevel', 2);
$title 		    = JTExt::_('COM_CONFERENCEPLUS_SUBMITNOMINATION_TITLE');

$doc = JFactory::getDocument()->setTitle($title);

// load js
JFactory::getDocument()->addScript(JUri::base(true) . '/media/conferenceplus/js/awards.js');
?>

<div class="conferenceplus submitnomination">

	<?php
	echo "<h$headerlevel>" . $title . "</h$headerlevel>";
	echo JText::_('COM_CONFERENCEPLUS_HEADTEXTSUBMIT_NOMINATION');
	?>

	<?php echo JText::_('COM_CONFERENCEPLUS_NOMINATION_PRETEXT');?>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<form action="index.php?option=com_conferenceplus" method="post" id="adminForm" role="form">
			<div class="row">
				<?php $fields = array('firstname', 'lastname', 'email'); ?>

				<?php foreach($fields AS $f) : ?>

					<?php if (in_array($f, $keys)) : ?>
						<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'PREINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
						<?php $displayData->label = $form->getLabel($f); ?>
						<?php $displayData->input = $form->getInput($f); ?>
						<?php echo JLayoutHelper::render('form.formelement', $displayData, $baseLayoutPath); ?>
						<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'POSTINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
					<?php endif; ?>

				<?php endforeach; ?>

			</div>

			<div class="row nominationlist">
				<div id="nomination0">
					<?php $fields = array('nominee', 'awardcategory_id'); ?>
					<?php foreach($fields AS $f) : ?>

						<?php if (in_array($f, $keys)) : ?>
							<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'PREINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
							<?php $displayData->label = $form->getLabel($f); ?>
							<?php $displayData->input = $form->getInput($f); ?>
							<?php echo JLayoutHelper::render('form.formelement', $displayData, $baseLayoutPath); ?>
							<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'POSTINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<button class="btn btn-primary" type="button" onclick="ConferencePlus.addNomination();">
				<span class="glyphicon glyphicon-plus"></span><?php echo JText::_('COM_CONFERENCEPLUS_MORE_NOMINATION'); ?>
			</button>
			<hr />
			<div class="form-actions">
				<input type="submit" value="<?php echo JText::_('COM_CONFERENCEPLUS_SEND');?>" class="btn btn-primary" />
			</div>

			<input type="hidden" name="option" value="com_conferenceplus" />
			<input type="hidden" name="view" value="awardnomination" />
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="nominationcount" value="0" />
			<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />
		</form>
	</div>


</div>