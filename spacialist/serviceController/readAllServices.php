<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/services.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$service = new Service($db);

$service->bus_id  = isset($_GET['bus_id']) ? $_GET['bus_id'] : die();

 
// query products
$stmt = $service->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $service_arr=array();
    $service_arr["services"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $service_item=array(
           
            "bus_id" => $bus_id,
            "bus_name" => $bus_name,
            "service_id" => $service_id,
            "service_name" => $service_name,
            "service_desc" => $service_desc,
            "duration" => $duration,
            "service_type" => $service_type,
            "price" => $price,

            
        );
 
        array_push($service_arr["services"], $service_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($service_arr);
    }
 
	else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No service found.")
    );
}