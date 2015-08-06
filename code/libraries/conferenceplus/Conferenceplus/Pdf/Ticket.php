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
class Ticket extends Base
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

		$this->pdf->SetTitle('Ticket');
		$this->pdf->setPrintHeader(false);
		$this->pdf->AddPage();

		$this->pdf->writeHTML($this->text, true, false, true, false, '');

		$filename = $data['basename'];
		$fullpath = JPATH_BASE . '/media/conferenceplus/tickets/' . $filename . '.pdf';

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
	<title>{ticket_number}</title>

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
		<td style="width:60%;vertical-align:top">
			<p><span style="font-size:32pt;padding-top: 0;margin-top: 0;font-weight:bold;">J and Beyond</span><br /><br />
			<span style="font-weight:normal;font-size:8pt">Verein zur Förderung freier Content Management Systeme</span></p>

			<div style="font-weight:bold;">Dein Ticket für den JoomlaDay&trade; Deutschland 2015 in Hamburg:</div>
			<br />
			<table>
				<tr style="width:35%">
					<td>
						Ticket-Nr.:
					</td>
					<td>
						{ticket_number}
					</td>
				</tr>
				<tr>
					<td>
						Ticket-Typ:
					</td>
					<td>
						{ticket_type}
					</td>
				</tr>
				<tr>
					<td>
						Essenspräferenz:&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						{food}
					</td>
				</tr>
				<tr>
					<td>
						T-Shirt Größe:
					</td>
					<td>
						{tshirtsize}
					</td>
				</tr>
			</table>

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
		USt-IdNr. DE277018347<br/>
		</td>
	</tr>
</table>

<hr />

<p>&nbsp;</p>

<div>
	<table style="width:85mm;height:49mm;border:1px solid #000;">
		<tr>
			<td>
				<table style="margin: 5px;">
					<tr>
						<td style="text-align:center;font-size:12px;height:15mm">
							<strong>JoomlaDay&trade; Deutschland 2015</strong><br />
							18. - 19.09.2015 in Hamburg
						</td>
					</tr>
					<tr>
						<td style="text-align:center; font-size:14px;height:20mm">
							<strong>{name}</strong>
						</td>
					</tr>
					<tr>
						<td style="text-align:center;height:10mm">
							{ticket_type}<br />{ticket_number} / {tshirtsize}
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<br />
<br />
<div style="font-weight:bold;">Dieses Ticket bitte zur Veranstaltung mitbringen!</div>



</body>
</html>
EOD;

		return $html;
	}
}
