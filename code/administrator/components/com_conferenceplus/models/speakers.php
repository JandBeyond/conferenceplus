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

require_once 'default.php';

class ConferenceplusModelSpeakers extends ConferenceplusModelDefault
{
	/**
	 * This method runs before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array     &$data   The data to save
	 * @param   FOFTable  &$table  The table to save the data to
	 *
	 * @return  boolean  Return false to prevent saving, true to allow it
	 */
	protected function onBeforeSave(&$data, &$table)
	{
		if ( ! parent::onBeforeSave($data, $table))
		{
			return false;
		}

		if ( ! array_key_exists('userid', $data) || $data['userid'] == 0)
		{
			$db = $this->_db;
			$query = $db->getQuery(true);
			$query->select('*')
					->from('#__users')
					->where($db->qn('email') . ' = ' . $db->q($data['email']));

			$db->setQuery($query);

			$user = $db->loadObject();

			if (!empty($user))
			{
				$data['userid'] = $user->id;
			}
		}

		if (array_key_exists('imagefile', $data) && ! strpos($data['imagefile'], '/'))
		{
			$data['imagefile'] = $this->extendFilenameWithPath($data['imagefile']);
		}

		return true;
	}

	/**
	 * This method runs after the data is saved to the $table.
	 *
	 * @param   FOFTable  &$table  The table which was saved
	 *
	 * @return  boolean
	 */
	protected function onAfterSave(&$table)
	{
		if ( ! parent::onAfterSave($table))
		{
			return false;
		}

		if ($this->_isNewRecord && $table->userid == 0)
		{
			$task = new Conferenceplus\Task\ConfirmEmail;

			if ( ! $task->create($table->firstname, $table->lastname, $table->email, $table->event_id))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * adds the uploadpath to a filename
	 *
	 * @param   string  $filename  The filename
	 *
	 * @return  string
	 */
	private function extendFilenameWithPath($filename)
	{
		if (strpos($filename, '/'))
		{
			return $filename;
		}

		$conf = $this->getComponentConfiguration();
		$path = 'images/' . $conf->get('uploadfolder') . '/';
		$filename = $path . $filename;

		return $filename;
	}

	/**
	 * get the component configuration
	 *
	 * @return  object
	 */
	private function getComponentConfiguration()
	{
		return JComponentHelper::getComponent('com_conferenceplus')->params;
	}
}
