<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $user_id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;
    public $phone_num;
    public $email;
    public $address;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
 
    // select all query
    $query = "SELECT
                * from user ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
    // create product
    function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                 username=:username, password=:password, firstname=:firstname, lastname=:lastname, phone_num=:phone_num, email=:email, address=:address";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->phone_num=htmlspecialchars(strip_tags($this->phone_num));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->address=htmlspecialchars(strip_tags($this->address));

 
    // bind values
   
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":firstname", $this->firstname);
    $stmt->bindParam(":lastname", $this->lastname);
    $stmt->bindParam(":phone_num", $this->phone_num);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":address", $this->address);

 
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
                user_id,username,firstname,lastname,phone_num,email,address
            FROM
                " . $this->table_name . " 
                
            WHERE
               username=:username AND  password=:password
            LIMIT
                0,1";

 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
    
   
   
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->user_id = $row['user_id'];
    $this->username = $row['username'];
    //$this->password = $row['password'];
    $this->firstname = $row['firstname'];
    $this->lastname = $row['lastname'];
    $this->phone_num = $row['phone_num'];
    $this->email = $row['email'];
    $this->address = $row['address'];

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
                email = :email,
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
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->address=htmlspecialchars(strip_tags($this->address));
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));

 
    // bind values
   
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":firstname", $this->firstname);
    $stmt->bindParam(":lastname", $this->lastname);
    $stmt->bindParam(":phone_num", $this->phone_num);
    $stmt->bindParam(":email", $this->email);
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

