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

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->csrfProtection = false;

		$this->cacheableTasks = array();
	}


	public function execute($task)
	{
		if ( ! in_array($task, array('read', 'cancel')))
		{
			$task = 'read';
			$this->input->set('task','read');
		}

		parent::execute($task);
	}

	public function read($cachable = false)
	{
		$type = $this->input->get('type');

		if ($type == 'register')
		{
			$this->registerUser();

			// Display
			$this->display();

			return true;
		}

		if ($type == 'payment')
		{
			$model = FOFModel::getTmpInstance('Payments', 'ConferenceplusModel');
			$result = $model->runCallback($this->input->getCmd('paymentmethod','none'));

			echo $result ? 'OK' : 'FAILED';
		}

		JFactory::getApplication()->close();
	}

	public function cancel()
	{

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