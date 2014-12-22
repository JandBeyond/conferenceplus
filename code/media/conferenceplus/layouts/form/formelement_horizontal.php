<?php
// No direct access
defined('_JEXEC') or die;

$labelWidth= isset($displayData->labelWidth) ? $displayData->labelWidth : [12, 6, 6, 6];
$inputWidth= isset($displayData->inputWidth) ? $displayData->inputWidth : [12, 6, 6, 6];

?>
<div class="form-group">
    <div class="col-xs-<?php echo $labelWidth[0]; ?> col-sm-<?php echo $labelWidth[1]; ?> col-md-<?php echo $labelWidth[2]; ?> col-lg-<?php echo $labelWidth[3]; ?>">
        <?php echo $displayData->label; ?>
    </div>
    <div class="col-xs-<?php echo $inputWidth[0]; ?> col-sm-<?php echo $inputWidth[1]; ?> col-md-<?php echo $inputWidth[2]; ?> col-lg-<?php echo $inputWidth[3]; ?>">
        <?php echo $displayData->input; ?>
    </div>
</div>
