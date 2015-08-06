<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * field type
 *
 * @package  conferenceplus
 * @since    1.0.0
 */
class ConferenceplusFormFieldTask extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Task';

	/**
	 * Method to get the field options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		$options = array();

		$options[] = JHtml::_('select.option', 0, '- Select -');
		$options[] = JHtml::_('select.option', 'ConfirmEmail', 'ConfirmEmail');
		$options[] = JHtml::_('select.option', 'SendInvoice', 'SendInvoice');
		$options[] = JHtml::_('select.option', 'SendTicket', 'SendTicket');

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
