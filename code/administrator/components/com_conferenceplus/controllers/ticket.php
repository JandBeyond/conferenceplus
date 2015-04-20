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
 * controller ticket
 *
 * @package  Conferenceplus
 * @since    1.0
 */
class ConferenceplusControllerTicket extends FOFController
{
	/**
	 * onBeforeAdd runs before the add task
	 *
	 * @return  true in success
	 */
	protected function onBeforeAdd()
	{
		$ticketypeId = $this->input->get('tickettype');

		$tickettype = FOFModel::getAnInstance('tickettypes', 'ConferenceplusModel');

		if ($tickettype->isValid($ticketypeId))
		{
			JFactory::getApplication()->setUserState('com_conferenceplus.tickettypeId', $ticketypeId);

			return true;
		}

		JFactory::getApplication()->setUserState('com_conferenceplus.tickettypeId', null);

		return false;
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
			$model = $this->getThisModel();

			// We need to check if save was successful
			if ( ! empty($model->_errors))
			{
				return true;
			}

			$ticketId = $model->getId();

			$model->resetSavedState();

			JFactory::getApplication()->setUserState('com_conferenceplus.tickettypeId', null);
			JFactory::getApplication()->setUserState('com_conferenceplus.ticketId', $ticketId);

			$Itemid = Conferenceplus\Route\Helper::getItemid();

			$this->setRedirect("index.php?option=com_conferenceplus&view=payment&layout=buy&Itemid=$Itemid");
		}

		return true;
	}
}
