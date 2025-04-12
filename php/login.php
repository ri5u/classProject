<?php
    session_start();
    if(isset($_SESSION["error_message"])){
        echo $_SESSION["error_message"];
        echo "<a href=\"signup.php\">Sign Up</a>";
        unset($_SESSION["error_message"]);
    }
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<body>
    <form action="login.php" method="POST">
        <label for="username">username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Log In</button>
    </form>
</body>
</html>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["username"])){
            $username = $_POST["username"];
        }
        
        if(isset($_POST["password"])){
            $password = $_POST["password"];
        }

        // echo $username." ".$password;

        /* DATABASE SECTION */
        error_reporting(E_ALL);
        ini_set("display_errors","1");

        $servername = "localhost";
        $user = "r15u";
        $pass = "1234";
        $db_name = "projectDB";

        $conn = new mysqli($servername, $user, $pass, $db_name);

        if($conn->connect_error){
            die("Connection Failed".$conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM userInfo where username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($user){
            $hashed_password = $user["password"];
            if(password_verify($password, $hashed_password)){
                $_SESSION["username"] = $username;
                header("Location: ../index.php");
                exit();
            }
            else{
                echo "FUCK OFF BITCH";
            }
        }
        else{
            $_SESSION["error_message"] = "USER DOESN'T EXIST";
            header("Location: login.php");
        }
    }
?>

