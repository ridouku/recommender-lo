<?php 
use RecommenderEngine\CollaborativeFilter;
use RecommenderEngine\BasedContentFilter;
use RecommenderEngine\HybridFilter;
use RecommenderEngine\ShowOL;
use ProfileTest\profileTest;
use MatrixGenerator\JSONtoMatrix;//Calls the class to transform JSON data into Matrix

$arrContextOptions=array(
  "ssl"=>array(
    "verify_peer"=>false,
    "verify_peer_name"=>false,
  ),
); 

$host="192.168.200.2:8000";//Define your host to request information
$topic="Sistemas operativos, definición";//Define topic of the day
$id=intval( "4");//Define user's id
$tags="";
$url = "http://".$host."/ObjectLearningList/?q=".urlencode($topic); 
$response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
$arrayHeaders=["idobject","title","tag","location","pg_level","lod_type"
,"colg_type","object_group_description","level_value","object_type_description","pg_name"];
    $keyMerge="idobject";//field to compare repeated elements
    $sortField="idobject";
    $fieldMerge="tag";//field that contains information to merge 
    $matrixOL=JSONtoMatrix::JSONtoMatrixMerge($response,$arrayHeaders,$keyMerge,$fieldMerge,$sortField);
    //echo JSONtoMatrix::printMatrix($matrixOL,$arrayHeaders);
    // $matrixMerge=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders,'idobject'); #Test to convert json into matrix without merge
    // echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);
    #Test matrix rankings list LO(Learning Object)
    $url = "http://".$host."/ObjectLearningRankingsList/?q=".urlencode($topic);
    $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
    $arrayHeaders=["object_learning","student","ranking"];
    $matrixMerge=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders,'object_learning');
    //echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeaders);

    $url="http://".$host."/ObjectLearningRankingsListTeacher/?q=".urlencode($topic);

    $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
    $arrayHeaders2=["object_learning","id","ranking"];
    $matrixMerge2=JSONtoMatrix::dataJSONtoMatrix($response,$arrayHeaders2,'object_learning');
    $arrayHeadersNewMatrix=["object_learning","id","ranking"];
    $matrixMerge=JSONtoMatrix::dataMatrixMerge($matrixMerge,$matrixMerge2,$arrayHeadersNewMatrix,$arrayHeaders,$arrayHeaders2,'object_learning');
    //echo JSONtoMatrix::printMatrix($matrixMerge,$arrayHeadersNewMatrix);
    #Matrix Profile Student
    $url="http://".$host."/ProfilesList/?q=".urlencode($id);
    $response = @file_get_contents($url, false, stream_context_create($arrContextOptions));
    $arrayHeaders=["performance_group","lvl_difficulty","style","value"];
    $arrayKeys=["pg_level","lod_type"
    ,"colg_type"
    ,"rsi_result"];
    //rint_r( $properties);
    $profile=JSONtoMatrix::dataJSONtoMatrixProfile($response,$arrayHeaders,$arrayKeys);
    // echo JSONtoMatrix::printMatrix($profile,$arrayKeys);
    $arrayDataKeys=["idobject","title","tag","location","ranking","object_group_description","level_value","object_type_description","pg_name"];

    #Collaborative Filter
    $dataCollaborative=CollaborativeFilter::collaborativeFilter($matrixMerge,'object_learning','id','ranking',$id);
    //print_r($dataCollaborative);
    $dataBasedContent=BasedContentFilter::basedContentFilter($matrixOL,$profile,'rsi_result','idobject','colg_type');

    $dataHybridFilter=HybridFilter::hybridFilter($dataCollaborative,$dataBasedContent,$matrixOL,'idobject',$arrayDataKeys);


    
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/stylesheet.css">
</head>
<script>

function addRating(obj,ol,id)  {
    var stol=ol;
    var stid=id;
    var strate=obj.value;
    var host="http://192.168.200.2:8000/updateRating/";
    var nurl=host.concat(stid,"/",stol,"/",strate);
    $.ajax({
        url : nurl, // the endpoint
        type : "GET", // http method
        data : { csrfmiddlewaretoken : '{{ csrf_token }}' }, // data sent with the post request

        // handle a successful response
        success : function(json) {
            console.log(json); // log the returned json to the console
            console.log("success"); // another sanity check
        },

        // handle a non-successful response
        error : function(xhr,errmsg,err) {
           //alert('Problemas con el servidor, tu solicitud no pudo ser resuelta');
        }
    });
};
function isAnswered() {
  var radioGroups = {}
  for(i in (inputs = document.getElementsByTagName('input'))) {
    if(inputs[i].type === 'radio') {
      radioGroups[inputs[i].name] = radioGroups[inputs[i].name] ? true : inputs[i].checked;
    }
  }
  for(i in radioGroups) {
    if(radioGroups[i] === false) return false; 
  }
  return true;
}
 </script>
<body>
	<?php
		echo ShowOL::showOL($dataHybridFilter,$arrayDataKeys,$id);
		//echo profileTest::startTest();
	?>

</body>
</html>