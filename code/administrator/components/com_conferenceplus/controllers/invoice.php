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
 * controller invoice
 *
 * @package  Conferenceplus
 * @since    0.0.1
 */
class ConferenceplusControllerInvoice extends FOFController
{
	/**
	 * onBeforeRead runs before the read task
	 *
	 * @return  true in success
	 */
	protected function onBeforeRead()
	{
		$result = $this->isValid();

		return true;
	}


	/**
	 * onBeforeRead runs before the read task
	 *
	 * @return  true in success
	 */
	protected function onBeforeSave()
	{
		if ($this->input->getInt('id') != $this->input->getInt('conferenceplus_invoice_id'))
		{
			return false;
		}

		return $this->isValid();
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
			$Itemid = Conferenceplus\Route\Helper::getItemid('invoice');

			$redirect = "index.php?option=com_conferenceplus&view=invoice&layout=confirm&Itemid=$Itemid";

			$this->setRedirect($redirect);
		}

		return true;
	}

	/**
	 * Checking if a given id/hash combination is valid
	 *
	 * @return bool
	 */
	private function isValid()
	{
		// Check the combination of id and hash

		$id = $this->input->getInt('id', 0);

		$Itemid = Conferenceplus\Route\Helper::getItemid('invoice');

		$invalidRedirect = "index.php?option=com_conferenceplus&view=invoice&layout=invalid&Itemid=$Itemid";

		if ($id == 0)
		{
			$this->setRedirect($invalidRedirect);

			return false;
		}

		$model = $this->getModel();
		$table = $model->getTable();

		$result = $table->load($id);

		if ( ! $result)
		{
			$this->setRedirect($invalidRedirect);

			return false;
		}

		$hash = $this->input->get('h', '');

		if ($hash == '')
		{
			$this->setRedirect($invalidRedirect);

			return false;
		}

		if ($hash != $table->hash)
		{
			$this->setRedirect($invalidRedirect);

			return false;
		}

		return true;
	}
}
