<?php 
/*
     Conexion a la base de datos.
       Atributos publicos
           $conn -> Conexion
       Atributos privados
            $pass -> Password de la base de datos
            $usr -> Usuario de la base de datos
            $db -> Base de datos a la que se conectara
            $host -> Lugar donde se encuentra la base de datos
*/
    class Conexion
    {
        public $conn;
        public $db;
        private $pass;
        private $usr;
        private $host;
        
        function __construct()
        {
            /*
                1.- Se obtienen los datos con el script getdata.sh
                2.- Conectamos a la base de datos
                3.- Manejo de errores
                    3.1.- En caso de no lograr la conexion terminamos la funcion
                    3.2.- En caso contrario se envia el mensaje de exito
            */  
            $this -> pass = exec("./../sh/getdata.sh Pass ");
            $this -> host = exec("./../sh/getdata.sh Host");
            $this -> usr = exec("./../sh/getdata.sh User");
            $this -> db = exec("./../sh/getdata.sh DB");
            $this -> conn = mssql_connect( $this -> host, $this -> usr,$this -> pass);
            if (!$this -> conn || !mssql_select_db($this -> db,$this -> conn)) {
                die(print_r("MSSQL ERROR:". mssql_get_last_message()));
            }
        }
        
        function __destruct()
        {
            /*
                Funcion Destructor
                    1.- Cerramos la conexion a la base de datos
                    2.- Apuntamos todas las variables a NULL
            */
            mssql_close($this -> conn);
            $this -> pass = null;
            $this -> usr = null;
            $this -> db = null;
            $this -> host = null;
        }
    }
?>