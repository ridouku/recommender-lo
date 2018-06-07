<?php
namespace RecommenderEngine;
class ShowOL
{
	public static function showOL($dataFilter,$keys,$id){
		$data="";
		if(count($dataFilter)>0){
			$data=ShowOL::generateStringHTML($dataFilter,$keys,$id);
		}else{
			$data=ShowOL::generateNoSourcesHTML();
		}
		
		return $data;
	}
	private static function generateNoSourcesHTML(){
		$string='';
		$string.='<div class="container">
				<h2>Lo sentimos, información insuficiente para recomendar</h2>
				</div>';
		return $string;
	}
	private static function generateStringHTML($dataFilter,$keys,$id){
		$string='';
		for ($i=0; $i < count($dataFilter) ; $i++) { 
			if($i===0){
				$string.='<div class="container" style="width: 100%; ">
				<h2>Tus Recomendaciones</h2>
				<p>Clic en el título para ver los detalles</p>
				<div class="panel-group">
				<div class="panel panel-default">
				<div class="panel-heading">
				<h4 class="panel-title">';
				$string.=' <a data-toggle="collapse" href="#collapse'.$i.'">'.$dataFilter[$i]['title'].'</a>
				</h4>
				<span class="starRating"'.$dataFilter[$i]['idobject'].'>';

				$string.=ShowOL::fillStars($dataFilter[$i]['ranking'],$dataFilter[$i]['idobject'],$id);
				$string.='</span>
				</div>
				<div id="collapse'.$i.'" class="panel-collapse collapse in">';
				$string.='<div class="panel-body style="display:inline-block;
				width:100%;
				height:7%;"><a style="color:  #00004d;" href="'.$dataFilter[$i]['location'].'
				" target="_blank">Ver Recurso </a></div>';
				$string.='<div class="panel-body" style="height:7%;">
				<div style="width: 25%;float:left;">Estilo de Aprendizaje: '.$dataFilter[$i]['object_group_description'].'
				</div>
				<div style="width: 25%;float:left;">Tipo de Recurso: '.$dataFilter[$i]['object_type_description'].'
				</div>
				<div style="width: 25%;float:left;">Grupo de Desempeño: '.$dataFilter[$i]['pg_name'].'
				</div>
				<div style="width: 25%;float:left;">Nivel de Dificultad: '.$dataFilter[$i]['level_value'].'
				</div>
				
				
				</div>

				<div class="panel-body">Tags: '.$dataFilter[$i]['tag'].'
				</div>
				</div>
				</div>
				</div>
				</div>';
			}else{
				$string.='<div class="container" style="width: 100%; ">
				<div class="panel-group">
				<div class="panel panel-default">
				<div class="panel-heading" >
				<h4 class="panel-title">';
				$string.=' <a data-toggle="collapse" href="#collapse'.$i.'">'.$dataFilter[$i]['title'].'</a>
				</h4>
				<span  class="starRating" id='.$dataFilter[$i]['idobject'].'>';

				$string.=ShowOL::fillStars($dataFilter[$i]['ranking'],$dataFilter[$i]['idobject'],$id);
				$string.='</span>
				</div>
				<div id="collapse'.$i.'" class="panel-collapse collapse in">';
				$string.='<div class="panel-body style="display:inline-block;
				width:100%;
				height:7%;"><a style="color:  #00004d;" href="'.$dataFilter[$i]['location'].'
				" target="_blank">Ver Recurso </a></div>';
				$string.='<div class="panel-body" style="height:7%;">
				<div style="width: 25%;float:left;">Estilo de Aprendizaje: '.$dataFilter[$i]['object_group_description'].'
				</div>
				<div style="width: 25%;float:left;">Tipo de Recurso: '.$dataFilter[$i]['object_type_description'].'
				</div>
				<div style="width: 25%;float:left;">Grupo de Desempeño: '.$dataFilter[$i]['pg_name'].'
				</div>
				<div style="width: 25%;float:left;">Nivel de Dificultad: '.$dataFilter[$i]['level_value'].'
				</div>
				
				
				</div>

				<div class="panel-body">Tags: '.$dataFilter[$i]['tag'].'
				</div>
				</div>
				</div>
				</div>
				</div>';
			}
			
		}
		$string.='</div>';;
		return $string;
	}
	private static function fillStars($starsNumber,$OL,$id){
		$starsNumber=round($starsNumber);
		$string="";
		for ($i=5	; $i >=1; $i--) { 
			
			if($i==$starsNumber){
				
				$string.='<input id="'.$OL.$i.'" type="radio" name="'.$OL.'"  value="'.$i.'" checked onclick="addRating(this,'.$OL.','.$id.');" >
				<label for="'.$OL.$i.'">'.$i.'</label>';
			}else if ($i!=$starsNumber) {
				$string.='<input id="'.$OL.$i.'" type="radio" name="'.$OL.'"  value="'.$i.'" onclick="addRating(this,'.$OL.','.$id.');" >
				<label for="'.$OL.$i.'">'.$i.'</label>';
			}
			
		}
		return $string;
	} 
}
?>










