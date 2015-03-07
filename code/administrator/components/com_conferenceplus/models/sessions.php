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
 * Class ConferenceplusModelSessions
 *
 * @since  0.0
 */
class ConferenceplusModelSessions extends ConferenceplusModelDefault
{

	protected $default_behaviors = array('enabled');

	protected $programme = array();

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 * @since   12.2
	 */
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Load the filters.
		$this->setState('filter.title', $this->getUserStateFromRequest('filter.title', 'title', ''));

	}

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

			if (FOFPlatform::getInstance()->isFrontend())
			{
				// Join rooms/slots
				$query->join('INNER', '#__conferenceplus_sessions_to_rooms_slots AS relation_rs ON relation_rs.session_id = session.conferenceplus_session_id')
					->join('INNER', '#__conferenceplus_rooms AS r ON relation_rs.room_id = r.conferenceplus_room_id')
					->join('INNER', '#__conferenceplus_slots AS s ON relation_rs.slot_id = s.conferenceplus_slot_id')
					->join('INNER', '#__conferenceplus_days AS d ON s.day_id = d.conferenceplus_day_id')
					->select($db->qn('r.conferenceplus_room_id') . ' AS ' . $db->qn('room_id'))
					->select($db->qn('r.name') . ' AS ' . $db->qn('roomname'))
					->select($db->qn('r.description') . ' AS ' . $db->qn('roomdesciption'))
					->select($db->qn('s.conferenceplus_slot_id') . ' AS ' . $db->qn('slot_id'))
					->select($db->qn('s.name') . ' AS ' . $db->qn('slotname'))
					->select($db->qn('s.description') . ' AS ' . $db->qn('slotdesciption'))
					->select($db->qn('s.stime') . ' AS ' . $db->qn('slotstime'))
					->select($db->qn('s.stimeset') . ' AS ' . $db->qn('slotstimeset'))
					->select($db->qn('s.etime') . ' AS ' . $db->qn('slotetime'))
					->select($db->qn('s.etimeset') . ' AS ' . $db->qn('slotetimeset'))
					->select($db->qn('d.name') . ' AS ' . $db->qn('dayname'))
					->where($db->qn('s.enabled') . ' = 1')
					->where($db->qn('d.enabled') . ' = 1')
					->where($db->qn('r.enabled') . ' = 1')
					->where($db->qn('session.event_id') . ' =' . $db->qn('d.event_id'))
					->order('d.sdate, s.stime');
			}

			// Filter
			$filter = $this->getState('filter.title');

			if ( ! empty($filter))
			{
				$qFilter = $db->q('%' . $filter . '%');
				$query->where('( ' . $db->qn('session.title') . ' like ' . $qFilter . ') OR ('
									. $db->qn('session.description') . ' like ' . $qFilter . ') OR ('
									. $db->qn('c.title') . ' like ' . $qFilter . ')');
			}
		}

		return $query;
	}

	/**
	 * Adding more useful fields to the resultArray
	 *
	 * @param   array  &$resultArray  An array of objects, each row representing a record
	 *
	 * @return  void
	 */
	protected function onProcessList(&$resultArray)
	{
		if (FOFPlatform::getInstance()->isFrontend())
		{
			$speakers = $this->getAllSpeakers();

			foreach ($resultArray AS &$result)
			{
				$speakerIds 	  = explode(',', $result->speaker_listids);
				$assignedSpeakers = [];

				if ( ! empty($speakerIds))
				{
					foreach ($speakerIds AS $sid)
					{
						if (array_key_exists($sid, $speakers))
						{
							$assignedSpeakers[] = $speakers[$sid];
						}
					}

					$result->assignedSpeakers = $assignedSpeakers;
				}
			}
		}
	}

	/**
	 * Get the programme so that displaying it is simpler
	 *
	 * @param   integer  $eventId  The event id
	 *
	 * @return array
	 */
	public function getProgramme($eventId)
	{
		if (empty($this->programme))
		{
			$result = [];

			$rooms  = $this->getRooms($eventId);
			$slots  = $this->getSlots($eventId, true);

			// Items are sorted be slot time
			if ( ! empty($slots))
			{
				$dayname = $slots[0]->dayname;
				$day = [];

				$tba = new stdClass;
				$tba->tba = 'TBA';

				foreach ($slots AS $slot)
				{
					if ($slot->dayname != $dayname)
					{
						$dayname  = $slot->dayname;
						$result[] = $day;
						$day      = [];
					}

					$sessions = $this->getSessionBySlotId($slot->conferenceplus_slot_id);

					$sessionsOrdered = [];

					// We only need to check all sessions when slottype less 2, above are keynotes and such
					if ($slot->slottype < 2)
					{
						foreach ($rooms AS $room)
						{
							$foundSession = false;

							foreach ($sessions AS $session)
							{
								if ($room->conferenceplus_room_id == $session->room_id)
								{
									$sessionsOrdered[] = $session;
									$foundSession = true;
								}
							}

							if ( ! $foundSession)
							{
								$sessionsOrdered[] = $tba;
							}
						}
					}

					// This is a keynote so when have a speaker and a session we use the first array element
					if ($slot->slottype == 2 && ! empty($sessions))
					{
						$sessionsOrdered[] = $sessions[0];
					}

					$slot->sessionsOrdered = $sessionsOrdered;

					$day[] = $slot;
				}

				$result[] = $day;

				$this->programme = $result;
			}
		}

		return $this->programme;
	}

	/**
	 * Get a session by slotId
	 *
	 * @param   integer  $slotId  the slot id
	 *
	 * @return array
	 */
	public function getSessionBySlotId($slotId)
	{
		$items  = $this->getItemList();
		$result = [];

		if (! empty($items) && 0 != (int) $slotId)
		{
			foreach ($items as $item)
			{
				if ($item->slot_id == $slotId)
				{
					$result[] = $item;
				}
			}
		}

		return $result;
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

		if (FOFPlatform::getInstance()->isBackend())
		{
			if ( ! is_null($record))
			{
				$event_id = $record->event_id;

				$record->assignedRoomsSlots = $this->getAssignedRoomsSlots($record->conferenceplus_session_id);

				$record->rooms = $this->getRooms($event_id);
				$record->slots = $this->getSlots($event_id);
			}
		}

		if (FOFPlatform::getInstance()->isFrontend())
		{
			$speakerIds = explode(',', $record->speaker_listids);
			$assignedSpeakers = [];

			if ( ! empty($speakerIds))
			{
				$speakers = $this->getAllSpeakers();

				foreach ($speakerIds AS $sid)
				{
					if (array_key_exists($sid, $speakers))
					{
						$assignedSpeakers[] = $speakers[$sid];
					}
				}
			}

			$record->assignedSpeakers = $assignedSpeakers;
		}
	}

	/**
	 * Get assigned rooms for a session
	 *
	 * @param   integer  $sessionId  the sessionid
	 *
	 * @return  mixed
	 */
	private function getAssignedRoomsSlots($sessionId)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('CONCAT(room_id, "_", slot_id)')
			->from('#__conferenceplus_sessions_to_rooms_slots')
			->where($db->qn('session_id') . ' = ' . $db->q($sessionId));

		$db->setQuery($query);
		$result = $db->loadColumn();

		return $result;
	}

	/**
	 * Get slots for an event
	 *
	 * @param   integer  $eventId  the event id
	 * @param   bool     $all      if we need all slot set to true
	 *
	 * @return  mixed
	 */
	public function getSlots($eventId, $all=false)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('s.*,d.name as dayname')
			->from('#__conferenceplus_slots as s')
			->from('#__conferenceplus_days as d')
			->where('d.conferenceplus_day_id = s.day_id')
			->where('d.event_id' . ' = ' . $db->q($eventId))
			->where($db->qn('s.enabled') . ' = 1')
			->where($db->qn('d.enabled') . ' = 1')
			->order('d.sdate ASC');

		if (! $all)
		{
			$query->where($db->qn('s.slottype') . ' < 3');
		}

		$db->setQuery($query);
		$slots = $db->loadObjectList();

		return $slots;
	}

	/**
	 * Get rooms for an event
	 *
	 * @param   integer  $eventId  the event id
	 *
	 * @return  mixed
	 */
	public function getRooms($eventId)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('#__conferenceplus_rooms')
			->where($db->qn('event_id') . ' = ' . $db->q($eventId))
			->where($db->qn('enabled') . ' = 1');

		$db->setQuery($query);
		$rooms = $db->loadObjectList();

		return $rooms;
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
			return $this->onBeforeSaveFrontend($data, $table);
		}

		return $this->onBeforeSaveBackend($data, $table);
	}

	/**
	 * This method runs in frontend before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array     &$data   The data to save
	 * @param   FOFTable  &$table  The table to save the data to
	 *
	 * @return  boolean  Return false to prevent saving, true to allow it
	 */
	protected function onBeforeSaveBackend(&$data, &$table)
	{
		$speaker_lists = $table->speaker_listids;

		$old_mainspeakers = explode(',', $speaker_lists);

		if ($old_mainspeakers[0] != $data['mainspeakerid'])
		{
			$old_mainspeakers[0] = $data['mainspeakerid'];
			$data['speaker_listids'] = implode(',', $old_mainspeakers);
		}

		$data['modified'] = JFactory::getDate()->toSql();

		return true;
	}

	/**
	 * This method runs in frontend before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array     &$data   The data to save
	 * @param   FOFTable  &$table  The table to save the data to
	 *
	 * @return  boolean  Return false to prevent saving, true to allow it
	 */
	protected function onBeforeSaveFrontend(&$data, &$table)
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

		$data['modified'] = JFactory::getDate()->toSql();

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

		if (FOFPlatform::getInstance()->isFrontend())
		{
			return true;
		}

		$result = $this->assignSessionsToRoomSlots($table->event_id, $table->conferenceplus_session_id);

		return true;
	}

	/**
	 * Assign a session to rooms
	 *
	 * @param   integer  $eventId    the id of the event data
	 * @param   integer  $sessionId  the session id
	 *
	 * @return bool
	 */
	private function assignSessionsToRoomSlots($eventId, $sessionId)
	{
		$input = $this->input;

		$rooms = $this->getRooms($eventId);
		$slots = $this->getSlots($eventId);

		$savedAssignedRoomsSlots = $this->getAssignedRoomsSlots($sessionId);

		$assignedRoomsSlot = [];

		foreach ($slots as $slot)
		{
			foreach ($rooms as $room)
			{
				$tag = 'assignment_' . $room->conferenceplus_room_id . '_' . $slot->conferenceplus_slot_id;

				if ($input->get($tag, 0) != 0 )
				{
					$assignedRoomsSlot[] = $room->conferenceplus_room_id . '_' . $slot->conferenceplus_slot_id;
				}
			}
		}

		$this->assignRoomsSlots(array_diff($assignedRoomsSlot, $savedAssignedRoomsSlots), $sessionId);
		$this->unassignRoomsSlots(array_diff($savedAssignedRoomsSlots, $assignedRoomsSlot), $sessionId);

		return true;
	}


	/**
	 * Assign a session to rooms/slots
	 *
	 * @param   array    $items      the data
	 * @param   integer  $sessionId  the session id
	 *
	 * @return bool
	 */
	private function assignRoomsSlots($items, $sessionId)
	{
		if ( ! empty($items))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$query->insert('#__conferenceplus_sessions_to_rooms_slots')
				->columns(
					array(
						$db->quoteName('session_id'),
						$db->quoteName('room_id'),
						$db->quoteName('slot_id')
					)
				);

			foreach ($items as $item)
			{
				list($room, $slot) = explode('_', $item);
				$query->values(
					(int) $sessionId . ', ' .
					(int) $room . ', ' .
					(int) $slot
				);
			}

			$db->setQuery($query);

			if ( ! $db->execute())
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Unassign a session to rooms/slots
	 *
	 * @param   array    $items      the data
	 * @param   integer  $sessionId  the session id
	 *
	 * @return bool
	 */
	private function unassignRoomsSlots($items, $sessionId)
	{
		$result = true;

		if ( ! empty($items))
		{
			foreach ($items AS $item)
			{
				list($room, $slot) = explode('_', $item);

				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->delete('#__conferenceplus_sessions_to_rooms_slots')
					->where('session_id =' . (int) $sessionId)
					->where('room_id = ' . (int) $room)
					->where('slot_id = ' . (int) $slot);

				$db->setQuery($query);

				if ( ! $db->execute())
				{
					$result = false;
				}
			}
		}

		return $result;
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

	/**
	 * Get a list of all speakers
	 *
	 * @return mixed
	 */
	private function getAllSpeakers()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__conferenceplus_speakers')
			->where($db->qn('enabled') . ' = 1');

		$db->setQuery($query);

		$result = $db->loadAssocList('conferenceplus_speaker_id');

		return $result;
	}
}
