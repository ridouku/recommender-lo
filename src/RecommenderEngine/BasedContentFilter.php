<?php
namespace RecommenderEngine;
class BasedContentFilter
{

	public static function basedContentFilter($matrixOL,$matrixProfile,$keywordResult,$keywordIdObject,$optionalKeyword){
		$matrixProfile=BasedContentFilter::getPrimaryValueProfile($matrixProfile,$keywordResult);
		$listOLCleared=BasedContentFilter::clearOlList($matrixOL,$matrixProfile,$keywordResult,$keywordIdObject,$optionalKeyword);

		return $listOLCleared;
	}

	private static function getPrimaryValueProfile($matrixProfile,$keywordResult){//Searches the higher values in the results of student profile, to after use arrays indicators and compare into the OL matrix
		BasedContentFilter::dataSort($matrixProfile,$keywordResult,'desc','matrix');
		$newMatrixProfile[]=$matrixProfile[0];
		for ($i=1; $i <count($matrixProfile); $i++) { 
			if (in_array($matrixProfile[0][$keywordResult],$matrixProfile[$i])||$matrixProfile[$i][$keywordResult]>=$matrixProfile[0][$keywordResult]/2) {//Check for equal values with the higher value and values that are higher or equal to half of higher value
				$newMatrixProfile[]=$matrixProfile[$i];
			}
		}
		return($newMatrixProfile);
	}

	
	private static function clearOlList($matrixOL,$matrixProfile,$keywordResult,$keywordIdObject,$optionalKeyword){//Creates an array with the objects ID that meet the indicators of student profile
		$properties=array_keys($matrixProfile[0]);
		$key = array_search($keywordResult, $properties);
		unset($properties[$key]);//Delete key of learning styles results 
		$idOL=[];
		for ($i=0; $i < count($matrixProfile); $i++) { 
			$data = $matrixProfile[$i];
			for ($j=0; $j < count($matrixOL); $j++) { 
				$bool=BasedContentFilter::checkIntersectionElementsArraysbyKey($data,$matrixOL[$j],$properties);//Check if the object learning indicators are equals to profile student, if is true the object ID is put into an array
				if($bool){
					$idOL[]=$matrixOL[$j][$keywordIdObject];
				}else{

				}
			}
			
		}
		if(count($idOL)===0){
			$properties=[];
			$properties=array($optionalKeyword);
			$idOL=[];
			for ($i=0; $i < count($matrixProfile); $i++) { 
				$data = $matrixProfile[$i];
				for ($j=0; $j < count($matrixOL); $j++) { 
				$bool=BasedContentFilter::checkIntersectionElementsArraysbyKey($data,$matrixOL[$j],$properties);//Check if the object learning indicators are equals to profile student, if is true the object ID is put into an array
				if($bool){
					$idOL[]=$matrixOL[$j][$keywordIdObject];
				}else{

				}
			}
			
		}
	}
	return $idOL;

}

	private static function checkIntersectionElementsArraysbyKey($dataProfilesResults,$matrixOL,$properties){//Using the properties like keys, searches the similar values by key 
		$bool=true;
		for ($i=0; $i < count($properties) ; $i++) { 
			if(isset($matrixOL[$properties[$i]])){
				if($matrixOL[$properties[$i]]!==$dataProfilesResults[$properties[$i]]){
					$bool=false;
					break;
				}	
			}
		}
		return $bool;

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

			if (is_string($t1) && is_string($t2)) {
				if ($order === 'asc') {
					return strcmp($t1, $t2);
				} else {
					return strcmp($t2, $t1);
				}
			} elseif (is_int($t1) && is_int($t2)) {
				if ($order === 'asc') {
					return $t1 - $t2;
				} else {
					return $t2 - $t1;
				}
			} 
		});
	}
	// private function array_remove_keys($array, $keys = array()) {

	// // If array is empty or not an array at all, don't bother
	// // doing anything else.
	// 	if(empty($array) || (! is_array($array))) {
	// 		return $array;
	// 	}

	// // If $keys is a comma-separated list, convert to an array.
	// 	if(is_string($keys)) {
	// 		$keys = explode(',', $keys);
	// 	}

	// // At this point if $keys is not an array, we can't do anything with it.
	// 	if(! is_array($keys)) {
	// 		return $array;
	// 	}

 //    // array_diff_key() expected an associative array.
	// 	$assocKeys = array();
	// 	foreach($keys as $key) {
	// 		$assocKeys[$key] = true;
	// 	}

	// 	return array_diff_key($array, $assocKeys);
	// }
}
?>