<?php


defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla CMS.
 * Provides a upload mechanism,
 * needs some more magic on component side
 */
class ConferenceplusFormFieldMediaupload extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Mediaupload';

	/**
	 * The initialised state of the document object.
	 *
	 * @var    boolean
	 */
	protected static $initialised = false;

	/**
	 * @var    string
	 */
	protected $filetype = '';

	/**
	 * @var bool
	 */
	protected $placeholder = false;

	/**
	 * @var string
	 */
	protected $uploadbuttontext = '';

	/**
	 * @var bool
	 */
	protected $showalttext = false;

	/**
	 * @var string
	 */
	protected $alttext = '';

	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'filetype':
			case 'placeholder':
			case 'uploadbuttontext':
			case 'edit':
			case 'alttext':
			case 'directory':
			return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'filetype':
			case 'uploadbuttontext':
			case 'alttext':
				$this->$name = (string) $value;
				break;

			case 'placeholder':
			case 'directory':
			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see 	JFormField::setup()
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{
			$fields = ['uploadbuttontext', 'filetype', 'alttext', 'directory' ];

			foreach ($fields AS $field)
			{
				if (isset($this->element[$field]))
				{
					$this->{$field} = (string) $this->element[$field];
				}
			}

			$fields = ['placeholder', 'showalttext'];

			foreach ($fields AS $field)
			{
				if (isset($this->element[$field]))
				{
					$this->{$field} = in_array(strtolower((string) $this->element[$field]), ['yes', '1',  'true']);
				}
			}
		}

		return $result;
	}


	/**
	 * Method to get the field input markup for a upload field.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$this->init();

		$basedir = JUri::base(true);
		$baseUri = JUri::base();

		$baseUploadDir    = $this->getBaseUploadDirectory();
		$thunmbnailfolder = $baseUploadDir . '/thumbnail';

		$filetypes = $this->mapFiletypeToUrlParameterValue();

		$script = "
			jQuery( document ).ready(function( ) {
				Conferenceplus.Upload.add('{$this->id}','{$filetypes}');
			  });
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		$html = [];

		$opendiv  = '<div class="uploadarea';
		$opendiv .= $filetypes == 2 ? ' pdf' : ' image';
		$opendiv .= '">';

		$html[] = $opendiv;

		if ($this->placeholder)
		{
			$html[] = ' <div class="areapicture">';

			if ($this->value != "")
			{
				$html[] = '    <img id="thumbnail' . $this->id . '" src="' . $thunmbnailfolder . '/' . $this->value . '" />';
			}
			else
			{
				$html[] = '    <img id="thumbnail' . $this->id . '" src="' . $basedir . '/media/conferenceplus/images/placeholder.png" />';
			}

			$html[] = ' </div>';
		}

		if ($this->showalttext)
		{
			$html[] = '<div class="row fields">';
			$html[] = '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">';
			$html[] = '<input id="' . $this->id . 'alttext" type="text" class="form-control" name="' . $this->id . 'alttext" value="' . $this->alttext . '">';
			$html[] = '</div>';
			$html[] = '<div class="col-xs-3 col-sm-4 col-md-4 col-lg-4">';
			$html[] = '<input id="' . $this->id . '" type="text" name="' . $this->id . '" value="' . $this->value . '" class="form-control" readonly="readonly">';
			$html[] = '</div>';
	    	$html[] = '<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">';
		}
		else
		{
			$html[] = '<input id="' . $this->id . '" type="hidden" name="' . $this->id . '" value="' . $this->value . '">';
		}

		$actiondiv  = '<div class="actions';
		$actiondiv .= $filetypes == 2 ? ' pdf' : ' image';
		$actiondiv .= '">';

		$html[] = $actiondiv;
		$html[] = '  <div class="btn btn-primary fileinput-button">';
		$html[] = '    <i class="glyphicon glyphicon-plus"><span>+</span></i>';
		$html[] = '    <input id="file' . $this->id . '" type="file" name="files[]" >';
		$html[] = '  </div>';
		$html[] = '  <button class="btn btn-danger" onclick="return Conferenceplus.Upload.remove(\'' . $this->id . '\')">';
		$html[] = '    <i class="glyphicon glyphicon-remove"><span>-</span></i>';
		$html[] = '  </button>';
		$html[] = '</div> <!-- actions -->';

		if ($this->showalttext)
		{
			$html[] = '</div>';
			$html[] = '</div> <!-- row fields -->';
		}

		$html[] = '</div> <!-- uploadarea -->';

		return implode("\n", $html);
	}

	protected function init()
	{
		if (self::$initialised)
		{
			return true;
		}

		$doc           = JFactory::getDocument();
		$basedir       = JUri::base(true);

		$baseUploadDir = $this->getBaseUploadDirectory();

		$doc->addStyleSheet($basedir . '/media/conferenceplus/css/main.css');
		$doc->addScript($basedir . '/media/conferenceplus/js/fileupload.js');

		$script = "
				jQuery( document ).ready(function( ) {
						Conferenceplus.Upload.basedir     = '{$baseUploadDir}';
						Conferenceplus.Upload.placeholder = '{$basedir}/media/conferenceplus/images/placeholder.png';
						Conferenceplus.Upload.spinner     = '{$basedir}/media/conferenceplus/images/loading_small.gif';
						Conferenceplus.Upload.url         = 'index.php?option=com_conferenceplus&view=upload&format=raw';
				});
		";

		JFactory::getDocument()->addScriptDeclaration($script);

		self::$initialised = true;

		return true;
	}

	/**
	 * maps a filetype string to an integer
	 *
	 * @return  int  filetype
	 */
	protected function mapFiletypeToUrlParameterValue()
	{
		switch ($this->filetype)
		{
			default:
			case 'image':
				$filetypes = 1;
				break;

			case 'pdf':
				$filetypes = 2;
				break;
		}

		return $filetypes;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		$label = '';

		if ($this->hidden)
		{
			return $label;
		}

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = $this->translateLabel ? JText::_($text) : $text;

		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTooltip' : '';
		$class = $this->required == true ? $class . ' required' : $class;
		$class = !empty($this->labelclass) ? $class . ' ' . $this->labelclass : $class;

		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->id . '-lbl" for="file' . $this->id . '" class="' . $class . '"';

		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description))
		{
			// Don't translate discription if specified in the field xml.
			$description = $this->translateDescription ? JText::_($this->description) : $this->description;
			JHtml::_('bootstrap.tooltip');
			$label .= ' title="' . JHtml::tooltipText(trim($text, ':'), $description, 0) . '"';
		}

		// Add the label text and closing tag.
		if ($this->required)
		{
			$label .= '>' . $text . '<span class="star">&#160;*</span></label>';
		}
		else
		{
			$label .= '>' . $text . '</label>';
		}

		return $label;
	}

	/**
	 * returns the base directory for file uploads
	 *
	 * @return string
	 */
	private function getBaseUploadDirectory()
	{
		$basedir       = JUri::base(true);
		$baseUploadDir = $basedir . '/images';

		if (!empty($this->directory))
		{
			$baseUploadDir .= "/" . $this->directory;

			return $baseUploadDir;
		}

		return $baseUploadDir;
	}
}
