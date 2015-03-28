<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

namespace Conferenceplus\Task;

/**
 * Class Repository
 *
 * @package  Conferenceplus\Task
 * @since    1.0
 */
class Repository
{
	protected $table = null;

	private $isNew = true;

	/**
	 * constructor
	 */
	public function __construct()
	{
		// That's needed to find the correct table
		$config['input']['option'] = 'com_conferenceplus';

		$this->table = \FOFTable::getAnInstance('tasks', 'JTable', $config);
	}

	/**
	 * Get A repository object
	 *
	 * @param   int  $id  the id
	 *
	 * @return \FOFTable|null
	 */
	public function getItem($id=0)
	{
		if (!empty($id))
		{
			$this->table->load($id);
			$this->isNew = false;

			// Decode Process data
			$this->table->processdata = json_decode($this->table->processdata, true);
		}

		return $this->table;
	}

	/**
	 * store a record in the database
	 *
	 * @return true on success
	 */
	public function store()
	{
		if ($this->isNew)
		{
			$this->table->created = JFactory::getDate()->toSql();

			if (trim($this->table->name) == '')
			{
				$this->table->name = 'UNDEFINED';
			}
		}
		else
		{
			$this->table->modified = JFactory::getDate()->toSql();
		}

		return $this->table->store();
	}
}