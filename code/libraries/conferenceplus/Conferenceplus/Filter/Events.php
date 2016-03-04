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

namespace Conferenceplus\Filter;

// No direct access
defined('_JEXEC') or die;


/**
 * Class Events
 * @package  Conferenceplus\Filter
 * @since   1.0
 */
class Events
{
    /**
     *
     * @return array
     */
    public static function getItems()
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('conferenceplus_event_id as value, name as text')
            ->from('#__conferenceplus_events')
            ->where('enabled = 1')
            ->order('name');

        $db->setQuery($query);

        $results = $db->loadObjectList();

        $options = [];

        foreach($results as $result)
        {
            $options[] = \JHtml::_('select.option', $result->value, $result->text);
        }

        return $options;
    }
}
