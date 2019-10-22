<?php
class Business{
 
    // database connection and table name
    private $conn;
    private $table_name = "business";
    private $table_name1= "business_operating_hr";
 
    // object properties
    public $bus_id;
    public $bus_logo;
    public $bus_name;
    public $owner;
    public $address;
    public $bus_email;
    public $bus_password;
    public $contact_no;
    public $dti_no;
    public $maps_latitude;
    public $maps_longitude;
    public $selected_time= "hh:mm:ss";
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
 
    // select all query
    $query = "SELECT
               bus_id,bus_logo,bus_name,owner,address,contact_no,dti_no,maps_latitude,maps_longitude from business
                ORDER BY bus_id ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
   
    function readOne(){
 
    // query to read single record
    $query = "SELECT
              bus_logo,bus_name,owner,address,contact_no,dti_no,maps_latitude,maps_longitude
            FROM
                " . $this->table_name . " 
                
            WHERE
               bus_id= ?
            LIMIT
                0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->bus_id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    //$this->bus_id = $row['bus_id'];
    $this->bus_logo = $row['bus_logo'];
    $this->bus_name = $row['bus_name'];
    $this->owner = $row['owner'];
    $this->address = $row['address'];
    $this->contact_no = $row['contact_no'];
    $this->maps_latitude = $row['maps_latitude'];
    $this->maps_longitude = $row['maps_longitude'];

    }

   
    // search business
    function search($keywords){
 
    // select all query
    $query = "SELECT
                *
            FROM
                " . $this->table_name . " 
                
            WHERE
                bus_name LIKE ? OR address LIKE? ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
 
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    //$stmt->bindParam(3, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;

    }

     // search business
    function searchBusinessByTime($selected_time){
 
    // select all query
    $query = "SELECT * FROM business as bus inner join business_operating_hr as bus1 on bus.bus_id = bus1.bus_id WHERE TIME(bus1.open_hr) <= TIME(\"$selected_time\")  AND TIME(bus1.close_hr) > TIME(\"$selected_time\")";
     

            /*SELECT * FROM business as bus inner join business_operating_hr as bus1 on bus.bus_id = bus1.bus_id WHERE TIME(bus1.open_hr) <= TIME("08:00:00") AND TIME(bus1.close_hr) >= TIME("08:00:00")*/
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
   // $selected_time=htmlspecialchars(strip_tags($selected_time));
    //$selected_time = "h:i:s";
 
    // bind
    $stmt->bindParam(1, $selected_time);
  // $stmt->bindParam(2, $keywords);
    //$stmt->bindParam(3, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;

    }
}

