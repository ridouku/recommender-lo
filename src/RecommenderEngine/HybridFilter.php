<?php
namespace RecommenderEngine;
class HybridFilter
{
	public static function hybridFilter($dataCollaborativeFilter,$dataBasedContentFilter,$matrixOL,$primaryKey,$keys){
		$olArray=[];
		if(count($dataBasedContentFilter)==0 && count($dataCollaborativeFilter)>0  ){
		$olArray=HybridFilter::setOLAtributesByIdBasedContentFilter($dataCollaborativeFilter,$matrixOL,$primaryKey,$keys,10);
		}elseif (count($dataCollaborativeFilter)==0 && count($dataBasedContentFilter)>0) {
		$olArray=HybridFilter::setOLAtributesByIdCollaborativeFilter($dataBasedContentFilter,$matrixOL,$primaryKey,$keys);	
		}
		if(count($dataCollaborativeFilter)>0 && count($dataBasedContentFilter)>0){
		$arrayMerge=HybridFilter::searchIntersectionValues($dataCollaborativeFilter,$dataBasedContentFilter);
		$olArray=HybridFilter::setOLAtributesById($arrayMerge,$matrixOL,$primaryKey,$keys);	
		}elseif (count($dataCollaborativeFilter)==0 && count($dataBasedContentFilter)==0) {
		$olArray=[];	
		}
	 	return $olArray;
	}
	private static function searchIntersectionValues($dataCollaborativeFilter,$dataBasedContentFilter){//Using the values of two filters, here we searches the equal values between arrays, in this case uses objects' ids
		$dataCollaborativeFilter2 = array_values($dataCollaborativeFilter);
		$arrayMerge;
		for ($i=0; $i < count($dataBasedContentFilter); $i++) { 

			for ($j=0; $j < count($dataCollaborativeFilter2); $j++) {
				if($dataBasedContentFilter[$i]==$dataCollaborativeFilter2[$j][0]){
					$arrayMerge[]=$dataCollaborativeFilter2[$j];
					unset($dataCollaborativeFilter[$j]);
					break;
				}
			}
			$dataCollaborativeFilter2 = array_values($dataCollaborativeFilter);
			
		}
		HybridFilter::dataSort($arrayMerge, 'ranking', 'desc','matrix');//Sorts the elements in common according the rankings
		HybridFilter::dataSort($dataCollaborativeFilter2, 'ranking', 'desc','matrix');//Sorts the rest of elements according the rankings
       // $arrayMerge=$arrayMerge+$dataCollaborativeFilter2;//Here we can merge the arrays into one, if you want show more ols
		return $arrayMerge;
	}
	private static function setOLAtributesById($rankingArray,$matrixOL,$primaryKey,$keys){//After find out the data intersections, we need add the rest of attributes according the keys that we need
		$arrayMerge=[];
		for ($i=0; $i < count($rankingArray); $i++) { 
			for ($j=0; $j < count($matrixOL); $j++) {
				
					if($rankingArray[$i][0]===$matrixOL[$j][$primaryKey]){
						$tmp[]=$rankingArray[$i]+$matrixOL[$j];
						$values=[];
						for ($k=0; $k <count($keys) ; $k++) {
							if(isset($tmp[0][$keys[$k]])){
								$values[$keys[$k]] =$tmp[0][$keys[$k]];
							} 
								
						}
						$tmp=[];
						$arrayMerge[]=$values;
						$values=[];
						break;
					
				}
				
			}
		}
		return $arrayMerge;

	}

	private static function setOLAtributesByIdBasedContentFilter($rankingArray,$matrixOL,$primaryKey,$keys,$limit){//After find out the data intersections, we need add the rest of attributes according the keys that we need
		$arrayMerge=[];
		if($limit>count($rankingArray)){
		$limit=count($rankingArray);
		}
		for ($i=0; $i < $limit; $i++) { 
			for ($j=0; $j < count($matrixOL); $j++) {
				
					if($rankingArray[$i][0]===$matrixOL[$j][$primaryKey]){
						$tmp[]=$rankingArray[$i]+$matrixOL[$j];
						$values=[];
						for ($k=0; $k <count($keys) ; $k++) {
							if(isset($tmp[0][$keys[$k]])){
								$values[$keys[$k]] =$tmp[0][$keys[$k]];
							} 
								
						}
						$tmp=[];
						$arrayMerge[]=$values;
						$values=[];
						break;
					
				}
				
			}
		}
		return $arrayMerge;

	}
	private static function setOLAtributesByIdCollaborativeFilter($rankingArray,$matrixOL,$primaryKey,$keys){//After find out the data intersections, we need add the rest of attributes according the keys that we need
		$arrayMerge=[];
		for ($i=0; $i < count($rankingArray); $i++) { 
			for ($j=0; $j < count($matrixOL); $j++) {
				
					if($rankingArray[$i]===$matrixOL[$j][$primaryKey]){
						$tmp[]=array($rankingArray[$i])+$matrixOL[$j];
						$values=[];
						for ($k=0; $k <count($keys) ; $k++) {
							if(isset($tmp[0][$keys[$k]])){
								$values[$keys[$k]] =$tmp[0][$keys[$k]];
							} 
								
						}
						$tmp=[];
						$arrayMerge[]=$values;
						$values=[];
						break;
					
				}
				
			}
		}
		return $arrayMerge;

	}
	private static function  dataSort(array &$data, $key, $order, $case) {
	if ($order !== 'desc' && $order !== 'asc') {
		return false;
	}

	usort($data, function($a, $b) use ($key, $order,$case) {
		$t1;
		$t2;
		switch ($case) {
			case 'matrix':
			$t1 = $a[$key];
			$t2 = $b[$key];
			break;
			
			case 'json':
			$t1 = $a->$key;
			$t2 = $b->$key;
			break;
		}

		$result = 0;
		if ($order === 'asc') {
				if ($t1 > $t2) {
            $result = 1;
        } else if ($t1 < $t2) {
            $result = -1;
        }
			} else {
				if ($t1 < $t2) {
            $result = 1;
        } else if ($t1 > $t2) {
            $result = -1;
        }
			}
        
        return $result;
	});
}

}
?>