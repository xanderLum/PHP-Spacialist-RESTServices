<?php
class Notification{
 
    // database connection and table name
    private $conn;
    private $table_name = "notification";
 
    // object properties
    public $notif_id;
    //public $status;
    public $status="unread";
    public $sender_user_id;
    public $receiver_bus_id;
    public $appointment_id;
  
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
 
    // select all query
    $query = "SELECT
                * from notification ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
    // create product
    function create(){
 
    // query to insert record
    $query = "INSERT INTO notification (status,sender_user_id,receiver_bus_id,appointment_id) VALUES
            (?,(SELECT user_id from user where user_id=?),(SELECT bus_id from business where bus_id=?),(SELECT appointment_id from appointment where appointment_id=?))";
    //echo $query;
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->sender_user_id=htmlspecialchars(strip_tags($this->sender_user_id));
    $this->receiver_bus_id=htmlspecialchars(strip_tags($this->receiver_bus_id));
    $this->appointment_id=htmlspecialchars(strip_tags($this->appointment_id));

    

 
    // bind values
   
    $stmt->bindParam(1, $this->status);
    $stmt->bindParam(2, $this->sender_user_id);
    $stmt->bindParam(3, $this->receiver_bus_id);
    $stmt->bindParam(4, $this->appointment_id);
    

 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
    }
    // used when filling up the update product form
    function readOne(){
 
    // query to read single record
    $query = "SELECT
                notif_id,status,sender_user_id,receiver_bus_id,appointment_id
            FROM
               notification
                
            WHERE
               status=:staus ";

 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(':status', $this->status, PDO::PARAM_STR);
   
    
   
   
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->notif_id = $row['notif_id'];
    $this->status = $row['status'];
    //$this->password = $row['password'];
    $this->sender_user_id = $row['sender_user_id'];
    $this->receiver_bus_id = $row['receiver_bus_id'];
    $this->appointment_id = $row['appointment_id'];
  

    }

    // update the user
    function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                status = :status,
                sender_user_id = :sender_user_id,
                receiver_bus_id = :receiver_bus_id,appointment_id=:appointment_id
            WHERE
                status = :status";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->sender_user_id=htmlspecialchars(strip_tags($this->sender_user_id));
    $this->receiver_bus_id=htmlspecialchars(strip_tags($this->receiver_bus_id));
    $this->appointment_id=htmlspecialchars(strip_tags($this->appointment_id));
    $this->notif_id=htmlspecialchars(strip_tags($this->notif_id));

 
    // bind values
   
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":sender_user_id", $this->sender_user_id);
    $stmt->bindParam(":receiver_bus_id", $this->receiver_bus_id);
    $stmt->bindParam(":appointment_id", $this->appointment_id);
    $stmt->bindParam(":notif_id", $this->notif_id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
    }

    // delete the product
    function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE notif_id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->notif_id=htmlspecialchars(strip_tags($this->notif_id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->notif_id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
    }

    // search products
    function search($keywords){
 
    // select all query
    $query = "SELECT
                *
            FROM
                " . $this->table_name . " p
                
            WHERE
                username LIKE ? OR firstname LIKE ? OR email LIKE? OR lastname LIKE ?";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
 
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
    $stmt->bindParam(4, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;

    }

}

