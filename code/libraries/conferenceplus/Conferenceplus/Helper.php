<?php 
/**
 * conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 * @package    conferenceplus
 *
 * @copyright  JandBeyond
 * @license    GNU General Public License version 2 or later
**/

namespace Conferenceplus;

// No direct access
defined('_JEXEC') or die;

/**
 * helper class
 **/
class Helper 
{
	/**
	 * Checks if we are running a Joomla-Version greater or equal 3.0
	 *
	 * @return  boolean
	 */
	public static function isVersion3()
	{
		return version_compare(JVERSION, '3.0', 'ge');
	}

	/**
	 * checks if a lang tag exists
	 *
	 * @param   string  $key      The Key
	 * @param   string  $var      The variable part of the key
	 * @param   string  $default  default value when key doesn't exists, when null then return value is the key
	 * @param   string  $prefix   A optional prefix
	 *
	 * @return  string           The Key
	 */
	public static function checkLangTag($key, $var, $default=null, $prefix='_TYPE')
	{
		$lang = \JFactory::getLanguage();

		if ($lang->hasKey($key . $prefix . $var))
		{
			$key = $key . $prefix . $var;
		}
		else
		{
			if (!is_null($default))
			{
				$key = $default;
			}
		}

		return $key;
	}
}
