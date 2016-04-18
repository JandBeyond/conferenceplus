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
defined('_JEXEC') or die('Restricted access');


class ConferenceplusToolbar extends FOFToolbar
{
	/**
	 * Renders the submenu (toolbar links) for all detected views of this component
	 *
	 * @return  void
	 */
	public function renderSubmenu()
	{
		parent::renderSubmenu();

		$activeView = $this->input->getCmd('extension', '');
		$this->appendLink(
			JText::_('COM_CONFERENCEPLUS_CATEGORIES'),
			'index.php?option=com_categories&extension=com_conferenceplus',
			'com_conferenceplus' == $activeView
		);

		array_multisort($this->linkbar);
	}

	public function setToolbarTitle($task="")
	{
		// Set toolbar title
		$option = $this->input->getCmd('option', 'com_conferenceplus');
		$subtitle_key = strtoupper($option . '_TITLE_' . $this->input->getCmd('view', 'cpanel'));
		$subtitle_key = $task == "" ? $subtitle_key : $subtitle_key . '_' . strtoupper($task);
		JToolBarHelper::title(JText::_(strtoupper($option)) . ': ' . JText::_($subtitle_key), str_replace('com_', '', $option));
	}

	/**
	 * Generic OnAdd
	 *
	 * @return  void
	 */
	public function onTaskAdd()
	{
		parent::onAdd();
		$this->setToolbarTitle('ADD');
	}

	/**
	 * Renders the toolbar for the component attendees browse page
	 *
	 * @return  void
	 */
	public function onAttendeesBrowse()
	{
		if ($this->perms->edit)
		{
			JToolBarHelper::editList();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}

		$this->setToolbarTitle();
		$this->renderSubmenu();
	}

	/**
	 * Renders the toolbar for the component attendees edit page
	 *
	 * @return  void
	 */
	public function onAttendeesEdit()
	{
		if ($this->perms->edit)
		{
			JToolBarHelper::save();
		}

		JToolBarHelper::cancel();
		$this->setToolbarTitle('Edit');
	}

	/**
	 * Renders the toolbar for the component awardcategories add page
	 *
	 * @return  void
	 */
	public function onAwardcategoriesAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component awardnominations add page
	 *
	 * @return  void
	 */
	public function onAwardnominationsAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component attendees browse page
	 *
	 * @return  void
	 */
	public function onAwardvotesBrowse()
	{
		$this->setToolbarTitle();
		$this->renderSubmenu();
	}

	/**
	 * Renders the toolbar for the component day add page
	 *
	 * @return  void
	 */
	public function onDaysAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component templates add page
	 *
	 * @return  void
	 */
	public function onTemplatesAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component event add page
	 *
	 * @return  void
	 */
	public function onEventAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component payments browse page
	 *
	 * @return  void
	 */
	public function onPaymentsBrowse()
	{
		if ($this->perms->edit)
		{
			JToolBarHelper::editList();
		}

		$this->setToolbarTitle();
		$this->renderSubmenu();

	}

	/**
	 * Renders the toolbar for the component payments edit page
	 *
	 * @return  void
	 */
	public function onPaymentsEdit()
	{
		if ($this->perms->edit)
		{
			//JToolBarHelper::save();
		}

		JToolBarHelper::cancel();
		$this->setToolbarTitle('Edit');
	}

	/**
	 * Renders the toolbar for the component rooms add page
	 *
	 * @return  void
	 */
	public function onRoomsAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component sessiontypes add page
	 *
	 * @return  void
	 */
	public function onSessiontypesAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component session add page
	 *
	 * @return  void
	 */
	public function onSessionsAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component slot add page
	 *
	 * @return  void
	 */
	public function onSlotsAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component speaker add page
	 *
	 * @return  void
	 */
	public function onSpeakersAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component ticket add page
	 *
	 * @return  void
	 */
	public function onTicketsAdd()
	{
		$this->onTaskAdd();
	}

	/**
	 * Renders the toolbar for the component tickettypes add page
	 *
	 * @return  void
	 */
	public function onTickettypesAdd()
	{
		$this->onTaskAdd();
	}
}
