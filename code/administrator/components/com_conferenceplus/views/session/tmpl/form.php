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

$fields = ['enabled','title','description','catid','sessiontype','addidionalinfo',
	       'speaker_listids','notes','slides','video','event_id'];

$rooms = $this->item->rooms;
$slots = $this->item->slots;

$asingingPossible = !empty($rooms) && !empty($slots);

if ($asingingPossible)
{
	$dayName = $slots[0]->dayname;
	$allColCount = count($rooms) + 1;
}

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

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'assign', JText::_('COM_CONFERENCEPLUS_ASSIGN_SESSION', true)); ?>
				<div class="row-fluid">
					<div class="span12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>
										Slot
									</th>
									<?php foreach($rooms as $room) : ?>
										<th>
											<?php echo $room->name; ?>
										</th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<tr style="background-color: #004099;color:#fff;font-weight: bold"><td colspan="<?php echo $allColCount; ?>"><?php echo $dayName; ?></td></tr>
								<?php foreach($slots as $slot) : ?>
									<?php if ($slot->dayname != $dayName) : ?>
										<?php $dayName = $slot->dayname; ?>
										<tr style="background-color: #004099;color:#fff;font-weight: bold"><td colspan="<?php echo $allColCount; ?>"><?php echo $dayName; ?></td></tr>
									<?php endif; ?>
									<tr>
										<td>
											<?php echo $slot->name; ?>
											&nbsp;(<?php echo substr($slot->stime, 0, 5); ?> - <?php echo substr($slot->etime, 0,5); ?>)
										</td>
										<?php foreach($rooms as $room) : ?>
											<td>
												<?php
													$id = $room->conferenceplus_room_id . '_' . $slot->conferenceplus_slot_id;
													$checked = '';
													if (in_array($id, $this->item->assignedRoomsSlots))
													{
														$checked = ' checked="checked"';
													}
												?>
												<input type="checkbox" name="<?php echo 'assignment_' . $id; ?>" value="1"<?php echo $checked; ?>>
											</td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" value="com_conferenceplus" name="option">
	<input type="hidden" value="session" name="view">
	<input type="hidden" value="" name="task">
	<input type="hidden" value="<?php echo $this->item->conferenceplus_session_id; ?>" name="conferenceplus_session_id">
	<?php echo JHtml::_('form.token'); ?>

</form>
