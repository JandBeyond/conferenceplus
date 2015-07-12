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
			$query->join('INNER', '#__conferenceplus_awardcategories AS c ON c.conferenceplus_awardcategory_id = awardcategory_id')
				->select($db->qn('c.name') . ' AS ' . $db->qn('category'));
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
