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
 * Class BaseTask
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
abstract class BaseTask implements Base
{
	/*
	 * Taskrepository
	 */
	protected $taskRepository = null;

	/*
	 * application
	 */
	protected $application;

	/*
	 * database connection
	 */
	protected $db;

	/*
	 * date object
	 */
	protected $date;

	/*
	 * start date
	 */
	protected $start;

	/**
	 * configuration
	 */
	protected $config;

	/**
	 * taskname
	 */
	protected $taskname;

	/**
	 * class constructor
	 *
	 * @param   array  $config  configuration
	 */
	public function __construct($config = array())
	{
		if (array_key_exists('repository', $config))
		{
			$this->taskRepository = $config['repository'];
		}
		else
		{
			$this->taskRepository = new Repository;
		}

		if (array_key_exists('application', $config))
		{
			$this->application = $config['application'];
		}
		else
		{
			$this->application = \JFactory::getApplication();
		}

		if (array_key_exists('db', $config))
		{
			$this->db = $config['db'];
		}
		else
		{
			$this->db = \JFactory::getDbo();
		}

		if (array_key_exists('date', $config))
		{
			$this->date = $config['date'];
		}
		else
		{
			$this->date = new \JDate;
		}

		$class             = get_class($this);
		$this->taskname    = substr($class, strrpos($class, '\\') + 1);

		$this->start = $this->date->getInstance();

		$this->config = $config;
	}

	/**
	 * Create a task
	 *
	 * @param   array  $data  the data to process
	 *
	 * @return bool
	 */
	public function create($data)
	{
		$task = $this->taskRepository->getItem();

		if (method_exists($this, 'onBeforeCreate'))
		{
			if ( ! $this->onBeforeCreate($data))
			{
				// Stop working on this task
				return true;
			}
		}

		$task->processdata = json_encode($data);
		$task->name        = $this->taskname;
		$task->created     = $this->date->getInstance()->toSql();

		return $task->store();
	}

	/**
	 * Process the task
	 *
	 * @param   integer  $taskId  the id of the task
	 *
	 * @return bool|void
	 */
	public function process($taskId)
	{
		$task = $this->taskRepository->getItem($taskId);

		if (method_exists($this, 'onBeforeDoProcess'))
		{
			if ( ! $this->onBeforeDoProcess($task))
			{
				// Stop working on this task
				return true;
			}
		}

		$task->started = $this->start->toSql();

		if ( ! $this->doProcess($task))
		{
			return false;
		}

		if (method_exists($this, 'onAfterDoProcess'))
		{
			// We don't care if that has worked well
			$this->onAfterDoProcess($task);
		}

		$this->setFinished($task);

		return true;
	}

	/**
	 * Set a task as finished
	 *
	 * @param   \JTable  $task  task
	 *
	 * @return void
	 */
	protected function setFinished($task)
	{
		$task->result = 1;
		$task->finished = $this->date->getInstance()->toSql();

		$task->store();
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

		// @TODO: implement in child class

		return true;
	}


	/**
	 * get component parameters
	 *
	 * @return  mixed
	 */
	protected function getComponentParams()
	{
		return \JComponentHelper::getComponent('com_conferenceplus')->params;
	}

	/**
	 * decode the processdata json into an array
	 *
	 * @param   \JTable  $task  taskdata
	 *
	 * @return mixed
	 */
	protected function decodeProcessdata($task)
	{
		$data = json_decode($task->processdata, true);

		return $data;
	}
}
