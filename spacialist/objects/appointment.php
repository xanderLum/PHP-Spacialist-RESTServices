<?php
class Appointment{
 
    // database connection and table name
    private $conn;
    private $table_name = "appointment";
 
    // object properties
    public $appointment_id;
    public $transaction_id;
    public $appointment_name;
    public $appointment_desc;
    public $status;
    
    
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;

    }

    // read products
    function read(){
 
    // select all query
    $query = "SELECT
                * from appointment ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
    // create product
    function create(){
 
    // query to insert record
    $query = "INSERT INTO appointment (transaction_id,appointment_name,appointment_desc,status) VALUES
            ((SELECT transaction_id FROM TRANSACTION WHERE transaction_id=?),?,?,?)";

    //echo $query;
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->transaction_id=htmlspecialchars(strip_tags($this->transaction_id));
    $this->appointment_name=htmlspecialchars(strip_tags($this->appointment_name));
    $this->appointment_desc=htmlspecialchars(strip_tags($this->appointment_desc));
    $this->status=htmlspecialchars(strip_tags($this->status));
   

 
    // bind values
   
    $stmt->bindParam(1, $this->transaction_id);
    $stmt->bindParam(2, $this->appointment_name);
    $stmt->bindParam(3, $this->appointment_desc);
    $stmt->bindParam(4, $this->status);
  
    


 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
    }
    // used when filling up the update product form
    function readOne(){
 
    // query to read single record
    $query =  "SELECT transaction_id,appointment_name,appointment_desc,status from appointment where transaction_id=?";

    //echo $query;
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->transaction_id);
    
    
   
   
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->transaction_id = $row['transaction_id'];
    $this->appointment_name = $row['appointment_name'];
    $this->appointment_desc = $row['appointment_desc'];
    $this->status = $row['status'];

    }

    // update the user
    function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                username = :username,
                password = :password,
                firstname = :firstname,
                lastname = :lastname,
                phone_num = :phone_num,
                address = :address

            WHERE
                user_id = :user_id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->phone_num=htmlspecialchars(strip_tags($this->phone_num));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));

 
    // bind values
   
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":firstname", $this->firstname);
    $stmt->bindParam(":lastname", $this->lastname);
    $stmt->bindParam(":phone_num", $this->phone_num);
    $stmt->bindParam(":address", $this->address);
    $stmt->bindParam(":user_id", $this->user_id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
    }

    // delete the product
    function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->user_id);
 
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
                username LIKE ? OR firstname LIKE ? OR lastname LIKE ?";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
 
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;

    }

}

