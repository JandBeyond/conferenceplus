<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die;

require_once 'default.php';

class ConferenceplusModelSessions extends ConferenceplusModelDefault
{
	/**
	 * Ajust the query
	 *
	 * @param   boolean  $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$query = parent::buildQuery($overrideLimits);

		$db    = $this->getDbo();

		$formName = $this->getState('form_name');

		if ($formName == 'form.default')
		{
			// Join categories
			$query->join('INNER', '#__categories AS c ON c.id = catid')
				->select($db->qn('c.title') . ' AS ' . $db->qn('categoryname'));
		}

		return $query;
	}

	/**
	 * This method runs before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array     &$data   The data to save
	 * @param   FOFTable  &$table  The table to save the data to
	 *
	 * @return  boolean  Return false to prevent saving, true to allow it
	 */
	protected function onBeforeSave(&$data, &$table)
	{
		if ( ! parent::onBeforeSave($data, $table))
		{
			return false;
		}

		if (FOFPlatform::getInstance()->isFrontend())
		{
			if ($this->_isNewRecord)
			{
				$event_id = JFactory::getApplication()->getUserState('com_conferenceplus.eventId');
				$data['event_id'] = $event_id;

				$speakerId = $this->checkSpeaker($data);

				if ($speakerId === false)
				{
					$speakerId = $this->createNewSpeaker($data);
				}

				// At this point we should have a speaker id
				$data['speaker_listids'] = $speakerId;
			}
			else
			{
				$speaker_lists = $table->speaker_listids;
				$old_mainspeakers = explode(',', $speaker_lists);

				if ($old_mainspeakers[0] != $data['mainspeaker'])
				{
					$old_mainspeakers[0] = $data['mainspeaker'];
					$data['speaker_listids'] = implode(',', $old_mainspeakers);
				}
			}

			$data['modified'] = JFactory::getDate()->toSql();
		}

		return true;
	}

	/**
	 * This method creates a speakers
	 *
	 * @param   array  $data  The data we have
	 *
	 * @return  mixed  Return false if nothing is found, id of the speaker otherwise
	 */
	private function createNewSpeaker($data)
	{
		$speaker = FOFModel::getAnInstance('speakers', 'ConferenceplusModel');

		$fields = ['email', 'firstname', 'lastname', 'bio', 'event_id', 'imagefile'];

		foreach ($fields AS $field)
		{
			$speakerData[$field] = $data[$field];
		}

		$result = $speaker->save($speakerData);

		if ($result)
		{
			return $speaker->getId();
		}

		return false;
	}


	/**
	 * Allows data and form manipulation after preprocessing the form
	 *
	 * @param   FOFForm  &$form  A FOFForm object.
	 * @param   array    &$data  The data expected for the form.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return  void
	 */
	public function onAfterPreprocessForm(FOFForm &$form, &$data)
	{
		$formName = $this->getState('form_name');
		$params   = JComponentHelper::getParams('com_conferenceplus');

		if ($formName == 'form.default')
		{
			return;
		}

		if ($this->_isNewRecord)
		{
			return;
		}

		if (FOFPlatform::getInstance()->isBackend())
		{
			if ($params->get('sessionselectionmethod') == 1)
			{
				$form->setFieldAttribute('mainspeakerid', 'type', 'hidden');
			}

			$mainspeakerid = explode(",", $data['speaker_listids'])[0];

			$data['mainspeakerid'] = $mainspeakerid;
		}

		if (FOFPlatform::getInstance()->isFrontend())
		{
			$fields = array_keys($form->getFieldset());

			foreach ($fields as $field)
			{
				$type = $form->getFieldAttribute($field, 'type');

				if ($type == 'conferenceplus.mediaupload')
				{
					$form->setFieldAttribute($field, 'directory', $params->get('uploadfolder'));
				}
			}
		}
	}

	/**
	 * This method checks if we have already a speaker in our database
	 *
	 * @param   array  $data  The data we have
	 *
	 * @return  mixed  Return false if nothing is found, id of the speaker otherwise
	 */
	private function checkSpeaker($data)
	{
		// A match is: when email is the same or when first and last name are the same
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('conferenceplus_speaker_id')
				->from('#__conferenceplus_speakers')
				->where($db->qn('email') . ' = ' . $db->q($data['email']));

		$db->setQuery($query);

		$result = $db->loadResult();

		return empty($result) ? false : $result;
	}

	/**
	 * Check if a speaker has already a user account
	 *
	 * NOTE: this will only work after save
	 *
	 * @return mixed
	 */
	public function hasUserAccount()
	{
		if ( ! is_null($this->otable))
		{
			$id = explode(',', $this->otable->speaker_listids)[0];
			$speaker = FOFTable::getAnInstance('speakers');
			$speaker->load($id);

			return ! empty($speaker->userid);
		}

		return false;
	}
}
