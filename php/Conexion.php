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
if( $conn ) {
     echo "Conexión establecida.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
    
?>