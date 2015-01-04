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

namespace Conferenceplus\Registration;

// Load not namespaced class if doesn't loaded
if ( ! class_exists('\UsersModelRegistration', false))
{
	require_once JPATH_SITE . '/components/com_users/models/registration.php';
}

/**
 * Class UserRegistration
 *
 * @package  Conferenceplus\Registration
 * @since    1.0
 */
class UserRegistration extends \UsersModelRegistration
{
	/**
	 * Constructor
	 *
	 * @param   array  $config  An array of configuration options (name, state, dbo, table_path, ignore_request).
	 *
	 * @since   12.2
	 * @throws  Exception
	 */
	public function __construct($config=array())
	{
		$this->option = 'com_users';
		$this->name = 'Registration';
		$config['table_path'] = JPATH_ADMINISTRATOR . '/components/com_users/tables';

		parent::__construct($config);

		// Load language files
		$jlang = \JFactory::getLanguage();
		$jlang->load($this->option, JPATH_SITE, 'en-GB', true);
		$jlang->load($this->option, JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load($this->option, JPATH_SITE, null, true);

		$jlang->load($this->option, JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load($this->option, JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load($this->option, JPATH_ADMINISTRATOR, null, true);
	}


	/**
	 * Method to register a user
	 *
	 * @param   array  $temp  Form data
	 *
	 * @return  mixed  The user id on success, false on failure.
	 */
	public function register($temp)
	{
		$sessionId = \JFactory::getApplication()->getUserState('com_conferenceplus.sessionId');

		if (!empty($sessionId))
		{
			$session = \FOFTable::getAnInstance('sessions');
			$session->load($sessionId);

			$speakerId = explode(',', $session->speaker_listids)[0];

			$speaker = \FOFTable::getAnInstance('speakers');
			$speaker->load($speakerId);

			$temp['username']  = $speaker->email;
			$temp['email1']    = $speaker->email;
			$temp['email2']    = $speaker->email;

			$temp['name']      = $speaker->firstname . ' ' . $speaker->lastname;

			$temp['password1'] = \JUserHelper::genRandomPassword();
			$temp['password2'] = $temp['password1'];

			\JFactory::getApplication()->setUserState('com_conferenceplus.sessionId', null);

			return parent::register($temp);
		}
	}
}
