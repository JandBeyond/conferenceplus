<?php

$displayData 	= new stdClass;
$params 		= JComponentHelper::getParams('com_conferenceplus');

$headerlevel    = $params->get('headerlevel', 2);

$title = JTExt::_('COM_CONFERENCEPLUS_SUBMITVOTE_TITLE');

$doc = JFactory::getDocument()->setTitle($title);

$Itemid = Conferenceplus\Route\Helper::getItemid();

?>

<div class="conferenceplus submitvote">


</div>