<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

// No direct access
defined('_JEXEC') or die;

$displayData = new StdClass;

$headerlevel    = $this->params->get('headerlevel', 1);
$shl1 			= $headerlevel + 1;
$shl11 			= $headerlevel + 2;
$shl111			= $headerlevel + 3;
$prog			= $this->programme;

$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';
$title = JLayoutHelper::render('html.title', $displayData, $baseLayoutPath);

$doc = JFactory::getDocument()->setTitle($title);

$Itemid = Conferenceplus\Route\Helper::getItemid('programme');

$uri       = JUri::getInstance();
$returnurl = base64_encode($uri->toString(['path', 'query', 'fragment']));

$script ='
	$(document).ready(function() {
	});
';
//$doc->addScriptDeclaration($script);

$useTabs = count($prog) > 1;
?>

<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus programme">

	<?php if ($useTabs) : ?>
		<?php echo $this->loadTemplate('tabs'); ?>
	<?php else : ?>
		plain list
	<?php endif; ?>

</div>
<!-- ************************** END: conferenceplus ************************** -->
