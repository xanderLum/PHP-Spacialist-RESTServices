<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
//include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/business.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$business = new Business($db);
 
// get keywords
$selected_time=isset($_GET["selected_time"]) ? $_GET["selected_time"] : "";



// query products
$stmt = $business->searchBusinessByTime($selected_time);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $business_arr=array();
    $business_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $business_item=array(
             "bus_id" => $bus_id,
            "bus_logo" => $bus_logo,
            "bus_name" => $bus_name,
            "owner" => $owner,
            //"bus_logo" => $bus_logo,
            "address" => $address,
            /*"bus_email" => $bus_email,
            "bus_password" => $bus_password,*/
            "contact_no" => $contact_no,
            "maps_latitude" => $maps_latitude,
            "maps_longitude" => $maps_longitude,
        );
 
        array_push($business_arr["records"], $business_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data
    echo json_encode($business_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No business found.")
    );
}
?>