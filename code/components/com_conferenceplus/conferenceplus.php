<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

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

FOFDispatcher::getTmpInstance('com_conferenceplus')->dispatch();

/** EOF **/