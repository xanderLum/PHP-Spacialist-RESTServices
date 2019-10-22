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
include_once '../objects/transaction.php';
 
$database = new Database();
$db = $database->getConnection();

 
$transaction = new Transaction($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->user_id) &&
    !empty($data->bus_id) &&
    !empty($data->payment_id) &&
    !empty($data->payment_details) &&
    !empty($data->amount) &&
    !empty($data->staff_service_id) &&
    !empty($data->start_time) &&
    !empty($data->end_time) &&
    !empty($data->sched_date) &&
    !empty($data->appointment_name) &&
    !empty($data->appointment_desc) /*&&
    !empty($data->status)*/

    /*!empty($data->transaction_id) &&
    !empty($data->staff_schedule_id) &&*/
   
)

{
 
    // set product property values
     //echo "if transaction";
    $transaction->user_id = $data->user_id;
    $transaction->bus_id = $data->bus_id;
    $transaction->payment_id = $data->payment_id;
    $transaction->payment_details= $data->payment_details;
    $transaction->amount = $data->amount;
    $transaction->staff_service_id = $data->staff_service_id;
    $transaction->start_time = $data->start_time;
    $transaction->end_time = $data->end_time;
    $transaction->sched_date = $data->sched_date;
    $transaction->appointment_name = $data->appointment_name;
    $transaction->appointment_desc = $data->appointment_desc;
    $transaction->status ="confirmed";

   /* public $user_id;
    public $bus_id;
    public $payment_id; 
    public $payment_details;
    public $amount;
    public $staff_service_id;
    public $start_time= "hh:mm:ss";
    public $end_time = "hh:mm:ss";
    public $sched_date="yyyy-mm-dd";*/
   // $transaction->amount = $data->transaction_id;
    //$transaction->amount = $data->staff_schedule_id;
    /*$transaction->amount = $data->appointment_name;
    $transaction->amount = $data->appointment_desc;
    $transaction->amount = $data->status;*/
    /*echo "user id:".$user_id= $data->user_id;
    echo "\n";
    echo "bus_id:".$bus_id= $data->bus_id;
    echo "\n";
    echo "payment_id:".$payment_id= $data->payment_id;
    echo "\n";
    echo "payment_details:".$payment_details= $data->payment_details;
    echo "\n";
    echo "amount:".$amount= $data->amount;
    echo "\n";
    echo "staff_service_id:".$staff_service_id= $data->staff_service_id;
    echo "\n";
    echo "start_time:".$start_time= $data->start_time;
    echo "\n";
    echo "end_time:".$end_time= $data->end_time;
    echo "\n";
    echo "sched_date:".$sched_date= $data->sched_date;
    echo "\n";
    echo "appointment_name:".$appointment_name= $data->appointment_name;
    echo "\n";
    echo "appointment_desc:".$appointment_desc= $data->appointment_desc;
    echo "\n";
    echo "status:".$status= $data->status;
    echo "\n";*/
       
 
    // create the product
    if($transaction->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("appointment_id" => $transaction->appointment_id));

       // echo json_encode(array("message" => "Transaction was created."));
       // echo "appointment__id:" .$this->appointment_id; echo "\n";
         
         //echo "appointment_id:" .$transaction->appointment_id ;
       // echo json_encode (array("appointment_id: ". $this->appointment_id ."\n"));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create transaction."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create transaction. Data is incomplete."));
}
?>