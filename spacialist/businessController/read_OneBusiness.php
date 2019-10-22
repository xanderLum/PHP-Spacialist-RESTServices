<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/business.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$business = new Business($db);
 
// set ID property of record to read
$business->bus_id = isset($_GET['bus_id']) ? $_GET['bus_id'] : die();
 
// read the details of product to be edited
$business->readOne();
 
if($business->bus_name!=null){
    // create array
    $business_arr = array(
        "bus_id" =>  $business->bus_id,
        "bus_logo" =>  $business->bus_logo,
        "bus_name" => $business->bus_name,
        "owner" => $business->owner,
        "address" => $business->address,
        "contact_no" => $business->contact_no,
        "maps_latitude" => $business->maps_latitude,
        "maps_longitude" => $business->maps_longitude,
    );
 
    // set response code - 200 OK
    http_response_code(200);
   
    // make it json format
    echo json_encode($business_arr);
}
 
else{ 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Business does not exist."));
}
?>