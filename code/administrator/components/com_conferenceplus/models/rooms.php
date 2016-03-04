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

class ConferenceplusModelRooms extends ConferenceplusModelDefault
{
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
        // Load the filters.
        $this->setState('filter.event_id', $this->getUserStateFromRequest('filter.rooms.event_id', 'eventname', ''));
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
            $query->select('e.name AS eventname');

            // Join events
            $query->join('INNER', '#__conferenceplus_events AS e ON e.conferenceplus_event_id = room.event_id');

            $query->where($db->qn('e.enabled') . ' = 1');

            // Filter
            $filterevent_id = $this->getState('filter.event_id');

            if ( ! empty($filterevent_id))
            {
                $query->where($db->qn('e.conferenceplus_event_id') . ' = ' . $db->q($filterevent_id));
            }
        }

        return $query;
    }
}
