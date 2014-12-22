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

class ConferenceplusModelTickettypes extends ConferenceplusModelDefault
{
	/**
	 * Class Constructor
	 *
	 * @param   array  $config  Configuration array
	 */
	public function __construct($config = array())
	{
		if (!isset($config['behaviors']))
		{
			$config['behaviors'] = array('filters', 'access', 'enabled');
		}

		parent::__construct($config);
	}
}
