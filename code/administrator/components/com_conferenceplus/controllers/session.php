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
 * controller session
 *
 * @package  Conferenceplus
 * @since    1.0
 */
class ConferenceplusControllerSession extends FOFController
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

			$hasUserAccount = $model->hasUserAccount();

			$sessionId = $model->getId();

			$model->resetSavedState();

			$Itemid = Conferenceplus\Route\Helper::getItemid('call4papers');
			$layout = 'thankyou';

			if ( ! $hasUserAccount)
			{
				$layout = 'offeruseraccount';
				JFactory::getApplication()->setUserState('com_conferenceplus.sessionId', $sessionId);
			}

			$this->setRedirect("index.php?option=com_conferenceplus&view=session&layout=$layout&Itemid=$Itemid");
		}

		return true;
	}

	/**
	 * onAfterSave redirects after save to the thank you page
	 *
	 * @return  true in success
	 */
	protected function onAfterAdd()
	{
		JFactory::getApplication()->setUserState('com_conferenceplus.eventId', $this->input->get('event_id'));

		return true;
	}
}
