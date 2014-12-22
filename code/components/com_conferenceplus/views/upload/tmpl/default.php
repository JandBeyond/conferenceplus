<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

// No direct access
defined('_JEXEC') or die;

$componentConfiguration = JComponentHelper::getComponent('com_conferenceplus')->params;

$input = JFactory::getApplication()->input;

$realbasefolder = JPATH_BASE . '/images/' . $componentConfiguration->get('uploadfolder') . '/';

$imagefolder =  'images/' . $componentConfiguration->get('uploadfolder') . '/';

$filestypes = $input->getUint('filetypes',1);

switch($filestypes)
{
	case 1:
		$accept_file_types = '/\.(gif|jpe?g|png)$/i';
		break;

	case 2:
		$accept_file_types = '/\.(pdf)$/i';
		break;

}

$options =  [
	'upload_dir' => $realbasefolder,
	'upload_url' => $imagefolder,
	'accept_file_types' => $accept_file_types
];

try {
	$upload_handler = new Conferenceplus\Upload\Handler($options);
}
catch (Exception $e)
{
	return false;
}
