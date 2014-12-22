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
class ConferenceplusControllerTicket extends FOFController
{
	/**
	 * onBeforeAdd runs before the add task
	 *
	 * @return  true in success
	 */
	protected function onBeforeAdd()
	{
		JFactory::getApplication()->setUserState('com_conferenceplus.ticketId', $this->input->get('tickettype'));

		return true;
	}
}
