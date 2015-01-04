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
 * Class AfterPayment
 *
 * @package  Conferenceplus\Task
 * @since    1.0
 */
class AfterPayment extends Base
{
	/**
	 * Create a task
	 *
	 * @param   FofTable  $firstname  the firstname
	 *
	 * @return  boolean true on success
	 */
	public function create($paymentTable)
	{
		$taskRepository = new Repository;

		$task = $taskRepository->getItem();

		$data = ['payment_id'  => $paymentTable->conferenceplus_payment_id,
		         'processdata' => $paymentTable->processdata,
				 'processkey'  => $paymentTable->processkey ];

		$task->processdata = json_encode($data);
		$task->name        = 'AfterPayment';

		return $task->store();
	}
}