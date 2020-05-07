<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    </head>
    <body>
        <form class = "Resubmit" action = "redirect.php" method="post">
                <h2> You have already submitted this form under this UMID, Would you like to resubmit? </h2>
                <input type = "radio" id = "resubmit" name = "resubmit" value = "yes"> Yes <br>
                <input type = "radio" id = "resubmit" name = "resubmit" value = "no"> No <br>
                <input type = "submit" name = "Confirm" value = "Confirm">
        </form>
    </body>
</html>
<?php
    require 'dbconfig.php';
    if(isset($_POST["Confirm"]))
    {
        $choice = $_POST["resubmit"];
        if($choice == "yes")
        {
            session_start();
            $umid = $_SESSION["UMID"];
            echo "<script type='text/javascript'> alert('RE-FILL CHOSEN! PREVIOUS FIELDS WILL BE ERASED!') </script>";
            $query2 = "select timeslot from registrants where UMID = '$umid'";
            $result2 = mysqli_query($con, $query2)->fetch_assoc();
            $query = "delete from registrants where UMID = '$umid'";
            $result = mysqli_query($con, $query);
            $query3 = "update timeslots set slotsAvailable = slotsAvailable + 1 where timeslots.id = '$result2";
            $result3 = mysqli_query($con, $query3);
            
            if(isset($_SESSION["UMID"]))
            {
                session_unset();
                session_destroy();;
            }
             header('Location: index.php');
            
        }
        else {
            echo "<script type='text/javascript'> alert('You chose to not re-fill the form! Re-directing to registrations!') </script>";
            if(isset($_SESSION["UMID"]))
            {
                session_unset();
                session_destroy();
            }
            header('Location: display.php');
        }
    }
?>