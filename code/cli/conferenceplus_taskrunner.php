<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus-cli
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2015 JandBeyond
 * @license    GNU General Public License version 2 or later
 */

// Set flag that this is a parent file.
const _JEXEC = 1;

error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 1);

// Load system defines
if (file_exists(dirname(__DIR__) . '/defines.php'))
{
	require_once dirname(__DIR__) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(__DIR__));
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_LIBRARIES . '/cms.php';

// Load the configuration
require_once JPATH_CONFIGURATION . '/configuration.php';

// Load the JApplicationCli class
JLoader::import('joomla.application.cli');
JLoader::import('joomla.application.component.helper');
JLoader::import('cms.component.helper');

// Include the library
JLoader::import('conferenceplus.library');

Conferenceplus\Helper::logData('START TaskRunner', 'NOTICE');

/**
 * This script will fetch task in the task table and trigger the task,
 * so that the task gets executed and can do what they are supposed to do
 *
 * @since  0.0.1
 */
class ConferenceplusTaskrunner extends JApplicationCli
{
	/**
	 * Entry point for the script
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		$cpconf = JComponentHelper::getParams('com_conferenceplus');

		$config['cpconf']      = $cpconf;
		$config['application'] = $this;

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('#__conferenceplus_tasks')
			->where('result = 0 AND name not in ("ConfirmEmail", "CreateAttendees") AND name != ""');

		$db->setQuery($query, 0, 100);

		$tasks = $db->loadObjectList();

		$result = true;

		foreach ($tasks as $task)
		{
			$class  = "\\Conferenceplus\\Task\\" . $task->name;
			$worker = new $class($config);

			$workerResult = $worker->process($task->conferenceplus_task_id);

			echo 'Task: ' . $task->name . '(' . $task->conferenceplus_task_id . ') == ';
			echo $workerResult ? 'Success' : 'Failed';
			echo "\n";

			$result &= $workerResult;
		}
	}
}

JApplicationCli::getInstance('ConferenceplusTaskrunner')->execute();

Conferenceplus\Helper::logData('END TaskRunner', 'NOTICE');
