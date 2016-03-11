<?php

// No direct access
defined('_JEXEC') or die;

$doc = JFactory::getDocument();

$url = JUri::base();

$callbacklink = $url . '/index.php?option=com_conferenceplus&view=callback&type=recalcticketfee&tickettype='
                     . $displayData->tickettypeId . '&coupon=';

$script = "
    jQuery(document).ready(function() {
        jQuery('div.coupon span.recal-btn').toggle();
        jQuery('div.coupon span.recalculateresult').toggle();
    });

    function cprecal()
    {
        var coupon = jQuery('#couponcodeid').val();

        if (typeof coupon === 'undefined' || coupon === '') {
            return;
        }
        var link = '" . $callbacklink . "' + coupon;

        jQuery.ajax({
            method: 'GET',
            cache: false,
            url: link,
            dataType: 'json'
            })
            .done(function (result) {
                if (result.state == 99) {
                    jQuery('#fee').html(result.discounted);
                    if (result.value == 0) {
                        // This is an dead end, you can not change the coupon
                        jQuery('#invoicefields').hide();
                        jQuery('#couponcodeid').prop('readonly', true);
                    }
                }
                jQuery('.coupon .recalculateresult').html(result.msg);
            });
    }
";

$doc->addScriptDeclaration($script);

$css = ".coupon {padding:20px}";

$doc->addStyleDeclaration($css);

// Check if we have a coupon code in the url and the result of the check?
$msg = '';
$readonly = '';
$button = true;
if ( ! empty($displayData->couponCode))
{
    $msg = JText::_('COM_CONFERENCEPLUS_RETURNMESSAGE_COUPON_' . $displayData->resultCouponCheck['returnType']);
    if ($displayData->resultCouponCheck['returnType'] == 99)
    {
        $readonly = 'readonly="readonly"';
        $button = false;
        $doc->addScriptDeclaration("jQuery(document).ready(function() {
                                        cprecal();
                                    });");
    }
}

?>
<div class="form-group form-inline coupon clearfix">
    <label for="couponcodeid"><?php echo JText::_('COM_CONFERENCEPLUS_COUPON'); ?></label>
    <input <?php echo $readonly; ?> id="couponcodeid" name="coupon" type="text" class="form-control" placeholder="<?php echo JText::_('COM_CONFERENCEPLUS_COUPONCODE'); ?>" value="<?php echo $displayData->couponCode; ?>"/>
    <?php if ($button) : ?>
        <span class="recal-btn" style="display: none">
            <button class="btn btn-default" type="button" onclick="cprecal(); return false;">
                <span class="glyphicon glyphicon-retweet"></span><?php echo JText::_('COM_CONFERENCEPLUS_RECALULATE'); ?>
            </button>
        </span>
    <?php endif; ?>
    <span class="recalculateresult" style="display: none">
        <?php echo $msg; ?>
    </span>
</div>
