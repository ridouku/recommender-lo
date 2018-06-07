<?php
namespace RecommenderEngine;
use RecommenderEngine\CollaborativeFilter;
use RecommenderEngine\BasedContentFilter;
use RecommenderEngine\HybridFilter;
use RecommenderEngine\ShowOL;
use ProfileTest\profileTest;
use MatrixGenerator\JSONtoMatrix;//Calls the class to transform JSON data into Matrix

class Engine
{
	public static function start($host,$topic,$tags,$id){
		// $tag=$tags;
		// $url = "http://".$host."/ObjectLearningList/?q=".urlencode($topic); 
		// $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		// $arrayHeaders=["idobject","title","tag","location","pg_level","lod_type"
		// ,"colg_type"];
		// $keyMerge="idobject";//field to compare repeated elements
		// $sortField="idobject";
		// $fieldMerge="tag";//field that contains information to merge 
		// $matrixOL=JSONtoMatrix::JSONtoMatrixMerge($response,$arrayHeaders,$keyMerge,$fieldMerge,$sortField);
		// //echo JSONtoMatrix::printMatrix($matrixOL,$arrayHeaders);
		// // $matrixMerge=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders,'idobject'); #Test to convert json into matrix without merge
		// // echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);
		// #Test matrix rankings list LO(Learning Object)
		// $title="Sistemas de numeración: decimal, binario, hexadecimal";
		// $url = "http://192.168.200.2:8000/ObjectLearningRankingsList/?q=".urlencode($topic);

		// $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		// $arrayHeaders=["object_learning","student","ranking"];
		// $matrixMerge=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders,'object_learning');
		// //echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);

		// $url="http://".$host."/ObjectLearningRankingsListTeacher/?q=".urlencode($title);

		// $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		// $arrayHeaders2=["object_learning","id","ranking"];
		// $matrixMerge2=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders2,'object_learning');
		// $arrayHeadersNewMatrix=["object_learning","id","ranking"];
		// $matrixMerge=JSONtoMatrix::dataMatrixMerge($matrixMerge,$matrixMerge2,$arrayHeadersNewMatrix,$arrayHeaders,$arrayHeaders2,'object_learning');
		// //echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeadersNewMatrix);
		// #Matrix Profile Student
		// $url="http://".$host."/ProfilesList/?q=".urlencode($id);
		// $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		// $arrayHeaders=["performance_group","lvl_difficulty","style","value"];
		// $arrayKeys=["pg_level","lod_type"
		// ,"colg_type"
		// ,"rsi_result"];
		// //rint_r( $properties);
		// $profile=JSONtoMatrix::dataJSONtoMatrixProfile($response,$arrayHeaders,$arrayKeys);
		// // echo JSONtoMatrix::printMatrix($profile,$arrayKeys);
		// $arrayDataKeys=["idobject","title","tag","location","ranking"];

		// #Collaborative Filter
		// $dataCollaborative=CollaborativeFilter::collaborativeFilter($matrixMerge,'object_learning','id','ranking',$id);
		// //print_r($dataCollaborative);
		// $dataBasedContent=BasedContentFilter::basedContentFilter($matrixOL,$profile,'rsi_result','idobject');

		// $dataHybridFilter=HybridFilter::hybridFilter($dataCollaborative,$dataBasedContent,$matrixOL,'idobject',$arrayDataKeys);

		return "hola"; //ShowOL::showOL($dataHybridFilter,$arrayDataKeys,$id);
	}
	

}
?>