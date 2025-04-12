<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>
    <form action="signup.php" method="POST">
        <h1>Signup to Pictoria</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>

<?php
    if(isset($_SESSION["error_message"])){
        print_r($_SESSION);
        echo $_SESSION["error_message"];
        unset($_SESSION["error_message"]);
    }
?>


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

        error_reporting(E_ALL);
        ini_set("display_errors","1");

        // mysqli_report(MYSQLI_REPORT_OFF);
        // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        
        $servername = "localhost";
        $user = "r15u";
        $pass = "1234";
        $db_name = "projectDB";
        $conn = new mysqli($servername, $user, $pass, $db_name);

        if($conn->connect_error){
            die("Connection to the server failed". $conn->connect_error);
        }

        //Vulnerable to sql injection

        // $sql = "INSERT INTO userInfo (username, password, email) VALUES ('$username', '$password', '$email')";
        // $result = $conn->query($sql);

        //Prepared Statements

        // echo $username." ".$password." ".$email;

        /* Checking if the username or email already exists */

        $checkStmt = $conn->prepare("SELECT uid FROM userInfo WHERE userName = ? OR email = ?");
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if($checkResult->num_rows > 0){
            $_SESSION["error_message"] = "USERNAME OR EMAIL ALREADY EXISTS";
            header("Location: signup.php");
            exit();
        }

        
        /* If it doesn't exist then we add that user to the database */


        $stmt = $conn->prepare("INSERT INTO userInfo(username, password, email) VALUES(?,?,?)");

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $username, $hashed_password, $email);
        $result = $stmt->execute();
        $stmt->close();

        if($result){
            $_SESSION["username"] = $username;
            header("Location: ../index.php");
            exit();
        }
        

        // try {
        //     $stmt = $conn->prepare("INSERT INTO userInfo(username, password, email) VALUES(?,?,?)");
        //     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        //     $stmt->bind_param("sss", $username, $hashed_password, $email);
        //     $stmt->execute();
        //     $stmt->close();
        
        //     $_SESSION["username"] = $username;
        //     header("Location: ../index.php");
        //     exit();
        // } catch (mysqli_sql_exception $e) {
        //     echo "SIGNUP FAILED: " . $e->getMessage();
        //     exit();
        // }
        
    }
?>

