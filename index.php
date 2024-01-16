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
	<h1>This is home page</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label for="inputBoks">Skriv noe:</label>
		<input type="text" id="inputBoks" name="inputBoks">
		<input type="submit" value="Send">
	</form>

	
	
</body>
</html>
