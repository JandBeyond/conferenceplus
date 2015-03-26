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
 * @since    0.0.1
 */
class ConferenceplusControllerCallback extends FOFController
{
	/**
	 * Public constructor of the Controller class
	 *
	 * @param   array  $config  Optional configuration parameters
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->csrfProtection = false;

		$this->cacheableTasks = array();
	}


	/**
	 * Executes a given controller task. The onBefore<task> and onAfter<task>
	 * methods are called automatically if they exist.
	 *
	 * @param   string  $task  The task to execute, e.g. "browse"
	 *
	 * @throws  Exception   Exception thrown if the onBefore<task> returns false
	 *
	 * @return  null|bool  False on execution failure
	 */
	public function execute($task)
	{
		if ( ! in_array($task, array('read', 'cancel')))
		{
			$task = 'read';
			$this->input->set('task', 'read');
		}

		parent::execute($task);
	}

	/**
	 * callback handeling
	 *
	 * @param   bool  $cachable  is Cachebale
	 *
	 * @return  bool
	 *
	 * @throws Exception
	 */
	public function read($cachable = false)
	{
		$type = $this->input->get('type');

		switch ($type)
		{
			case 'register':
					$this->registerUser();

					// Display
					$this->display();

					return true;
				break;

			case 'payment':
					$model = FOFModel::getTmpInstance('Payments', 'ConferenceplusModel');
					$result = $model->runCallback($this->input->getCmd('paymentmethod', 'none'));

					echo $result ? 'OK' : 'FAILED';
				break;

			case 'recalcticketfee':
					$this->recalcticketfee();
				break;
		}

		JFactory::getApplication()->close();
	}

	/**
	 * ToDo: find out why added this, does that makes any sense
	 *
	 * @return void
	 */
	public function cancel()
	{
	}

	/**
	 * recalculate the ticket fee
	 *
	 * @return  void
	 */
	private function recalcticketfee()
	{
		$couponNo   = $this->input->get('coupon');
		$tickettype = $this->input->get('tickettype');

		$coupon = FOFModel::getAnInstance('coupons', 'ConferenceplusModel');

		$discount = $coupon->checkCouponAndTicket($couponNo, $tickettype);

		$returnType = $discount['returnType'];

		$msg = JText::_('COM_CONFERENCEPLUS_RETURNMESSAGE_COUPON_' . $returnType);

		switch ($returnType)
		{
			case '99' :
				$fee        = $discount['fee'];
				$discounted = $discount['discounted'];
				break;

			default:
				$fee        = $discount['fee'];
				$discounted = $fee;
				break;

		}

		$params   = JComponentHelper::getParams('com_conferenceplus');
		$currency = explode('|', $params->get('currency'))[0];

		$data['fee']   		= $currency . ' ' . number_format($fee / 100, 0, ',', '');
		$data['discounted'] = $currency . ' ' . number_format($discounted / 100, 0, ',', '');
		$data['msg']   		= $msg;
		$data['state'] 		= $returnType;

		$result = json_encode($data);

		echo $result;
	}

	/**
	 * register a user
	 *
	 * @return  void
	 */
	private function registerUser()
	{
		$register = new Conferenceplus\Registration\UserRegistration;

		$register->register(array());
	}
}