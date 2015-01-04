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
	 * onAfterSave redirects after save to the buy page
	 *
	 * @return  mixed  true on success, exeception if something goes wrong
	 */
	protected function onAfterSave()
	{
		if (FOFPlatform::getInstance()->isFrontend())
		{
			$Itemid = Conferenceplus\Route\Helper::getItemid();

			$this->setRedirect("index.php?option=com_conferenceplus&view=payment&layout=confirm&Itemid=$Itemid");
		}

		return true;
	}

}
