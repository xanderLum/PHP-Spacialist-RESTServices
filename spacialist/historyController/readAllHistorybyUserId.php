<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/history.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$history = new History($db);

$history->user_id  = isset($_GET['user_id']) ? $_GET['user_id'] : die();

 
// query products
$stmt = $history->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $history_arr=array();
    $history_arr["history"]=array();
 
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
         $history_item=array(
            "user_id" =>  $user_id,
            "transaction_id" =>  $transaction_id,
            "payment_id" =>  $payment_id,
            "amount"  => $amount,
            "transaction_date"=>  $transaction_date,
            "appointment_id" => $appointment_id,
            "status"=> $status,
            "dti_no" => $dti_no,
            "bus_name" => $bus_name,
            "bus_address"=> $bus_address,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "appointment_date" => $appointment_date,
            "service_name" => $service_name,
            "firstname" => $firstname,

            
        );
 
         array_push($history_arr["history"], $history_item);
    }
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($history_arr);
    }
 
    else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No history found.")
    );
}