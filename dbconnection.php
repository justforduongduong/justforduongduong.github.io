<?php
    // $con = mysqli_connect("localhost", "root", "", "justforduongduong");

    // if($con == false)
    // {
    //     die("ERROR: Could not connect. " . mysqli_connect_error());
    // }
?>

<?php
    $servername = "sql12.freesqldatabase.com";
    $username = "sql12760856";
    $password = "LQUhSeiB7J";
    $dbname = "sql12760856";

    // Create connection
    $con = new mysqli_connecti($servername, $username, $password, $dbname);

    // Check connection
    if($con == false)
    {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>