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

namespace Conferenceplus\Upload;

// Load not namespaced class if doesn't loaded
if ( ! class_exists('\UploadHandler', false))
{
	require_once __DIR__ . '/UploadHandler.php';
}

/**
 * Class Handler
 *
 * @package  Conferenceplus\Upload
 * @since    1.0
 */
class Handler extends \UploadHandler
{
	/**
	 * overwrites the original implementation because I don't like how the files names are created
	 *
	 * @param   string  $name  The file name to check
	 *
	 * @return  string
	 */
	protected function upcount_name($name)
	{
		// If we are here name is already used
		// Find first dot from right
		$dotpos = strrpos($name, '.');

		$ext       = substr($name, $dotpos);
		$filename = substr($name, 0, $dotpos);

		$underlinepos = strrpos($filename, '_');

		if (false === $underlinepos)
		{
			return $filename . '_1' . $ext;
		}

		$num = (int) substr($filename, $underlinepos + 1);
		$num++;

		$rawfilename = substr($filename, 0, $underlinepos);

		return $rawfilename . '_' . $num . $ext;
	}

	/**
	 * trim the file name, replace everything what could be end bad for us
	 *
	 * @param   string  $file_path      keep the interface
	 * @param   string  $name           the file name
	 * @param   string  $size           keep the interface
	 * @param   string  $type           keep the interface
	 * @param   string  $error          keep the interface
	 * @param   string  $index          keep the interface
	 * @param   string  $content_range  keep the interface
	 *
	 * @return  mixed|string
	 */
	protected function trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range)
	{
		/* Remove path information and dots around the filename, to prevent uploading
		 Into different directories or replacing hidden system files.
		 Also remove control characters and spaces (\x00..\x20) around the filename:
		*/
		$name = trim(basename(stripslashes($name)), ".\x00..\x20");
		$name = strtolower($name);
		$name = preg_replace('/[^a-z|^\\-|^_|^0-9]/', '', $name);
		$name = str_replace(' ', '_', $name);

		// Use a timestamp for empty filenames
		if (!$name)
		{
			$name = str_replace('.', '-', microtime(true));
		}

		return $name;
	}
}