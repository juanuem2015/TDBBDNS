<!DOCTYPE HTML>
<html>
<head>
 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head> 	
<body>
<?php

	set_time_limit ( 0 );

	if ($_FILES["file"]["error"] > 0) {
  		echo "Error: " . $_FILES["file"]["error"] . "<br>";	
	} else {
  		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  		echo "Type: " . $_FILES["file"]["type"] . "<br>";
  		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  		echo "Stored in: " . $_FILES["file"]["tmp_name"];
  		//move_uploaded_file( $_FILES["file"]["name"], "C:\\xampp\\htdocs\\proyecto\\FicherosSubidos\\"."lalala.txt" );
  		copy( $_FILES["file"]["tmp_name"] , "C:\\xampp\\htdocs\\proyecto\\FicherosSubidos\\".$_FILES["file"]["name"]  ) ;
  		//generar un fichero con el fichero seleccionado actual.
  		$NombreFichero=$_FILES["file"]["name"];
  		$FicheroTestigo="C:\\xampp\\htdocs\\proyecto\\FicherosSelecionado\\"."FicheroSeleccionado.txt" ;
  		$Fichero = fopen( $FicheroTestigo, "w");
  		$Linea = $NombreFichero.chr(13).chr(10) ;
  		fwrite( $Fichero, $Linea);
  		flush();
		fclose( $Fichero );
  		
	}
	
	
	echo ("<script>location.href='index.html'</script>");
?>
	
 
 





	

</body> 
</html> 