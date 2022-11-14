<?php 
// Start session 
session_start(); 
// Include and initialize DB class 
require_once 'Class/DB.php'; 
$db = new DB(); 
// Database table name 
$tblName = 'users';  
$postData = $statusMsg = $valErr = ''; 
$status = 'danger'; 
$redirectURL = './view.php'; 
$action=$_REQUEST['action_type'];
switch($action) {
    case "login":
        $redirectURL='./dashboard.php';
        if(isset($_POST['submit']))
        {
            // Getting username/ email and password
            $uname = $_POST['email'];
            $password =md5($_POST['pswd']);
            // Fetch data from database on the basis of username/email and password
            $conditons = [ 
            'where' => [
           'email' => $uname,
           'password' => $password
            ], 
            'return_type' => 'single' 
            ]; 
            $userData = $db->getRows('users', $conditons); 
            if($userData)
            {
                if(!empty($_POST["remember"])) {
                    setcookie ("username",$_POST["email"],time()+ 3600);
                    setcookie ("password",$_POST["pswd"],time()+ 3600);
                    echo "Cookies Set Successfuly";
                } 
                $key = key($userData);
                $value = $userData[$key];
                $_SESSION['id']=$value;
                $_SESSION['username']=$_POST['email']; 
                $_SESSION['password']=$_POST['pswd'];
                $condition= [
                'where' => [
                'user_id' => $_SESSION['id'],
                 ],
                'return_type' => 'single'
                 ];
                $details=$db->getRows('user_details',$condition);
                if(!empty($details)){
                    $status="login successfull";
                    header("Location:./view.php ");
                    exit;
                }else {
                    $status="login successfull";
                    header("location:./add_details.php");
                    exit;
                }
            }else {
                    echo ("<script LANGUAGE='JavaScript'>
                            window.alert('invalid user');
                            window.location.href='./login.php';
                            </script>");
            } 
        }
        break;
    case "add" :
        $redirectURL = './add_details.php'; 
        // Get user's input 
        $postData = $_POST; 
        $name = !empty($_POST['name'])?trim($_POST['name']):''; 
        $dob = !empty($_POST['dob'])?trim($_POST['dob']):''; 
        $email = !empty($_POST['email'])?trim($_POST['email']):''; 
        $pswd = !empty($_POST['pswd'])?md5(trim($_POST['pswd'])):''; 
        $count=0;
        $conditions=[
            'where' =>[
            'email' =>$email,
            ],
        'return_type' =>'count',
         ];
        $count=$db->getRows('users',$conditions);
        if($count>0) {
            echo ("<script LANGUAGE='JavaScript'>
                    window.alert('email already exist');
                    window.location.href='./index.php';
                  </script>");
                  exit;
        }
        if(empty($name)){ 
            $valErr .= 'Please enter your name.<br/>'; 
            
        }
        if(empty($dob)){ 
            $valErr .= 'Please enter your date of birth<br/>'; 
           
        }
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
            $valErr .= 'Please enter a valid email.<br/>'; 
            
        }
        if(empty($pswd)){ 
            $valErr .= 'Please enter password<br/>'; 

        }
        if(empty($valErr)) { 
            // Insert data into the database 
            $userData = [
                'name' => $name, 
                'dob' => $dob,
                'email' => $email, 
                'password' => $pswd, 
            ]; 
            $insertId = $db->insert($tblName, $userData); 
            if($insertId) { 
                $key = $insertId;
                $_SESSION['id']=$key;
                $_SESSION['email']=$_POST['email'];
                $_SESSION['password']=$_POST['pswd'];
                $status = 'success'; 
                $statusMsg = 'User data has been added successfully!'; 
                $postData = ''; 
                header("location:./add_details.php");
            }else { 
                $statusMsg = 'Something went wrong, please try again after some time.'; 
            } 
        }else { 
            $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>');
            
        }  
        // Store status into the SESSION 
    $sessData['postData'] = $postData; 
    $sessData['status']['type'] = $status; 
    $sessData['status']['msg'] = $statusMsg; 
    $_SESSION['sessData'] = $sessData; 
    header("location:$redirectURL");
        break;
    case "edit" :
        // Get user's input 
        $postData = $_POST; 
        $id=$_SESSION['id'];
        $name = !empty($_POST['name'])?trim($_POST['name']):''; 
        $dob = !empty($_POST['dob'])?trim($_POST['dob']):''; 
        $email = !empty($_POST['email'])?trim($_POST['email']):''; 
        $file = $_FILES["fileToUpload"]["name"];
        if(!empty($file)) {
        $m="./uploads/".$_FILES["fileToUpload"]["name"];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($m,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            header("location:./edit.php");
            exit;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            
        }else {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$m);
        }
        }
        $address = !empty($_POST['address'])?trim($_POST['address']):'';
        $number = $_POST['number'];
        $qualification = $_POST['qualification'];
        // Validate form fields 
        if(empty($name)){ 
            $valErr .= 'Please enter your name.<br/>'; 
        } 
        if(empty($dob)){ 
            $valErr .= 'Please enter date of birth<br/>'; 
        } 
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){ 
            $valErr .= 'Please enter a valid email.<br/>'; 
        } 
        if(empty($address)){ 
            $valErr .= 'Please enter your address<br/>'; 
        } 
        if(empty($number)) { 
            $valErr .= 'Please enter contact number.<br/>'; 
        } 
        if(empty($qualification)){ 
            $valErr .= 'Please enter your qualification<br/>'; 
        }  
        // Check whether user inputs are empty 
        if(empty($valErr)){ 
            if(empty($file)) {
                $result=$db->getData();
                foreach($result as $row) {
                    $photo=$row['photo'];
                }
        // Update data in the database 
            $userData = [
                'name' => $name, 
                'dob' => $dob, 
                'email' => $email,  
                'photo' => $photo, 
                'address' => $address, 
            ]; 
        }else{
            $userData = [
                'name' => $name, 
                'dob' => $dob, 
                'email' => $email,  
                'photo' => $file, 
                'address' => $address, 
            ]; 
        }
            $update = $db->updateProfile($userData,$id); 
            $user_id=$_SESSION['id'];
            $table='user_contact';
            $db->delete($table,$user_id);
            $number = $_POST['number'];
            foreach($number as $value) {
                $tableName = 'user_contact';
                $userDataPhoneNumber = [ 
                    'user_id' => $user_id, 
                    'number' => $value,
                ]; 
                $add_number=$db->insert($tableName,$userDataPhoneNumber);
            }
            $user_id=$_SESSION['id'];
            $table='user_qualification';
            $db->delete($table,$user_id);
             $qualification = $_POST['qualification']; 
             foreach($qualification as $value) {
                 $tableName='user_qualification';
                 $userDataQualification = [
                     'user_id' =>$user_id,
                     'qualification' =>$value,
                 ];
                 $add_qualification=$db->insert($tableName,$userDataQualification);
             } 
            if($update ) { 
                echo ("<script LANGUAGE='JavaScript'>
                        window.alert('User data has been updated successfully!');
                        window.location.href='./view.php';
                       </script>");
            }else { 
            $statusMsg = 'Something went wrong, please try again after some time.'; 
            }
        }else { 
            $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>'); 
        } 
        // Store status into the SESSION 
        $sessData['postData'] = $postData; 
        $sessData['status']['type'] = $status; 
        $sessData['status']['msg'] = $statusMsg; 
        $_SESSION['sessData'] = $sessData; 
        break;
    case "adddetails" :
        $postData = $_POST; 
        $user_id=$_SESSION['id'];
        $file = $_FILES["fileToUpload"]["name"];
        $m="./uploads/".$_FILES["fileToUpload"]["name"];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($m,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
            header("location:./add_details.php");
            exit;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        }else {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$m);
        }
        $address = !empty($_POST['address'])?trim($_POST['address']):''; 
        $number = $_POST['number'];
        foreach($number as $value) {
            $tableName = 'user_contact';
            $userDataPhoneNumber = array( 
                'user_id' => $user_id, 
                'number' => $value,
            ); 
            $add_number=$db->insert($tableName,$userDataPhoneNumber);
        }
        $qualification = $_POST['qualification']; 
        foreach($qualification as $value) {
            $tableName='user_qualification';
            $userDataQualification = array(
                'user_id' =>$_SESSION['id'],
                'qualification' =>$value,
            );
            $add_qualification=$db->insert($tableName,$userDataQualification);
        }
        // Validate form fields 
        if(empty($address)){ 
            $valErr .= 'Please enter your address<br/>'; 
        } 
        if(empty($number)) { 
            $valErr .= 'Please enter contact number.<br/>'; 
        } 
        if(empty($qualification)) { 
            $valErr .= 'Please enter your qualification<br/>'; 
        } 
        // Check whether user inputs are empty 
        if(empty($valErr)) { 
        // Insert data into the database 
            $userData = [
                'user_id' => $user_id, 
                'photo' => $file, 
                'address' => $address, 
            ]; 
            $tblName='user_details';
            $add_details = $db->insert($tblName, $userData); 
            if($add_details) { 
                echo ("<script LANGUAGE='JavaScript'>
                        window.alert('User data has been added successfully!');
                        window.location.href='./view.php';
                       </script>");
            }else { 
                $statusMsg = 'Something went wrong, please try again after some time.'; 
            } 
        }else { 
            $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>'); 
        } 
        // Store status into the SESSION 
        $sessData['postData'] = $postData; 
        $sessData['status']['type'] = $status; 
        $sessData['status']['msg'] = $statusMsg; 
        $_SESSION['sessData'] = $sessData;
        break;
    case "changepassword" : 
        $postData = $_POST;
        $id=$_SESSION['id'];
        $oldpswd=md5($_POST['oldpswd']);
        $newpswd=md5($_POST['newpswd']);
        $confirmpswd=md5($_POST['confirmpswd']);
        $condition= [
            'where' => [
                'password' =>$oldpswd,
            ],
            'return-type' =>'single',
        ];
        $result=$db->getRows($tblName,$condition);
        if($result == false) {
            echo ("<script LANGUAGE='JavaScript'>
                    window.alert('Mismatch old password!');
                    window.location.href='./change_password.php';
                    </script>");
            exit;
        }
        // Validate form fields 
        if(empty($oldpswd)) { 
            $valErr .= 'Please enter password<br/>'; 
        } 
        if(empty($newpswd)) { 
            $valErr .= 'Please enter password<br/>'; 
        } 
        if(empty($confirmpswd)) { 
            $valErr .= 'Please enter password<br/>'; 
        } 
        if ($confirmpswd != $newpswd) {
            echo ("<script LANGUAGE='JavaScript'>
                        window.alert('Password not match');
                        window.location.href='./change_password.php';
                        </script>");
                        exit;
        }
        // Check whether user inputs are empty 
        if(empty($valErr)) { 
            // Update data in the database 
            $userData = [
                'password' => $confirmpswd,
            ]; 
            $change = $db->updatePassword($userData,$id);
            if($change){ 
                echo ("<script LANGUAGE='JavaScript'>
                        window.alert('Password Updated Successfully');
                        window.location.href='./view.php';
                        </script>");
            }else { 
                $statusMsg = 'Something went wrong, please try again after some time.'; 
            } 
        }else { 
                $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>'); 
        } 
          // Store status into the SESSION 
        $sessData['postData'] = $postData; 
        $sessData['status']['type'] = $status; 
        $sessData['status']['msg'] = $statusMsg; 
        $_SESSION['sessData'] = $sessData; 
        break;
    default: 
        header("location:$redirectURL");
        exit;
}

