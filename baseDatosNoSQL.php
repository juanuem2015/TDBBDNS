<!DOCTYPE HTML>
<html>
<head>
 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 	
        
        
</head> 	
<body>
<?php

require_once('evseevnn/Cassandra/Database.php');
require_once('evseevnn/Cassandra/Cluster.php');
require_once('evseevnn/Cassandra/Connection.php');
require_once('evseevnn/Cassandra/Cluster/Node.php');

require_once('evseevnn/Cassandra/Protocol/RequestFactory.php');
require_once('evseevnn/Cassandra/Protocol/Request.php');
require_once('evseevnn/Cassandra/Protocol/Frame.php');
require_once('evseevnn/Cassandra/Protocol/Response.php');
require_once('evseevnn/Cassandra/Protocol/BinaryData.php');

require_once('evseevnn/Cassandra/Protocol/Response/Rows.php');
require_once('evseevnn/Cassandra/Protocol/Response/DataStream.php');

require_once('evseevnn/Cassandra/Protocol/Response/DataStream/TypeReader.php');


require_once('evseevnn/Cassandra/Enum/OpcodeEnum.php');

require_once('evseevnn/Cassandra/Enum/ConsistencyEnum.php');
require_once('evseevnn/Cassandra/Enum/ErrorCodesEnum.php');
require_once('evseevnn/Cassandra/Enum/ResultTypeEnum.php');
require_once('evseevnn/Cassandra/Enum/DataTypeEnum.php');
require_once('evseevnn/Cassandra/Enum/FlagsEnum.php');
require_once('evseevnn/Cassandra/Enum/VersionEnum.php');


require_once('evseevnn/Cassandra/Exception/CassandraException.php');
require_once('evseevnn/Cassandra/Exception/ConnectionException.php');
require_once('evseevnn/Cassandra/Exception/QueryException.php');
require_once('evseevnn/Cassandra/Exception/ClusterException.php');
require_once('evseevnn/Cassandra/Exception/ResponseExceprion.php');



require_once('evseevnn/Cassandra/Protocol/Response/DataStream.php');






	/**
 	* Escribe lo que le pasen a un archivo de logs
 	* @param string $cadena texto a escribir en el log
 	* @param string $tipo texto que indica el tipo de mensaje. Los valores normales son Info, Error,  
 	*                                       Warn Debug, Critical
 	*/
	function write_log($cadena,$tipo)
		{
			
			//print("<h3>Base de datos ".$BaseDatos."</h3>");
			print("<h3>[".date("Y-m-d H:i:s")." "." - $tipo ] ".$cadena.chr(13).chr(10)."</h3>");
			
			$arch = fopen(realpath( '.' )."\logs\milog_".date("Y-m-d").".txt", "a+"); 

			fwrite($arch, "[".date("Y-m-d H:i:s")." "." - $tipo ] ".$cadena.chr(13).chr(10));
			fclose($arch);
			
		} //end of function write_log
		
		
		
		
	/**
 	* inserta en base de datos tiempo de carga de datos y tiempo de creación de índice
 	* @param string $cadena texto a escribir en el log
 	* @param string $tipo texto que indica el tipo de mensaje. Los valores normales son Info, Error,  
 	*                                       Warn Debug, Critical
 	*/		
	function cargadatos_cassandra( $FicheroSeleccionado, $totalcarga, $tiempo_indexacion )
	{
	
	
		$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());
		
		#Seleccionamos la base de datos a utilizar
		mysqli_select_db($conexion, "tfg");
		
		#insertamos los campos NombreFicheroCarga, TiempoCarga, TiempoIndice, descripcion
		$Insert = "INSERT INTO tfg.cargadatoscassandra ( NombreFicheroCarga, TiempoCarga, TiempoIndice ) VALUES ( '".$FicheroSeleccionado."' ,".$totalcarga." ," .$tiempo_indexacion." ) ; " ;


		mysqli_query($conexion, $Insert );
		
		#Cerramos la conexión con la base de datos
		mysqli_close($conexion);


	}// end of cargadatos_cassandra
	
	
	function cargadatos_mongo( $FicheroSeleccionado, $tiempo_carga, $tiempo_indice )
	{
	

		$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());
		
		#Seleccionamos la base de datos a utilizar
		mysqli_select_db($conexion, "tfg");
		
		#insertamos los campos NombreFicheroCarga, TiempoCarga, TiempoIndice, descripcion
		$Insert = "INSERT INTO tfg.cargadatosmongo ( NombreFicheroCarga, TiempoCarga, TiempoIndice ) VALUES
		 ( '".$FicheroSeleccionado."' ,".$tiempo_carga." ," .$tiempo_indice." ) ; " ;


		mysqli_query($conexion, $Insert );
		
		#Cerramos la conexión con la base de datos
		mysqli_close($conexion);


	}// end of cargadatos_mongo
	
			

	//No limitamos el tiempo de ejecución 
	set_time_limit ( 0 );
	

	
	$BaseDatos=$_POST['id_basedatos'];


	//Obtener nombre fichero seleccionado
		
	$FicheroTestigo="C:\\xampp\\htdocs\\proyecto\\FicherosSelecionado\\"."FicheroSeleccionado.txt" ;
	$Fichero = fopen( $FicheroTestigo, "r");
	$FicheroSeleccionado = fgets ( $Fichero ) ;
	fclose( $Fichero );
		
	

	
	
	
	switch ($BaseDatos) {
    case "MongoDB":
    
        echo "<br>";
    	echo "<center><img border=1 src=\"mongodb.jpg\"></center>";

        echo "<br>";
		echo "<br>";
		echo "<br>";

        
  
        $FicheroSeleccionadoLimpio = trim ( $FicheroSeleccionado, $character_mask = " \t\n\r\0\x0B" ) ; 
		$PathFicheroSeleccionadoLimpio = "C:\\xampp\\htdocs\\proyecto\\FicherosSubidos\\".$FicheroSeleccionadoLimpio ;


	 	write_log("Nombre fichero seleccionado: ".$FicheroSeleccionadoLimpio,"Info");

        
		echo '<pre>';
		// Connect to the MongoD with defaults which are localhost and port 27017)
		$m = new MongoClient();
		write_log("Creada conexión a base de datos","Info");
 
	

	
		//Use a DataBase (will be created if it doesn't exist)
		$db = $m->TFG;
		write_log("Seleccionamos base de datos TFG.","Info");
 
				
		// Use a Collection (will be created if it doesn't exist)
		$Coleccion = explode(".",$FicheroSeleccionadoLimpio);
		$NombreColeccion="coleccion_".$Coleccion[0] ;
		$coll = $db->$NombreColeccion;
		write_log("Creada coleccion.","Info");

		

		$Fichero = fopen( $PathFicheroSeleccionadoLimpio, "r" );
		write_log("Inicio carga de datos.","Info");

		$tiempo_inicio_carga = microtime(true);
		while (( $data = fgetcsv ( $Fichero , 100 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
		
			$NumeroDeElementos = count($data) ;
			//print("<h3> NumeroDeElementos ".$NumeroDeElementos."</h3>");
				
			$i = 0;
				
			$DatosArray = array ();
			$Secuencial = intval ( $data[ 0 ] );
			$Slot = intval ( $data[ 1 ] );	
	
			for ($Contador=2; $Contador <  $NumeroDeElementos ; $Contador++) {
				$DatosArray[$i] = floatval( $data[$Contador] );
				$i++;				

			}//End of for

			
			 //Antes de la primera insercion creamos la primary key
   			if ($data[ 0 ] == 1) {
   				write_log("Creando índice.","Info");
  				$coll->createIndex(array ('Valor1' => 1));
  				write_log("Creado índice.","Info");
				}; //end of if

    			
    		$coll->insert(array( 'Secuencial' => $Secuencial , 'Slot' =>  $Slot , 'Valor1' => $DatosArray [ 0 ], 'Valor2' => $DatosArray[ 1 ], 'Valor3' => $DatosArray[ 2 ],
  	 			'Valor4' => $DatosArray[ 3 ],
    			));
			



		} //end of while
		$tiempo_fin_carga = microtime(true);
		write_log("Fin carga de datos.","Info");
 
		fclose ( $Fichero );
		$tiempo_carga = $tiempo_fin_carga - $tiempo_inicio_carga ; 

	
		//Crear indices por campos
		write_log("Inicio creación índices.","Info");

		$tiempo_inicio_indice = microtime(true);
		
		$coll->createIndex(array ('Valor2' => 1));

		$tiempo_fin_indice = microtime(true);
		$tiempo_indice = $tiempo_fin_indice - $tiempo_inicio_indice ;

		write_log("Fin creación índices.","Info");
 
		
		//Insertamos tiempos de carga y tiempo de ejecución en base de datos
		cargadatos_mongo( $FicheroSeleccionado, $tiempo_carga, $tiempo_indice );
 
        
	echo '</pre>'; 
        
        break;
        
        
        
    case "Cassandra":
    	echo '<pre>';
   
    	
        echo "<br>";
    	echo "<center><img border=1 src=\"cassandra.jpg\"></center>";
    	echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		    	
    	$FicheroSeleccionadoLimpio = trim ( $FicheroSeleccionado, $character_mask = " \t\n\r\0\x0B" ) ; 
		$PathFicheroSeleccionadoLimpio = "C:\\xampp\\htdocs\\proyecto\\FicherosSubidos\\".$FicheroSeleccionadoLimpio ;

        $NombreColumFamily = explode(".",$FicheroSeleccionadoLimpio);
        write_log("Nombre fichero seleccionado: ".$FicheroSeleccionadoLimpio,"Info");


		//Realizamos conexion con la base de datos
		$nodes = [ '127.0.0.1:9042' => [ 'username' => 'cassandra', 'password' => 'cassandra'] ];
		$database = new evseevnn\Cassandra\Database($nodes, 'tfg1000');
		$database->connect();

		

		//Creamos un keyspace
		write_log("Creando keyspace","Info");

		//CREATE KEYSPACE tfg WITH replication = {'class':'SimpleStrategy', 'replication_factor':3};
		$Linea="CREATE KEYSPACE tfg".$NombreColumFamily[0]." WITH replication = {'class':'SimpleStrategy', 'replication_factor':1};" ;

		$keyspace = $database->query($Linea);




		write_log("Creado keyspace","Info");

		
		
		
		//Creamos una tabla
		write_log("Creando Column Family","Info");

		//CREATE TABLE tabla ( secuencial bigint, slot int, valor1 float, valor2 float, valor3 float, valor4 float, PRIMARY KEY ( secuencial, valor1, valor2)  )
		$Linea="CREATE TABLE tfg".$NombreColumFamily[0].".tabla".$NombreColumFamily[0]." ( secuencial bigint, slot int, valor1 double, valor2 double, valor3 double, valor4 double, PRIMARY KEY ( secuencial, valor1, valor2));" ;
		//print("<h3>Linea ".$Linea."</h3>");
		$columfamily = $database->query($Linea);
		//var_dump($columfamily);
		write_log("Creada Column Family","Info");


		



		write_log("Inicio inserción datos","Info");


		$Fichero = fopen( $PathFicheroSeleccionadoLimpio, "r" );
		
		$Keyspace = "tfg".$NombreColumFamily[0] ;
		$database->setKeyspace($Keyspace);


		$results = array();
		$inicio= microtime(true);

		while (( $data = fgetcsv ( $Fichero , 100 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
	
					

			$insercion = $columfamily = $database->query(
			'INSERT INTO tabla'.$NombreColumFamily[0].' ( secuencial, valor1, valor2, slot, valor3, valor4  )
			VALUES ( :secuencial, :valor1, :valor2, :slot, :valor3, :valor4   )',
			['secuencial' => $data[ 0 ], 'valor1' =>  $data[ 2 ], 'valor2' =>  $data[ 3 ],'slot' => $data[ 1 ], 
			'valor3' =>  $data[ 4 ], 'valor4' => $data[ 5 ] ] 
			);
				
			
		} //end of while



		$final = microtime(true);

		$totalcarga= $final- $inicio;

		
   		fclose ( $Fichero );
		
		write_log("Fin inserción datos","Info");
 



		write_log("Inicio creación índice","Info");
 
		$Linea="CREATE INDEX indice_valor3_".$NombreColumFamily[0]." ON tfg".$NombreColumFamily[0].".tabla".$NombreColumFamily[0]." ( valor3 );" ;
		$inicio= microtime(true);
		$creacion_indice = $database->query($Linea);
		$final = microtime(true);
		$tiempo_indexacion= $final- $inicio;


		write_log("Fin creación índice","Info");
 
		
		
		//Cargamos datos 
		cargadatos_cassandra(  $FicheroSeleccionado, $totalcarga, $tiempo_indexacion );

        echo '</pre>';
        
        break;
        
        
        
        

        
        
        
        
	} //end of switch
	
	
	
	
	
	
	

	
?>
</body> 
</html> 