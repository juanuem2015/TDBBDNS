<?php


/*  **********************************************************************************************************************
    FUNCIÓN PARA CONVERTIR UN ARRAY ASOCIATIVO EN UNA CADENA EN FORMATO XML.
    PARÁMETROS:
    -----------
    $data             --> ES EL ARRAY DE ORIGEN
    $rootNodeName    --> INDICA CÓMO SE LLAMARÁ EL NODO RAÍZ.
    $xml            --> NO CAMBIAR. INDICA EL NODO DÓNDE ESTA. SE USA PARA EL PROCESO RECURSIVO.
    **********************************************************************************************************************
/**
 * Function for convert an array associative to XML string.
 * @param string $data The source array.
 * @param string $rootNodeName Indicates how to call at root node.
 * @param string $xml Is an attributte that is necessary for operation of the method.
 * @return string Returns a XML string.
 */    
function array2XML($data, $rootNodeName = 'results', $xml=NULL){
    if ($xml == null){
        $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
    }

    foreach($data as $key => $value){
        if (is_numeric($key)){
            $key = "nodeId_". (string) $key;
        }

        if (is_array($value)){
            $node = $xml->addChild($key);
            array2XML($value, $rootNodeName, $node);
        } else {
            $value = htmlentities($value);
            $xml->addChild($key, $value);
        }

    }

    return html_entity_decode($xml->asXML());
}

//$arr = array("user" => "root", "user_id" => "1", "password" => "contraseña", "mensaje" => array("id" => "1", "descripcion" => "Descripción del texto", "prioridad" => "Alta"));
//$arr=array( "Valor1" => array( "tokengt" => 4.12 ) ) ;
$arr=array( "tokenor" => array( array("Valor1" =>array( "tokengt" => 4.12, "tokenlt" => 6.00) ), array("Valor2"=>array( "tokengt" => 4.12, "tokenlt" => 6.00))) ) ;
$xml = array2xml($arr,'query');
header('Content-Type: application/xml; charset=utf-8');
echo $xml;







//$xmlsalida = simplexml_load_string($xml);

//print_r($xmlsalida);

exit ;

//$cadena = array ( eval('Valor1' => array( '$gt' => 4.12 )) ) ; 
//print_r ( $cadena1 );
//parse_str($cadena);
//eval($cadena);

//$Cadena = "'Valor1' => array( 'token1gt' => 4.12 )" ;
//print_r ( $Cadena );


//$Query = ( array ) str_replace( "token1", "$" , $Cadena );
//$Query = str_replace( "token1", "count(*)" , $Query );
//print_r ( $Query ); 


//$Consulta = array( $Query );
//print_r ( $Consulta );



		//Conectar a la base de datos mongo
		//$m = new MongoClient();
		//$db = $m->TFG;
		
        //Seleccionamos coleccion

        //print("<h3>Coleccion: ".$Coleccion."</h3>");
        //$Coleccion = "coleccion_"."100000" ;
        $coll = $db->$Coleccion;
        
        
        //$cursor = $coll->find($Cadena)->count();
        
 //      var_dump($cursor);
        
       //print_r($cursor); 		
		

exit ; 


$x_axis = array();
$y1_axis = array();

$i = 0;
 
//Abrimos conexión contra la base de datos
$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());

#Seleccionamos la base de datos a utilizar
mysqli_select_db($conexion, "tfg");

$sql = "SELECT Id_prueba, nombre_fichero, tiempo, descripcion FROM testcassandra where Id_prueba = 'CAS_1' ";
$result = $conexion->query($sql);
 
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
	    $NumeroEjemplares = explode(".",$row["nombre_fichero"]);
		$x_axis[$i] = $NumeroEjemplares[0];
		$y1_axis[$i] = $row["tiempo"];
		$descripcion = $row["descripcion"];
		$identificadorPrueba = $row["Id_prueba"] ;


		//echo "<br> id: ". $row["NombreFicheroCarga"]. " - Name: ". $row["TiempoCarga"]. " " . $row["TiempoIndice"] . "<br>";
    	$i++;
	
	} //end of while
	
;	

}// end of if
 


//mysqli_close($conexion);
     
array_multisort($x_axis, $y1_axis);

$Legenda = $identificadorPrueba." - ".$descripcion ;
     
//print_r($x_axis);
//print_r($y1_axis);
//print_r($y2_axis);
     
//exit;


//$datay1 = array(20,15,23,15,80,20,45,10,5,45,60);
//$datay2 = array(12,9,12,8,41,15,30,8,48,36,14,25);
//$datay3 = array(5,17,32,24,4,2,36,2,9,24,21,23);
 
// Setup the graph
$graph = new Graph(900,350);
$graph->SetScale("textlin");
 
$theme_class=new UniversalTheme;
 
$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set($Legenda);
$graph->SetBox(false);
 
$graph->img->SetAntiAliasing();
 
$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->title->Set('Segundos');
 
$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($x_axis);
$graph->xgrid->SetColor('#E3E3E3');
 
// Create the first line
$p1 = new LinePlot($y1_axis);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend($descripcion);
 

 

 
$graph->legend->SetFrameWeight(1);
 
// Output line
$graph->Stroke();



?>
    </body>
</html>