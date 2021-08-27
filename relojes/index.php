<?php
class RelojEstandar
{
	public function getGastoEnergetico($seconds = ""){
		$i = 0;
		$result = 0;
		$aNumberEnergy = null;
		/*Esta matriz la saque de este sitio https://www.electronics-tutorials.ws/blog/7-segment-display-tutorial.html en el cual me oriento para tener un 
		panorama de como armarlo*/
		$aNumberMatrix = array(	
			array(1,1,1,1,1,1,0), //0
			array(0,1,1,0,0,0,0), //1
			array(1,1,0,1,1,0,1), //2
			array(1,1,1,1,0,0,1), //3
			array(0,1,1,0,0,1,1), //4
			array(1,0,1,1,0,1,1), //5
			array(1,0,1,1,1,1,1), //6
			array(1,1,1,0,0,0,0), //7
			array(1,1,1,1,1,1,1), //8
			array(1,1,1,0,0,1,1) //9
		);
		/*
		Esta funcion lo que hace es ingresar los segundos como primer parametro y el digito que necesito
		*/
		function getDigit ($number,$digit){
			$strNumber = strval($number);
			$aDigits = str_split($strNumber);
			if ($digit > strlen($strNumber)) {
				return 0;
			}
			return intval($aDigits[$digit-1]);
		}

		while ($i <= $seconds) 
		{
			$cantidadDigitos = strlen($i);
			/*
			Realice un switch porque en base a los digitos que tienen los segundos, voy almacenando el consumo 
			*/
			switch ($cantidadDigitos) 
			{
				case '1':
					$aNumberEnergy[$i] = (30+ array_sum($aNumberMatrix[$i])) ;
		 			$i++;
					break;
				case '2':
					$nro1= getDigit($i,1);	
					$nro2= getDigit($i,2);
					$aNumberEnergy[$i] = (24+ array_sum($aNumberMatrix[$nro1]) + array_sum($aNumberMatrix[$nro2]));
		 			$i++;
					break;
				case '3':
					$nro1= getDigit($i,1);					
					$nro2= getDigit($i,2);
					$nro3= getDigit($i,3);
					$aNumberEnergy[$i] = (18+ array_sum($aNumberMatrix[$nro1])+ array_sum($aNumberMatrix[$nro2]) +array_sum($aNumberMatrix[$nro3]));
		 			$i++;
					break;
				case '4':
					$nro1= getDigit($i,1);				
					$nro2= getDigit($i,2);
					$nro3= getDigit($i,3);
					$nro4= getDigit($i,4);
					$aNumberEnergy[$i] = (12+ array_sum($aNumberMatrix[$nro1]) + array_sum($aNumberMatrix[$nro2]) + array_sum($aNumberMatrix[$nro3]) + array_sum($aNumberMatrix[$nro4]));
		 			$i++;
					break;
				case '5':
					$nro1= getDigit($i,1);					
					$nro2= getDigit($i,2);
					$nro3= getDigit($i,3);
					$nro4= getDigit($i,4);
					$nro5= getDigit($i,5);
					$aNumberEnergy[$i] = (6+ array_sum($aNumberMatrix[$nro1]) + array_sum($aNumberMatrix[$nro2]) + array_sum($aNumberMatrix[$nro3]) + array_sum($aNumberMatrix[$nro4]) + array_sum($aNumberMatrix[$nro5]));
		 			$i++;
					break;				

				default:
					echo "Error: Check input";
					break;
			}		 	
		 } 
		 /*Verifico que el parametro ingresado en la funcion , sea un numero*/
		if (!is_numeric($seconds) ) {
			return 0;
		}
		$cantidadDigitos = strlen($seconds); //almaceno la cantidad de digitos que voy a recorrer en el while
		$i=0;

		/*En este While almaceno la cantidad de gasto de energia de cada segundo en el array*/
		while ( $i <= $seconds) {
			$result += $aNumberEnergy[$i];
			$i ++;
		}
		return $result;
	}
}

class RelojPremium
{
	

	public function getGastoEnergetico($seconds = ""){

		/*
		Esta funcion es para transformar las segundos ingresados al siguiente formato EJ: 120 seg. -> 00:02:00
		*/
		function getClockDigit($cantSeconds,$clockDigit){

			$strNumber = str_replace(":", "", gmdate('H:i:s',$cantSeconds)) ;
			$aDigits = str_split($strNumber);
			if ($clockDigit > 6 || $clockDigit < 1) {
				return 0;
			}
			return intval($aDigits[$clockDigit-1]);
		}


		$i = 0;
		$totalMWatts = 0;
		$aNumberMatrix = array(	
			array(1,1,1,1,1,1,0), //0
			array(0,1,1,0,0,0,0), //1
			array(1,1,0,1,1,0,1), //2
			array(1,1,1,1,0,0,1), //3
			array(0,1,1,0,0,1,1), //4
			array(1,0,1,1,0,1,1), //5
			array(1,0,1,1,1,1,1), //6
			array(1,1,1,0,0,0,0), //7
			array(1,1,1,1,1,1,1), //8
			array(1,1,1,0,0,1,1) //9
		);

		if (!is_numeric($seconds) ) {
			return 0;
		}

		while ($i <= $seconds){

			if ($i==0){

				$totalMWatts = 36;

			}else{
				/*Este For recorre los digitos del reloj. ejemplo 00:01:59*/
				for ($j=1; $j<7 ; $j++) { 

					$prevClk = getClockDigit(($i-1),$j);
					$actClk  = getClockDigit($i,$j);

					if( $prevClk != $actClk){

						#calculo el costo entre el segundo anterior y el actual
						$aPrev = $aNumberMatrix[$prevClk]; 
						$aAct = $aNumberMatrix[$actClk];

						/*Este for recorre la matriz de $aNumberMatrix*/
						for ($k=0; $k<7 ; $k++) { 

							if ($aPrev[$k] == 0 && $aAct[$k] == 1){

								#agrego un microWatt al total
								$totalMWatts += 1;
							} 							
						}
					}						
				}
			}
			$i++;
		} 
		return $totalMWatts;
	}
}

$relojEstandar = new RelojEstandar();
$resultadoEstandar = $relojEstandar->getGastoEnergetico(86400); //2580675 2,58 W
#echo $resultado;

#echo 'Reloj Estandar  (86400seg)     : ' . $resultadoEstandar . "<br>";

$relojPremium      = new RelojPremium();
$resultadoPremium  = $relojPremium->getGastoEnergetico(86400); //155265 0,15 W
#echo 'Reloj Premium (86400seg): ' . $resultadoPremium . "\n";
/*$resultado = $relojPremium->getGastoEnergetico(4);
echo 'Reloj Premium (4seg)       : ' . $resultado . "\n";

// Completar con resolucion de punto 2
echo 'Ahorro Premium vs Estandar : ' . $ahorro . "\n";*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!--Bootstrap CSS-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<title>Los 2 Relojes</title>
</head>
<body>
	<div class="container">
		<div class="tab-content">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead class="table-primary">
						<th colspan="4">LOS 2 RELOJES - Challenges Braintly</th>
					</thead>
					<thead class="table-primary">
						<th>RELOJ</th>
						<th>CANTIDAD DE SEGUNDOS</th>
						<th>COSTO CONSUMIDO EN MICROWATTS</th>
						<th>CONSUMO EN WATTS</th>
					</thead>
					<tbody>
						<tr>
							<td>STANDAR</td>
							<td>86400</td>
							<td><?php echo $resultadoEstandar ?></td>
							<td><?php echo (($resultadoEstandar/1000)/1000) ?></td>
						</tr>
						<tr>
							<td>PREMIUM</td>
							<td>86400</td>
							<td><?php echo $resultadoPremium ?></td>
							<td><?php echo (($resultadoPremium/1000)/1000) ?></td>
						</tr>
						<tr>
							<th colspan="3">Ahorro Premium vs Estandar : </th>
							<th><?php echo (($resultadoEstandar/1000)/1000) -(($resultadoPremium/1000)/1000)  ?></th>
						</tr>
					</tbody>
				</table>
			</div>			
		</div><!--end class="tab-content" -->
	</div><!--end class="container"-->
</body>
</html>
