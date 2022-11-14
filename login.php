<?php
session_start();
if(isset($_SESSION['id'])) {
    require_once 'Class/DB.php'; 
    $db = new DB(); 
    $conditions=[
        'where' =>[
            'user_id' =>$_SESSION['id'],
        ],
        'return_type' => 'single'
    ];
   $details=$db->getRows('user_details',$conditions);
   if(empty($details)) {
       header("Location:./add_details.php ");
       exit;
   }else {
       header("location:./view.php");
       exit;
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .error{
            color:red;
        }
    </style>
</head>
<body>
    <div class="bg-img">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mx-auto">
                    <?php if(!empty($statusMsg)){ ?>
                <div class=" pb-5 alert alert-<?php echo $status; ?>" id="alert1">
                    <label class="close" title="close" for="alert1">
                    <i class="icon-remove"></i>
                    </label>
                    <?php echo $statusMsg; ?>
                </div>
                    <?php } ?>
                <div class="myform form">
                    <div class="logo mb-3">
                        <div class="col-md-12 text-center">
                            <h1>Login</h1>
                        </div>
                    </div>
                    <form action="action.php" name="login" method="POST">
                        <div class="form-group">
                            <label for="inputEmail">Email Address</label><span class="error">*</span>
                            <input type="email" class="form-control" name="email" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>"  id="email" onkeyup="clrmsg('sp1')" placeholder="Input email" required>
                            <span id="sp1" style="color:red ">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Password</label><span class="error">*</span>
                            <input type="password" class="form-control" name="pswd" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>"  id="pswd" onkeyup="clrmsg('sp2')" placeholder="Input password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                            <span id="sp2" style="color:red ;">
                        </div>
                        <div class="form-group">
                            <p class="text-center">By signing up you accept our<a href="#">Terms & policy</a></p>
                        </div>
                        <input type="hidden" name="action_type" value="login"/>
                        <div class="col-md-12 text-center">
                            <input type="submit" class="btn btn-primary btn-block mybtn" name="submit" value="Login" onclick="return validate()"/>
                        </div>
                    </form>
                    <div class="col-md-12">
                        <p class="text-center">
                            Don't have an account?
                            <a href="index.php">sign up here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function validate() {
        var email=document.getElementById('email').value;
        var pswd=document.getElementById('pswd').value;
        if(email == "") {
            document.getElementById('sp1').innerHTML="Please fill this field";
        }
        if(pswd == "") {
            document.getElementById('sp2').innerHTML="Please fill this field";
        }
    }
    function clrmsg(sp) {
            document.getElementById(sp).innerHTML='';
    }
</script>
</body>
</html>