<?php
	require('Conexion.php');
	error_reporting(E_ALL);
ini_set('display_errors', true);
	/*
		TO DOOMS
			* INSERTAR A LA BASE DE DATOS LAS DIVISAS OBTENIDAS ACTUALMENTE
			* HACER UNA FUNCION PARA MANEJO DE ERRORES
			* IMPLEMENTAR EL MANEJO DE ERRORES EN LAS FUNCIONES PARA ENTREGAR MENSAJES DE ERROR
		ERRORES
			1 = ERROR: Fecha invalida, puede ser una fecha posterior a la actual o el formato de la fecha no es valido
	*/

	/*
		Clase Operaciones
			Atributos privados
				$archivo -> Nombre del archivo que se manejara
				$url -> Url de la cual se descargara el archivo
				$pais -> Pais del cual se esta trabajando
				$divisa -> Valor actual de la moneda
				$fecha -> fecha de la cual se esta trabajando
	*/
	class Operaciones
	{
		private $archivo;
		private $url;
		private $pais;
		private $divisa;
		private $fecha;
		private $error;
		private $mes;
		private $dia;
		private $anio;
		private $conn;
		private $query;
		
		private function DescargaArchivo()
		{
			//Esta funcion descargara el archivo de la pagina
			file_put_contents("../".$this -> archivo,fopen($this -> url,'r'));	
		}

		private function GuardaErrores($mensaje)
		{
			echo "ERROR";
			$file = fopen("../errores.log", "a");
			$date = exec("date");
			fwrite($file,$date.",".$mensaje."\n");
			fclose($file);
		}
		
		private function ArmaMexURL()
		{
			/*Esta funcion recibe la variable $fecha
				Nota: la fecha debe estar en formato dd/mm/yyyy de caso contrario el programa encontrara un error en la funcion ObtieneDivisaMexico()

				Desarrollo:
				1.- Arma la URL de Mexico uniendo parte de la url basica y le agrega la fecha en donde es conveniente
				2.- Le establece valores a las variables archivo y pais
				3.- Descarga el archivo
				4.- Obtiene la divisa actual de mexico
			*/
			$this-> url = "http://www.banxico.org.mx/tipcamb/datosieajax?accion=dato&idSeries=SF43786&decimales=2&fecha=".$this -> fecha;
			$this -> archivo = "mexico.txt";
			$this -> pais = "MEXICO";
		}
		
		private function ObtieneDivisaMexico()
		{
			/* Esta funcion obtiene la divisa actual de Mexico
				1.- Obtiene el posible valor de la divisa con el script DivisaMex.sh
				2.- Verifica que no sea un valor invalio
					2.1.- En caso de ser asi envia un mensaje de error, elimina el archivo y termina el proceso.
					2.2.- En caso contrario se inserta a la base de datos... //Por agregar
			*/
			$this -> divisa = exec("./../sh/DivisaMex.sh");
			if ($this -> divisa == "N/E" | $this -> divisa == null) {
				$mesaje = "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);
			//Insertar en la base de datos
		}

		public function DivisaMexico($fecha)
		{
			$this -> fecha = $fecha;
			$this -> ArmaMexURL();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaMexico();
		}

		private function ObtieneDivisaBrazil()
		{
			$this -> divisa = exec("./../sh/DivisaBra.sh");
			if ($this -> divisa == null) {
				$mensaje = "ERROR 2: Archivo descargado vacio";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);
			//Insertar a la base de datos
		}

		public function DivisaBrazil()
		{
			/*
				Esta funcion no necesita parametros ya que la URL que provee la pagina oficial no necesita fecha por esto mismo podemos realizar algunas operaciones directas desde aqui.
			*/
			$this -> url = "https://ptax.bcb.gov.br/ptax_internet/consultarUltimaCotacaoDolar.do";
			$this -> archivo = "brazil.txt";
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaBrazil();
		}

		private function ArmaCanURL()
		{
			$this -> url = "https://www.bankofcanada.ca/valet/observations/group/FX_RATES_DAILY/json?start_date=".$this -> fecha;
			$this -> archivo = "canada.txt";
			$this -> pais = "CANADA";

		}

		private function ArmaCanCSVURL()
		{
			$this -> url = "https://www.bankofcanada.ca/valet/observations/group/FX_RATES_DAILY/csv?start_date=".$this -> fecha;
			$this -> archivo = "Can.csv";
			$this -> pais = "CANADA";
		}

		private function ObtieneDivisaCanada()
		{
			$this -> divisa = exec("./../sh/DivisaCan.sh ".$this -> fecha." 2>&1");
			if ($this -> divisa == null) {
				$mensaje = "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);
			//$this -> InsertaDB();
			//Insertar a la base de datos
		}

		private function ObtieneDivisaCanadaCSV()
		{
			$this -> divisa = exec("./../sh/DivisaCanCSV.sh ".$this -> fecha." 2>&1");
			if ($this -> divisa == null) {
				$mensaje = "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);
		}

		public function DivisaCan($fecha)
		{
			/*
				Formato de fecha para Canada
					$fecha = YYYY-MM-DD
				Ejemplo
					$fecha = 2018-06-10
			*/
			$this -> fecha = $fecha;
			$this -> ArmaCanURL();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaCanada();
		}

		public function DivisaCanCSV($fecha)
		{
			/*
				Formato de fecha para Canada
					$fecha = YYYY-MM-DD
				Ejemplo
					$fecha = 2018-06-10
			*/
			$this -> fecha = $fecha;
			$this -> ArmaCanCSVURL();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaCanadaCSV();
		}

		private function ArmaUnEurlink()
		{
			$this -> url = "https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/eurofxref-graph-usd.en.html";
			$this -> archivo = "UnEur.txt";
			$this -> pais = "UNION EUROPEA";
		}

		private function ObtieneDivisaUnEur()
		{
			$this -> divisa = exec("./../sh/DivisaUnE.sh ".$this -> mes." ".$this -> dia);
			if($this -> divisa == "&nbsp;" || $this -> divisa == null)
			{
				$mensaje = "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			
			echo $this -> divisa;
			unlink("../".$this -> archivo);
			//Insertar a la Base de Datos
		}
		
		public function DivisaUnEur($dia,$mes)
		{
			/*
				Formato de fehca para Union Europea
					$dia = DD
					$mes = MMM // El mes va con 3 letras
				Ejemplo
					$dia = 10
					$mes  = Jun
			*/
			$this -> dia = $dia;
			$this -> mes = $mes;
			$this -> ArmaUnEurlink();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaUnEur();
		}

		private function ArmaArgURL()
		{
			$this -> url = "http://www.bcra.gob.ar/PublicacionesEstadisticas/Evolucion_moneda_2.asp?Fecha=".$this -> fecha."&Moneda=2";
			$this -> archivo = "Argen.txt";
			$this -> pais = "ARGENTINA";
		}

		private function ObtieneDivisaArg()
		{
			$this -> divisa = exec("./../sh/DivisaArgen.sh ".$this -> dia." ".$this -> mes." ".$this -> anio." 2>&1");
			if ($this -> divisa == null) {

				$mensaje = "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);

			//Insertar a la BD
		}

		public function DivisaArgen($dia,$mes,$anio)
		{
			/*
				Formato de fecha para Argentina:
					Fecha separada por partes:
						$dia = DD
						$mes = MM
						$anio = YYYY
					Ejemplo
						$dia = 09
						$mes = 06
						$anio = 2018
			*/
			$this -> dia = $dia;
			$this -> mes = $mes;
			$this -> anio = $anio;
			$this -> ArmaArgURL();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaArg();
		}

		private function ArmaColURL()
		{

			$this -> url = "https://www.superfinanciera.gov.co/descargas?com=institucional&name=pubFile1010997&downloadname=historia.csv" ; 
			$this -> archivo = "Cop.txt";
			$this -> pais = "COLOMBIA"; 
		}

		private function ObtieneDivisaCol()
		{
			$this -> divisa = exec("./../sh/DivisaCol.sh ".$this -> fecha." 2>&1");
			if ($this -> divisa == null) {
				$mensaje = "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana";
				$this -> GuardaErrores($mensaje);
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);
		}

		public function DivisaCol($fecha)
		{
			/*
				Formato de fecha para colombia:
					MM/DD/YYYY
				Ejemplo:
					10/10/2018
				Octubre 10 de 2018
			*/
			$this -> fecha = $fecha;
			$this -> ArmaColURL();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaCol();
		}

		/*private function InsertaDB()
		{
			$this -> conn = new Conexion();
			$this -> query = "EXEC INSERTA_DIVISA @Moneda = ".$this ->pais." @Cambio =".$this -> divisa." @Fecha =".$this -> fecha;
		}
		*/
	}
$meh = new Operaciones();
echo "<br>MEXICO: ";
$meh -> DivisaMexico("11/06/2018");
echo "<br>BRAZIL: ";
$meh -> DivisaBrazil();
echo "<br>CANADA: ";
$meh -> DivisaCan("2018-10-17");
echo "<br>ARGENTINA: ";
$meh -> DivisaArgen("08","06","2018");
echo "<br>UNION EUROPEA: ";
$meh -> DivisaUnEur("15","Oct");
echo "<br>CANADA CSV: ";
$meh -> DivisaCanCSV("2018-10-17");
echo "<br>COLOMBIA: ";
$meh -> DivisaCol("11/10/2018");
?>