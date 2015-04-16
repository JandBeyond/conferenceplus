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
	 * Ajust the query
	 *
	 * @param   boolean  $overrideLimits  Are we requested to override the set limits?
	 *
	 * @return  JDatabaseQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$query = parent::buildQuery($overrideLimits);

		$db    = $this->getDbo();

		$formName = $this->getState('form_name');

		if ($formName == 'form.default')
		{
			// Join payments
			$query->join('INNER', '#__conferenceplus_payments AS p ON p.conferenceplus_payment_id = invoice.payment_id')
				->select($db->qn('p.processkey') . ' AS ' . $db->qn('processkey'));

			// Join tickets
			$query->join('INNER', '#__conferenceplus_tickets AS t ON t.payment_id = invoice.payment_id')
				->select('t.firstname, t.lastname, t.email');

			// Filter
			$filter = $this->getState('filter.identifier');

			if ( ! empty($filter))
			{
				$qFilter = $db->q('%' . $filter . '%');
				$query->where('( ' . $db->qn('payment_id') . ' like ' . $qFilter . ') OR ('
					. $db->qn('t.firstname') . ' like ' . $qFilter . ') OR ('
					. $db->qn('t.lastname') . ' like ' . $qFilter . ') OR ('
					. $db->qn('t.email') . ' like ' . $qFilter . ')');
			}
		}

		return $query;
	}



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

