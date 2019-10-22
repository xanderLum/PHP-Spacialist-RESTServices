<?php
class Transaction{
 
    // database connection and table name
    private $conn;
    private $table_name = "transaction";
 
    // object properties
    public $transaction_id; 
    public $staff_schedule_id;
    
    public $user_id;
    public $bus_id;
    public $payment_id; 
    public $payment_details;
    public $amount;
    public $staff_service_id;
    public $start_time= "hh:mm:ss";
    public $end_time = "hh:mm:ss";
    public $sched_date="yyyy-mm-dd";
   
    //public $staff_schedule_id;
   /* public $appt_transaction_id;
    public $appt_staff_schedule_id;*/
    public $appointment_id;
    public $appointment_name;
    public $appointment_desc;
    public $status= "confirmed";

    
    //define('status', 'confirmed');

/*{
    "user_id" : "1",
    "bus_id" : "1",
    "payment_id" :"9",
    "payment_details" :"Argie's Body Massage",
    "amount" : "400",
    "staff_service_id":"2",
    "start_time":"08:00:00",
    "end_time":"09:00:00",
    "sched_date":"2019-09-10"
}*/
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;

    }

    // read products
    function read(){
 
    // select all query
    $query = "SELECT
                * from transaction ";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
    // create product
    function create(){
 

   
    $query1 = "INSERT INTO transaction (user_id,bus_id,payment_id,payment_details,amount) VALUES
            ((SELECT user_id FROM USER WHERE user_id=?),(SELECT bus_id from business where bus_id=?),?,?,?)";


            /*INSERT INTO transaction (user_id,bus_id,payment_id,payment_details,amount) VALUES
            ((SELECT user_id FROM USER WHERE user_id=?),(SELECT bus_id from business where bus_id=?),?,?,?)*/
    //echo $query1;
    echo "\n";

    $stmt = $this->conn->prepare($query1);
     //echo " after prepare:"; echo "\n";
            $this->user_id=htmlspecialchars(strip_tags($this->user_id));
            $this->bus_id=htmlspecialchars(strip_tags($this->bus_id));
            $this->payment_id=htmlspecialchars(strip_tags($this->payment_id));
            $this->payment_details=htmlspecialchars(strip_tags($this->payment_details));
            $this->amount=htmlspecialchars(strip_tags($this->amount));

            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->bus_id);
            $stmt->bindParam(3, $this->payment_id);
            $stmt->bindParam(4, $this->payment_details);
            $stmt->bindParam(5, $this->amount);

          /*  echo "user_id:" .$this->user_id; echo "\n";
            echo "bus_id:" .$this->bus_id; echo "\n";
            echo "payment_id:" .$this->payment_id; echo "\n";
            echo "payment_details:" .$this->payment_details; echo "\n";
            echo "amount:" .$this->amount; echo "\n";*/

    
    
    //$transaction_id= lastInsertId();
          //  echo "\nexecute";
    /*if($stmt->execute()){
        //echo "herez".$this->conn->lastInsertId();
        $transaction_id=$this->conn->lastInsertId();
        echo "\n";

        echo "transaction_id: ".$transaction_id ."\n";
        //$this->conn->commit();
    }
    else{ 
       
       echo "wala nakuha ang transaction_id"."\n";
    }*/
    try {  
        $stmt->execute();
        $this->transaction_id=$this->conn->lastInsertId(); 
       // echo "transaction_id: ". $this->transaction_id ."\n";   
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br />\n";
        return false;
    }
    
     /* $stmt = $this->conn->prepare($query1);
      $result = mysqli_query($query1,$conn);*/
    /*echo "ID of last inserted record is: " . mysql_insert_id($transaction_id);*/
      
    /*$mysqli=$this->conn;
    $mysqli->query($query1);
    echo "New Record has id %d.\n", $mysqli->transaction_id;
    $transaction_id=mysqli_insert_id($conn);
    echo "transactionid=" .$transaction_id;*/


    /*working manual
    $query2= "INSERT INTO staff_schedule(staff_service_id,start_time,end_time,sched_date) values((select staff_service_id from staff_service where staff_service_id=2), TIME(\"08:00:00\"),TIME(\"09:00:00\"),DATE(\"2019-09-10\")) ";*/

    $query2= "INSERT INTO staff_schedule(staff_service_id,start_time,end_time,sched_date) values((select staff_service_id from staff_service where staff_service_id=?), TIME(?),TIME(?),DATE(?)) ";
    //echo $query2;
    echo "\n";
     $stmth = $this->conn->prepare($query2);

    // sanitize
       // echo " after prepare:"; echo "\n";
            $this->staff_service_id=htmlspecialchars(strip_tags($this->staff_service_id));
            $this->start_time=htmlspecialchars(strip_tags($this->start_time));
            $this->end_time=htmlspecialchars(strip_tags($this->end_time));
            $this->sched_date=htmlspecialchars(strip_tags($this->sched_date));
             
    // bind values
            
            $stmth->bindParam(1, $this->staff_service_id);
            $stmth->bindParam(2, $this->start_time);
            $stmth->bindParam(3, $this->end_time);
            $stmth->bindParam(4, $this->sched_date);

           /* echo "staff_service_id:" .$this->staff_service_id; echo "\n";
            echo "start_time:" .$this->start_time; echo "\n";
            echo "end_time:" .$this->end_time; echo "\n";
            echo "sched_date:" .$this->sched_date; echo "\n";
           */


   /* if($stmth->execute()){
        //echo "here".$this->conn->lastInsertId();
        $staff_schedule_id=$this->conn->lastInsertId();
        echo ".\n";
        echo "staff_schedule_id: ".$staff_schedule_id ."\n";
       
        //echo $staff_schedule_id;
        //$this->conn->commit();
        return true;
    }*/
     try {  
        $stmth->execute();
        $this->staff_schedule_id=$this->conn->lastInsertId(); 
       // echo "staff_schedule_id: ".$this->staff_schedule_id ."\n";   
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br />\n";
        return false;
    }
    /*else{ 
       
       echo "wala nakuha ang staff_schedule_id"."\n";
    }
     */
   
   //else{return false;}
     /*$appt_transaction_id= $transaction_id;
     $appt_staff_schedule_id= $staff_schedule_id;*/

    $query3= "INSERT INTO appointment (transaction_id,staff_schedule_id,appointment_name,appointment_desc,status) VALUES
            (?,?,?,?,?)";
    //echo $query3;
    echo "\n";
    $stmti = $this->conn->prepare($query3);
    //echo $query;
    // prepare query
           /* $this->transaction_id=htmlspecialchars(strip_tags($this->transaction_id));
            $this->staff_schedule_id=htmlspecialchars(strip_tags($this->staff_schedule_id));
            $this->appointment_name=htmlspecialchars(strip_tags($this->appointment_name));
            $this->appointment_desc=htmlspecialchars(strip_tags($this->appointment_desc));
            $this->status=htmlspecialchars(strip_tags($this->status));*/


            $stmti->bindParam(1, $this->transaction_id);
            $stmti->bindParam(2, $this->staff_schedule_id);
            $stmti->bindParam(3, $this->appointment_name);
            $stmti->bindParam(4, $this->appointment_desc);
            $stmti->bindParam(5, $this->status);

         /*   echo "transaction_id:" .$this->transaction_id; echo "\n";
            echo "staff_schedule_id:" .$this->staff_schedule_id; echo "\n";
            echo "appointment_name:" .$this->appointment_name; echo "\n";
            echo "appointment_desc:" .$this->appointment_desc; echo "\n";
            echo "status:" .$this->status; echo "\n";
  */
           
  
    /*if($stmti->execute()){
        //echo "here".$this->conn->lastInsertId();
        $appointment_id=$this->conn->lastInsertId();
        echo ".\n";
        echo "appointment_id: ".$appointment_id ."\n";
        return true;
   // $this->conn->commit();
    }*/

     try {  
        $stmti->execute();
        $this->appointment_id=$this->conn->lastInsertId(); 
        //echo "appointment_id: ". $this->appointment_id ."\n";  
        return true; 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br />\n";
        return false;
    }
/*
    else{return false;}*/

}
    
   


    // used when filling up the update product form
    function readOne(){
 
    // query to read single record
    $query =  "SELECT u.user_id,sd.staff_schedule_id,b.bus_id,t.payment_id,t.amount,t.date_time from user u inner join transaction t on u.user_id=t.user_id inner join staff_schedule sd on sd.staff_schedule_id=t.staff_schedule_id inner join business b on b.bus_id=t.bus_id where u.user_id=?";

 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->user_id);
    
    
   
   
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->user_id = $row['user_id'];
    $this->staff_schedule_id = $row['staff_schedule_id'];
    $this->bus_id = $row['bus_id'];
    $this->payment_id = $row['payment_id'];
    $this->amount = $row['amount'];
   

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

