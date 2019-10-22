<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../objects/services.php';
 
$database = new Database();
$db = $database->getConnection();
 
$service = new Service($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->bus_id) &&
    !empty($data->bus_name) &&
    !empty($data->service_id) &&
    !empty($data->service_name) &&
    !empty($data->service_desc) &&
    !empty($data->duration) && 
    !empty($data->price) &&
)
{
 
    // set product property values
    
    $user->bus_id = $data->bus_id;
    $user->bus_name = $data->bus_name;
    $user->service_id = $data->service_id;
    $user->service_name = $data->service_name;
    $user->service_desc = $data->service_desc;
    $user->duration = $data->duration;
    $user->price = $data->price;
 
    // create the product
    if($user->createServiceById()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Service was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create service."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create service. Data is incomplete."));
}
?>