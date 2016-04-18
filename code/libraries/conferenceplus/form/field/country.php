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
class ConferenceplusFormFieldCountry extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Country';

	/**
	 * Method to get the field options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		$data = (new Conferenceplus\Country\Helper)->getData();

		$options = array();
		$options[] = JHtml::_('select.option', '', '- Select -');

		foreach($data AS $key => $countryElement)
		{
			$options[] = JHtml::_('select.option', $key, $countryElement['name']);
		}

		return $options;
	}
}
