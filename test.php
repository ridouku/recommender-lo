<?php 
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use MatrixGenerator\JSONtoMatrix;//Calls the class to transform JSON data into Matrix

$arrContextOptions=array(
	"ssl"=>array(
		"verify_peer"=>false,
		"verify_peer_name"=>false,
	),
); 
#Test matrix LO(Learning Object)
$tags="Sistemas de numeración,Computadoras,Programación";
$url = "http://192.168.200.2:8000/ObjectLearningList/?q=".urlencode($tags); 

$response = file_get_contents($url, false, stream_context_create($arrContextOptions));

$arrayHeaders=["idobject","title","tag","location","object_type_value","level_description","object_group_value"];
$keyMerge="idobject";//field to compare repeated elements
$sortField="idobject";
$fieldMerge="tag";//field that contains information to merge 

$matrixMerge=JSONtoMatrix::JSONtoMatrixMerge($response,$arrayHeaders,$keyMerge,$fieldMerge,$sortField);

echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);

//$matrixMerge=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders); #Test to convert json into matrix without merge

//echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);

#Test matrix rankings list LO(Learning Object)
$title="Sistemas de numeración: decimal, binario, hexadecimal";
$url = "http://192.168.200.2:8000/ObjectLearningRankingsList/?q=".urlencode($title);
 
$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
$arrayHeaders=["object_learning","student","ranking"];
$matrixMerge=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders);
//echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);

$url="http://192.168.200.2:8000/ObjectLearningRankingsListTeacher/?q=".urlencode($title);
 
$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
$arrayHeaders2=["object_learning","id","ranking"];
$matrixMerge2=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders2);
$arrayHeadersNewMatrix=["object_learning","id","ranking"];
$matrixMerge=JSONtoMatrix::dataMatrixMerge($matrixMerge,$matrixMerge2,$arrayHeadersNewMatrix,$arrayHeaders,$arrayHeaders2);
echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeadersNewMatrix);

#Matrix Profile Student

$id=1;
$url="http://192.168.200.2:8000/ProfilesList/?q=".urlencode($id);
$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
$arrayHeaders=["performance_group","lvl_difficulty","style","value"];
$arrayKeys=["pg_level","lod_type"
,"colg_type"
,"rsi_result"];
//rint_r( $properties);
$profile=JSONtoMatrix::dataJSONtoMatrixProfile($response,$arrayHeaders,$arrayKeys);
echo JSONtoMatrix::printMatrix($profile,$arrayKeys);
?>