<?php
//CHANGE THIS FOR DEPLOYING TO AWS
$con = mysqli_connect("127.0.0.1:3360", "root", "") or die ("Unable to connect to the database");
mysqli_select_db($con, "pregistration");
?>