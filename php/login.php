<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <?php include("header.php"); ?>

    <form action="login.php" method="POST">
        <h1>Login to Pictoria</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
       <!-- <div class="error"> -->
        <?php if(isset($_SESSION["error_message"])): ?>
            <div id="err-msg"><?=$_SESSION["error_message"];?></div>
            <?php unset($_SESSION["error_message"]);?>
        <?php endif;?>
        <!-- </div> -->
        <button type="submit">Log In</button>
        <div class="register">
            <p>Don't have an account?</p>
            <a href="signup.php">Signup here</a>
        </div>

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
            $uid = $user["uid"];
            if(password_verify($password, $hashed_password)){
                $_SESSION["username"] = $username;
                if($username == "admin"){
                    $_SESSION["admin"] = true;
                }
                $_SESSION["uid"] = $uid;
                header("Location: ../index.php");
                exit();
            }
            else{
                $_SESSION["error_message"] = "WRONG PASSWORD";
                header("Location: login.php");
                exit();
            }
        }
        else{
            $_SESSION["error_message"] = "USER DOESN'T EXIST";
            header("Location: login.php");
            exit();
        }
    }
?>

