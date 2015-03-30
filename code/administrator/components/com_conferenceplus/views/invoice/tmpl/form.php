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

$fields = ['enabled','identifier','name','description',
			'sdate','stimehh','stimemm','edate','etimehh','etimemm',
	       'number_valid_items','freeticket','fixed_fee','discount_fix','discount_percentaged'];

?>
<form id="adminForm" class="form-validate" name="adminForm" method="post" action="index.php">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_CONFERENCEPLUS_SESSION_DATA', true)); ?>
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
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'assign', JText::_('COM_CONFERENCEPLUS_ASSIGN_TICKETTYPE', true)); ?>
				<div class="row-fluid">
					<div class="span12">
						<?php foreach($this->item->tickettypes as $tickettype) : ?>
								<?php
									$id = $tickettype->conferenceplus_tickettype_id;
									$checked = '';
									if (in_array($id, $this->item->assignedTickettypes))
									{
										$checked = ' checked="checked"';
									}
								?>
								<label for="<?php echo 'id_tickettype_' . $id; ?>" class="checkbox">
									<input type="checkbox" id="<?php echo 'id_tickettype_' . $id; ?>" name="<?php echo 'tickettype_' . $id; ?>" value="1"<?php echo $checked; ?>>
									<?php echo $tickettype->eventname; ?> - <?php echo $tickettype->productname; ?>
								</label>
						<?php endforeach; ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" value="com_conferenceplus" name="option">
	<input type="hidden" value="coupon" name="view">
	<input type="hidden" value="" name="task">
	<input type="hidden" value="<?php echo $this->item->conferenceplus_coupon_id; ?>" name="conferenceplus_coupon_id">
	<?php echo JHtml::_('form.token'); ?>

</form>
