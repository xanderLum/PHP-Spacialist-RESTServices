<?php
class History{
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $user_id;
    public $transaction_id;
    public $payment_id;
    public $amount;
    public $transaction_date;
    public $appointment_id;
    public $status;
    public $dti_no;
    public $bus_name;
    public $bus_address;
    public $start_time;
    public $end_time;
    public $appointment_date;
    public $service_name;
    public $firstname;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
 
    // select all query
        //SELECT b.bus_id,b.bus_name,s.service_id,s.service_name,s.duration,bsp.price from business b,service s,bus_service_price bsp where b.bus_id=1 limit 5
   $query = "SELECT u.user_id,t.transaction_id,t.payment_id,t.amount,t.created_dt as transaction_date,a.appointment_id,a.status,b.dti_no,b.bus_name,b.address as bus_address,sd.start_time,sd.end_time,sd.sched_date as appointment_date, s.service_name,st.firstname from appointment a inner join transaction t on a.transaction_id=t.transaction_id inner join staff_schedule sd on sd.staff_schedule_id=a.staff_schedule_id inner join business b on t.bus_id=b.bus_id inner join staff_service ss on ss.staff_service_id=sd.staff_service_id inner join service s on s.service_id=ss.service_id inner join staff st on st.staff_id=ss.staff_id inner join user u on u.user_id=t.user_id where u.user_id=? order by transaction_date desc ";
 
    // prepare query statement
     $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->user_id);
    
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
   
     function readAllServicesByStaff($selected_time, $selected_date){
        $query="SELECT bus.bus_id, bus.bus_name, ss.staff_service_id, s.service_id, s.service_name, s.service_desc, s.duration, bsp.price, staff_service_avail.staff_id, st.firstname 
            from business bus
            INNER JOIN bus_service_price bsp
            ON bsp.bus_id=bus.bus_id
            INNER JOIN staff st
            ON bus.bus_id=st.bus_id
            INNER JOIN staff_service ss
            ON st.staff_id=ss.staff_id
            INNER JOIN service s
            ON ss.service_id=s.service_id,
            (SELECT ss.service_id, ss.staff_service_id, ss.staff_id FROM staff_service ss, service s
                    WHERE 
                        s.service_id = ss.service_id
                    AND
                        ss.staff_id NOT IN (
                        SELECT sub_ss.staff_id from staff_service sub_ss
                            WHERE 
                                sub_ss.service_id=s.service_id
                                AND
                                    sub_ss.staff_id
                                IN (SELECT sub_sub_ss.staff_id FROM staff_service sub_sub_ss
                                WHERE 
                                    sub_sub_ss.staff_service_id IN (SELECT sd.staff_service_id FROM staff_schedule sd
                                    WHERE   TIME(\"$selected_time\") >= sd.start_time
                                    AND (TIME(\"$selected_time\")+s.duration <= TIME(sd.end_time) 
                                    OR TIME(\"$selected_time\") <= TIME(sd.end_time))
                                    AND sd.sched_date = date(\"$selected_date\"))))) staff_service_avail
            WHERE 
            staff_service_avail.staff_id=st.staff_id
            AND staff_service_avail.service_id=s.service_id
            AND bus.bus_id=?
            AND bsp.service_id=s.service_id";
 
    // select all query
        //SELECT b.bus_id,b.bus_name,s.service_id,s.service_name,s.duration,bsp.price from business b,service s,bus_service_price bsp where b.bus_id=1 limit 5
    /*$query = "SELECT b.bus_id,b.bus_name,ss.service_id,s.service_name,s.service_desc,s.duration,bsp.price,ss.staff_id,st.firstname from business b INNER JOIN bus_service_price bsp ON bsp.bus_id=B.bus_id INNER JOIN service s ON bsp.service_id=s.service_id INNER JOIN staff_service ss ON ss.service_id=bsp.service_id INNER JOIN staff st ON ss.staff_id=st.staff_id where b.bus_id=? limit 10";*/
        /*$query= "SELECT bus.bus_id, bus.bus_name, s.service_id, s.service_name, s.service_desc, s.duration, bsp.price, staff_service_avail.staff_id, st.firstname 
                from business bus
                INNER JOIN bus_service_price bsp
                ON bsp.bus_id=bus.bus_id
                INNER JOIN staff st
                ON bus.bus_id=st.bus_id
                INNER JOIN staff_service ss
                ON st.staff_id=ss.staff_id
                INNER JOIN service s
                ON ss.service_id=s.service_id,
                (SELECT ss.service_id, ss.staff_service_id, ss.staff_id FROM staff_service ss, service s
                        WHERE 
                            s.service_id = ss.service_id
                        AND
                            ss.staff_id NOT IN (
                            SELECT sub_ss.staff_id from staff_service sub_ss
                                WHERE 
                                    sub_ss.service_id=s.service_id
                                    AND
                                        sub_ss.staff_id
                                    IN (SELECT sub_sub_ss.staff_id FROM staff_service sub_sub_ss
                                    WHERE 
                                        sub_sub_ss.staff_service_id IN (SELECT sd.staff_service_id FROM staff_schedule sd
                                        WHERE   TIME(\"$selected_time\")+s.duration >= sd.start_time
                                        AND TIME(\"$selected_time\") <= TIME(sd.end_time)
                                        AND sd.sched_date = date(\"$selected_date\"))))) staff_service_avail
                WHERE 
                staff_service_avail.staff_id=st.staff_id
                AND staff_service_avail.service_id=s.service_id
                AND bus.bus_id=1
                AND bsp.service_id=s.service_id";*/
     /*   $query="SELECT b.bus_id,b.bus_name,ss.service_id,s.service_name,s.service_desc,s.duration,bsp.price,ss.staff_id,st.firstname, sd.start_time, sd.end_time from business b INNER JOIN bus_service_price bsp ON bsp.bus_id=B.bus_id INNER JOIN service s ON bsp.service_id=s.service_id INNER JOIN staff_service ss ON ss.service_id=bsp.service_id INNER JOIN staff st ON ss.staff_id=st.staff_id inner join staff_schedule sd on ss.staff_service_id=sd.staff_service_id where b.bus_id=1 and ( TIME(\"$selected_time\") < TIME(sd.start_time) || TIME(\"$selected_time\") >= TIME(sd.end_time)) and (TIME(\"$selected_time\")+s.duration < sd.start_time || TIME(\"$selected_time\")+s.duration >= sd.end_time) || ((TIME(\"$selected_time\") > TIME(sd.start_time) || TIME(\"$selected_time\") <= TIME(sd.end_time)) and (TIME(\"$selected_time\")+s.duration > sd.start_time|| TIME(\"$selected_time\")+s.duration <= sd.end_time) AND sd.sched_date != DATE(\"$selected_date\"))";*/

/*       $query= "SELECT bus.bus_id, bus.bus_logo, bus.bus_name, bus.owner, bus.address,s.service_id, s.service_name, s.service_desc,staff_service_avail.staff_id, st.firstname 
            from business bus
            INNER JOIN staff st
            ON bus.bus_id=st.bus_id
            INNER JOIN staff_service ss
            ON st.staff_id=ss.staff_id
            INNER JOIN service s
            ON ss.service_id=s.service_id,
            (SELECT ss.service_id, ss.staff_service_id, ss.staff_id FROM staff_service ss, service s
                    WHERE 
                        s.service_id = ss.service_id
                    AND
                        ss.staff_id NOT IN (
                        SELECT sub_ss.staff_id from staff_service sub_ss
                            WHERE 
                                sub_ss.service_id=s.service_id
                                AND
                                    sub_ss.staff_id
                                IN (SELECT sub_sub_ss.staff_id FROM staff_service sub_sub_ss
                                WHERE 
                                    sub_sub_ss.staff_service_id IN (SELECT sd.staff_service_id FROM staff_schedule sd
                                    WHERE   TIME("08:00:00")+s.duration >= sd.start_time
                                    AND TIME("08:00:00") <= TIME(sd.end_time)
                                    AND sd.sched_date = date('2019-09-19'))))) staff_service_avail
            WHERE 
            staff_service_avail.staff_id=st.staff_id
            AND staff_service_avail.service_id=s.service_id
            AND bus.bus_id=1";*/
        //echo $query;

    // prepare query statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->bus_id);
    /*$stmt->bindParam(2, $selected_time);
    $stmt->bindParam(3, $selected_date);*/
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }
    // create product
    function createServiceById(){
 
    // query to insert record
       // INSERT INTO bus_service_price (bus_id,service_id,price) VALUES
//((SELECT bus_id FROM BUSINESS WHERE bus_id=1),(SELECT service_id from service where service_id=1),300 )
    $query = "INSERT INTO bus_service_price (bus_id,service_id,price) VALUES
            ((SELECT bus_id FROM BUSINESS WHERE bus_id=?),(SELECT service_id from service where service_id=?),? )";

    //echo $query;
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->bus_id=htmlspecialchars(strip_tags($this->bus_id));
    $this->service_id=htmlspecialchars(strip_tags($this->service_id));
    $this->price=htmlspecialchars(strip_tags($this->price));
    

 
    // bind values
   
    $stmt->bindParam(1, $this->bus_id);
    $stmt->bindParam(2, $this->service_id);
    $stmt->bindParam(3, $this->price);
    
 
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
                service_id,service_name,service_desc,duration
            FROM
                " . $this->table_name3 . " 
                
            WHERE
               service_name=:service_name 
            LIMIT
                0,1";

 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(':service_name', $this->service_name, PDO::PARAM_STR);
   // $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
    
   
   
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->service_id = $row['service_id'];
    $this->service_name = $row['service_name'];
    //$this->password = $row['password'];
    $this->service_desc = $row['service_desc'];
    $this->duration = $row['duration'];
    

    }

    // update the user
    function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                service_name = :service_name,
                service_desc = :service_desc,
                duration = :duration,
                

            WHERE
                service_id = :service_id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->service_name=htmlspecialchars(strip_tags($this->service_name));
    $this->service_desc=htmlspecialchars(strip_tags($this->service_desc));
    $this->duration=htmlspecialchars(strip_tags($this->duration));
   

 
    // bind values
   
    $stmt->bindParam(":service_name", $this->service_name);
    $stmt->bindParam(":service_desc", $this->service_desc);
    $stmt->bindParam(":duration", $this->duration);
    
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
    }

    // delete the product
    function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE service_id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->service_id=htmlspecialchars(strip_tags($this->service_id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->service_id);
 
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
                service_name LIKE ? OR service_desc LIKE ? ";
 
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

}

