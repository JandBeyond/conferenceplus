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

class ConferenceplusModelSessiontypes extends ConferenceplusModelDefault
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
            $query->select('e.name AS eventname');

            // Join events
            $query->join('INNER', '#__conferenceplus_events AS e ON e.conferenceplus_event_id = st.event_id');

            $query->where($db->qn('e.enabled') . ' = 1');
        }

        return $query;
    }

}
