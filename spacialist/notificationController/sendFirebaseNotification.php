
<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once __DIR__ . '/notification.php';

// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
	/*!empty($data->status) &&*/
	!empty($data->title) &&
	!empty($data->message) &&
	!empty($data->image_url) &&
	!empty($data->firebase_token) &&
	!empty($data->firebase_api)
)
{

    // set product property values
	$notification = new Notification();

	$title = $data->title;
	$message = $data->message;
	$imageUrl = $data->image_url;
	$action = $data->action;

	// $actionDestination = isset($_POST['action_destination'])?$_POST['action_destination']:'';
	
	$actionDestination = $data->action_destination;

	if($actionDestination ==''){
		$action = '';
	}
	$notification->setTitle($title);
	$notification->setMessage($message);
	$notification->setImage($imageUrl);
	$notification->setAction($action);
	$notification->setActionDestination($actionDestination);

	$firebase_token = $data->firebase_token;
	$firebase_api = $data->firebase_api;


	$requestData = $notification->getNotificatin();

	$fields = array(
		'to' => $firebase_token,
		'data' => $requestData,
	);

	// Set POST variables
	$url = 'https://fcm.googleapis.com/fcm/send';

	$headers = array(
		'Authorization: key=' . $firebase_api,
		'Content-Type: application/json'
	);

						// Open connection
	$ch = curl_init();

						// Set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

						// Disabling SSL Certificate support temporarily
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

						// Execute post
	$result = curl_exec($ch);
	if($result === FALSE){
		die('Curl failed: ' . curl_error($ch));
	}

			// Close connection
	curl_close($ch);

		/*echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
		echo json_encode($fields,JSON_PRETTY_PRINT);
		echo '</pre></p><h3>Response </h3><p><pre>';
		echo $result;
		echo '</pre></p>';*/
	// }
		http_response_code(200);

        // tell the user
		echo json_encode(array("message" => "Firebase Notification sent."));
	}else{
		http_response_code(400);

    // tell the user
		echo json_encode(array("message" => "Unable to send firebase notification. Data is incomplete."));
	}
	?>
