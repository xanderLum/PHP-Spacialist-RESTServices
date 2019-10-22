<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/appointment.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$appointment = new Appointment($db);
 
// query products
$stmt = $appointment->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $appt_arr=array();
    $appt_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $appt_item=array(
            "transaction_id" => $transaction_id,
            "appointment_name" => $appointment_name,
            /*"bus_email" => $bus_email,
            "bus_password" => $bus_password,*/
            "appointment_desc" => $appointment_desc,
            "status" => $status,
        );
 
        array_push($appt_arr["records"], $appt_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($appt_arr);
    }
 
	else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No transaction found.")
    );
}