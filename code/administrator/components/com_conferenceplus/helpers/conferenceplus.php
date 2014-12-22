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

/**
 * ConferenceplusHelper
 *
 * @package  Conferenceplus
 * @since    1.0
 */
class ConferenceplusHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 */
	public static function addSubmenu($vName)
	{
		// Load FOF
		include_once JPATH_LIBRARIES . '/fof/include.php';

		if (!defined('FOF_INCLUDED'))
		{
			JError::raiseError('500', 'FOF is not installed');
		}

		$strapper = new FOFRenderJoomla3;

		$strapper->renderCategoryLinkbar('com_conferenceplus');
	}


}	