<?php 
// Start session 
session_start(); 
if(!isset($_SESSION['id'])) {
    header("location:login.php");
  }
// Include and initialize DB class 
require_once __DIR__.'/Class/DB.php';
$db = new DB(); 
// Fetch the user data by ID 
if(!empty($_SESSION['id'])){ 
    $userData = $db->getData();
    $user_contact=$db->getContact();
    $user_qualification=$db->getQualification();
}  
// Redirect to list page if invalid request submitted 
if(empty($userData)){ 
    header("Location: index.php"); 
    exit; 
} 
// Get submitted form data  
$postData = array(); 
if(!empty($sessData['postData'])){ 
    $postData = $sessData['postData']; 
    unset($_SESSION['postData']); 
}   
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
      background-color:deepskyblue;
    }
    .row.content {height: 450px}
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
    body {
        background-image: url('images/backgroundmain.jpg');
        background-repeat:no-repeat;
        background-size:cover;
    }
    .form {
        background-image:url('images/form.jpg');
        color: white;
        opacity:0.8;
    }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark ">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li class="active  text-white px-3"><a href="dashboard.php">Home</a></li>
            <li class=" px-3"><a class="text-white" href="change_password.php">Change Password</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a class="text-white px-3" href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Status message -->
    <?php if(!empty($statusMsg)){ ?>
      <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
    <?php } ?>
    <div class="col-md-5 mx-auto pb-3 form">  
      <form method="post" action="action.php" class="form" enctype="multipart/form-data">
        <?php if(!empty($userData)){  foreach($userData as $row){ ?>   
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" id="name" onkeyup="clrmsg('sp1')" value="<?php echo !empty($postData['name'])?$postData['name']:$row['name']?>" required="">
            <span id="sp1" style="color:red"></span>
          </div>
          <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" class="form-control" name="dob" id="dob" onkeyup="clrmsg('sp2')" value="<?php echo!empty($postData['dob'])?$postData['dob']: $row['dob']; ?>" required="">
            <span id="sp2" style="color:red"></span>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" id="email" onkeyup="clrmsg('sp3')" value="<?php echo !empty($postData['email'])?$postData['email']:$row['email']; ?>" required="">
            <span id="sp3" style="color:red"></span>
          </div>
          <div class="form-group">
            <label>Photo</label>
            <input type="file" name="fileToUpload" class="form-control" id="file" onclick="clrmsg('sp4')" value="<?php echo !empty($postData['fileToUpload'])?$postData['fileToUpload']:$row['photo']; ?>" />
            <span id="sp4" style="color:red"></span>
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" placeholder="Address" onkeyup="clrmsg('sp5')" id="address" required=""><?php echo !empty($postData['address'])?$postData['address']:$row['address']; ?></textarea>
            <span id="sp5" style="color:red"></span>
          </div>
          <?php } } ?>
          <div class="form-group textbox-wrapper" id="textbox">
            <label>Contact Number</label>
            <?php foreach($user_contact as $row) { ?>
            <div class="input">
              <input type="text" name="number[]" class="form-control" placeholder="Contact Number" id="number" onkeyup="clrmsg('sp6')" value="<?php echo !empty($postData['number'])?$postData['number']:$row['number'].'  '; ?>" />
              <button type="button" class="btn btn-danger remove1" value="Remove">Remove</button>  
            </div>
            <?php } ?>
            <span id="sp6" style="color:red"></span>
            <button type="button" class="btn btn-success add-textbox">Add More</i></button>
          </div>
          <div class="form-group textbox-wrapp" id="textbox">
            <label>Qualification</label>
            <?php foreach($user_qualification as $row) { ?>
            <div class="input">
              <input type="text" name="qualification[]" class="form-control" placeholder="Qualification" id="qualification" onkeyup="clrmsg('sp7')" value="<?php echo !empty($postData['qualification'])?$postData['qualification']:$row['qualification'].'  '; ?>" />
              <button type="button" class="btn btn-danger remove" value="Remove">Remove</button>
            </div>
            <?php } ?>
            <span id="sp7" style="color:red"></span>
            <button type="button" class="btn btn-success add-textbox1">Add More</button>
          </div>
          <input type="hidden" name="id" value="<?php foreach($userData as $row) { echo $row['id']; }?>"/>
          <input type="hidden" name="action_type" value="edit"/>
          <input type="submit" class="form-control btn btn-primary" name="submit" value="Update User" onclick="return validate()"/>
      </form>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var max = 3;
        var cnt = 1;
        $(".add-textbox").on("click", function(e){
            e.preventDefault();
            if(cnt <= max){
                cnt++;
                $(".textbox-wrapper").append('<div class="input-group"><input type="text" name="number[]" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-danger remove-textbox" value="Remove">Remove</button></span></div>');
            }
            else{
                alert('only 3 inputs are allowed');
            }
        });
        $(".textbox-wrapper").on("click",".remove-textbox", function(e){
            e.preventDefault();
            $(this).parents(".input-group").remove();
            cnt--;
        });
    });
    $(document).ready(function() {
        var max = 4;
        var cnt = 1;
        $(".add-textbox1").on("click", function(e){
            e.preventDefault();
            if(cnt <= max){
                cnt++;
                $(".textbox-wrapp").append('<div class="input-group"><input type="text" name="qualification[]" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-danger remove-textbox1" value="Remove">Remove</button></span></div>');
            }
            else {
                alert('only 4 inputs are allowed');
            }
        });
        $(".textbox-wrapp").on("click",".remove-textbox1", function(e){
            e.preventDefault();
            $(this).parents(".input-group").remove();
            cnt--;
        });
    });
    $(document).ready(function() {
      $(".textbox-wrapp").on("click",".remove", function(e){
            e.preventDefault();
            $(this).parents(".input").remove();
            cnt--;
        });
    });
    $(document).ready(function() {
      $(".textbox-wrapper").on("click",".remove1", function(e){
            e.preventDefault();
            $(this).parents(".input").remove();
            cnt--;
        });
    });
  function validate() {
    var name=document.getElementById('name').value;
        var dob=document.getElementById('dob').value;
        var email=document.getElementById('email').value;
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var address=document.getElementById('address').value;
        if(name == "") {
            document.getElementById('sp1').innerHTML="Name can not be blank";
        }
        if(dob == "") {
            document.getElementById('sp2').innerHTML="Please fill this field";
        }
        if(!email.match(mailformat) || email == "") {
            document.getElementById('sp3').innerHTML="Invalid email or the field is empty!";
        }
        if(address == "") {
            document.getElementById('sp5').innerHTML="Please fill this field";
        }
    } 
    function clrmsg(sp) {
            document.getElementById(sp).innerHTML='';
    }

   

</script>

</body>
</html>
