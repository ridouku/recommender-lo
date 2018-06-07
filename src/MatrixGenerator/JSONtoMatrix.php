<?php 
namespace MatrixGenerator;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;
class JSONtoMatrix
{

	public static function JSONtoMatrixMerge($dataJSON,$arrayLabels,$keyMerge,$fieldMerge,$sortField)
	{

    	$json=str_replace('},]',"}]",$dataJSON); //Replace comma between curly braces and square brackets to decode json data
		$data = json_decode($json);	//Decode json data to assign its value into 
		$matrixLO=[];// Matrix headers' definition
		$concatFieldMerge="";
		$i=1;
		$tmpData=[];//Stores the positions stdClass of JSON data (data) that are equals to elementKey 
		$j=0;
		$tmpArrayKeys=$arrayLabels;
		$tmpArrayValues=[];
		JSONtoMatrix::dataSort($data, $sortField, 'asc','json');//Sort JSON data by a key field, choice between asc or desc
		foreach($data as $elementKey => $element) {//JSON data loop, use two arrays when the length is equal, means that it was found all objects with equal "key"
		
		if(!in_array($elementKey, $tmpData)){
			$tmpArrayValues=[];
			$tmpData=[] ;
			$i=0;
			foreach ($data as $key => $value) {
				if($value->$keyMerge===$element->$keyMerge){
					$tmpData[]=$key;
				}
			}
			for($k=0;$k<count($arrayLabels);$k++){//Starts to concatenate the tags into only one string 
				if($arrayLabels[$k]===$fieldMerge){
					$tmpArrayValues[$tmpArrayKeys[$k]]=JSONtoMatrix::fieldDataMerge($data,$tmpData,$fieldMerge);
				}else{
					$var =$arrayLabels[$k];
					$var2 =$data[$tmpData[0]]->$var;
					$tmpArrayValues[$tmpArrayKeys[$k]]=$var2;
				}


			}
			$matrixLO[]=$tmpArrayValues;
		}elseif($i===count($tmpData)){
			$tmpArrayValues=[];
			$tmpData=[] ;
			$i=0;
		}
		if(in_array($elementKey, $tmpData)){
			$i++;
		}

	}

	return $matrixLO;
}

public static function dataJSONtoMatrix($dataJSON,$arrayLabels,$sortField){
		$matrixLO;// Matrix headers' definition
		$json=str_replace('},]',"}]",$dataJSON); //Replace comma between curly braces and square brackets to decode json data
		$data = json_decode($json);
		$tmpArrayKeys=$arrayLabels;
		$tmpArrayValues=[];
		JSONtoMatrix::dataSort($data, $sortField, 'asc','json');
		foreach ($data as $key => $value) {//Starts the loop to asign values from json to matrix based in $arrayLabels values
			for($k=0;$k<count($arrayLabels);$k++){
				$var =$arrayLabels[$k];//Take the value of key from array 
				$var2 =$data[$key]->$var;//Use the variable var to get the value of the object, so it can avoid errors trying to get the value from object
				$tmpArrayValues[$tmpArrayKeys[$k]]=$var2;
			}
			$matrixLO[]=$tmpArrayValues;
			$tmpArrayValues=[];
		}	
		return $matrixLO;		
	}
	public static function dataJSONtoMatrixProfile($dataJSON,$arrayHeaders,$arrayKeys){
		try{
			$matrixLO;
		$json=str_replace('},]',"}]",$dataJSON); //Replace comma between curly braces and square brackets to decode json data
		$objJson = json_decode($json,true);
		$data           = $objJson[0];
		$properties = array_keys($data);// gets name of main properties of json data
		$tmpArrayKeys=[];
		$tmpArrayUnknowKeys=[];
		for ($i=0; $i < count($properties)  ; $i++) { 
        	# code...
			$array=$objJson[0][$properties[$i]];
			foreach(new FlatRecursiveArrayIterator($array) as $key => $value)//Callis to class FlatRecursiveArrayIterator, the class gets all values from json data including its childs
			{
				if(filter_var($key, FILTER_VALIDATE_INT) === false){//Check if the key is a name or integer, the integer means that value is part of json's child 

					if(in_array($key, $arrayKeys))//check if the key is part of values that are required
					{
						$tmpArrayKeys[$key]=$value;
					}
				}
				else{
					$tmpArrayUnknowKeys=$array;//The values without name are saved into this array 
				}
			};     	
		}
		$i=0;
		$tmpSourceIL=[];
		foreach(new FlatRecursiveArrayIterator($tmpArrayUnknowKeys) as $key => $value)
			{		//the values into $tmpArrayUnknowKeys are assing into a predefined array with name of keys with are required
				switch ($i) {
				case 0://Get values of learning group of student
				if(filter_var($value, FILTER_VALIDATE_INT) === false){
					$i++;
				}else{

					$tmpSourceIL[$arrayKeys[2]][]=$value;
				}
				continue;
				case 1://Get values of results from test IL
				if(filter_var($value, FILTER_VALIDATE_INT) === false){
					$i++;
				}else{
					$tmpSourceIL[$arrayKeys[3]][]=$value;
					$i=0;
				}
				continue;
			}
		};
		$tmpArray=[];
		$tmpArray2=[];
		for ($i=0; $i < count($tmpSourceIL[$arrayKeys[2]]) ; $i++) {//In this part, there are two arrays with differents size, so starts to fill an array with the same value until reach the size of $tmpSourceIL
			# code...
			$tmpArray[]=$tmpArrayKeys[$arrayKeys[0]];
			$tmpArray2[]=$tmpArrayKeys[$arrayKeys[1]];
		}
		$tmpArrayKeys[$arrayKeys[0]]=$tmpArray;
		$tmpArrayKeys[$arrayKeys[1]]=$tmpArray2;
		$tmpMerge=array_merge($tmpSourceIL, $tmpArrayKeys);
		$tmpKeys=$arrayHeaders;
		$tmpArrayValues=[];
		for($i=0;$i<count($tmpMerge[$arrayKeys[0]]);$i++){
			for($j=0;$j<count($tmpMerge);$j++){
				$tmpArrayValues[$arrayKeys[$j]]=$tmpMerge[$arrayKeys[$j]][$i];
			}
			$newMatrix[]=$tmpArrayValues;
			$tmpArrayValues=[];
		}
		return $newMatrix;//The arrays are merge into only one		

	} catch (Exception $e) {

		trigger_error("The structure is empty!", E_WARNING);
	}
}


public static function dataMatrixMerge($matrix1,$matrix2,$arrayHeadersNewMatrix,$arrayLabelsMatrix1,$arrayLabelsMatrix2,$sortField){//Takes the two arrays with the same structure, but different data and merge into only one; based in the data that is required
	try {
		$newMatrix;
		$i;
		$j;
		$tmpArrayKeys=$arrayHeadersNewMatrix;
		$tmpArrayValues=[];
		for($i=0;$i<count($matrix1);$i++){
			for($j=0;$j<count($arrayLabelsMatrix1);$j++){
				$tmpArrayValues[$tmpArrayKeys[$j]]=$matrix1[$i][$arrayLabelsMatrix1[$j]];
			}
			$newMatrix[]=$tmpArrayValues;
			$tmpArrayValues=[];
		}
		for($i=0;$i<count($matrix2);$i++){
			for($j=0;$j<count($arrayLabelsMatrix2);$j++){
				$tmpArrayValues[$tmpArrayKeys[$j]]=$matrix2[$i][$arrayLabelsMatrix2[$j]];
			}
			$newMatrix[]=$tmpArrayValues;
			$tmpArrayValues=[];
		}

	} catch (Exception $e) {
		
		trigger_error("The structure doesn't match!", E_WARNING);
	}
	JSONtoMatrix::dataSort($newMatrix, $sortField, 'asc','matrix');
	return $newMatrix;
}	



private static function fieldDataMerge($data,$arrayKeysMerge,$fieldMerge){
	$concat="";
	for ($i=0; $i <count($arrayKeysMerge) ; $i++) { 
			# code...
		$concat.=$data[$arrayKeysMerge[$i]]->$fieldMerge.",";
	}
	$concat[strlen($concat)-1]=" ";

	return $concat;
}

public static function  dataSort(array &$data, $key, $order, $case) {
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
		} else {
			trigger_error('Invalid type in data!', E_WARNING);
		}
	});
}


public static function printMatrix($data,$arrayHeaders){
	$concatTable="<table border='1'>
	<tr>";
	for ($i=0; $i <count($arrayHeaders) ; $i++) { 
		$concatTable.="<th>".$arrayHeaders[$i]."</th>";
	}
	for($i=0;$i<count($data);$i++){
		$concatTable.="</tr>";
		for($j=0;$j<count($arrayHeaders);$j++){
			
			$concatTable.="<td>".$data[$i][$arrayHeaders[$j]]."</td>";
			
		}
		$concatTable.="</tr>";
	}
	$concatTable.= "</table>";

	return $concatTable;
}
}
?>