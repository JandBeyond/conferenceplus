<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package CONFERENCEPLUS
 **/

defined('_JEXEC') or die;

/**
 * Conferenceplus User plugin
 *
 * @package     CONFERENCEPLUS
 * @since       1.0
 */
class PlgUserConferenceplus extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  3.2
	 */
	protected $db;

	/**
	 * Remove user id in the speakers table
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param   array    $user     Holds the user data
	 * @param   boolean  $success  True if user was succesfully stored in the database
	 * @param   string   $msg      Message
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$query = $this->db->getQuery(true);

		$query->update($this->db->quoteName('#__conferenceplus_speakers'))
			->set($this->db->quoteName('userid') . ' = ' . 0)
			->where($this->db->quoteName('userid') . ' = ' . (int) $user['id']);

		$this->db->setQuery($query)->execute();

		return true;
	}

	/**
	 * Syncs the speakers table with the users table mapping via email
	 *
	 * @param   array    $user     Holds the new user data.
	 * @param   boolean  $isnew    True if a new user is stored.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{

		if (!$success)
		{
			return;
		}	

		$query = $this->db->getQuery(true);
		$query->select('count(*)')
				->from($this->db->quoteName('#__conferenceplus_speakers'))
				->where($this->db->quoteName('userid') . ' = ' . (int) $user['id']);

		$result = $this->db->setQuery($query)->loadResult();

		if ($result != 0)
		{
			return;
		}	

		$query->clear();
		$query->select('conferenceplus_speaker_id')
				->from($this->db->quoteName('#__conferenceplus_speakers'))
				->where($this->db->quoteName('email') . ' = ' . $this->db->q($user['email']));

		$speakerId = $this->db->setQuery($query)->loadResult();

		if (empty($speakerId))
		{
			return;
		}	

		// Load FOF
		include_once JPATH_LIBRARIES . '/fof/include.php';

		if (!defined('FOF_INCLUDED'))
		{
			JError::raiseError('500', 'FOF is not installed');
		}

		$config['input'] = new FOFInput;
		$config['input']->set('option', 'com_conferenceplus');

		$speakers = FOFTable::getInstance('speakers', 'JTable', $config);
		
		$speakers->load($speakerId);
		$speakers->userid = (int) $user['id'];

		if(!$speakers->store())
		{
			// failed, return false but nobody looks at the result
			return false;
		}

		return;
	}

	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data
	 * @param   array  $options  Array holding options (remember, autoregister, group)
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.5
	 */
	public function onUserLogin($user, $options = array())
	{
		return true;
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data.
	 * @param   array  $options  Array holding options (client, ...).
	 *
	 * @return  object  True on success
	 *
	 * @since   1.5
	 */
	public function onUserLogout($user, $options = array())
	{
		return true;
	}


	/**
	 * We set the authentication cookie only after login is successfullly finished.
	 * We set a new cookie either for a user with no cookies or one
	 * where the user used a cookie to authenticate.
	 *
	 * @param   array  $options  Array holding options
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.2
	 */
	public function onUserAfterLogin($options)
	{
		return true;
	}

	/**
	 * This is where we delete any authentication cookie when a user logs out
	 *
	 * @param   array  $options  Array holding options (length, timeToExpiration)
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.2
	 */
	public function onUserAfterLogout($options)
	{
		return true;
	}
}
