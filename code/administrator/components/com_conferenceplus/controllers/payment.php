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

// No direct access
defined('_JEXEC') or die;

/**
 * controller payment
 *
 * @package  Conferenceplus
 * @since    1.0
 */
class ConferenceplusControllerPayment extends FOFController
{

	private $isFreeTicket = false;

	/**
	 * onBeforeAdd runs before the add task
	 *
	 * @return  true in success
	 */
	protected function onBeforeAdd()
	{
		$ticketId = JFactory::getApplication()->getUserState('com_conferenceplus.ticketId');

		if (empty($ticketId))
		{
			return false;
		}

		return true;
	}

	/**
	 * Runs before the save task is executed
	 *
	 * @return  boolean  True to allow the method to run
	 */
	protected function onBeforeSave()
	{
		if ( ! parent::onBeforeSave())
		{
			return false;
		}

		$ticketId = JFactory::getApplication()->getUserState('com_conferenceplus.ticketId');

		if (empty($ticketId))
		{
			return false;
		}

		$ticketTable = FOFTable::getAnInstance('tickets');
		$result = $ticketTable->load($ticketId);

		if ( ! $result)
		{
			return false;
		}

		if ($ticketTable->payment != 0)
		{
			return false;
		}

		// Coupon check
		$tickettypeId = $ticketTable->tickettype_id;

		$tickettypeTable = FOFTable::getAnInstance('tickettypes');
		$tickettypeTable->load($tickettypeId);

		$ticketTable->processdata = json_decode($ticketTable->processdata, true);

		if ($ticketTable->processdata['coupon'] != "")
		{
			$coupon = FOFModel::getAnInstance('coupons', 'ConferenceplusModel');
			$fee = $coupon->getTicketDiscountedFee($ticketTable, $tickettypeTable->fee);
		}

		if ($fee != 0)
		{
			return false;
		}

		$this->isFreeTicket = true;

		return true;
	}

	/**
	 * Execute something before applySave is called. Return false to prevent
	 * applySave from executing.
	 *
	 * @param   array  &$data  The data upon which applySave will act
	 *
	 * @return  boolean  True to allow applySave to run
	 */
	protected function onBeforeApplySave(&$data)
	{
		// We need to prepare the data so that we don't loose information
		$model    = $this->getThisModel();
		$ticketId = JFactory::getApplication()->getUserState('com_conferenceplus.ticketId');

		$ticketData = $model->getTicketData($ticketId);

		// Save payment
		$saveData = [];
		$saveData['processkey']  = 'FREETICKET' . time();
		$fakePluginData          = ['processkey' => 'INTERNAL', 'state' => 'C'];
		$saveData['processdata'] = $model->prepareProcessData($fakePluginData, $ticketData, []);
		$saveData['name']        = $ticketData->ticket->firstname . ' ' .
									$ticketData->ticket->lastname . ', ' .
									$ticketData->ticket->email;

		$data = $saveData;

		return true;
	}

	/**
	 * onAfterSave redirects after save to the buy page
	 *
	 * @return  mixed  true on success, exeception if something goes wrong
	 */
	protected function onAfterSave()
	{
		if (FOFPlatform::getInstance()->isFrontend())
		{
			$Itemid = Conferenceplus\Route\Helper::getItemid();
			$url    = "index.php?option=com_conferenceplus&view=payment&layout=confirm&Itemid=$Itemid";
			$url   .= $this->isFreeTicket ? '&ft=1' : '';

			$this->setRedirect($url);
		}

		return true;
	}
}
