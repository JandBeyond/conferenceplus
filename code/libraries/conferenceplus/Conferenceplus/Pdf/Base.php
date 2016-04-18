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

namespace Conferenceplus\Pdf;

/**
 * Base
 *
 * @package  Conferenceplus\Pdf
 * @since    0.0.1
 */
abstract class Base
{
	/** @var string Pageorientation */
	protected $pageOrientation = 'P';

	/** @var string pageUnit */
	protected $pageUnit = 'mm';

	/** @var string pageSize */
	protected $pageSize = 'mm';

	/** @var array fields allowed to overwrite */
	protected $overwritableFields = ['pageOrientation', 'pageUnit', 'pageSize', 'text', 'creator', 'author'];

	/** @var string text */
	protected $text = '';

	/** @var null|\TCPDF pdf object */
	protected $pdf = null;

	/** @var string title */
	protected $title = '';

	/** @var string folder */
	protected $folder = '';

	/** @var string creator */
	protected $creator = 'ConferencePlus/Tcpdf';

	/** @var string author */
	protected $author = 'Conference Staff';

	/**
	 * Base constructor.
	 * @param array $config
     */
	public function __construct($config = array())
	{
		foreach ($this->overwritableFields as $field)
		{
			if (array_key_exists($field, $config))
			{
				$this->$field = $config[$field];
			}
		}

		$this->pdf = new \TCPDF($this->pageOrientation, $this->pageUnit, $this->pageSize, true, 'UTF-8', false);
	}

	/**
	 * @param $data
	 * @param $template
     *
	 * @return string
     */
	public function render($data, $template)
	{
		$this->text = $this->replacePlaceHolders($template, $data);

		$this->pdf->SetCreator($this->creator);
		$this->pdf->SetAuthor($this->author);

		$this->pdf->SetTitle($this->title);
		$this->pdf->setPrintHeader(false);
		$this->pdf->AddPage();

		$this->pdf->writeHTML($this->text, true, false, true, false, '');

		$filename = $data['basename'];
		$fullpath = JPATH_BASE . '/media/conferenceplus/' . $this->folder . '/' . $filename . '.pdf';

		$this->pdf->Output($fullpath, 'F');

		return $fullpath;
	}

	/**
	 * replace tags with data within the text
	 *
	 * @param   string  $text  the text
	 * @param   mixed   $data  the data
	 *
	 * @return  string
	 */
	protected function replacePlaceHolders($text, $data)
	{
		foreach ($data as $placeHolder => $value)
		{
			$placeHolder = '{' . $placeHolder . '}';
			$text = str_replace($placeHolder, $value, $text);
		}

		return $text;
	}
}
