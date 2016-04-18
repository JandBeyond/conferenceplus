<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2015 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

namespace Conferenceplus\Task;

/**
 * Class BaseEmail
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
abstract class BaseEmail extends BaseTask
{

	use TemplateTrait;

	/*
	 * Mailer
 	 */
	protected $mailer = null;

	/**
	 * class constructor
	 *
	 * @param   array  $config  configuration
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (array_key_exists('mailer', $config))
		{
			$this->mailer = $config['mailer'];
		}
		else
		{
			$this->mailer = \JFactory::getMailer();
		}
	}

	/**
	 * Do what we need to do
	 *
	 * @param   \JTable  $task  taskdata
	 *
	 * @return bool
	 */
	protected function doProcess($task)
	{
		$data = $task->processdata;

		$mailfrom = $this->application->get('mailfrom');
		$fromname = $this->application->get('fromname');

		$et = $this->getTemplate();

		if (trim($et->from_email) != "")
		{
			$mailfrom = $et->from_email;
		}

		if (trim($et->from_name) != "")
		{
			$fromname = $et->from_name;
		}

		return $this->mailer->sendMail(
									$mailfrom,
									$fromname,
									$data['email'],
									$this->getTextFromTemplate($data, 'subject'),
									$this->getTextFromTemplate($data, 'html'),
									true
		);
	}
}
