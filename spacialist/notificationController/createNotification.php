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
include_once '../objects/notification.php';
 
$database = new Database();
$db = $database->getConnection();
 
$notification = new Notification($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    /*!empty($data->status) &&*/
    !empty($data->sender_user_id) &&
    !empty($data->receiver_bus_id) &&
    !empty($data->appointment_id)
)
{
 
    // set product property values
    
    $notification->status = "unread";
    $notification->sender_user_id = $data->sender_user_id;
    $notification->receiver_bus_id = $data->receiver_bus_id;
    $notification->appointment_id = $data->appointment_id;
    
 
    // create the product
    if($notification->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Notification was created."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create notification."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create notification. Data is incomplete."));
}
?>