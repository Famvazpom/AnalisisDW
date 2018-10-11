<?php

	
/*
	PARA EJECUTAR UN PROCEDURE SE NECESITA USAR 2 FUNCIONES, UNA PARA PREPARARLO Y OTRA PARA EJECUTARLO:
	PARA PREPARARLO SE USA ESTE:
		$result = sqlsrv_prepare($conexion,$query);
	DONDE EN QUERY VA EL SCRIPT PARA EMPEZAR EL PROCEDURE
		$query = "EXEC NOMBRE_DE_PROCEDURE";
	PARA EJECUTARLO DESPUES DE PREPARARLO SE UTILIZA:
		sqlsrv_execute($result);
	Y DESPUES SE TRABAJA NORMAL
*/
$serverName = "NOVAPRIME\ANALISIDB"; //serverName
$user="NOVAPRIME";
$pass="@hotmail2";
$bd="Divisas";
$connectionInfo = array( "Database"=> $bd, "UID"=>$user, "PWD"=>$pass);
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$query = "EXEC SEL_TODO_MONEDAS";
if( $conn ) {
     echo "Conexión establecida.<br />";
     $result = sqlsrv_prepare($conn,$query); // PREPARAMOS
     if (!sqlsrv_execute($result)) //VERIFICAMOS ERRORES
    {
 		die(print_r(sqlsrv_errors(),true)); //EN CASO DE TENER ALGUN ERROR LOS IMPRIMIMOS Y TERMINAMOS LA CORRIDA
 	}
 	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) { //DE LO CONTRARIO IMPRIMIMOS TODO LO QUE ENCONTREMOS
 		echo $row['Id_Moneda']." | ".$row['Moneda']." | ".$row['Pais']."|<br>";

 	}
     sqlsrv_free_stmt($result);	//LIBERAMOS LA VARIABLE
     die( ); //TERMINAMOS

}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
	
?>