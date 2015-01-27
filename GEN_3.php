<?php


 require_once('/jpgraph-3.5.0b1/src/jpgraph.php');
 require_once('/jpgraph-3.5.0b1/src/jpgraph_line.php');
 require_once('/jpgraph-3.5.0b1/src/jpgraph_error.php'); 

 
 
$x1_axis = array();
$x2_axis = array();

$y1_axis = array();
$y2_axis = array();
$i = 0;
 
//Abrimos conexión contra la base de datos
$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());

#Seleccionamos la base de datos a utilizar
mysqli_select_db($conexion, "tfg");

$sql = "SELECT tiempo, nombre_fichero FROM testmongo WHERE Id_prueba = 'MON_3' ORDER BY nombre_fichero;";
$result = $conexion->query($sql);
 
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
	    $NumeroEjemplares = explode(".",$row["nombre_fichero"]);
		$x1_axis[$i] = $NumeroEjemplares[0];
		$y1_axis[$i] = $row["tiempo"];
		//echo "<br> id: ". $row["NombreFicheroCarga"]. " - Name: ". $row["TiempoCarga"]. " " . $row["TiempoIndice"] . "<br>";
    	$i++;
	
	} //end of while

}// end of if
 
 
 
$sql = "SELECT tiempo, nombre_fichero FROM testcassandra WHERE Id_prueba = 'CAS_3' ORDER BY nombre_fichero;";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
	    $NumeroEjemplares = explode(".",$row["nombre_fichero"]);
		$x2_axis[$i] = $NumeroEjemplares[0];
		$y2_axis[$i] = $row["tiempo"];
		//echo "<br> id: ". $row["NombreFicheroCarga"]. " - Name: ". $row["TiempoCarga"]. " " . $row["TiempoIndice"] . "<br>";
    	$i++;
	
	} //end of while

}// end of if
 
 
 
mysqli_close($conexion);

array_multisort($x1_axis, $y1_axis);     
array_multisort($x2_axis, $y2_axis);

     
 


 
// Setup the graph
$graph = new Graph(900,350);
$graph->SetScale("textlin");
$graph->SetBox(true);
 
$theme_class=new UniversalTheme;
 
$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('GEN_3: Búsqueda sobre un campo con valor compredido entre uno menor y mayor dados.');
$graph->SetBox(false);
 
$graph->img->SetAntiAliasing();
 
$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->title->Set('Segundos');
 
$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($x1_axis);
$graph->xgrid->SetColor('#E3E3E3');
 
// Create the first line
$p1 = new LinePlot($y1_axis);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Prueba MON_3 en Mongodb');
 
// Create the second line
$p2 = new LinePlot($y2_axis);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('Prueba CAS_3 en Cassandra');
 

 
$graph->legend->SetFrameWeight(1);


 
// Output line
$graph->Stroke();



?>
    </body>
</html>