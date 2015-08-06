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

namespace Conferenceplus\Task;

/**
 * Class RenderPdf
 *
 * @package  Conferenceplus\Task
 * @since    0.0.1
 */
abstract class RenderPdf extends BaseTask
{
	/*
	 * pdf
 	 */
	protected $pdf = null;

	/**
	 * class constructor
	 *
	 * @param   array  $config  configuration
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		if (array_key_exists('pdf', $config))
		{
			$this->pdf = $config['pdf'];
		}
		else
		{
			$class = ucwords(substr(get_class($this), strlen(get_class())));
			$class = '\Conferenceplus\Pdf\\' . $class;

			$this->pdf = new $class($this->config);
		}
	}

	/**
	 * Do the work
	 *
	 * @param   Repository  $task  task data
	 *
	 * @return bool
	 */
	protected function doProcess($task)
	{
		$data = $this->prepareData($task);

		if ($data === false)
		{
			return false;
		}

		$filename = $this->render($data);

		if ($filename === false)
		{
			return false;
		}

		$task->processdata['filename'] 	= $filename;
		$task->processdata['email'] 	= $data['email'];

		return $this->instantiateNextTasks($task);
	}

	/**
	 * prepare the data from processdata
	 *
	 * @param   mixed  $task  task data
	 *
	 * @return array
	 */
	protected function prepareData($task)
	{
		return [];
	}

	/**
	 * do the actual rendering and save the file
	 *
	 * @param   array  $data  the data
	 *
	 * @return mixed
	 */
	protected function render($data)
	{
		return $this->pdf->render($data);
	}
}
