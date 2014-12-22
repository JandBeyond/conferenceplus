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
class ConferenceplusFormFieldSpeaker extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Speaker';

	/**
	 * Method to get the field options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('a.*, e.name as eventname, u.username as username')
			->from('#__conferenceplus_events AS e')
			->from('#__conferenceplus_speakers AS a')
			->leftJoin('#__users AS u ON ' . $db->qn('a.userid') . ' = ' . $db->qn('u.id'))
			->where('a.event_id = e.conferenceplus_event_id');

		$db->setQuery($query);
		$results = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		$options = array();

		$obj = new StdClass;

		$obj->text  = JText::_('CON_CONFERENCEPLUS_SELECTSPEAKER');
		$obj->value = 0;
		$options[]  = $obj;
		unset($obj);

		foreach($results as $result)
		{
			$text  = $result->firstname . ' ' . $result->lastname;
			$text .= empty($result->username) ? '' : ' (' . $result->username . ')';
			$text .= ' | ' . $result->eventname;

			$obj = new StdClass;

			$obj->text  = $text;
			$obj->value = $result->conferenceplus_speaker_id;
			$options[]  = $obj;

			unset($obj);
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
