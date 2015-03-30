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

$form 			= $this->form;
$params 		= JComponentHelper::getParams('com_conferenceplus');
$keys 			= array_keys($form->getFieldset());
$headerlevel    = $params->get('headerlevel', 2);

$item = $this->item;
$base = JUri::base(true);

$title = JText::_('COM_CONFERENCEPLUS_CHANGE_INVOICE_ADDRESS');;
JFactory::getDocument()->setTitle($title);

$baseLayoutPath = JPATH_ROOT . '/media/conferenceplus/layouts';

$uri       = JUri::getInstance();
$returnurl = base64_encode($uri->toString(['path', 'query', 'fragment']));

$Itemid = Conferenceplus\Route\Helper::getItemid();

$showMessages = ! empty(JFactory::getApplication()->getMessageQueue());

$fields = array('address');

?>
<!-- ************************** START: conferenceplus ************************** -->
<div class="conferenceplus invoice">

    <?php
    echo "<h$headerlevel>" . $title . "</h$headerlevel>";
    ?>
    <form action="index.php?option=com_conferenceplus&view=invoice&task=save&Itemid=<?php echo $Itemid;?>" method="post" id="adminForm" role="form">

        <?php echo JText::_('COM_CONFERENCEPLUS_CHANGE_INVOICE_ADDRESS_PRETEXT');?>

        <?php if ($showMessages) : ?>
            <?php echo JLayoutHelper::render('html.messages', '', $baseLayoutPath); ?>
        <?php endif; ?>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <?php foreach($fields AS $f) : ?>

                    <?php if (in_array($f, $keys)) : ?>
                        <?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'PREINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
                        <?php $displayData = new stdClass; ?>
                        <?php $displayData->label = $form->getLabel($f); ?>
                        <?php $displayData->input = $form->getInput($f); ?>
                        <?php echo JLayoutHelper::render('form.formelement', $displayData, $baseLayoutPath); ?>
                        <?php echo JText::_(Conferenceplus\Helper::checkLangTag('COM_CONFERENCEPLUS_' . strtoupper($f) . 'POSTINFO', '', 'COM_CONFERENCEPLUS_EMPTY', '')); ?>
                    <?php endif; ?>

                <?php endforeach; ?>

                <div class="form-actions">
                    <input type="submit" value="<?php echo JText::_('COM_CONFERENCEPLUS_SEND');?>" class="btn btn-danger" />
                </div>

                <input type="hidden" name="option" value="com_conferenceplus" />
                <input type="hidden" name="conferenceplus_invoice_id" value="<?php echo $item->conferenceplus_invoice_id; ?>" />
                <input type="hidden" name="id" value="<?php echo $item->conferenceplus_invoice_id; ?>" />
                <input type="hidden" name="hash" value="<?php echo $item->hash; ?>" />
                <input type="hidden" name="h" value="<?php echo $item->hash; ?>" />
                <input type="hidden" name="view" value="invoice" />
                <input type="hidden" name="task" value="save" />
                <input type="hidden" name="layout" value="confirm" />
                <input type="hidden" name="returnurl" value="<?php echo $returnurl; ?>" />
                <input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

            </div>
        </div>
    </form>

</div>

