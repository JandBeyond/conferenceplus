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

class ConferenceplusModelAwardnominations extends ConferenceplusModelDefault
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
		$this->setState('filter.event_id',
			$this->getUserStateFromRequest('filter.awardnominations.event_id', 'eventname', ''));
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
			// Join category
			$query->join('INNER', '#__conferenceplus_awardcategories AS awc ON awc.conferenceplus_awardcategory_id = awn.awardcategory_id')
				->select($db->qn('awc.name') . ' AS ' . $db->qn('category'));

			// Join events
			$query->join('INNER', '#__conferenceplus_events AS e ON e.conferenceplus_event_id = awc.event_id')
				->select('e.name AS eventname');

			$query->where($db->qn('e.enabled') . ' = 1');

			// Filter
			$filterevent_id = $this->getState('filter.event_id');

			if ( ! empty($filterevent_id))
			{
				$query->where($db->qn('e.conferenceplus_event_id') . ' = ' . $db->q($filterevent_id));
			}
		}

		return $query;
	}

	/**
	 * Allows data and form manipulation after preprocessing the form
	 *
	 * @param   FOFForm  &$form  A FOFForm object.
	 * @param   array    &$data  The data expected for the form.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return  void
	 */
	public function onAfterPreprocessForm(FOFForm &$form, &$data)
	{
		if (FOFPlatform::getInstance()->isFrontend())
		{
			$this->removeFields($form, 'shortlist');
		}
	}

	/**
	 * we overwrite save because we have to save a bunch of items
	 *
	 * @param   array|object  $data  The source data array or object
	 *
	 * @return  boolean  True on success
	 */
	public function save($data)
	{
		$data   = (array) $data;
		$table  = $this->getTable($this->table);
		$key    = $table->getKeyName();
		$result = true;

		$saveData['firstname'] 	      = $data['firstname'];
		$saveData['lastname']         = $data['lastname'];
		$saveData['email'] 	          = $data['email'];
		$saveData['nominee'] 	   	  = $data['nominee'];
		$saveData['awardcategory_id'] = $data['awardcategory_id'];
		$saveData['created'] 		  = JFactory::getDate()->toSql();

		// Make sure we save a new item
		$table->$key = null;

		$result &= $table->save($saveData);

		$nominationcount = $data['nominationcount'];

		if ($nominationcount > 0)
		{
			for ($i = 0;$i < $nominationcount; $i++)
			{
				$saveData['nominee'] 	   	  = $data['nominee' . '_' . $i];
				$saveData['awardcategory_id'] = $data['awardcategory_id' . '_' . $i];

				$table->$key = null;

				$result &= $table->save($saveData);
			}
		}

		return $result;
	}
}
