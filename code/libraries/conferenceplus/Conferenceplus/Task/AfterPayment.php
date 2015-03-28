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
class AfterPayment extends BaseTask
{

	/**
	 * run before the task will be created and allow to modify
	 * the data that will be saved as processdata
	 *
	 * @param   mixed  &$data  the paymentdata
	 *
	 * @return  mixed  same we got
	 */
	public function onBeforeCreate(&$data)
	{
		$data = ['payment_id'  => $data->conferenceplus_payment_id,
					'processdata' => $data->processdata,
					'processkey'  => $data->processkey ];

		return true;
	}

	/**
	 * run before the task is executed
	 *
	 * @param   mixed  $task  task data
	 *
	 * @return bool
	 */
	public function onBeforeDoProcess($task)
	{
		// Check if payment state is C == completed
		$state = $task->processdata['processdata']['paymentprovider']['state'];

		if ($state != 'C')
		{
			return false;
		}

		return true;
	}

	/**
	 * Do the work
	 *
	 * @param   Repository  $task  task data
	 *
	 * @return bool
	 */
	protected function doProcess($task)
	{
		// 1) Create the InvoiceHandle task
		$ih = new InvoiceHandling($this->config);
		$taskCreateResult1  = $ih->create($task->processdata);

		// 2) Create Attendee(s)
		$ca = new CreateAttendees($this->config);
		$taskCreateResult2  = $ca->create($task->processdata);

		return $taskCreateResult1 && $taskCreateResult2;
	}
}
