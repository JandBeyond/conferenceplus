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

require_once 'default.php';

class ConferenceplusModelInvoices extends ConferenceplusModelDefault
{
	/**
	 * This method runs after the data is saved to the $table.
	 *
	 * @param   FOFTable  &$table  The table which was saved
	 *
	 * @return  boolean
	 */
	protected function onAfterSave(&$table)
	{
		if (!parent::onAfterSave($table))
		{
			return false;
		}

		$processdata = json_decode($table->data, true);
		$processdata['invoiceaddress'] = $table->address;
		$processdata['invoice_id']     = $table->conferenceplus_invoice_id;
		$processdata['invoice_hash']   = $table->hash;

		$task = new Conferenceplus\Task\RenderPdfInvoice;

		return $task->create($processdata);
	}
}

