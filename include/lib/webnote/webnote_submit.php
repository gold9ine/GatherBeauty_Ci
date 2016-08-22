<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>WebNote 서버페이지</title>
</head>

<body>


<?php
	$data = print_r($_POST,true); 
	echo "<pre>";
	echo htmlspecialchars(stripslashes($data));
	echo "</pre>";
?>

</body>

</html>
