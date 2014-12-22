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

class ConferenceplusModelDefault extends FOFModel
{

	/**
	 * This method runs before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array     &$data   The data to save
	 * @param   FOFTable  &$table  The table to save the data to
	 *
	 * @return  boolean  Return false to prevent saving, true to allow it
	 */
	protected function onBeforeSave(&$data, &$table)
	{
		if (!parent::onBeforeSave($data, $table))
		{
			return false;
		}

		if ($this->_isNewRecord)
		{
			$data['created'] = JFactory::getDate()->toSql();

			return true;
		}

		$data['modified'] 		= JFactory::getDate()->toSql();

		return true;
	}

	/**
	 * Manage a field depending on a configuration value
	 *
	 * @param   FOFForm  $form       A form object
	 * @param   string   $fieldname  The fieldname
	 * @param   int      $confvalue  The configuration value
	 *
	 * @return  void
	 */
	protected function manageField($form, $fieldname, $confvalue)
	{
		if ($confvalue == 0)
		{
			$this->removeFields($form, $fieldname);
		}
		elseif ($confvalue == 2)
		{
			$this->setFieldsRequired($form, $fieldname);
		}
	}

	/**
	 * Set a field to be a requiered field
	 *
	 * @param   FOFForm  $form    A form object
	 * @param   mixed    $fields  Array of fieldnames or a fieldname
	 *
	 * @return  void
	 */
	protected function setFieldsRequired($form, $fields)
	{
		$fields = (array) $fields;

		foreach ($fields as $fieldname)
		{
			$form->setFieldAttribute($fieldname, 'required', 'true');
		}
	}

	/**
	 * remove a field
	 *
	 * @param   FOFForm  $form    A form object
	 * @param   mixed    $fields  Array of fieldnames or a fieldname
	 * @param   string   $group   The optional dot-separated form group path on which to find the field.
	 *
	 * @return  void
	 */
	protected function removeFields($form, $fields, $group = null)
	{
		$fields = (array) $fields;

		foreach ($fields as $fieldname)
		{
			$form->removeField($fieldname, $group);
		}
	}

	/**
	 * Copy a form field
	 *
	 * @param   FOFForm  $form        A form object
	 * @param   string   $sourceId    SourceId of the field
	 * @param   string   $targetId    TagetId of the copied field
	 * @param   array    $attributes  Attributes to copy
	 *
	 * @return SimpleXMLElement
	 */
	public function copyFormField(
		$form, $sourceId, $targetId,
		$attributes = [	'type', 'default', 'label', 'description', 'required', 'labelclass',
		'class', 'size', 'rows', 'cols'])
	{
		$field = new SimpleXMLElement('<field></field>');
		$field->addAttribute('name', $targetId);

		foreach ($attributes AS $attr)
		{
			$field->addAttribute($attr, $form->getFieldAttribute($sourceId, $attr));
		}

		$type = $form->getFieldAttribute($sourceId, 'type');

		$typesWithOptions = ['checkbox', 'radio', 'list', 'radiobs3'];

		if (in_array($type, $typesWithOptions))
		{
			$xmlField = $this->xmlForm->xpath("//form/fieldset/field[@name='" . $sourceId . "']");

			$options = (array) $xmlField[0]->children();
			$options = $options['option'];

			foreach ($options as $value => $text)
			{
				$option = $field->addChild('option', $text);
				$option->addAttribute('value', $value);
			}
		}

		return $field;
	}
}
