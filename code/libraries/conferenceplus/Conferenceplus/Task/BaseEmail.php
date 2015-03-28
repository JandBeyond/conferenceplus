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
	/*
	 * holds the email template
	 */
	public $emailTemplate = null;

	/*
	 * array of placeholders
	 */
	public $placeHolders = array('firstname' => 'firstname', 'lastname' => 'lastname', 'created' => 'created');

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
		$data = $this->decodeProcessdata($task);

		$mailfrom = $this->application->get('mailfrom');
		$fromname = $this->application->get('fromname');

		$et = $this->getEmailTemplate();

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
									$data['contactemail'],
									$this->getTextFromTemplate($data, 'title'),
									$this->getTextFromTemplate($data, 'html'),
									true
		);
	}

	/**
	 * get the mailtext for the email
	 *
	 * @param   array   $data   the data
	 * @param   string  $field  the data
	 *
	 * @return string
	 */
	protected function getTextFromTemplate($data, $field = 'title')
	{
		$text = '';
		$et   = $this->getEmailTemplate();

		if ( ! empty($et))
		{
			$text = $this->replacePlaceHolders($et->$field, $data);
		}

		return $text;
	}

	/**
	 * get the EmailTemplate
	 *
	 * @return mixed
	 */
	protected function getEmailTemplate()
	{
		if (is_null($this->emailTemplate))
		{
			$query = $this->db->getQuery(true);

			$query->select('*')
					->from('#__conferenceplus_emailtemplates')
					->where($this->db->qn('') . ' =' . $this->db->q($this->taskname));

			$this->db->setQuery($query);
			$this->emailTemplate = $this->db->loadObject();
		}

		return $this->emailTemplate;
	}

	/**
	 * replace tags with data within the text
	 *
	 * @param   string  $text  the text
	 * @param   mixed   $data  the data
	 *
	 * @return  string
	 */
	protected function replacePlaceHolders($text, $data)
	{
		foreach ($this->placeHolders as $placeHolder => $valueName)
		{
			if (array_key_exists($valueName, $data))
			{
				$text = str_replace($placeHolder, $data[$valueName], $text);
			}
		}

		return $text;
	}
}
