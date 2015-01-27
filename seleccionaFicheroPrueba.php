<html>
<head>
 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head> 	
<body>
<?php

	set_time_limit ( 0 );
	

	

	echo '<h3 style="font-family:verdana">Seleccionar fichero de ejemplares.</h3>';
?>

	<form action="subirFicheroDatos.php" method="post" enctype="multipart/form-data">
		<label for="file">Fichero:</label>
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Envio">
	</form>


	

</body> 
</html> 