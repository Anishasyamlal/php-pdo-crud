<?php
session_start(); 
// Get data from session 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
// Get status from session 
if(!empty($sessData['status']['msg'])){ 
    $statusMsg = $sessData['status']['msg']; 
    $status = $sessData['status']['type']; 
    unset($_SESSION['sessData']['status']); 
}  
?>
<html>
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
        .error{
            color:red;
        }
        h1{
            color:black;
        }
    </style>
</head>
<body class="main">
    <div class="row">
        <?php if(!empty($statusMsg)){ ?>
            <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
        <?php } ?>
        <div class="col-md-5 mx-auto pb-3">
            <div class="col-md-5 text-center text-dark">
                <h1>Signup</h1>
            </div>
            <form method="post" action="action.php" class="form" >
                <div class="form-group">
                    <label>Name</label><span class="error">*</span>
                    <input type="text" class="form-control" name="name" id="name" onkeyup="clrmsg('sp1')" value="<?php echo !empty($postData['name'])?$postData['name']:''; ?>" required="">
                    <span id="sp1" style="color:red"></span>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label><span class="error">*</span>
                    <input type="date" class="form-control" name="dob" id="dob" onclick="clrmsg('sp2')" value="<?php echo !empty($postData['dob'])?$postData['dob']:''; ?>" required="">
                    <span id="sp2" style="color:red"></span>
                </div>
                <div class="form-group">
                    <label>Email</label><span class="error">*</span>
                    <input type="email" class="form-control" name="email" id="email" onkeyup="clrmsg('sp3')" value="<?php echo !empty($postData['email'])?$postData['email']:''; ?>" required="">
                    <span id="sp3" style="color:red"></span>
                </div>
                <div class="form-group">
                    <label>Password</label><span class="error">*</span>
                    <input type="password" class="form-control" name="pswd" id="pswd" onkeyup="clrmsg('sp4')" value="<?php echo !empty($postData['password'])?$postData['password']:''; ?>" required="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                    <span id="sp4" style="color:red"></span>
                </div>
                <div class="form-group pt-3">
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="submit" class="form-control btn-primary" name="submit" value="Signup" onclick="return validate()"/>
                </div>
            </form>
            <div class="col-md-12">
                <p class="text-center">
                    Already have an account?
                    <a href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </div>
<script>
    function validate() {
        var name=document.getElementById('name').value;
        var dob=document.getElementById('dob').value;
        var email=document.getElementById('email').value;
        var pswd=document.getElementById('pswd').value;
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var pswdformat = /^[a-zA-Z0-9!@#$%^&*]{8,16}$/;
        if(name == "") {
            document.getElementById('sp1').innerHTML="Name can not be blank";
        }
        if(dob == "") {
            document.getElementById('sp2').innerHTML="Please fill this field";
        }
        if(!email.match(mailformat) || email == "") {
            document.getElementById('sp3').innerHTML="Invalid email or the field is empty!";
        }
        if(!pswd.match(pswdformat) || pswd == "") {
            document.getElementById('sp4').innerHTML="Invalid password or the field is empty!";
        }
    }
    function clrmsg(sp) {
            document.getElementById(sp).innerHTML='';
    }
</script>
</body>
</html>