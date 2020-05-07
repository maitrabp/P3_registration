<?php
    require 'dbconfig.php'
?>

<!DOCTYPE html>
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
        <table>
            <tr>
                <th> UMID </th>
                <th> FirstName </th>
                <th> LastName </th>
                <th> Email </th>
                <th> Phone </th>
                <th> ProjectTitle </th>
                <th> SlotDate </th>
                <th> SlotTime </th>
            </tr>
            <?php 
                $query = "select UMID, FirstName, LastName, Email, Phone, Ptitle, date, timeRange from registrants, timeslots where registrants.timeslot = timeslots.id;";
                $result = mysqli_query($con, $query);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        echo "<tr><td>" . $row["UMID"] . "</td><td>" . $row["FirstName"] . "</td><td>" . $row["LastName"] . "</td><td>" . $row["Email"]. "</td><td>" . $row["Phone"] . "</td><td>" . $row["Ptitle"] . "</td><td>" . $row["date"] . "</td><td>". $row["timeRange"] . "</td></tr>";
                    }
                }
                else{
                    echo "0 results";
                }
            ?>
        </table>
    </body>

</html>