<?php

// No direct access
defined('_JEXEC') or die();
?>

<p class="gravatar"><img class="gravatar img-circle" src="<?php echo $displayData->gravatar; ?>" /></p>
<hr />
<p>Name: <?php echo $displayData->name; ?></p>
<p>Email: <?php echo $displayData->email; ?></p>
<p>Username: <?php echo $displayData->username; ?></p>
