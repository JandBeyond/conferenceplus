<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

namespace Conferenceplus\Route;

// No direct access
defined('_JEXEC') or die;

/**
 * helper class
 **/
class Helper 
{
	/**
	 * get the item id
	 *
	 * @param   string  $type  type
	 *
	 * @return int             itemid
	 */
	public static function getItemid($type="default")
	{
		$result = null;
		$app    = \JFactory::getApplication();

		switch ($type)
		{
			case "call4papers":
				$result = $app->getMenu()
					->getItems('link', 'index.php?option=com_conferenceplus&view=sessions&layout=call4papers', true);
				break;
			case "invoice":
				$result = $app->getMenu()
					->getItems('link', 'index.php?option=com_conferenceplus&view=invoice', true);
				break;

		}

		$result = is_object($result) ? $result->id : $app->input->get('Itemid', 0, 'uint');

		return $result;
	}
}
