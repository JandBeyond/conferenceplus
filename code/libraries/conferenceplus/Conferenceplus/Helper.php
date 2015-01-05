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

	public static function logData($data, $type="ERROR")
	{
		jimport('joomla.log.log');
		$types = array(
			'EMERGENCY',
			'ALERT',
			'CRITICAL',
			'ERROR',
			'WARNING',
			'NOTICE',
			'INFO',
			'DEBUG'
		);

		if (!in_array($type, $types))
		{
			$type = 'EMERGENCY';
		}

		$loglevel = constant('JLog::' . $type);
		$date = date('Ymd');
		\JLog::addLogger(
			array(
				//Sets file name
				'text_file' => 'com_conferenceplus.' . $date . '.' . strtolower($type) . '.php'
			),
			$loglevel,
			//Chooses a category name
			'com_conferenceplus'
		);


		\JLog::add('- START LOGGING DATA -', $loglevel, 'com_conferenceplus');

		// we are trying to convert the data to an array
		$convertedData = array();

		if (is_array($data))
		{
			$convertedData = $data;
		}
		else
		{
			if (is_string($data) || is_bool($data) || is_integer($data) || is_float($data))
			{
				$convertedData = (array) $data;
			}
			else
			{
				if (is_object($data))
				{
					$convertedData = \JArrayHelper::fromObject($data);
				}

			}

		}

		self::doLog($convertedData, $loglevel);

		\JLog::add('- END LOGGING DATA -', $loglevel, 'com_conferenceplus');

	}

	private static function doLog($data, $loglevel, $level=0, $component='com_conferenceplus')
	{
		foreach ($data as $key => $value)
		{
			if (is_array($value))
			{
				if ($level < 50)
				{
					self::doLog($value, $loglevel, $level++);
				}
			}
			else
			{
				\JLog::add($key . '=' . $value, $loglevel, $component);
			}

		}

	}

}
