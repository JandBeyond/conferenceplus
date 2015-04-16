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

/**
 * Class ConferenceplusModelSpeakers
 *
 * @since  1.0
 */
class ConferenceplusModelSpeakers extends ConferenceplusModelDefault
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
			if (FOFPlatform::getInstance()->isFrontend())
			{
				$filterspeaker = $this->getState('parameters.menu')->get('filterspeaker', array());

				if ( ! empty($filterspeaker))
				{
					$inFilter = '(' . implode(',', $filterspeaker) . ')';
					$query->where($db->qn('conferenceplus_speaker_id') . ' in ' . $inFilter);
				}

				$query->clear('order');
				$query->order('firstname, lastname');
			}
		}

		return $query;
	}

	/**
	 * This method runs after an item has been gotten from the database in a read
	 * operation. You can modify it before it's returned to the MVC triad for
	 * further processing.
	 *
	 * @param   FOFTable  &$record  The table instance we fetched
	 *
	 * @return  void
	 */
	protected function onAfterGetItem(&$record)
	{
		parent::onAfterGetItem($record);

		if (FOFPlatform::getInstance()->isFrontend())
		{
			$record->assignedSessions = $this->getSessionsForASpeaker($record->conferenceplus_speaker_id);
		}
	}

	/**
	 * get the sessions for a speaker
	 *
	 * @param   integer  $speakerId  the speaker id
	 *
	 * @return array
	 */
	private function getSessionsForASpeaker($speakerId)
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$query->select('session.*')
			->from('#__conferenceplus_sessions AS session')
			->from('#__conferenceplus_sessions_to_rooms_slots AS rel')
			->select('room.name AS roomname')
			->from('#__conferenceplus_rooms AS room')
			->select('slot.name AS slotname, slot.stime AS stime, slot.etime AS etime')
			->from('#__conferenceplus_slots AS slot')
			->select('day.name AS dayname')
			->from('#__conferenceplus_days AS day')
			->where('speaker_listids = ' . (int) $speakerId)
			->where('session.conferenceplus_session_id = rel.session_id')
			->where('room.conferenceplus_room_id = rel.room_id')
			->where('slot.conferenceplus_slot_id = rel.slot_id')
			->where('slot.day_id = day.conferenceplus_day_id')
			->order('day.sdate, slot.stime');

		$db->setQuery($query);

		return $db->loadObjectList();
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

		if ( ! array_key_exists('userid', $data) || $data['userid'] == 0)
		{
			$db = $this->_db;
			$query = $db->getQuery(true);
			$query->select('*')
					->from('#__users')
					->where($db->qn('email') . ' = ' . $db->q($data['email']));

			$db->setQuery($query);

			$user = $db->loadObject();

			if (!empty($user))
			{
				$data['userid'] = $user->id;
			}
		}

		if (array_key_exists('imagefile', $data) && ! strpos($data['imagefile'], '/'))
		{
			$data['imagefile'] = $this->extendFilenameWithPath($data['imagefile']);
		}

		return true;
	}

	/**
	 * This method runs after the data is saved to the $table.
	 *
	 * @param   FOFTable  &$table  The table which was saved
	 *
	 * @return  boolean
	 */
	protected function onAfterSave(&$table)
	{
		if ( ! parent::onAfterSave($table))
		{
			return false;
		}

		if ($this->_isNewRecord && $table->userid == 0)
		{
			$task = new Conferenceplus\Task\ConfirmEmail;

			if ( ! $task->create($table))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * adds the uploadpath to a filename
	 *
	 * @param   string  $filename  The filename
	 *
	 * @return  string
	 */
	private function extendFilenameWithPath($filename)
	{
		if (strpos($filename, '/'))
		{
			return $filename;
		}

		$conf = $this->getComponentConfiguration();
		$path = 'images/' . $conf->get('uploadfolder') . '/';
		$filename = $path . $filename;

		return $filename;
	}
}
