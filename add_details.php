<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("location:login.php");
};
// Get data from session 
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:''; 
// Get status from session 
if(!empty($sessData['status']['msg'])) { 
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
    <title>Add Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style type="text/css">
    .input-group {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .navbar {
        background-color: deepskyblue;
    }
    .form {
        background-image:url('images/detail.jpg');
        color: white;
    }
    body {
        background-image: url('images/back.jpg'); 
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
                    <li><a href="dashboard.php" style="color:white ;">Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="margin-top: 30px;">
        <?php if(!empty($statusMsg)) { ?>
        <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
        <?php } ?>
        <div class="col-xs-6 col-xs-offset-3">
            <div class="panel panel-success">
                <div class="panel-body form">
                    <form name="demo-form" method="post" action="action.php" enctype='multipart/form-data' id="myForm">
                        <div class="textbox1">
                            <div class="input-group">
                                <input type="file" name="fileToUpload" id="photo"  class="form-control" required="" />
                            </div>
                        </div>
                        <div class="textbox2">
                            <div class="input-group">
                                <textarea name="address" class="form-control" id="address"  placeholder="Address" required=""></textarea>  
                            </div>
                        </div>
                        <div class="textbox-wrapper">
                            <div class="input-group">
                                <input type="text" name="number[]" id="number" class="form-control num " onkeyup="clrmsg('sp1')" placeholder="Contact Number" required=""/>
                                <span id="sp1" style="color:red"></span>
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-success add-textbox"><i class="glyphicon glyphicon-plus"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="textbox-wrapp">
                            <div class="input-group">
                                <input type="text" name="qualification[]" id="qualification" onkeyup="clrmsg('sp2')" class="form-control qualification" placeholder="Qualification" required="" />
                                <span id="sp2" style="color:red"></span>
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-success add-textbox1"><i class="glyphicon glyphicon-plus"></i></button>
                                </span>
                            </div>
                        </div>
                        <input type="hidden" name="action_type" value="adddetails"/>
                        <div class="form-group">
                            <input type="submit" name="submit" value="Submit Form"  class="btn btn-lg btn-block btn-success" onclick="return validate()"/>
                         </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var max = 3;
        var cnt = 1;
        $(".add-textbox").on("click", function(e) {
            e.preventDefault();
            if(cnt <= max) {
                cnt++;
                $(".textbox-wrapper").append('<div class="input-group"><input type="text" name="number[]" id="number" class="form-control num" /><span class="input-group-btn"><button type="button" class="btn btn-danger remove-textbox" value="Remove">Remove</button></span></div>');
            }
            else {
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
        $(".add-textbox1").on("click", function(e) {
            e.preventDefault();
            if(cnt <= max) {
                cnt++;
                $(".textbox-wrapp").append('<div class="input-group"><input type="text" name="qualification[]" id="qualification" class="form-control qualification" /><span class="input-group-btn"><button type="button" class="btn btn-danger remove-textbox1" value="Remove">Remove</button></span></div>');
            }else {
                alert('only 4 inputs are allowed');
            }
        });
        $(".textbox-wrapp").on("click",".remove-textbox1", function(e) {
            e.preventDefault();
            $(this).parents(".input-group").remove();
            cnt--;
        });
    });
    function validate() {
        var photo=document.getElementById('photo').value;
        var address=document.getElementById('address').value;
        var number=document.getElementById('number').value;
        var numformat=/^[0-9]{10}$/;
        var qualification=document.getElementById('qualification').value;
        if(photo == "") {
            alert("Please fill this field");
            document.demo-form.fileToUpload.focus();
            return false;
        }
        if(address == "") {
            alert("Please fill this field");
            document.demo-form.address.focus();
            return false;
        }
        if(number == "" || !number.match(numformat)) {
            document.getElementById('sp1').innerHTML="invalid format or field is empty";
            return false;
        }
        if(qualification == "") {
            document.getElementById('sp2').innerHTML="Please fill";
            return false;
        }
    }  
    function clrmsg(sp) {
            document.getElementById(sp).innerHTML='';
    }   
</script>
</body>
</html>