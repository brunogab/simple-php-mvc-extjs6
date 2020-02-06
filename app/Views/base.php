<?php

$config = $this->variables;
?>

<!DOCTYPE HTML />
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $config['app.name'] ?>
	</title>

	<link rel="shortcut icon" type="image/x-icon" href="inc/images/favicon.ico">
	<script type="text/javascript">
		<?php foreach ($config['app_menu'] as $key => $data) { ?>
		var <?php echo $key; ?> = <?php echo $config['app_menu'][$key] ?> ;
		<?php } ?>
	</script>
</head>

<body>

	<!-- load css from \inc\css -->
	<?php
		foreach (glob($config['path.css'] . '/*.css') as $file) { ?>
	<link rel="stylesheet" type="text/css"
		href="<?php echo $file ?>">
	<?php } ?>

	<!-- Loading mask css div's -->
	<div class="loadlogo" id="center"
		style="background: linear-gradient(rgba(245,245,245,0.6), rgba(245,245,245,0.6)), url(<?php echo $config['logo.loading'] ?>) center center no-repeat">
		<div class="loader">
			<div class="dot d1"></div>
			<div class="dot d2"></div>
			<div class="dot d3"></div>
			<div class="dot d4"></div>
			<div class="dot d5"></div>
		</div>
	</div>

	<!-- ExtJS library css, js -->
	<link rel="stylesheet" type="text/css"
		href="<?php echo $config['path.extjs'] ?>/build/classic/theme-neptune/resources/theme-neptune-all.css" />
	<script type="text/javascript"
		src="<?php echo $config['path.extjs'] ?>/build/ext-all.js">
	</script>

	<!-- load js helpers first xxx_fn.js (after xxx.js) -->
	<?php
		$fn_js = [];
		$dir = $config['path.js'];
		foreach (glob($dir . '/*fn.js') as $file) {
			$fn_js[] = $file; ?>
	<script type="text/javascript" src="<?php echo $file ?>"></script>
	<?php
		} ?>

	<!-- load js helpers xxx.js -->
	<?php
		foreach (glob($dir . '/*.js') as $file) {
			if (!in_array($file, $fn_js)) { ?>
	<script type="text/javascript" src="<?php echo $file ?>"></script>
	<?php 	}
		} ?>


	<!--
		content, function from TempateController;
		-->
	<?php $this->template_content(); ?>

</body>

</html>