<?php


 require_once('/jpgraph-3.5.0b1/src/jpgraph.php');
 require_once('/jpgraph-3.5.0b1/src/jpgraph_line.php');
 require_once('/jpgraph-3.5.0b1/src/jpgraph_error.php'); 

 
 
$x_axis = array();
$y1_axis = array();

$i = 0;
 
//Abrimos conexión contra la base de datos
$conexion = mysqli_connect("localhost","user","user") or die('Fallo en el establecimiento de la conexión: ' . mysql_error());

#Seleccionamos la base de datos a utilizar
mysqli_select_db($conexion, "tfg");

$sql = "SELECT Id_prueba, nombre_fichero, tiempo, descripcion FROM testcassandra where Id_prueba = 'CAS_2' ";
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
 


mysqli_close($conexion);
     
array_multisort($x_axis, $y1_axis);

$Legenda = $identificadorPrueba." - ".$descripcion ;
     

 
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