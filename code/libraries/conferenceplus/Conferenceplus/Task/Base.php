<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2015 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

namespace Conferenceplus\Task;

/**
 * interface Base
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
interface Base
{
	/**
	 * Create a task
	 *
	 * @param   array  $data  the data to process
	 *
	 * @return bool
	 */
	public function create($data);

	/**
	 * Process the task
	 *
	 * @param   integer  $taskId  the id of the task
	 *
	 * @return bool|void
	 */
	public function process($taskId);

}
