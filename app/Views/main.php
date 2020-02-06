<?php

$config = $this->variables;

/** load app-js from view | first xxx_fn.js after xxx.js */
$fn_js = [];
$dir = $config['path.views'] . '/' . $config['viewpage'];
foreach (glob($dir . '/*fn.js') as $file) {
	$fn_js[] = $file; ?>
<script type="text/javascript" src="<?php echo $file ?>"></script>
<?php
} ?>

<?php
foreach (glob($dir . '/*.js') as $file) {
		if (!in_array($file, $fn_js)) { ?>
<script type="text/javascript" src="<?php echo $file ?>"></script>
<?php }
	}
