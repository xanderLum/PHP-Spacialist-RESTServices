<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");  
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/appointment.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$appointment = new Appointment($db);
 
// set ID property of record to read
$appointment->transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : die();
 
// read the details of product to be edited
$appointment->readOne();
 
if($appointment->transaction_id!=null){
    // create array
    $appointment_arr = array(
        "transaction_id" =>  $appointment->transaction_id,
        "appointment_name" =>  $appointment->appointment_name,
        "appointment_desc" =>  $appointment->appointment_desc,
        "status"=>  $appointment->status,
    );
 
    // set response code - 200 OK
    http_response_code(200);
   
    // make it json format
    echo json_encode($appointment_arr);
}
 
else{ 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Appointment does not exist."));
}
?>