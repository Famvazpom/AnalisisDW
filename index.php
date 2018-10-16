<?php


/*
	PARA EJECUTAR UN PROCEDURE SE NECESITA USAR 2 FUNCIONES, UNA PARA PREPARARLO Y OTRA PARA EJECUTARLO:
	PARA PREPARARLO SE USA ESTE:
		$result = mssql_prepare($conexion,$query);
	DONDE EN QUERY VA EL SCRIPT PARA EMPEZAR EL PROCEDURE
		$query = "EXEC NOMBRE_DE_PROCEDURE";
	PARA EJECUTARLO DESPUES DE PREPARARLO SE UTILIZA:
		mssql_execute($result);
	Y DESPUES SE TRABAJA NORMAL
*/

error_reporting(E_ALL);
ini_set('display_errors', true);
$serverName = exec("./sh/getdata.sh Host "); //serverName
$user = exec("./sh/getdata.sh User 2>&1");
$pass = exec("./sh/getdata.sh  Pass 2>&1");
$db = exec("./sh/getdata.sh DB 2>&1");
$connectionInfo = array( "Database"=> $db, "UID"=>$user, "PWD"=>$pass);
$conn = mssql_connect( $serverName, $user,$pass);

$query = "EXECUTE SEL_TODO_MONEDAS";
if( $conn ) {
     echo "Conexión establecida.<br />";
     $result = mssql_query($query); // PREPARAMOS
     if (!$result || !mssql_select_db($db)) //VERIFICAMOS ERRORES
    {
 		echo "Nelson";
 		die('MSSQL error: '. mssql_get_last_message()); //EN CASO DE TENER ALGUN ERROR LOS IMPRIMIMOS Y TERMINAMOS LA CORRIDA
 	}
 	while ($row = mssql_fetch_assoc($result)) { //DE LO CONTRARIO IMPRIMIMOS TODO LO QUE ENCONTREMOS
 		echo $row['Id_Moneda']." | ".$row['Moneda']." | ".$row['Pais']."|<br>";
 		echo "Ye";
 	}
     mssql_free_statement($result);	//LIBERAMOS LA VARIABLE
     die( ); //TERMINAMOS

}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( mssql_errors(), true));
}

?>