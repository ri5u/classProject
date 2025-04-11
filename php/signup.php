<?php
    if(isset($_GET["success"])){
        if($_GET["success"] === "1"){
            echo "SUCCESSFULLY ADDED";
        } else {
            echo "USERNAME OR EMAIL ALREADY EXISTS";
        }

        echo '<p><a href="signup.php" style="color: blue; text-decoration: underline;">🔙 Return to Sign Up</a></p>';
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <form action="signup.php" method="POST">
        <input type="text" name="username" required>
        <input type="password" name="password" required>
        <input type="email" name="email" required>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["username"])){
            $username = $_POST["username"];
        }

        if(isset($_POST["password"])){
            $password = $_POST["password"];
        }

        if(isset($_POST["email"])){
            $email = $_POST["email"];
        }


        /* DATABASE SECTION */

        // error_reporting(E_ALL);
        // ini_set("display_errors","1");

        mysqli_report(MYSQLI_REPORT_OFF);
        
        $servername = "localhost";
        $user = "r15u";
        $pass = "1234";
        $db_name = "projectDB";

        $conn = new mysqli($servername, $user, $pass, $db_name);

        if($conn->connect_error){
            die("Connection to the server failed". $conn->connect_error);
        }


        $sql = "INSERT INTO userInfo (username, password, email) VALUES ('$username', '$password', '$email')";
        $result = $conn->query($sql);
        if($result){
            header("Location: signup.php?success=1");
            exit();
        }
        else{
            header("Location: signup.php?success=0");
            exit();
        }
    }
?>

