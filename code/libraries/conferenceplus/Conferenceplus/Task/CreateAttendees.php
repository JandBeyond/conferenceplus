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

namespace Conferenceplus\Task;

/**
 * Class CreateAttendees
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
class CreateAttendees extends BaseTask
{
	/**
	 * run before the task is executed
	 *
	 * @param   mixed  $task  task data
	 *
	 * @return bool
	 */
	public function onBeforeDoProcess($task)
	{
		// We need to implement the process

		return false;
	}
}
