<?php
//CHANGE THIS FOR DEPLOYING TO AWS
$con = mysqli_connect("us-cdbr-east-02.cleardb.com", "bb5d95ebd69f95", "3ae0b3ce") or die ("Unable to connect to the database");
mysqli_select_db($con, "heroku_652a56754b50c35");
?>