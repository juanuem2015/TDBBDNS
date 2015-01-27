<?php
set_time_limit (600);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html>
<head>

<title>lala</title>
</head>

<body>
<?php

 	//$Query = str_replace( "token1", "count(*)" , $Query );
    //$Query = str_replace( "token2", $Keyspace.".".$table, $Query );
    //$Query = str_replace( "token3", "valor1", $Query );
    //$Query = str_replace( "token4", "valor2", $Query );
    //$Query = str_replace( "token5", "secuencial", $Query );


$cadenaBBDD = " 'Valor1' => array( 'token1gt' => 4.12 ) " ;
print("<h3>Cadena 1: ".$cadenaBBDD."</h3>");
$cadenaBBDD = str_replace( "token1", "$" , $cadenaBBDD );
print("<h3>Cadena 2: ".$cadenaBBDD."</h3>");


eval("\$cadenaBBDD = \"$cadenaBBDD\";");
//echo $cadenaBBDD. "\n";

?>



</body>
</html>