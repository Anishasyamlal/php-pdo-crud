<?php 
session_start();
class DB{ 
    private $dbHost     = "localhost"; 
    private $dbUsername = "root"; 
    private $dbPassword = "root"; 
    private $dbName     = "user_info"; 
    public function __construct() { 
        if(!isset($this->db)){ 
            // Connect to the database 
            try{ 
                $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword); 
                $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                $this->db = $conn; 
            }catch(PDOException $e) { 
                die("Failed to connect with MySQL: " . $e->getMessage()); 
            } 
        } 
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } 
    public function getRows($table,$conditions = array()) { 
        $sql = 'SELECT '; 
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*'; 
        $sql .= ' FROM '.$table; 
        if(array_key_exists("where",$conditions)) { 
            $sql .= ' WHERE '; 
            $i = 0; 
            foreach($conditions['where'] as $key => $value) { 
                $pre = ($i > 0)?' AND ':''; 
                $sql .= $pre.$key." = '".$value."'"; 
                $i++; 
            } 
        } 
        if(array_key_exists("order_by",$conditions)){ 
            $sql .= ' ORDER BY '.$conditions['order_by'];  
        } 
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)) { 
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit'];  
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)) { 
            $sql .= ' LIMIT '.$conditions['limit'];  
        } 
        $query = $this->db->prepare($sql); 
        $query->execute(); 
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all') { 
            switch($conditions['return_type']) { 
                case 'count': 
                    $data = $query->rowCount(); 
                    break; 
                case 'single': 
                    $data = $query->fetch(PDO::FETCH_ASSOC); 
                    break; 
                default: 
                    $data = ''; 
            } 
        }else { 
            if($query->rowCount() > 0) { 
                $data = $query->fetchAll(); 
            } 
        } 
        return !empty($data)?$data:false; 
    } 
    public function insert($table,$data) { 
        if(!empty($data) && is_array($data)) { 
            $columns = ''; 
            $values  = ''; 
            $i = 0; 
            $columnString = implode(',', array_keys($data)); 
            $valueString = ":".implode(',:', array_keys($data));
            $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")"; 
            $query = $this->db->prepare($sql); 
            foreach($data as $key=>$val) { 
                 $query->bindValue(':'.$key, $val); 
            } 
            $insert = $query->execute(); 
            return $insert?$this->db->lastInsertId():false; 
        }else { 
            return false; 
        } 
    } 
    public function update($table,$data,$conditions) { 
        if(!empty($data) && is_array($data)) { 
            $colvalSet = ''; 
            $whereSql = ''; 
            $i = 0; 
            foreach($data as $key=>$val) { 
                $pre = ($i > 0)?', ':''; 
                $colvalSet .= $pre.$key."='".$val."'"; 
                $i++; 
            } 
            if(!empty($conditions)&& is_array($conditions)) { 
                $whereSql .= ' WHERE '; 
                $i = 0; 
                foreach($conditions as $key => $value) { 
                    $pre = ($i > 0)?' AND ':''; 
                    $whereSql .= $pre.$key." = '".$value."'"; 
                    $i++; 
                } 
            } 
            $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql; 
            $query = $this->db->prepare($sql); 
            $update = $query->execute(); 
            return $update?$query->rowCount():false; 
        }else { 
            return false; 
        } 
    } 
    public function updateProfile($userData,$id) {
        $id=$_SESSION['id'];
        $name=$userData['name'];
        $dob=$userData['dob'];
        $email=$userData['email'];
        $photo=$userData['photo'];
        $address=$userData['address'];
        $sql="update users,user_details set users.name='$name',users.dob='$dob',users.email='$email',user_details.photo='$photo',user_details.address='$address' where users.id=$id and user_details.user_id=$id";
        $query = $this->db->prepare($sql); 
        $result=$query->execute();
        if($result) {
            return true;
        }else {
            return false;
        }
    }
    public function getData()
    {
       $id=$_SESSION['id'];
       $sql = "select users.id,users.name,users.dob,users.email,users.password,user_details.photo,user_details.address from users INNER JOIN user_details ON users.id = $id and user_details.user_id = $id";       
       try{
        $results = $this->db->prepare($sql);
        $results->execute();
        }catch (Exception $e) {
        echo "Error! " . $e->getMessage() . "<br/>";
        return false;
        }
        return $results->fetchAll();
    }
    public function getContact() {
        $id=$_SESSION['id'];
        $sql="select number from user_contact where user_id=$id";
        try{
        $query=$this->db->prepare($sql);
        $query->execute();
        }catch (Exception $e) {
        echo "Error! " . $e->getMessage() . "<br/>";
        return false;
        }
        return $query->fetchAll();
    }
    public function getQualification() {
        $id=$_SESSION['id'];
        $sql="select qualification from user_qualification where user_id=$id";
        try{
        $query=$this->db->prepare($sql);
        $query->execute();
        }catch (Exception $e) {
        echo "Error! " . $e->getMessage() . "<br/>";
        return false;
        }
        return $query->fetchAll();
    }
    public function updatePassword($userData,$id) {
        $id=$_SESSION['id'];
        $pswd=$userData['password'];
        $sql="update users set password='$pswd' where id='$id' ";
        $query = $this->db->prepare($sql); 
        $result=$query->execute();
        if($result) {
            return true;
        }else {
            return false;
        }
    }
    public function delete($table,$user_id) {
        $sql="DELETE FROM $table WHERE user_id=$user_id";
        $query=$this->db->prepare($sql);
        $result=$query->execute();
        if($result) {
            return true;
        }else {
            return false;
        }
    }
}

