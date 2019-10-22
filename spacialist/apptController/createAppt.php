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
include_once '../objects/appointment.php';
 
$database = new Database();
$db = $database->getConnection();
 
$appointment = new Appointment($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->transaction_id) &&
    !empty($data->appointment_name) &&
    !empty($data->appointment_desc) &&
    !empty($data->status)  

)
{
 
    // set product property values
    
    $appointment->transaction_id = $data->transaction_id;
    $appointment->appointment_name = $data->appointment_name;
    $appointment->appointment_desc = $data->appointment_desc;
    $appointment->status = $data->status;
    
 
    // create the product
    if($appointment->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Appointment was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create appointment."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create appointment. Data is incomplete."));
}
?>