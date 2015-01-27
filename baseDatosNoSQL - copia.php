<!DOCTYPE HTML>
<html>
<head>
 	<meta charset="UTF-8" />
 	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 	
        
        
</head> 	
<body>
<?php



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
	//$Fichero=$_POST['id_fichero'];

	//Obtener nombre fichero seleccionado
		
	$FicheroTestigo="C:\\xampp\\htdocs\\proyecto\\FicherosSelecionado\\"."FicheroSeleccionado.txt" ;
	$Fichero = fopen( $FicheroTestigo, "r");
	$FicheroSeleccionado = fgets ( $Fichero ) ;
	//print("<h3>Nombre fichero seleccionado ".$FicheroSeleccionado."</h3>");
	fclose( $Fichero );
		
	

	
	
	
	switch ($BaseDatos) {
    case "MongoDB":
        print("<h3>Base de datos ".$BaseDatos."</h3>");
        $FicheroSeleccionadoLimpio = trim ( $FicheroSeleccionado, $character_mask = " \t\n\r\0\x0B" ) ; 
		$PathFicheroSeleccionadoLimpio = "C:\\xampp\\htdocs\\proyecto\\FicherosSubidos\\".$FicheroSeleccionadoLimpio ;
		print("<h3>Nombre fichero seleccionado ".$FicheroSeleccionadoLimpio."</h3>");
        
		echo '<pre>';
		// Connect to the MongoD with defaults which are localhost and port 27017)
		//print("<h3> Conexion con base de datos </h3>");  
		$m = new MongoClient();
		write_log("Creada conexión a base de datos","Info");
	

	
		//Use a DataBase (will be created if it doesn't exist)
		//print("<h3> Sellecionar base de datos: TFG </h3>");
		//echo '<br />database var dump below <br />';
		//$db = $m->demodb;
		$db = $m->TFG;
		write_log("Seleccionamos base de datos TFG.","Info");
		


	
		
		//Borramos la coleccion "coleccion" para no mezclar datos entre pruebas
		//print("<h3> Borramos la coleccion para no mezclar datos entre pruebas</h3>");
		//echo '<br />database var dump below <br />';
		//$coll = $m->TFG->coleccion;
		//$response = $coll->drop();
		//print_r($response) ;
		//var_dump($response);
	
	

		
		
		// Use a Collection (will be created if it doesn't exist)
		$NombreColeccion="coleccion_".$FicheroSeleccionadoLimpio ;
		$coll = $db->$NombreColeccion;
		write_log("Creada coleccion.","Info");
		


		//borramos todos los indices de esta tabla
		//print("<h3> Borramos indices previos</h3>");
		//$coll = $m->TFG->coleccion;
		//$response = $coll->deleteIndexes();
		//print_r($response);	
		
		


		$Fichero = fopen( $PathFicheroSeleccionadoLimpio, "r" );
			write_log("Inicio carga de datos.","Info");
		    $tiempo_inicio_carga = microtime(true);
			while (( $data = fgetcsv ( $Fichero , 100 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
	
				$NumeroDeElementos = count($data) ;
				//print("<h3> NumeroDeElementos ".$NumeroDeElementos."</h3>");
				
				$i = 0;
				
				$DatosArray = array ();
			
					
	
				for ($Contador=2; $Contador <  $NumeroDeElementos ; $Contador++) {
				
					$DatosArray[$i] = floatval( $data[$Contador] );
					$i++;				

				}//End of for
				//var_dump($DatosArray);
				


  				$coll->insert(array(
    			"Secuencial" => $data[ 0 ],
    			"Slot" =>  $data[ 1 ], 
  	 			"Valor1" => $data[ 2 ],
  	 			"Valor2" => $data[ 3 ],
  	 			"Valor3" => $data[ 4 ],
  	 			"Valor4" => $data[ 5 ],
    			));
   	



			} //end of while
			$tiempo_fin_carga = microtime(true);
			write_log("Fin carga de datos.","Info");
		fclose ( $Fichero );
		$tiempo_carga = round($tiempo_fin_carga - $tiempo_inicio_carga, 2); 
		echo "Tiempo empleado carga: " . $tiempo_carga ;
	
		//Crear indices por campos
		write_log("Inicio creación índices.","Info");
		$tiempo_inicio_indice = microtime(true);
		
		$coll->createIndex(array ('Valor1' => 1));
		$coll->createIndex(array ('Valor2' => 1));
		$coll->createIndex(array ('Valor3' => 1));
		$coll->createIndex(array ('Valor4' => 1));
		
		$tiempo_fin_indice = microtime(true);
		$tiempo_indice = round($tiempo_fin_indice - $tiempo_inicio_indice, 2);
		echo "Tiempo empleado indice: " . $tiempo_indice ;
		write_log("Fin creación índices.","Info");
		


    
    	//$tiempo_inicio = microtime(true);
    	//echo '<h2 style="color:red">Below is our Document</h2>';
		//$myDoc = $coll->findOne(array('Secuencial' => '10'));
		//$myDoc = $coll->count(Datos2('x'=>1)));
		//$tiempo_fin = microtime(true);
		//print_r($myDoc);
		//echo "Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
		
		echo '</pre>';        
        
		// db.coleccion.find( { Datos2: { $gt: 30 } } ).count();
        //var_dump($collection->count(array('x'=>1)));
        
        //$nosql = array(
 		//'lvl' => array('$gt' => $user_level));    
		//$result = $collection->find($nosql);
		//$length = $result->count();
        
        break;
        
        
        
    case "Cassandra":
    	echo '<pre>';
    	
    	
    	
    	function cql_get_rows($cqlResult) { 
        	if ($cqlResult->type == 1) { 
                $rows = array(); 
                foreach ($cqlResult->rows as $rowIndex => $cqlRow) { 
                        $cols = array(); 
                        foreach ($cqlRow->columns as $colIndex => $column) { 
                                //$cols[$column->name] = $column->value; 
                        } 
                        $rows[$cqlRow->key] = $cols; 
                } 
                	return $rows; 
        	} else { 
            	return null; 
        	} 
		};
    	
    	
    	$FicheroSeleccionadoLimpio = trim ( $FicheroSeleccionado, $character_mask = " \t\n\r\0\x0B" ) ; 
		$PathFicheroSeleccionadoLimpio = "C:\\xampp\\htdocs\\proyecto\\FicherosSubidos\\".$FicheroSeleccionadoLimpio ;
        print("<h3>Base de datos ".$BaseDatos."</h3>");
        print("<h3>Nombre fichero seleccionado ".$FicheroSeleccionadoLimpio."</h3>");
        $NombreColumFamily = explode(".",$FicheroSeleccionadoLimpio);
		//echo $NombreColumFamily[0]; // Imprime "usuario"
		//echo $NombreColumFamily[1]; // Imprime "email.dom"

        

        
        require_once('phpcassa/connection.php');	//Include Connection Classes
		require_once('phpcassa/columnfamily.php'); //Include column family Classes
		require_once('phpcassa/sysmanager.php'); //
		require_once('phpcassa/uuid.php'); //
		
		
		


		//Creamos un keyspace
		write_log("Creando keyspace","Info");
		$sys = new SystemManager('127.0.0.1', array("username" => 'juan', "password" => 'juan') );
		//print_r($sys);
		
		//$sys->create_keyspace('TFG', array( "strategy_class" => StrategyClass::SIMPLE_STRATEGY, "strategy_options" => array('replication_factor' => '3') ));
		

		//Creamos una conexion contra la base de datos
		write_log("Creando conexión contra la base de datos","Info");
		$pool = new ConnectionPool('TFG', array('127.0.0.1')); 

		write_log("Creando ColumFamily","Info");
		
		

		

		//$query="CREATE TABLE ( key uuid PRIMARY KEY ,secuencial bigint, slot int, valor1 decimal, valor2 decimal, valor3 decimal, valor4 decimal )" ;
		//$query="CREATE TABLE tabloide ( secuencial bigint, slot int, valor1 decimal, valor2 decimal, valor3 decimal, valor4 decimal, PRIMARY KEY ( secuencial, valor1)  )" ;
		//$query="CREATE TABLE tabloide ( secuencial bigint , slot int, valor1 decimal, valor2 decimal, valor3 decimal, valor4 decimal,  ) " ;
		//$query = "CREATE TABLE emp ( empID int, deptID int, first_name varchar, last_name varchar, PRIMARY KEY empID, deptID ) " ;
		//$raw->client->execute_cql_query("CREATE TABLE mc_user(mc_user_id uuid primary key ,mc_user_email varchar,mc_user_pwd varchar,mc_status varchar,mc_user_type map<varchar,varchar>)", Compression::NONE)
		//print("<h3>query CREATE TABLE:  ".$query."</h3>");
		// Retrieve a raw connection from the ConnectionPool
		//$raw  = $pool->get();
		//$raw->client->set_cql_version("3.0.0");
		
		//$rows = $raw->client->execute_cql_query($query, cassandra_Compression::NONE);
 		//return the connection to the pool so it may be used by other callers. Otherwise,
		// the connection will be unavailable for use.
		//$pool->return_connection($raw);
		//unset($raw);
		
		write_log("Creando ColumFamily creada.","Info");

		
		
		$column_family = new ColumnFamily($pool, 'tabla');

		write_log("Inicio inserción datos","Info");
		//print("<h3> Path Nombre fichero seleccionado ".$PathFicheroSeleccionadoLimpio."</h3>");
		$Fichero = fopen( $PathFicheroSeleccionadoLimpio, "r" );
		
			// Retrieve a raw connection from the ConnectionPool
			$raw  = $pool->get();
			
			while (( $data = fgetcsv ( $Fichero , 100 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
	
				$NumeroDeElementos = count($data) ;
				print("<h3>NumeroDeElementos ".$NumeroDeElementos."</h3>");
				print("<h3>data[ 0 ] ".$data[ 0 ]."</h3>");
				print("<h3>data[ 1 ] ".$data[ 1 ]."</h3>");
				print("<h3>data[ 2 ] ".$data[ 2 ]."</h3>");
				print("<h3>data[ 3 ] ".$data[ 3 ]."</h3>");
				print("<h3>data[ 4 ] ".$data[ 4 ]."</h3>");
				print("<h3>data[ 5 ] ".$data[ 5 ]."</h3>");


				//for ($Contador=2; $Contador <  $NumeroDeElementos ; $Contador++) {
				
					//$DatosArray[$i] = floatval( $data[$Contador] );
					//$Indice = $Contador -1 ; 
					//$Cadena	= $Cadena."'Valor".$Indice."'"." => ".$DatosArray[$i].", ";
					//$i++;	
					//$Clave = array("key", $data[ 0 ]);
					
					//$IndiceValor = $Contador - 1 ; 
					//$Data_column_family->insert( $data[ 0 ], array( "'Valor".$IndiceValor."'" => $data[ $Contador ] ) );
					
					//var_dump($Data_column_family);
					//$Data_column_family->insert( $data[ 0 ], array( 'Valor' => $data[ $Contador ] ) );
					
					//$Data_column_family->insert('key', array('Valor' => $data[ $Contador ]));
					
					
					//$query="INSERT INTO tabla ( secuencial, slot, valor1, valor2, valor3, valor4) VALUES ( ".$data[ 0 ].", ".$data[ 1 ].", ".$data[ 2 ].", ".$data[ 3 ].", ".$data[ 4 ].", ".$data[ 5 ]." )" ;
					//$query="INSERT INTO tabla ( secuencial, valor1 ) VALUES ( '".$data[ 0 ]."', '".$data[ 2 ]."')" ;
					//print("<h3>query ".$query."</h3>");
					//$rows = $raw->client->execute_cql_query($query, cassandra_Compression::NONE);



					
					$column_family->insert( $data[ 0 ], array('secuencial' => $data[ 0 ], 'valor1' => $data[ 2 ] ) );
					//$column_family->insert( $data[ 0 ], array('secuencial' => $data[ 0 ]));
					//$column_family->insert( $data[ 0 ], array('slot' => intval ( $data[ 1 ]     ) ) );
					//$column_family->insert( $data[ 0 ], array('valor1' => floatval ( $data[ 2 ] )  )   );

				//} //End of for


			} //end of while
		fclose ( $Fichero );
		//return the connection to the pool so it may be used by other callers. Otherwise,
		// the connection will be unavailable for use.
		$pool->return_connection($raw);
		unset($raw);	
		write_log("Fin inserción datos","Info");
		
	
		//Creación de índice
		//$sys->create_index('TFG', 'Data', 'Valor1',  DataType::UTF8_TYPE);
		




		//write_log("Lanzando consulta","Info");
		//$query = "SELECT * FROM Data";
		//$query = "SELECT * FROM TFG.Data where Valor1 = '1.54' " ;
		//$raw  = $pool->get(); 
		//$rows = cql_get_rows($raw->client->execute_cql_query($query,cassandra_compression::NONE));
		//var_dump($rows); 

		//foreach($rows as $key => $columns) { 
        	//$stringkey = CassandraUtil::import($key)); 
        	//$submitdate = $columns["SubmitDate"]; 
         
		//}; 
	
		//var_dump($rows);

		//$pool->return_connection($raw); 
		//unset($raw);




				
		
		
		// Clear out the column family
		//$Data_column_family->truncate();
		
		

	
		//Cerramos las conexiones
		write_log("Cerrando conexion contra la base de datos","Info");
		$pool->close();



		//Destruimos el esquema
		//write_log("Borrando Keyspace","Info");
		//$sys->drop_keyspace("TFG");
		write_log("Cerrando conexion contra el servidor","Info");
		$sys->close();

        echo '</pre>';
        
        break;
        
        
        
        
        
    case "c-treeACE":
        print("<h3>Base de datos ".$BaseDatos."</h3>");
        print("<h3>Nombre fichero seleccionado ".$FicheroSeleccionado."</h3>");
        break;
        
        
        
        
	} //end of switch
	
	
	
	
	
	
	

	
?>
</body> 
</html> 