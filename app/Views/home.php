<?php

$config = $this->config;
?>

<!DOCTYPE HTML>
<html>

<head>
	<meta charset="utf-8" />
</head>

<body>

	<div class="col-home">
		<?php if (!empty($_SESSION['user_name'])) {
	$tab = explode(' ', $_SESSION['user_name']); ?>
		<span style="font-size: 20px">
			<pre>Hello <?php echo $tab[0] ?>,</pre></span>
		<?php
} ?>

		<span style="font-size: 20px">
			<pre><?php echo date('l') ?>, <?php echo date('Y M d') ?> (CW <?php echo date('W') ?>)</pre>
		</span>
	</div>

	<div class="col-home-bottom-text-left">
		<pre>some info bottom left</pre>
	</div>

	<div class="col-homelogo">
		<img
			src="<?php echo $config['logo.home'] ?>" />
	</div>

	<div class="col-home-bottom-text-right">
		<pre><?php echo $config['app.name'] ?><small> <?php echo $config['app.vers'] ?></pre>
		</small>
	</div>

</body>

</html>