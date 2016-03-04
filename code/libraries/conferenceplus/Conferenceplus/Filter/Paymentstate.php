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
 * Class Paymentstate
 * @package  Conferenceplus\Helpers
 * @since   1.0
 */
class Paymentstate
{
    public static function getStates()
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('DISTINCT state')
            ->from('#__conferenceplus_payments')
            ->where('state <> ""');

        $db->setQuery($query);

        $results = $db->loadColumn();

        $options = [];

        foreach($results as $result)
        {
            $options[] = \JHtml::_('select.option', $result, $result);
        }

        return $options;
    }
}
