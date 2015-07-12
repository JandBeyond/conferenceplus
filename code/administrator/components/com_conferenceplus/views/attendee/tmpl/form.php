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

// No direct access
defined('_JEXEC') or die;

JHTML::_('behavior.framework', true);
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "cancel" || document.formvalidator.isValid(document.id("adminForm"))) {
			Joomla.submitform(task, document.getElementById("adminForm"));
		} else {
			alert("Invalid form");
		}
	};
');

$fields = ['enabled','firstname','lastname',
			'email','gender','tshirtsize','food'
		  ];

?>
<form id="adminForm" class="form-validate" name="adminForm" method="post" action="index.php">

	<div class="form-horizontal">
		<?php foreach($fields as $field) : ?>
			<div class="row-fluid">
				<div class="span2">
					<?php echo $this->form->getLabel($field); ?>
				</div>
				<div class="span10">
					<?php echo $this->form->getInput($field); ?>

				</div>
			</div>
			<br />
		<?php endforeach; ?>
	</div>

	<input type="hidden" value="com_conferenceplus" name="option">
	<input type="hidden" value="attendee" name="view">
	<input type="hidden" value="" name="task">
	<input type="hidden" value="<?php echo $this->item->conferenceplus_attendee_id; ?>" name="conferenceplus_attendee_id">
	<?php echo JHtml::_('form.token'); ?>

</form>
