<?php
session_start();
if(!isset($_SESSION['id'])) {
  header("location:login.php");
}
// Get data from session 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
// Get status from session 
if(!empty($sessData['status']['msg'])){ 
  $statusMsg = $sessData['status']['msg']; 
  $status = $sessData['status']['type']; 
  unset($_SESSION['sessData']['status']); 
}  
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style type="text/css">
      .input-group {
        margin-top: 10px;
        margin-bottom: 10px;
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
      .error {
        color:red;
      }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Logo</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav " >
                    <li><a href="logout.php" style="color:white ;">Logout</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="margin-top: 30px;">
        <?php if(!empty($statusMsg)){ ?>
        <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
        <?php } ?>
        <div class="col-xs-6 col-xs-offset-3">
            <div class="panel panel-success">
                <div class="panel-body form">
                    <h1>Change Password</h1>
                    <form method="post" action="action.php" class="form">
                        <div class="form-group">
                            <label>Old Password</label><span class="error">*</span>
                            <input type="text" class="form-control" name="oldpswd" id="oldpswd" required="" onkeyup="clrmsg('sp1')">
                            <span id="sp1" style="color:red ">
                        </div>
                        <div class="form-group">
                            <label>New Password</label><span class="error">*</span>
                            <input type="password" class="form-control" name="newpswd" id="newpswd"  required="" onkeyup="clrmsg('sp2')">
                            <span id="sp2" style="color:red ">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label><span class="error">*</span>
                            <input type="password" class="form-control" name="confirmpswd" id="confirmpswd"  required="" onkeyup="clrmsg('sp3')">
                            <span id="sp3" style="color:red ">
                        </div>
                        <div class="form-group pt-3">
                            <input type="hidden" name="action_type" value="changepassword"/>
                            <input type="submit" class="form-control btn-primary" name="submit" value="Save" onclick="return validate()"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
  function validate() {
    var oldpswd=document.getElementById('oldpswd').value;
    var newpswd=document.getElementById('newpswd').value;
    var confirmpswd=document.getElementById('confirmpswd').value;
    var pswdformat = /^[a-zA-Z0-9!@#$%^&*]{8,16}$/;
    if(oldpswd == "") {
        document.getElementById('sp1').innerHTML="Please fill this field";
    }
    if(!newpswd.match(pswdformat)) {
        document.getElementById('sp2').innerHTML="Invalid password ";
    }
    if(newpswd == "") {
        document.getElementById('sp2').innerHTML="Please fill this field";
    }
    if(confirmpswd != newpswd) {
        document.getElementById('sp3').innerHTML="Password Not Match";
    }
    if(confirmpswd == "") {
        document.getElementById('sp3').innerHTML="Please fill this field";
    }
    }
    function clrmsg(sp) {
            document.getElementById(sp).innerHTML='';
    }
</script>
</body>
</html>