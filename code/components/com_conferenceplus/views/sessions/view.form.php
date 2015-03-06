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

// No direct access
defined('_JEXEC') or die('Restricted access');


/**
 * Class ConferenceplusViewSpeakers
 *
 * @since  0.1
 */
class ConferenceplusViewSession extends FOFViewForm
{
	/**
	 * Executes before rendering the page for the Browse task.
	 *
	 * @param   string  $tpl  Subtemplate to use
	 *
	 * @return  boolean  Return true to allow rendering of the page
	 */
	protected function onBrowse($tpl = null)
	{
		$model = $this->getModel();

		$event_id = $this->input->getInt('event_id', 0);

		$this->rooms    = $model->getRooms($event_id);
		$this->slots    = $model->getSlots($event_id, true);

		$this->programme    = $model->getProgramme($event_id);

		return parent::onBrowse($tpl);
	}
}
