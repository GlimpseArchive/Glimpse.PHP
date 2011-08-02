<?php require_once '../source/index.php'; //'phar://../build/Glimpse.phar'; ?>
<html>
	<head>
		<title>Hello world!</title>
	</head>
	
	<?php Glimpse_Trace::info('Rendering body...'); ?>
	<body>
		<h1>Hello world!</h1>
		<p>This is just a test.</p>
	</body>
	<?php Glimpse_Trace::info('Rendered body.'); ?>
</html>