<!DOCTYPE HTML>
<html>
<head>
 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head> 	
<body>
<?php


	set_time_limit ( 0 );
	$NumeroDeLineas=$_POST['NumeroLineas'];
	$TiempoMinimoEntreMuestra=$_POST['TiempoMinimoEntreMuestra'];
	$TiempoMaximoEntreMuestra=$_POST['TiempoMaximoEntreMuestra'];

	
	
	//Ubicación dónde se van a generar los ficheros de carga
	//C:\\xampp\\htdocs\\proyecto\\FicherosDeCarga\\
	
	$NombreFichero="C:\\xampp\\htdocs\\proyecto\\FicherosDeCarga\\".$NumeroDeLineas."."."txt";

	$FicheroEjemplares = fopen( $NombreFichero, "w") or die("Unable to open file!");
	
	for ($i = 1; $i <= $NumeroDeLineas; $i++) {
	

		$NumeroMuestrasEnLinea = 4 ;
		
		$TiempoEntreMuestra = mt_rand($TiempoMinimoEntreMuestra,$TiempoMaximoEntreMuestra) ;
		

		
		$Mediciones="";

		
		for ( $contador = 1; $contador <= $NumeroMuestrasEnLinea ; $contador++  ){
		
			 
			$NumeroAleatorio=1000/mt_rand(1.01,999.99)  ;
			//print("<h3>NumeroAleatorio".$NumeroAleatorio."</h3>");
			$NumeroAletarioRedondeado = round ( $NumeroAleatorio, 2 );
			

			
			//Si el número aleatorio no tiene decimales, se le añaden dos. .00
			$caracterPunto  = '.';
			$posicion = strpos( ( string ) $NumeroAletarioRedondeado, $caracterPunto );

			if ( $posicion === false) {
				//Si no existe el caracer punto, se añaden dos decimales ".01"
    			$NumeroAletarioRedondeado = ( string ) $NumeroAletarioRedondeado.".01" ;
    			
			}
				else {
			
  				//Existe el carater punto decimal, pero vamos a comprobar si solo
  				//existe un numero decimal, redondeamos a otro más con cero
  				$LongitudCadena = strlen( ( string ) $NumeroAletarioRedondeado);

  				
  				
  				if ( ( (int) $LongitudCadena - (int ) $posicion  ) == 2 ){
  					( string ) $NumeroAletarioRedondeado = ( string ) $NumeroAletarioRedondeado."0" ;
  				}
  					
  				
			}
			
					
			if ( $contador == $NumeroMuestrasEnLinea){
				$Mediciones= $Mediciones.$NumeroAletarioRedondeado ;
			} else
			{
				$Mediciones= $Mediciones.$NumeroAletarioRedondeado."," ;
			}; 
			

			
		} //end of for
		
		
		
    	$Linea = $i.",".$TiempoEntreMuestra.",".$Mediciones.chr(13).chr(10) ; 
    	
		fwrite($FicheroEjemplares, $Linea);
	
	}
	
	flush();


	
	fclose($FicheroEjemplares);
	echo ("<script>location.href='index.html'</script>"); 
	
	
?>


 
	

</body> 
</html> 