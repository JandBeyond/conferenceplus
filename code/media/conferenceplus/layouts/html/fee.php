<?php
// No direct access
defined('_JEXEC') or die();

$numberComposer = new Conferenceplus\Composer\Number();

$fee 	= $displayData->fee / 100;
$output = $numberComposer->money($fee);

if ($displayData->vat != 0)
{
	$output .= JText::sprintf('COM_CONFERENCEPLUS_FEE_VAT', $displayData->vat . '%');
}

echo $output;