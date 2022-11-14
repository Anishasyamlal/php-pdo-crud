<?php 
// Start session 
session_start(); 
if(!isset($_SESSION['id'])) {
  header("location:login.php");
}
// Include and initialize DB class 
require_once __DIR__.'/Class/DB.php'; 
$db = new DB(); 
$users = $db->getData(); 
$user_contact=$db->getContact();
$user_qualification=$db->getQualification();
?>
<html>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
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
      .table-content{
        margin-left: 300px;
      }
      .row.content {
        height: 450px
      }
      @media screen and (max-width: 767px) {
      .row.content {height:auto;} 
      }
      #img {
        border-radius: 50%;
        margin: 10px;
        display: block;
      }
      table:after {
        opacity: 1;
      }
      body {
        background-image: url('images/backgroundmain.jpg');
        background-repeat:no-repeat;
        background-size:cover;
      }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark ">
    <div class="container-fluid">
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a class="px-3 text-white" href="change_password.php">Change Password</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a class="px-3 text-white" href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>  
  <div class="container-fluid text-center">    
    <div class="row content tab">
      <div class="col-sm-10 text-center"> 
      <!-- Status message -->
        <?php if(!empty($statusMsg)){ ?>
          <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?>
          </div>
        <?php } ?>
        <h1 class="text-dark">View Profile</h1>
        <!-- List the users -->
        <table class="  w-50 table-content">
          <thead class="thead-dark">
            <?php if(!empty($users)){  foreach($users as $row) { ?> 
            <tr>  <th width="18%" colspan="2" ><img src="uploads/<?php echo $row['photo']; ?>" id="img"  height=100 /></th></tr>  
            <tr>  <th width="5%">#</th><td><?php echo $row['id']; ?></td></tr>
            <tr>  <th width="20%">Name</th><td><?php echo $row['name']; ?></td></tr>
            <tr>  <th width="18%">Date Of Birth</th> <td><?php echo $row['dob']; ?></td></tr>
            <tr>  <th width="25%">Email</th> <td><?php echo $row['email']; ?></td></tr>
            <tr>  <th width="18%">Address</th> <td><?php echo $row['address']; ?></td></tr>
            <tr>  <th width="18%">Contact Number</th> <td><?php foreach($user_contact as $row) { echo $row['number'].' '; }?></td></tr>
            <tr>  <th width="18%">Qualification</th> <td><?php foreach($user_qualification as $row) { echo $row['qualification'].' ';} ?></td></tr>
            <tr>  <th width="14%">Action</th><td>
                  <a href="edit.php?id=<?php foreach($users as $row){ echo $row['id']; }?>" class="btn btn-success">edit</a>
                  </td>
            </tr>
          </thead>
            <?php } }else{ ?>
            <tr><td colspan="5">No data found...</td></tr>
            <?php }  ?>
        </table>
      </div>
    </div> 
  </div>
</body>
</html>