<?php
	class Conexion
	{
		pubilc $conn;
		private $usr;
		private $pass;
		private $host;
		private $db;


		function __construct()
		{
			$this->host = exec("../sh/getdata.sh Host ");
			echo this->$host;
		}
	}

	echo "Yep";

?>