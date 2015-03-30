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
	protected $pageOrientation = 'P';

	protected $pageUnit = 'mm';

	protected $pageSize = 'mm';

	protected $overwritableFields = ['pageOrientation', 'pageUnit', 'pageSize', 'text'];

	protected $text = '';

	protected $pdf = null;



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

	public function render($data)
	{
		$this->text = $this->replacePlaceHolders($this->getText(), $data);

		$this->pdf->SetCreator('ConferencePlus/Tcpdf');
		$this->pdf->SetAuthor('JAB e.V.');
	}


	protected function getText()
	{
		return '';
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
