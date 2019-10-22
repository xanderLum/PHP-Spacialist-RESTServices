<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");  
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/transaction.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$transaction = new Transaction($db);
 
// set ID property of record to read
$transaction->user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die();
 
// read the details of product to be edited
$transaction->readOne();
 
if($transaction->user_id!=null){
    // create array
    $transaction_arr = array(
        "user_id" =>  $transaction->user_id,
        "staff_schedule_id" =>  $transaction->staff_schedule_id,
        "bus_id" =>  $transaction->bus_id,
        "payment_id"=>  $transaction->payment_id,
        "amount" => $transaction->amount,
       
    );
 
    // set response code - 200 OK
    http_response_code(200);
   
    // make it json format
    echo json_encode($transaction_arr);
}
 
else{ 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Transaction does not exist."));
}
?>