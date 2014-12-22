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

// Load FOF
include_once JPATH_LIBRARIES . '/fof/include.php';

if (!defined('FOF_INCLUDED'))
{
	JError::raiseError('500', 'FOF is not installed');
}

// Include the library
JLoader::import('conferenceplus.library');

if ( ! Conferenceplus\Helper::isVersion3())
{
	JError::raiseError('500', 'Extension ConferencePlus is build for Joomla! Version 3 or higher');
}

FOFDispatcher::getTmpInstance('com_conferenceplus')->dispatch();

/** EOF **/