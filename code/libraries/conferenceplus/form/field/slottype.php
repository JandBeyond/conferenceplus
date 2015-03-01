<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('predefinedlist');

/**
 * field type
 *
 * @package  conferenceplus
 * @since    1.0.0
 */
class ConferenceplusFormFieldSlottype extends JFormFieldPredefinedList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Slottype';


	/**
	 * Available types
	 *
	 */
	protected $predefinedOptions = array(
		'0'  => 'COM_CONFERENCEPLUS_SLOTTYPE_SESSION',
		'1'  => 'COM_CONFERENCEPLUS_SLOTTYPE_SHARED',
		'2'  => 'COM_CONFERENCEPLUS_SLOTTYPE_KEYNOTE',
		'3'  => 'COM_CONFERENCEPLUS_SLOTTYPE_SOCIAL',
		'4'  => 'COM_CONFERENCEPLUS_SLOTTYPE_FOOD',
		'5'  => 'COM_CONFERENCEPLUS_SLOTTYPE_UNOFFICIAL'
	);

	/**
	 * Option text for a value
	 *
	 * @param   integer  $value  the key
	 *
	 * @return string
	 */
	public function getOption($value)
	{
		if (array_key_exists($value, $this->predefinedOptions))
		{
			return $this->predefinedOptions[$value];
		}

		return '';
	}
}
