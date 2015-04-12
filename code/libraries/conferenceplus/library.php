<?php
/**
 * @package     Sample
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2013 Roberto Segura. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// non supported PHP version detection. EJECT! EJECT! EJECT!
if(!version_compare(PHP_VERSION, '5.6.2', '>='))
{
	return JError::raise(E_ERROR, 500, 'PHP versions less than 5.6.2 are not supported.<br/><br/>');
}

// Ensure that autoloaders are set
JLoader::setup();

require_once __DIR__ . '/vendor/autoload.php';

// Global libraries autoloader
//JLoader::registerPrefix('Sample', dirname(__FILE__));

// Common fields
JFormHelper::addFieldPath(dirname(__FILE__) . '/form/field');

// Common form rules
//JFormHelper::addRulePath(dirname(__FILE__) . '/form/rule');

// Common HTML helpers
//JHtml::addIncludePath(dirname(__FILE__) . '/html');

// Load library language
// $lang = JFactory::getLanguage();
// $lang->load('lib_conferenceplus', __DIR__);
