<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

// No direct access
defined('_JEXEC') or die;

$displayData 	= new stdClass;
$form 			= $this->form;
$errors 		= $form->getErrors();
$params 		= JComponentHelper::getParams('com_conferenceplus');
$keys 			= array_keys($form->getFieldset());

$headerlevel    = $params->get('headerlevel', 2);
$shl1 			= $headerlevel + 1;
$shl11 			= $headerlevel + 2;
$shl111			= $headerlevel + 3;

$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';
$title = JLayoutHelper::render('html.title', $displayData, $baseLayoutPath);

$doc = JFactory::getDocument()->setTitle($title);

$Itemid = Conferenceplus\Route\Helper::getItemid('call4papers');

$uri       = JUri::getInstance();
$returnurl = base64_encode($uri->toString(['path', 'query', 'fragment']));

$script ='

	jQuery(document).ready(function() {
		jQuery("#speaker_listtext-lbl").parent().hide();
	    jQuery("input[name$=\'speaker_multiple\']").click(function() {
	        var test = $(this).val();
			if (test == 0) {
		        jQuery("#speaker_listtext-lbl").parent().hide();
			} else {
				jQuery("#speaker_listtext-lbl").parent().show();
			}
				
	    });
	});
';
$doc->addScriptDeclaration($script);

$showMessages = ! empty(JFactory::getApplication()->getMessageQueue());
?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus item">

	<?php
		echo "<h$headerlevel>" . $title . "</h$headerlevel>";
		echo JText::_('COM_CONFERENCEPLUS_HEADTEXTSUBMIT_SESSION');
	?>

	<?php echo JText::_('COM_CONFERENCEPLUS_CALL4PAPERS_PRETEXT');?>

	<?php if ($showMessages) : ?>
		<?php echo JLayoutHelper::render('html.messages', '', $baseLayoutPath); ?>
	<?php endif; ?>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">

			<form action="index.php?option=com_conferenceplus&view=session&layout=form&Itemid=<?php echo $Itemid;?>" method="post" id="adminForm" role="form">

			<?php $fields = array('title', 'firstname', 'lastname', 'email', 'description', 'bio', 'imagefile', 'catid', 'addidionalinfo', 'speaker_multiple', 'speaker_listtext'); ?>
			
			<?php foreach($fields AS $f) : ?>

				<?php if (in_array($f, $keys)) : ?>
					<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'PREINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
					<?php $displayData->label = $form->getLabel($f); ?>
					<?php $displayData->input = $form->getInput($f); ?>
					<?php echo JLayoutHelper::render('form.formelement', $displayData, $baseLayoutPath); ?>
					<?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'POSTINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
				<?php endif; ?>

			<?php endforeach; ?>

				<div class="form-actions">
					<input type="submit" value="<?php echo JText::_('COM_CONFERENCEPLUS_SEND');?>" class="btn btn-primary" />
				</div>

				<input type="hidden" name="option" value="com_conferenceplus" />
				<input type="hidden" name="view" value="session" />
				<input type="hidden" name="task" value="save" />
				<input type="hidden" name="layout" value="call4papers" />
				<input type="hidden" name="returnurl" value="<?php echo $returnurl; ?>" />
				<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />
			</form>	
		</div>
	</div>		
</div>
<!-- ************************** END: conferenceplus ************************** -->