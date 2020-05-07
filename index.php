<?php
    require 'dbconfig.php';
?>
<!Doctype HTML>
<html>
    <head>
        <link rel="stylesheet" href="index.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <title>Presentation Registration</title>
        <h1> Register for Presentation time </h1>
        <nav>
            <ul>
                <li><a href = "index.php">Sign Up</a></li>
                <li><a href = "display.php">Registrants</a></li>
            </ul>
        </nav>
    </head>
    <body>
        <form class = "register" action = "index.php" method = "post">
            <h2> <img src = "umlogo.jpg" height = "20" width = "20" alt="umdlogo"> Please enter a value for each field </h1>
            <label for = "UMID"> UMID: </label>
                <input type = "text" name = "UMID" id = "umid" value = "<?php if(isset($_POST["UMID"])) {echo $_POST["UMID"];} else { echo " ";}?>"> <span class = "umidErr"> * </span><br>
            <label for = "fname"> First Name: </label>
                <input type = "text" name = "fname" id = "fname" value = "<?php if(isset($_POST["fname"])) {echo $_POST["fname"];} else { echo " ";}?>"> <span class = "fnameErr"> * </span> <br>
            <label for = "lname"> Last Name: </label>
                <input type = "text" name = "lname" id = "lname" value = "<?php if(isset($_POST["lname"])) {echo $_POST["lname"];}else { echo " ";} ?>"><span class = "lnameErr"> * </span><br>
            <label for = "ptitle"> Project Title: </label>
                <input type = "text" name = "ptitle" id = "ptitle" value = "<?php if(isset($_POST["ptitle"])) {echo $_POST["ptitle"];}else { echo "";}?>"><span class = "ptitleErr"> * </span><br>
            <label for = "email"> Email: </label>
                <input type = "text" name = "email" id = "email" value = "<?php if(isset($_POST["email"])) {echo $_POST["email"];} else { echo " ";}?>"> <span class = "emailErr"> * </span><br>
            <label for = "phone"> Phone: </label>
                <input type = "text" name = "phone" id = "phone" value = "<?php if(isset($_POST["phone"])) {echo $_POST["phone"];} else { echo " ";}?>"> <span class = "phoneErr"> * </span><br><br>
           <label for = "ptime"> Presentation Time: </label> <span class = "scheduleErr"> * </span><br>
                <input type = "radio" id = "radio1" name = "schedule" value = "1"> 12/9/19, 6:00 PM – 7:00 PM <span class = "r1val"> * </span> spots left <br>
                <input type = "radio" id = "radio2" name = "schedule" value = "2">  12/9/19, 7:00 PM – 8:00 PM <span class = "r2val"> * </span>  spots left <br>
                <input type = "radio" id = "radio3" name = "schedule" value = "3">  12/9/19, 8:00 PM – 9:00 PM <span class = "r3val"> * </span>  spots left <br>
                <input type = "radio" id = "radio4" name = "schedule" value = "4"> 12/10/19, 6:00 PM – 7:00 PM <span class = "r4val"> * </span> spots left <br>
                <input type = "radio" id = "radio5" name = "schedule" value = "5"> 12/10/19, 7:00 PM – 8:00 PM <span class = "r5val"> * </span> spots left <br>
                <input type = "radio" id = "radio6" name = "schedule" value = "6"> 12/10/19, 8:00 PM – 9:00 PM <span class = "r6val"> * </span> spots left <br>
            <input type = "submit" name = "submit" value = "Sign Up">
        </form>
<?php
    ob_start();
    $umidError = $fnameErr = $lnameErr = $ptitleErr = $emailErr = $phoneErr = $scheduleErr = " ";
    checkRows($con, 1, "radio1", "r1val");
    checkRows($con, 2, "radio2", "r2val");
    checkRows($con, 3, "radio3", "r3val");
    checkRows($con, 4, "radio4", "r4val");
    checkRows($con, 5, "radio5", "r5val");
    checkRows($con, 6, "radio6", "r6val");
    if(isset($_POST["submit"]))
    {
        $checkUMID = validateUMID($_POST["UMID"]);
        $checkFname =  validateFname($_POST["fname"]);
        $checkLname = validateLname($_POST["lname"]);
        $checkEmail = emailValidate($_POST["email"]);
        $checkPhone = phoneValidation($_POST["phone"]);
        $checkPtitle = projectTitleValidation($_POST["ptitle"]);
        $schedule = "";
        if(isset($_POST["schedule"]))
        {
            $schedule = $_POST["schedule"];
        }
        $checkPschedule = projectScheduleValidation($schedule);

        if($checkUMID && $checkFname && $checkLname && $checkEmail && $checkPhone && $checkPhone && $checkPtitle && $checkPschedule)
        {
            $umid = $_POST["UMID"];
            $fname = $_POST["fname"];
            $lname = $_POST["lname"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $ptitle = $_POST["ptitle"];
            $schedule = $_POST["schedule"];

            $query = "select * from registrants where UMID = '$umid'";
            $result = mysqli_query($con, $query);
            if(mysqli_num_rows($result) > 0)
            {
                session_start();
                $_SESSION["UMID"] = $_POST["UMID"];
                header('Location: redirect.php');
            }
            else 
            {
                session_unset();
                session_destroy();
                $query = "insert into registrants values('$umid', '$fname', '$lname', '$email', '$phone', '$ptitle', '$schedule');";
                $result = mysqli_query($con, $query);
                $query2 = "update timeslots set slotsAvailable = slotsAvailable - 1 where id = '$schedule';";
                $result2 = mysqli_query($con, $query2);
                if($result && $result2)
                {
                    echo "<script type = 'text/javascript'> alert ('Successfully registered!'); </script>";
                }
                else{
                    echo "<script type = 'text/javascript'> alert ('Failed to register!'); </script>";
                }
                ?>
                <script type="text/javascript">
                window.location.href = 'display.php';
                </script>
                <?php
            }
        } 
    }
    function checkRows($con, $id, $id2, $className)
    {
        $query = "select slotsavailable from timeslots where id = '$id'";
        $result = mysqli_query($con, $query)->fetch_assoc();
        $result = $result['slotsavailable'];
        if($result <= 0)
        {
            echo "<script type = 'text/javascript'> document.getElementById('$id2').disabled=true </script>";
        }
        else
        {
            echo "<script type = 'text/javascript'> document.getElementsByClassName('$className')[0].innerHTML = '$result'; </script>";
        }
    }
    function validateUMID ($data)
    {
        $umidError = "";
        $validated = false;
        if(empty($data))
        {
            $umidError = "UMID cannot be blank!";
            echo "HEREEE";
        }
        else{
            $umid = test_input($data);
            if(strlen($umid) !== 8)
            {
                $umidError = "UMID must be exactly 8 digits";
                
            }
            else{
                if(!is_numeric($umid)) {
                    $umidError = "UMID must be numeric";
                } else {
                    $validated = true;
                }
            }
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('umidErr')[0].innerHTML = '$umidError'; </script>";
        return $validated;
    }
    function validateFname($data)
    {
        $validated = false;
        $fnameErr = "";
        echo $fnameErr;
        if(empty($data))
        {
            $fnameErr = "First name is required!";
        }
        else {
            $fname = test_input($data);
            if(!ctype_alpha($fname))
            {
                $fnameErr = "Firstname can only contain alphabetic characters!";
            }
            else{
                $validated = true;
            }
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('fnameErr')[0].innerHTML = '$fnameErr'; </script>";
        return $validated;
    }
    function validateLname($data)
    {
        $validated = false;
        $lnameErr = "";
        if(empty($data))
        {
            $lnameErr = "Lname name is required!";
        }
        else {
            $lname = test_input($data);
            if(!ctype_alpha($lname))
            {
                $lnameErr = "Lastname can only contain alphabetic characters!";
            }
            else{
                $validated = true;
            }
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('lnameErr')[0].innerHTML = '$lnameErr'; </script>";
        return $validated;
    }
    function emailValidate($data)
    {
        $emailErr = "";
        $validated = false;
        if(empty($data))
        {
            $emailErr = "Email is required!";
        }
        else
        {
            $email = test_input($data);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $emailErr = "Please provide a valid email address";
            }else{
                $validated = true;
            }
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('emailErr')[0].innerHTML = '$emailErr'; </script>";
        return $validated;
    }
    function phoneValidation($data)
    {
        $validated = false;
        $phoneErr = "";
        if(empty($data))
        {
            $phoneErr = "Phone number cannot be empty";
        }
        else{
            $phone = test_input($_POST["phone"]);
            if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone))
            {
                $phoneErr = "Provide a valid 10 digit phone number in format 000-000-0000";
            }else{
                $validated = true;
            }
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('phoneErr')[0].innerHTML = '$phoneErr'; </script>";
        return $validated;
    }
    function projectTitleValidation($data)
    {
        $validated = false;
        $ptitleErr = "";
        if(empty($data))
        {
            $ptitleErr = "Project title cannot be empty!";
        } else {
            $validated = true;
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('ptitleErr')[0].innerHTML = '$ptitleErr'; </script>";
        return $validated;
    }
    function projectScheduleValidation($data)
    {
        $validated = false;
        $scheduleErr = "";
        if(empty($data))
        {
            $scheduleErr = "Please choose a time for presentation!";
        }else{
            $validated = true;
        }
        echo "<script type = 'text/javascript'> document.getElementsByClassName('scheduleErr')[0].innerHTML = '$scheduleErr'; </script>";
        return $validated;
    }
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
        }
?>
    </body>
</html>