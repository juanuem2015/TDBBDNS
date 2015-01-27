<!DOCTYPE HTML>
<html>
<head>
 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head> 	
<body>
<?php

	set_time_limit ( 0 );
	


	echo '<h3 style="font-family:verdana">Generación de fichero de ejemplares.</h3>';
?>

	<br>

	<form action="generadorFicheros.php" method="post">

		

		Número de líneas en fichero: <input type="text" name="NumeroLineas"><br>
		Número mínimo de milisegundos entre muestra: <input type="number" name="TiempoMinimoEntreMuestra" min="0" max="100" step="1" value="1"><br>
		Número maximo de milisegundos entre muestra: <input type="number" name="TiempoMaximoEntreMuestra" min="0" max="100" step="1" value="1"><br>
		


	<input type="submit">
	</form>

	
 
 





	

</body> 
</html> 