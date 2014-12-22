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
 * Class ConfirmEmail
 *
 * @package  Conferenceplus\Task
 * @since    1.0
 */
class ConfirmEmail extends Base
{
	/**
	 * Create a task
	 *
	 * @param   string  $firstname  the firstname
	 * @param   string  $lastname   the lastname
	 * @param   string  $email      the email
	 * @param   string  $event_id   the event id
	 *
	 * @return  boolean true on success
	 */
	public function create($firstname, $lastname, $email, $event_id)
	{
		$taskRepository = new Repository;

		$task = $taskRepository->getItem();

		$data = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email];

		$task->processdata = json_encode($data);
		$task->event_id = $event_id;

		return $task->store();
	}
}