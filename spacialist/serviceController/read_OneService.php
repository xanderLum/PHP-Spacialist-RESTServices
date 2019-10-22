<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");  
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/services.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$service = new Service($db);
 
// set ID property of record to read
$service->service_name = isset($_GET['service_name']) ? $_GET['service_name'] : die();
 
// read the details of product to be edited
$service->readOne();
 
if($service->service_name!=null){
    // create array
    $service_arr = array(
        "service_id" =>  $service->service_id,
        "service_name" =>  $service->service_name,
        "service_desc" =>  $service->service_desc,
        "duration" => $service->duration,
       
    );
 
    // set response code - 200 OK
    http_response_code(200);
   
    // make it json format
    echo json_encode($service_arr);
}
 
else{ 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Business does not exist."));
}
?>