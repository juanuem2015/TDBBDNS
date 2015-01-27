<!DOCTYPE HTML>
<html>
<head>

 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 	        
        
</head> 	
<body>
<?php



//Modificación para que las operaciones contra MongoDB no terminen por timeout. 
MongoCursor::$timeout = -1;

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
		
			


	//No limitamos el tiempo de ejecución 
	set_time_limit ( 0 );
	
	$BaseDatos=$_POST['id_basedatos'];


	//Obtener nombre fichero seleccionado
		
	$FicheroTestigo="C:\\xampp\\htdocs\\proyecto\\FicherosSelecionado\\"."FicheroSeleccionado.txt" ;
	$Fichero = fopen( $FicheroTestigo, "r");
	$FicheroSeleccionado = fgets ( $Fichero ) ;
	//print("<h3>Nombre fichero seleccionado ".$FicheroSeleccionado."</h3>");
	fclose( $Fichero );
		
	echo "<div id='progress' style='position:relative;padding:0px;width:450px;height:60px;left:25px;'>";

	
	
	
	switch ($BaseDatos) {
    case "MongoDB":

    	echo '<pre>';  	

    	echo "<br>";
    	echo "<center><img border=1 src=\"mongodb.jpg\"></center>";
    	echo "<br>";
		echo "<br>";
		echo "<br>";
 
    	
    	write_log("Inicio test base de datos MongoDB","Info");

    	//Abrimos conexión contra la base de datos
    	$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());

		
		#Seleccionamos la base de datos a utilizar
		mysqli_select_db($conexion, "tfg");
		
		$acentos = $conexion->query("SET NAMES 'utf8'");
		
		$sql = "SELECT Id_prueba, descripcion, cadena, nombre_fichero FROM testmongo order by nombre_fichero asc";
		$result = $conexion->query($sql);
		
		//Conectar a la base de datos mongo
		$m = new MongoClient();
		$db = $m->TFG;
		//$Coleccion = explode(".",$FicheroSeleccionadoLimpio);
		//$NombreColeccion="coleccion_".$Coleccion[0] ;
		//$collection = new MongoCollection($db, $NombreColeccion);

		
		if ($result->num_rows > 0) {
     	// output data of each row
     		while($row = $result->fetch_assoc()) {
         		//echo "<br> id: ". $row["Id_prueba"]. " - cadena: ". $row["cadena"]. " " . $row["nombre_fichero"] . "<br>";
         		
         	
         		//Seleccionamos coleccion
         		$Fichero = $row["nombre_fichero"] ;
         		$NombreFichero = explode(".",$Fichero);
         		$Coleccion = "coleccion_".$NombreFichero[0] ;
         		//print("<h3>Coleccion: ".$Coleccion."</h3>");
         		$coll = $db->$Coleccion;
         	


         		//Conformar query 
         		switch ($row["Id_prueba"]) {
         		
         			case "MON_1":
						$Query = array( 'Valor1' => array( '$gt' => 4.12 ) );
        				break;
    				case "MON_2":
						$Query = array( 'Valor1' => array( '$lt' => 4.12 ) );
        				break;
    				case "MON_3":
        				$Query = array( 'Valor1' => array( '$gt' => 4.12, '$lt' => 6.00) );	
        				break;
        			case "MON_4":
        				$Query = array( 'Secuencial' => array('$in' => array(100000, 200000, 300000)) );	
        				break;
        			case "MON_5":
        				$Query = array( '$and' => array( array('Valor1' =>4.12), array('Valor2'=>1.72)) );
        				break;
        			case "MON_6":
        				$Query = array( '$or' => array( array('Valor1' =>9.01), array('Valor2'=>1.72)) );
        				break;
        			case "MON_7":
        				$Query = array( '$and' => array( array('Valor1' =>array( '$gt' => 4.12, '$lt' => 6.00) ), array('Valor2'=>array( '$gt' => 4.12, '$lt' => 6.00))) );
        				break;
        			case "MON_8":
        				$Query = array( '$or' => array( array('Valor1' =>array( '$gt' => 4.12, '$lt' => 6.00) ), array('Valor2'=>array( '$gt' => 4.12, '$lt' => 6.00)))  );
        				break;
				} //end of switch 
				

         		$Mensaje = "Inicio test: ".$row["Id_prueba"]." fichero: ".$Fichero." en colección: ".$Coleccion ; 
         		write_log( $Mensaje,"Info");
         		
         		$inicio= microtime(true);
				//$cursor = $coll->find($Query)->timeout(-1);
				$cursor = $coll->find($Query)->count();
				$final = microtime(true);
				write_log( "Número de ocurrencias encontradas: ".$cursor,"Info");

				$totaltiempo= $final- $inicio;
				

         	


         	
         		//Medir tiempo de query y registrar en MySQL
         		$update = "UPDATE testmongo SET tiempo=".$totaltiempo.", count=".$cursor." WHERE Id_prueba='".$row["Id_prueba"]."' AND nombre_fichero ='".$row["nombre_fichero"]."'";
         		//print("<h3>update: ".$update."</h3>");

				if (mysqli_query($conexion, $update)) {
    				//echo "Record updated successfully";
    				write_log("Registro actualizado correctamente","Info");
				} else {
    			echo "Error updating record: " . mysqli_error($conexion);
				}//end of if

         	 
     	}
		} else {
     		echo "0 results";
		} //end of while
		

	
		
		//Cerramos conexión contra la base de datos
		#Cerramos la conexión con la base de datos
		mysqli_close($conexion);
    	
		write_log("Fin test base de datos MongoDB","Info");

        echo '</pre>';
    

        
        break;
        
        
        
    case "Cassandra":
    	echo '<pre>';
    	

    	echo "<center><img border=1 src=\"cassandra.jpg\"></center>";
    	echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
    	
    	write_log("Inicio test base de datos Cassandra","Info");
    	
     	// Conexion a base de datos Cassandra.
		$nodes = [ '127.0.0.1:9042' => [ 'username' => 'cassandra', 'password' => 'cassandra'] ];
		$database = new evseevnn\Cassandra\Database($nodes, 'system');
		$database->connect();
    	
    	
    	//Abrimos conexión contra la base de datos
    	$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());

		
		//#Seleccionamos la base de datos a utilizar
		mysqli_select_db($conexion, "tfg");
		
		$acentos = $conexion->query("SET NAMES 'utf8'");
		
		$sql = "SELECT Id_prueba, descripcion, cadena, nombre_fichero FROM testcassandra order by nombre_fichero asc";
		$result = $conexion->query($sql);
		
		
		if ($result->num_rows > 0) {
     	// output data of each row
     	
     	
      	
     	
     	while($row = $result->fetch_assoc()) {
         	//echo "<br> id: ". $row["Id_prueba"]. " - Name: ". $row["cadena"]. " " . $row["nombre_fichero"] . "<br>";
         	write_log("TEST:".$row["Id_prueba"]."- Fichero:".$row["nombre_fichero"]." " ,"Info");
         	
         	//Conformar query

         	$Fichero = explode(".",$row["nombre_fichero"]);
         	$Keyspace= "tfg".$Fichero[0] ;
         	
         	//Cambiamos al keyspace correspondiente
         	$database->setKeyspace($Keyspace);
         	
         	$table = "tabla".$Fichero[0] ;
         	$Query = $row["cadena"];

         	$Query = str_replace( "token1", "count(*)" , $Query );
         	$Query = str_replace( "token2", $Keyspace.".".$table, $Query );
         	$Query = str_replace( "token3", "valor1", $Query );
         	$Query = str_replace( "token4", "valor2", $Query );
         	$Query = str_replace( "token5", "secuencial", $Query );
         	//print("<h3>Query ".$Query."</h3>");
         	
         	//Ejecutar query contra Cassandra midiendo tiempo
         	$Mensaje = "Inicio test: ".$row["Id_prueba"]." fichero: ".$Fichero[0].".txt en tabla: ".$table ; 
         	write_log( $Mensaje,"Info");
         	

			$inicio= microtime(true);
			
			$Busqueda = $database->query($Query);

			$Contador = (int ) $Busqueda[0]["count"];
			write_log( "Número de ocurrencias encontradas: ".$Contador,"Info");
			
						
			$final = microtime(true);

			$totaltiempo= $final- $inicio;
			//echo 'Resultado de las lineas ejecutadas en '.$totaltiempo.' segundos';

         	
         	//Medir tiempo de query y registrar en MySQL
         	$update = "UPDATE testcassandra SET tiempo=".$totaltiempo.", count=".$Contador." WHERE Id_prueba='".$row["Id_prueba"]."' AND nombre_fichero ='".$row["nombre_fichero"]."'";


			if (mysqli_query($conexion, $update)) {
    			//echo "Record updated successfully";
    			write_log("Registro actualizado correctamente","Info");
			} else {
    			//echo "Error updating record: " . mysqli_error($conexion);
    			write_log("Error actualizado el registro.","Error");
			}//end of if

         	 
     	}
		} else {
     		//echo "0 results";
		} //end of while
		
		
		//Cerramos conexión contra la base de datos
		//#Cerramos la conexión con la base de datos
		mysqli_close($conexion);
    	

		write_log("Fin test base de datos Cassandra","Info");

        echo '</pre>';
        
        break;
        
        
        
      
        
      
        
        
        
	} //end of switch
	
	
	
	
	
	
	

	
?>
</body> 
</html> 