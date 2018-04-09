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
		$matrixLO['headers'][]=$arrayLabels;// Matrix headers' definition
		$concatFieldMerge="";
		$i=0;
		$tmpData=[];//Stores the positions stdClass of JSON data (data) that are equals to elementKey 
		$tmpData2=[];//Contains values of primary key to compare into the second loop
		$i=0;
		$j=0;
		JSONtoMatrix::dataSort($data, $sortField, 'asc');//Sort JSON data by a key field, choice between asc or desc
		foreach($data as $elementKey => $element) {//JSON data loop, use two arrays when the length is equal, means that it was found all objects with equal "key"
			$tmpData2[$j]=$elementKey;//Stores value of elementKey, after the value is compared into the another loop to find out all objects with equal keymerge, the first iteration find all objects that share the value same keyMerge
			$j++;
			if(count($tmpData2)!==count($tmpData)){ //If the length isn't equal, continue the loop 
			foreach ($data as $key => $value) {
					if($element->$keyMerge===$value->$keyMerge && $key!==$elementKey){//Compare if the "key merge" of main loop is equal to "key merge" of second loop, but ignore values with diferent position of stdClass 
					$tmpData[$i]=$key;
					$i++;
					} elseif ($element->$keyMerge===$value->$keyMerge && $key==$elementKey) {//Compare if the "key merge" of main loop is equal to "key merge" of second loop, takes values with equal position of stdClass 
					$tmpData[$i]=$key;
					$i++;
				}	
			}	
		}
			if(count($tmpData2)===count($tmpData)){//When the arrays got the same length, means that found all objects with the same content  
				$stateMerge=true;
				for($k=0;$k<count($arrayLabels);$k++){//Starts to concatenate the tags into only one string 
					
					if($arrayLabels[$k]===$fieldMerge && $stateMerge==true){
						$matrixLO[$arrayLabels[$k]][]=JSONtoMatrix::fieldDataMerge($data,$tmpData,$fieldMerge);
						$stateMerge=false;
					}else{
						$matrixLO[$arrayLabels[$k]][]=$data[$tmpData[0]]->$arrayLabels[$k];
					}
				}
				$tmpData2=[];
				$tmpData=[] ;
				$i=0;
				$j=0;

			}
		}
		return $matrixLO;
	}

	public function dataJSONtoMatrix($dataJSON,$arrayLabels){
		$matrixLO['headers'][]=$arrayLabels;// Matrix headers' definition
		$json=str_replace('},]',"}]",$dataJSON); //Replace comma between curly braces and square brackets to decode json data
		$data = json_decode($json);

		foreach ($data as $key => $value) {//Starts the loop to asign values from json to matrix based in $arrayLabels values
			for($k=0;$k<count($arrayLabels);$k++){
				$matrixLO[$arrayLabels[$k]][]=$data[$key]->$arrayLabels[$k];
			}
		}		
		return $matrixLO;		
	}
	public function dataJSONtoMatrixProfile($dataJSON,$arrayHeaders,$arrayKeys){
		$matrixLO['headers'][]=$arrayHeaders;
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
return (array_merge($tmpSourceIL, $tmpArrayKeys));//The arrays are merge into only one		
}


public function dataMatrixMerge($matrix1,$matrix2,$arrayHeadersNewMatrix,$arrayLabelsMatrix1,$arrayLabelsMatrix2){//Takes the two arrays with the same structure, but different data and merge into only one; based in the data that is required
	try {
		$newMatrix['headers'][]=$arrayHeadersNewMatrix;
		$i;
		$j;
		for($i=0;$i<count($matrix1[$arrayLabelsMatrix1[0]]);$i++){
			for($j=0;$j<count($arrayLabelsMatrix1);$j++){
				$newMatrix[$arrayHeadersNewMatrix[$j]][]=$matrix1[$arrayLabelsMatrix1[$j]][$i];	
			}
		}
		for($i=0;$i<count($matrix2[$arrayLabelsMatrix2[0]]);$i++){
			for($j=0;$j<count($arrayLabelsMatrix2);$j++){
				$newMatrix[$arrayHeadersNewMatrix[$j]][]=$matrix2[$arrayLabelsMatrix2[$j]][$i];	
			}
		}

	} catch (Exception $e) {
		trigger_error("The structure doesn't match!", E_WARNING);
	}
	return $newMatrix;
}	


private function fieldDataMerge($data,$arrayKeysMerge,$fieldMerge){
	$concat="";
	for ($i=0; $i <count($arrayKeysMerge) ; $i++) { 
			# code...
		$concat.=$data[$arrayKeysMerge[$i]]->$fieldMerge.",";
	}
	$concat[strlen($concat)-1]='';
	return $concat;
}
public function  dataSort(array &$data, $key, $order) {
	if ($order !== 'desc' && $order !== 'asc') {
		return false;
	}

	usort($data, function($a, $b) use ($key, $order) {
		$t1 = $a->$key;
		$t2 = $b->$key;

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


public function printMatrix($data,$arrayHeaders){
	$concatTable="<table border='1'>
	<tr>";
	for ($i=0; $i <count($arrayHeaders) ; $i++) { 
		$concatTable.="<th>".$arrayHeaders[$i]."</th>";
	}
	for($i=0;$i<count($data[$arrayHeaders[0]]);$i++){
		$concatTable.="</tr>";
		for($j=0;$j<count($arrayHeaders);$j++){
			$concatTable.="<td>".$data[$arrayHeaders[$j]][$i]."</td>";
		}
		$concatTable.="</tr>";
	}
	$concatTable.= "</table>";

	return $concatTable;
}
}
?>