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
 * controller Awardnomination
 *
 * @package  Conferenceplus
 * @since    0.1.0
 */
class ConferenceplusControllerAwardnomination extends FOFController
{
	/**
	 * onAfterSave redirects after save to the thank you page
	 *
	 * @return  mixed  true on success, exeception if something goes wrong
	 */
	protected function onAfterSave()
	{
		if (FOFPlatform::getInstance()->isFrontend())
		{
			$model = $this->getThisModel();
			$model->resetSavedState();

			$Itemid = Conferenceplus\Route\Helper::getItemid();

			$this->setRedirect("index.php?option=com_conferenceplus&view=awardnomination&layout=thankyou&Itemid=$Itemid");
		}

		return true;
	}
}
