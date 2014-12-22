<?php
/**
 * conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 * @package    conferenceplus
 *
 * @copyright  JandBeyond
 * @license    GNU General Public License version 2 or later
 **/

// No direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Class ConferenceplusViewDefault
 */
class ConferenceplusViewDefault extends FOFViewHtml
{
	/**
	 * default class construtor
	 *
	 * @param   array  $config  Configuration array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Joomla! 3.x
		$renderer = new FOFRenderJoomla3;

		$this->setRenderer($renderer);
	}
}
