<?php
//To check the steps visit: https://ashokharnal.wordpress.com/2014/12/18/worked-out-example-item-based-collaborative-filtering-for-recommenmder-engine/
namespace RecommenderEngine;
class CollaborativeFilter
{
	public static function collaborativeFilter($RankingMatrix,$topPivot,$botPivot,$keywordRanking,$id)
	{
		$rankingCos;
		$matrixCos=CollaborativeFilter::matrixCosineSimilarity($RankingMatrix,$topPivot,$botPivot,$keywordRanking);//Step 1
		$matrixRankingsUser = CollaborativeFilter::getArraybyId($matrixCos,$id,$botPivot);//Step 2
		$matrixVauesSimilarity=CollaborativeFilter::itemToItemSimilarityMatrix($matrixCos);//Step 2
		$NewRankings=CollaborativeFilter::calculateRankingsToItems($matrixRankingsUser,$matrixVauesSimilarity);//Step 3
		return $NewRankings;
	}
	private static function matrixCosineSimilarity($RankingMatrix,$topPivot,$botPivot,$keywordRanking){//Here  writes  the user-item ratings data in a matrix form. The ranking matrix table gets rewritten as follows:
	// 	   m1	m2	m3
	// u1	2	?	3
	// u2	5	2	?
	// u3	3	3	1
	// u4	?	2	2

		$topPivots=[];
		$botPivots=[];

		array($topPivots);
		array($botPivots);
		for ($i=0; $i < count($RankingMatrix); $i++) {//Fills arrays with the values from the ranking matrix using top and bot pivots values as key values in matrix
			if(empty($topPivots)){
				$topPivots[]=$RankingMatrix[$i][$topPivot];
			}
			elseif(!in_array($RankingMatrix[$i][$topPivot], $topPivots)){
				$topPivots[]=$RankingMatrix[$i][$topPivot];
			}
			if(empty($botPivots)){
				$botPivots[]=$RankingMatrix[$i][$botPivot];
			}
			elseif(!in_array($RankingMatrix[$i][$botPivot],$botPivots)) {
				$botPivots[]=$RankingMatrix[$i][$botPivot];
			} 
		}
		sort($topPivots,1);//Sort the values from array
		sort($botPivots,1);
		$matrixCos= array($topPivots);//Put the top values as header in the new matrix calls matrixCos
		$tmpArrayFill=CollaborativeFilter::tmpArrayValuesToFill(count($topPivots),'');//Fills an array with empty values, to after fill it with the ranking values
		$tmpArrayFill2=$tmpArrayFill;
		if(count($botPivots)>=count($topPivots)){//This control could be used to calculate user-item or user-user matrix, in this case user-item
			for($i=0;$i<count($botPivots);$i++){

				$tmpValues=CollaborativeFilter::getValuesBasedInObjectLearningID($RankingMatrix,$botPivots[$i],$botPivot,$topPivot,$keywordRanking);//
			//Fills associative array with arrays with keys values idobject and ranking 

				for ($j=0; $j < count($tmpValues); $j++) {//Creates an array according to the user and fill in the structure with the values that the user rated at the element according the top pivot values  
					$key=array_search($tmpValues[$j][$topPivot],$topPivots);
					if($j==0){
						$tmpArrayFill[$botPivot]=$botPivots[$i];
					}
					$tmpArrayFill[$key]=$tmpValues[$j][$keywordRanking];

				}
				$tmpNew = array($botPivot=>$botPivots[$i]) + $tmpArrayFill;//Combine the ID user with his ranking values
				$matrixCos[]=$tmpNew;
				$tmpArrayFill=$tmpArrayFill2;
				$tmpNew=[];			

			}
		}else{
			for($i=0;$i<count($botPivots);$i++){

				$tmpValues=CollaborativeFilter::getValuesBasedInObjectLearningID($RankingMatrix,$botPivots[$i],$botPivot,$topPivot,$keywordRanking);	
				
				for ($j=0; $j < count($tmpValues); $j++) { 
					$key=array_search($tmpValues[$j][$topPivot],$topPivots);
					
					if($j==0){
						$tmpArrayFill[$botPivot]=$botPivots[$i];
					}
					$tmpArrayFill[$key]=$tmpValues[$j][$keywordRanking];

				}
				$tmpNew = array($botPivot=>$botPivots[$i]) + $tmpArrayFill;
				$matrixCos[]=$tmpNew;
				$tmpArrayFill=$tmpArrayFill2;
				$tmpNew=[];			
			}

		}
		return $matrixCos;
	}
	private static function tmpArrayValuesToFill($size,$simbol){
		$array=[];
		for ($i=0; $i < $size; $i++) { 
			$array[]=$simbol;
		}
		return $array;
	} 
	private static function getValuesBasedInObjectLearningID($RankingMatrix,$searchValue,$searchKey,$userKey,$rankingKey){
		$arrayValues=[];
		for($i=0;$i<count($RankingMatrix);$i++){
			if($RankingMatrix[$i][$searchKey]===$searchValue){
				$arrayValues[]=array($userKey=>$RankingMatrix[$i][$userKey],$rankingKey=>$RankingMatrix[$i][$rankingKey]);
			}
		}
		return $arrayValues;
	}
	private static function getArraybyId($matrix,$id,$key){//Here searches the array with ranking values according the User  ID into the matrixCos
		$values[0]=$matrix[0];
		for ($i=0; $i < count($matrix); $i++) { 
			$values[1]=$matrix[$i];
			if(isset($values[1][$key])){
				if ($values[1][$key]===$id) {
				break;
			}
			}
		}
		return $values;
	}
	private static function itemToItemSimilarityMatrix($matrixRanking){//now create an item-to-item similarity matrix. The idea is to calculate how similar an item is to another item. There are a number of ways of calculating this. We will use cosine similarity measure.  To calculate similarity between items m1 and m2, for example
		$numerator=0;
		$denominator=1;
		$itemToItemSimilarityMatrix=array($matrixRanking[0]);
		for ($i=0; $i < count($itemToItemSimilarityMatrix[0]); $i++) { //Creates associative arrays with key value (id object learning) and empty spaces to put the results from similarity cosine
			$tmpNew = array($itemToItemSimilarityMatrix[0][$i]=>$itemToItemSimilarityMatrix[0][$i]) + array_fill(0,count($itemToItemSimilarityMatrix[0]), '');
			$itemToItemSimilarityMatrix[]=$tmpNew;
		}
		for ($i=0; $i < count($matrixRanking[0]); $i++) { // We create two item-vectors, v1 for item m1 and v2 for item m2, in the user-space of (u2,u3) and then find the cosine of angle between these vectors. A zero angle or overlapping vectors with cosine value of 1 means total similarity (or per user, across all items, there is same rating) and an angle of 90 degree would mean cosine of 0 or no similarity. Thus, the two item-vectors would be,
            //v1 = 5 u2 + 3 u3
            //v2 = 3 u2 + 3 u3

			for ($j=($i+1); $j < count($matrixRanking[0]); $j++) { //The cosine similarity between the two vectors, v1 and v2, would then be:
             //cos(v1,v2) = (5*3 + 3*3)/sqrt[(25 + 9)*(9+9)] = 0.76
				for ($k=1; $k < count($matrixRanking); $k++) { 
					$v1=$matrixRanking[$k][$i];
					$v2=$matrixRanking[$k][$j];
					
					if($v1!=''&&$v2!=''){
						$numerator=$numerator+($v1*$v2);
						$tmpSum=0;
						
						for ($l=0; $l < count($matrixRanking[0]); $l++) { 
							if($matrixRanking[$k][$l]!=''){
							$tmpSum=$tmpSum+pow($matrixRanking[$k][$l],2);
							}
						}
						$denominator=$denominator*sqrt($tmpSum);
					}
				}
				$cos=$numerator/$denominator;
				$itemToItemSimilarityMatrix[$i+1][$i]=1;
				$itemToItemSimilarityMatrix[$i+1][$j]=$cos;
				$itemToItemSimilarityMatrix[$j+1][$i]=$cos;
				
				$numerator=0;
				$denominator=1;
			}
		}
		return $itemToItemSimilarityMatrix;//We now have the complete item-to-item similarity matrix as follows:
// 		m1	    m2	    m3
// m1	1	    0.76	0.78
// m2	0.76	1	    0.86
// m3	0.78	0.86	1
	}
	private static function calculateRankingsToItems($matrixRankingsUser,$matrixVauesSimilarity){//For each user, we next predict his ratings for items that he had not rated. We will calculate rating for user u1 in the case of item m2 (target item). To calculate this we weigh the just-calculated similarity-measure between the target item and other items that user has already rated. The weighing factor is the ratings given by the user to items already rated by him. We further scale this weighted sum with the sum of similarity-measures so that the calculated rating remains within a predefined limits. Thus, the predicted rating for item m2 for user u1 would be calculated using similarity measures between (m2,m1) and (m2,m3) weighted by the respective ratings for m1 and m3: rating = (2 * 0.76 + 3 * 0.86)/(0.76+0.86) = 2.53
		$tmpRank=$matrixRankingsUser;
		for ($i=0; $i < count($matrixRankingsUser[0]); $i++) { //Loop through array ranking user according ID
			$ranking=$matrixRankingsUser[1][$i];
			if($ranking===''){
				$newRanking=CollaborativeFilter::calculateNewSingleRanking($matrixVauesSimilarity,$matrixRankingsUser,$matrixRankingsUser[0][$i]);
				$tmpRank[1][$i]=$newRanking;
			}
		}
		$newRank=[];
		for ($i=0; $i < count($tmpRank[0]); $i++) { 
			$newRank[]=array('ranking'=>$tmpRank[1][$i],$tmpRank[0][$i] );
		}
		CollaborativeFilter::dataSort($newRank, 'ranking', 'desc','matrix');
		return $newRank;
	}
	private static function calculateNewSingleRanking($matrixVauesSimilarity,$matrixRankingsUser,$key){
		$rankings=CollaborativeFilter::getArraybyId($matrixVauesSimilarity,$key,$key);
		$numerator=0;
		$denominator=0;

		for ($i=0; $i < count($rankings[0]); $i++) { 
			if($rankings[1][$i]!=1){
			 $value=$rankings[1][$i];
			 $position=array_search($rankings[0][$i], $rankings[0]);
			 $valuesMult=$matrixRankingsUser[1][$position];
			 if($valuesMult!=''){
			 	$numerator+=$value*$valuesMult;
			 	$denominator+=$value;
			 }
			}
			 
		}
		$ranking=$numerator/$denominator;
		$ranking=round($ranking,2);
		return $ranking;
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
	
	

}
?>