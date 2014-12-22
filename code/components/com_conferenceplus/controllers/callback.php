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
 * controller callback
 *
 * @package  Conferenceplus
 * @since    1.0
 */
class ConferenceplusControllerCallback extends FOFController
{
	/**
	 * That is what fof usally calls we need to find out what we have to do
	 *
	 * @return  false|void
	 */
	public function add()
	{
		$layout = $this->input->get('layout');


		if ($layout == 'register')
		{
			$this->registerUser();
		}



		// Load and reset the model
		$model = $this->getThisModel();
		$model->reset();

		// Set the layout to form, if it's not set in the URL

		if (is_null($this->layout))
		{
			$this->layout = 'form';
		}

		// Do I have a form?
		$model->setState('form_name', 'form.' . $this->layout);

		$item = $model->getItem();

		if (!($item instanceof FOFTable))
		{
			return false;
		}

		$formData = is_object($item) ? $item->getData() : array();
		$form = $model->getForm($formData);

		if ($form !== false)
		{
			$this->hasForm = true;
		}

		// Display
		$this->display(in_array('add', $this->cacheableTasks));
	}

	/**
	 * register a user
	 */
	private function registerUser()
	{
		$register = new Conferenceplus\Registration\UserRegistration;

		$register->register(array());
	}
}