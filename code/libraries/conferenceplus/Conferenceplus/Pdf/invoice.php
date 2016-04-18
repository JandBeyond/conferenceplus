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
class Invoice extends Base
{
	/**
	 * render the pdf
	 *
	 * @param   array $data the data
	 *
	 * @param $template
	 * @return string
	 */
	public function render($data, $template)
	{
		$this->title = 'Invoice: ' . $data['invoice_number'];
		$this->folder = 'invoices';

		return parent::render($data, $template);
	}
}
