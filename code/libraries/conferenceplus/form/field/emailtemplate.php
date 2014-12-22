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
class ConferenceplusFormFieldEmailtemplate extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Emailtemplate';

	/**
	 * Method to get the field options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		$options = array();

		$obj = new StdClass;

		$obj->text  = JText::_('CON_CONFERENCEPLUS_EMAILTEMPLATE');
		$obj->value = 0;
		$options[]  = $obj;
		unset($obj);

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('CONCAT(a.name ,  " | ", e.name) as text, a.conferenceplus_emailtemplate_id as value')
			->from('#__conferenceplus_emailtemplates AS a')
			->from('#__conferenceplus_events AS e')
			->where('a.event_id = e.conferenceplus_event_id');

		$db->setQuery($query);
		$options = array_merge($options, $db->loadObjectList());

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
