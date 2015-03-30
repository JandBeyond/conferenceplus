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
 * Class SendTicket
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
class SendTicket extends BaseEmail
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
		$filename = $task->processdata['filename'];
		$name = 'Ticket';
		$this->mailer->addAttachment($filename, $name);

		return false;
	}
}
