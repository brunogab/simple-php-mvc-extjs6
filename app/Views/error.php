<?php
/**
 * simple html error
 */
 ?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

			<style type='text/css'>
				html, body {
					background-color:	#f5f5f5;
					font-size:			16px;
					color:				#e56666;
				}
			</style>

	</head>

	<body>
		<center>
			<code>
			</br>
				<?php echo $text; ?>
			</br>
				<small>Time: <?php echo date('Y-m-d H:i:s') ?></small>
			</code>
		</center>
	<body>
</html>
