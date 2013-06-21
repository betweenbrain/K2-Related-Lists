<?php defined('_JEXEC') or die;

/**
 * File       default.php
 * Created    6/20/13 3:22 PM 
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

foreach ($lists as $tag => $items) : ?>
	<h1>More from <?php echo $tag; ?></h1>
	<?php foreach ($items as $item) : ?>
		<ul>
			<li>
				<?php echo $item->id ?>
			</li>
			<li>
				<?php echo $item->extraFields->Class->value ?>
			</li>
			<li>
				<?php echo $item->extraFields->test->value ?>
			</li>
		</ul>
	<?php endforeach ?>
<?php endforeach ?>