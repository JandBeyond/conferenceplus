<?php

// No direct access
defined('_JEXEC') or die;

$title = '';

$app = JFactory::getApplication();
$menu = $app->getMenu();

if (is_object($menu))
{
	if ($item = $menu->getActive())
	{
		$params = $menu->getParams($item->id);

		if ($params->get('page_heading') != '' && $params->get('show_page_heading') == 1)
		{
			$title = $params->get('page_heading');
		}
		else
		{
			$title = $item->title;
		}
	}
}

echo $title;
