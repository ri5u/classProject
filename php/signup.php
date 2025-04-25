<?php 
session_start();

// Process form submission
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["username"])){
        $username = $_POST["username"];
    }

    if(isset($_POST["password"])){
        $password = $_POST["password"];
        
        // Server-side password validation
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $password)) {
            $_SESSION["error_message"] = "Password must be at least 8 characters long and contain at least one letter, one number, and one special character (@$!%*#?&)";
            header("Location: signup.php");
            exit();
        }
    }

    if(isset($_POST["email"])){
        $email = $_POST["email"];
        
        // Server-side email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_message"] = "Please enter a valid email address";
            header("Location: signup.php");
            exit();
        }
    }

    /* DATABASE SECTION */
    error_reporting(E_ALL);
    ini_set("display_errors","1");
    
    $servername = "localhost";
    $user = "r15u";
    $pass = "1234";
    $db_name = "projectDB";
    $conn = new mysqli($servername, $user, $pass, $db_name);

    if($conn->connect_error){
        die("Connection to the server failed". $conn->connect_error);
    }

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .password-requirements {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }
        .requirement {
            display: flex;
            align-items: center;
            margin: 2px 0;
        }
        .requirement i {
            margin-right: 5px;
        }
        .requirement.valid {
            color: green;
        }
        .requirement.invalid {
            color: red;
        }
        .error-message {
            color: red;
            margin: 10px 0;
            padding: 10px;
            background-color: #fee;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include"header.php"; ?>
    <form action="signup.php" method="POST" id="signupForm">
        <h1>Signup to Pictoria</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required 
               pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$"
               title="Password must be at least 8 characters long and contain at least one letter, one number, and one special character">
        <div class="password-requirements">
            <div class="requirement" id="length">
                <i class="fas fa-times"></i> At least 8 characters long
            </div>
            <div class="requirement" id="letter">
                <i class="fas fa-times"></i> Contains at least one letter
            </div>
            <div class="requirement" id="number">
                <i class="fas fa-times"></i> Contains at least one number
            </div>
            <div class="requirement" id="special">
                <i class="fas fa-times"></i> Contains at least one special character
            </div>
        </div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <?php
        if(isset($_SESSION["error_message"])){
            echo '<div class="error-message">' . $_SESSION["error_message"] . '</div>';
            unset($_SESSION["error_message"]);
        }
        ?>
        <button type="submit">Sign Up</button>
        <div class="users">
            <p>Already a user?</p>
            <a href="login.php">Login here</a>
        </div>
    </form>

    <script>
        const passwordInput = document.getElementById('password');
        const requirements = {
            length: document.getElementById('length'),
            letter: document.getElementById('letter'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            // Check length
            if (password.length >= 8) {
                requirements.length.classList.add('valid');
                requirements.length.classList.remove('invalid');
                requirements.length.querySelector('i').className = 'fas fa-check';
            } else {
                requirements.length.classList.add('invalid');
                requirements.length.classList.remove('valid');
                requirements.length.querySelector('i').className = 'fas fa-times';
            }

            // Check for letter
            if (/[A-Za-z]/.test(password)) {
                requirements.letter.classList.add('valid');
                requirements.letter.classList.remove('invalid');
                requirements.letter.querySelector('i').className = 'fas fa-check';
            } else {
                requirements.letter.classList.add('invalid');
                requirements.letter.classList.remove('valid');
                requirements.letter.querySelector('i').className = 'fas fa-times';
            }

            // Check for number
            if (/\d/.test(password)) {
                requirements.number.classList.add('valid');
                requirements.number.classList.remove('invalid');
                requirements.number.querySelector('i').className = 'fas fa-check';
            } else {
                requirements.number.classList.add('invalid');
                requirements.number.classList.remove('valid');
                requirements.number.querySelector('i').className = 'fas fa-times';
            }

            // Check for special character
            if (/[@$!%*#?&]/.test(password)) {
                requirements.special.classList.add('valid');
                requirements.special.classList.remove('invalid');
                requirements.special.querySelector('i').className = 'fas fa-check';
            } else {
                requirements.special.classList.add('invalid');
                requirements.special.classList.remove('valid');
                requirements.special.querySelector('i').className = 'fas fa-times';
            }
        });

        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            if (!/(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}/.test(password)) {
                e.preventDefault();
                alert('Please ensure your password meets all requirements');
            }
        });
    </script>
</body>
</html>

