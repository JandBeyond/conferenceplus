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
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 * @since   12.2
	 */
	protected function populateState()
	{
		// Load the filters.
		$this->setState('filter.event_id', $this->getUserStateFromRequest('filter.invoices.event_id', 'eventname', ''));
	}

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

			// Join tickettype
			$query->join('INNER', '#__conferenceplus_tickettypes AS tt ON tt.conferenceplus_tickettype_id = tickettype_id');

			// Join events
			$query->join('INNER', '#__conferenceplus_events AS e ON e.conferenceplus_event_id = tt.event_id')
				->select('e.name as eventname');

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

			$filterevent_id = $this->getState('filter.event_id');

			if ( ! empty($filterevent_id))
			{
				$query->where($db->qn('e.conferenceplus_event_id') . ' = ' . $db->q($filterevent_id));
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

