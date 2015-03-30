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
	 * @param   array  $data  the data
	 *
	 * @return string
	 */
	public function render($data)
	{
		parent::render($data);

		$this->pdf->SetTitle('Invoice: ' . $data['invoice_number']);
		$this->pdf->setPrintHeader(false);
		$this->pdf->AddPage();

		$this->pdf->writeHTML($this->text, true, false, true, false, '');

		$filename = $data['basename'];
		$fullpath = JPATH_BASE . '/media/conferenceplus/invoices/' . $filename . '.pdf';

		$this->pdf->Output($fullpath, 'F');

		return $fullpath;
	}


	/**
	 * Get the text to render
	 *
	 * @return string
	 */
	protected function getText()
	{
		$html = <<<EOD
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<title>{invoice_number}</title>

<style type="text/css">
	body {font-family:helvetica;font-size: 10pt;}
	.adrline {font-size: 8pt;}
	table {border-collapse: collapse;}
	table.pos th, table.pos td {border:1px solid #555;padding: 5px;}
	.tright {text-align: right;}
</style>

</head>
<body>

<table style="width:100%;">
	<tr>
		<td style="width:60%">
			<p><span style="font-size:32pt;padding-top: 0;margin-top: 0;font-weight:bold;">J and Beyond</span><br /><br />
			<span style="font-weight:normal;font-size:8pt">Verein zur Förderung freier Content Management Systeme</span></p>
			<br />
			<p style="font-size:8pt">JAB · c/o Robert Deutz · Brüsseler Ring 67 · 52074 Aachen</p>
			<div class="adrblock">
				{addressblock}
			</div>
		</td>

		<td style="width:38%;font-size: 8pt;">
		J and Beyond e.V.<br/>
		c/o Robert Deutz<br/>
		Brüsseler Ring 67<br/>
		52074 Aachen<br/>
		<br/>
		Phone +49 241 94319 67<br/>
		Fax +49 241 94319 71<br/>
		<br/>
		Email kontakt@jabev.de<br/>
		Internet jandbeyond.org/jabev.de <br/>
		<br/>
		Bank Account/ Bankverbindung: <br/>
		Bank Name: Sparkasse Aachen<br/>
		IBAN: DE38 3905 0000 0000 0137 71<br/>
		SWIFT/BIC: AACSDE33XXX<br/>
		<br/>
		USt-IdNr.DE277018347<br/>
		</td>
	</tr>
</table>
<br /><br /><br /><br />
<table style="width:100%;">
	<tr>
		<td style="width:60%">
			<div style="font-weight:bold;">Invoice/Rechnung: {invoice_number}</div>
		</td>
		<td style="width:38%;">
			<div style="text-align: right;">{date}</div>
		</td>
	</tr>
</table>

<br />

<div style="margin-top:10px">

	<table style="border:2px solid #555;width:100%;padding: 5px 5px 5px 5px;">
		<tr>
			<th style="width:84%;font-weight:bold;border:1px solid #555;">Description/Beschreibung</th>
			<th style="width:15%;font-weight:bold;border:1px solid #555;">Fee/Betrag</th>
		</tr>

		<tr>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;">
				{productname}<br />{productdesc}
			</td>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right">
				{productfee}
			</td>
		</tr>
		<tr>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right">Subtotal</td>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right">{productfee}</td>
		</tr>
		<tr>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right">{tax}</td>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right">{taxfee}</td>
		</tr>
		<tr>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right"><strong>Total</strong></td>
			<td style="padding: 5px 5px 5px 5px;border:1px solid #555;text-align:right"><strong>{totalfee}</strong></td>
		</tr>
	</table>
	<p>{note}</p>
</div>
<p>Best Regards</p>
<p>JAB e.V.</p>
</body>
</html>
EOD;

		return $html;
	}
}
