<?php
/**
 * Conferenceplus
 *
 * @package   Conferenceplus
 * @author    Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright 2014 JandBeyond
 * @license   GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die;

/**
 * controller session
 *
 * @package Conferenceplus
 * @since   1.0
 */
class ConferenceplusControllerSession extends FOFController
{

	private $botDetected = false;

	/**
	 * Save the incoming data and then return to the Browse task
	 *
	 * @return  bool
	 */
	public function save()
	{
		$params 		= JComponentHelper::getParams('com_conferenceplus');
		$starttime = JFactory::getApplication()->getUserState('com_conferenceplus.starttime');
		$now = time();
		$delay = (int) $params->get('delay', 5);

		$this->botDetected = $starttime + $delay > $now;

		if ($this->botDetected)
		{
			// some submitted the form to fast, seems a to be a bot
			$this->setRedirect('index.php', JText::_('COM_CONFERENCEPLUS_BOT_DETECTED'));

			return true;
		}

		return parent::save();
	}


	/**
	 * onAfterSave redirects after save to the thank you page
	 *
	 * @return  mixed  true on success, exeception if something goes wrong
	 */
	protected function onAfterSave()
	{
		if (FOFPlatform::getInstance()->isFrontend() && ! $this->botDetected)
		{
			$model = $this->getThisModel();

			// we need to check if save was successful
			if ( ! empty($model->_errors))
			{
				return true;
			}

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
	 * run before the add function is executed
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function onBeforeAdd()
	{
		JFactory::getApplication()->setUserState('com_conferenceplus.starttime', time());

		return true;
	}

	/**
	 * onAfterSave
	 *
	 * @return  true in success
	 */
	protected function onAfterAdd()
	{
		JFactory::getApplication()->setUserState('com_conferenceplus.eventId', $this->input->get('event_id'));

		return true;
	}
}
