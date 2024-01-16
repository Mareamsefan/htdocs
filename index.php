<?php

/*
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/dashboard/');
	exit;
	*/
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$inputVerdi = $_POST["inputBoks"]; 
	echo "Du skrev: " .$inputVerdi;
}	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<h1>Signup</h1>
	<form>
		<div>
			<label for="Name">Name</label>
			<input type="text" id="name" name="name">
		</div>

		<l
	</form>

	
	
</body>
</html>
