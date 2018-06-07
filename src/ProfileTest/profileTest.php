<?php
namespace ProfileTest;
class profileTest
{
	public static function startTest(){
		$questions=[
			"Entiendo mejor algo","Cuando estoy aprendiendo algo nuevo, me ayuda","En un libro con muchas imágenes y gráficas es más probable que",
			"Recuerdo mejor","Para divertirme, prefiero","Cuando alguien me enseña datos, prefiero",
			"Me gustan los maestros","Cuando veo un esquema o bosquejo en clase, es más probable que recuerde"];
			$answers=[
				array("a través de imágenes, diagramas, gráficas o mapas.","con instrucciones escritas.","si lo practico."),
				array("una imagen.","palabras/texto.","hablar de ello."),
				array("revise cuidadosamente las imágenes y las gráficas.","me concentre en el texto escrito.","comience a trabajar en su solución inmediatamente."),
				array("lo que veo.","lo que oigo.","algo que he hecho."),
				array("ver televisión.","leer un libro.","hacer algo y ver qué sucede."),
				array("gráficas.","resúmenes con texto.","participe y contribuya con ideas."),
				array("que utilizan muchos esquemas en el pizarrón.","que toman mucho tiempo para explicar.","realizan una 'tormenta de ideas' donde cada uno contribuye con ideas."),
				array("la imagen.","lo que el profesor dijo acerca de ella.","cómo resolvimos el esquema o bosquejo.")];
		//$data=ShowOL::generateStringHTML($dataFilter,$keys,$id);
				return profileTest::generateStringHTML($questions,$answers);
			}
			private static function generateStringHTML($questions,$answers){
				$string='';
				for ($i=0; $i < count($questions) ; $i++) { 
					if($i===0){
						$string.='<div class="container" style="width: 100%; ">
						<h2>Test de Estilo de Aprendizaje</h2>
						<form name="profileTest" id="profileTest" method="POST"   action="dashboard" onsubmit="return isAnswered()">
						<div class="panel-group">
						<div class="panel panel-default">
						<div class="panel-heading">
						<h4 class="panel-title">';
						$string.=' <a data-toggle="collapse" aria-expanded="true" href="#collapse'.$i.'">* '.$questions[$i].': </a>
						</h4>

						</div>
						<div id="collapse'.$i.'" aria-expanded="true" class="panel-collapse collapse in">';
						for ($j=0; $j < count($answers[$i]); $j++) { 
						# code...
							$string.='<div class="panel-body">
							<label> <input type="radio" class="slectOne" value="'.($j+1).'" name="answers['.$i.']"/> '.$answers[$i][$j].'</label></div>';
						}
						$string.='</div>
						</div>
						</div>
						</div>';
					}else{
						$string.='<div class="container"  style="width: 100%;">
						<div class="panel-group">
						<div class="panel panel-default">
						<div class="panel-heading">
						<h4 class="panel-title">';
						$string.=' <a data-toggle="collapse" href="#collapse'.$i.'">* '.$questions[$i].': </a>
						</h4>

						</div>
						<div id="collapse'.$i.'" expanded="true" class="panel-collapse collapse in">';
						for ($j=0; $j < count($answers[$i]); $j++) { 
						# code...
							$string.='<div class="panel-body">
							<label> <input type="radio" class="slectOne" value="'.($j+1).'" name="answers['.$i.']"/> '.$answers[$i][$j].'</label></div>';
						}
						$string.='</div>
						</div>
						</div>
						</div>';
					}

				}
				$string.='<div class="container" style="width: 100%;">
				<div class="panel-group" style="float: right;">
				<input id="submit" name="submit" type="submit" />
				<input type="hidden" name="_token" value="'.csrf_token().'">
				</form>
				</div>
				</div>
				';
				return $string;
			}
			public static function getResults($answers,$variablesToCount){
				$tmp = array_count_values($answers);
				$results=[];
				$totalPercent=count($answers);
				for ($i=0; $i < count($variablesToCount) ; $i++) { 
					if(isset($tmp[$variablesToCount[$i]])){
						$cnt = $tmp[$variablesToCount[$i]];
						$rsl=round($cnt*100/$totalPercent);
						$results[]=array($variablesToCount[$i] => $rsl );
					}else{
						$results[]=array($variablesToCount[$i] => 0 );
					}
					$rsl=0;
					
				}
				
				return $results;
			}
		}
		?>










