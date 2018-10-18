<?php
	require('Conexion.php');
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
		
		private function DescargaArchivo()
		{
			//Esta funcion descargara el archivo de la pagina
			file_put_contents("../".$this -> archivo,fopen($this -> url,'r'));	
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
				echo "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana<br>";
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
				echo "ERROR 2: Archivo descargado vacio<br>";
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

		}

		private function ObtieneDivisaCanada()
		{
			$this -> divisa = exec("./../sh/DivisaCan.sh ".$this -> fecha." 2>&1");
			if ($this -> divisa == null) {
				echo "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana<br>";
				unlink("../".$this -> archivo);
				return;
			}
			echo $this -> divisa;
			unlink("../".$this -> archivo);
			//Insertar a la base de datos
		}

		public function DivisaCan($fecha)
		{
			$this -> fecha = $fecha;
			$this -> ArmaCanURL();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaCanada();
		}

		private function ArmaUnEurlink()
		{
			$this -> url = "https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/eurofxref-graph-usd.en.html";
			$this -> archivo = "UnEur.txt";
		}

		private function ObtieneDivisaUnEur()
		{
			$this -> divisa = exec("./../sh/DivisaUnE.sh ".$this -> mes." ".$this -> dia);
			if($this -> divisa == "&nbsp;" || $this -> divisa == null)
			{
				echo "ERROR 1: Fecha invalida, puede ser una fecha posterior a la actual, el formato de la fecha no es valido o la fecha esta situada en fin de semana<br>";
				unlink("../".$this -> archivo);
				return;
			}
			
			echo $this -> divisa;
			unlink("../".$this -> archivo);
			//Insertar a la Base de Datos
		}
		
		public function DivisaUnEur($dia,$mes)
		{
			$this -> dia = $dia;
			$this -> mes = $mes;
			$this -> ArmaUnEurlink();
			$this -> DescargaArchivo();
			$this -> ObtieneDivisaUnEur();
		}
	}
$meh = new Operaciones();
/*
$meh -> DivisaMexico("11/06/2018");
echo "<br>";
$meh -> DivisaBrazil();
echo "<br>";
$meh -> DivisaCan("2018-10-17");
echo "<br>";
$meh -> DivisaUnEur("15","Oct");
/*
?>