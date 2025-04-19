<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="../css/upload.css">
</head>
<body>
        <?php include"header.php";?>
        
        <form action="upload.php" method="POST" enctype="multipart/form-data">
        <h2>UPLOAD ART</h2>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title">

            <label for="description">Description:</label>
            <input type="text" id="description" name="description">

            <label for="artwork">Choose art to upload:</label>
            <input type="file" id="artwork" name="artwork" accept="image/*" required>
            <?php 
            if(isset($_SESSION["success_message"])){
                echo $_SESSION["success_message"];
                unset($_SESSION["success_message"]);
            }
            ?>
            <button type="submit">Submit</button>
        </form>
</body>
</html>

<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_FILES["artwork"])) {
            if ($_FILES["artwork"]["error"] != 0) { //0 is for success here.
        
                echo "Error Code: " . $_FILES["artwork"]["error"];
                exit();
            }
            else{

                /*
                * VALIDATING AND MOVING THE FILE TO THE SERVER FROM THE TEMP
                */
                
                //Checking for the allowed file types. 
                $allowedType = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webm'];
                $fileType = mime_content_type($_FILES["artwork"]["tmp_name"]);
                if(!in_array($fileType, $allowedType)){
                    exit("Invalid Image");
                }
                
                //Moving the file from the php's temporary dir to the server
                $destinationDir = "../uploads";
                $originalFileName = basename($_FILES["artwork"]["name"]);
                $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $uniqueName = uniqid("art_", true) . "." . $ext; //generating a uniquer name for the file
                $destinationFileName = $destinationDir . "/" . $uniqueName;  //Concatenating the dir string with filename for the complete filepath of the new file.

                if(move_uploaded_file($_FILES["artwork"]["tmp_name"], $destinationFileName)){
                    echo "FILE UPLOADED SUCCESSFULLY!!! YIPEEEEE ";
                }else{
                    echo "Couldn't move the file";
                }
                

                /*
                 * ADDING FILES TO THE DATABASE PART
                 */

                $servername = "localhost";
                $user = "r15u";
                $pass = "1234";
                $db_name = "projectDB";

                $conn = new mysqli($servername, $user, $pass, $db_name);
                if ($conn->connect_error) {
                    exit("CONNECTION FAILED". $conn->connect_error);
                }

                //Fetching the uid of the current user using the session global variable
                $username = $_SESSION["username"];
                $sql = "SELECT uid FROM userInfo WHERE username = '$username'";
                $result = $conn->query($sql);
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $uid = $row["uid"];
                }

                $title = $_POST["title"];
                $description = $_POST["description"];
                $fileName = $_FILES["artwork"]["name"];
                $filePath = $destinationFileName;
                
                //Your usual sql insert statement but we are using prepared here because some of the inputs are user inputs and that 
                //could lead to sql injection errors. 
                $sql = "INSERT INTO artworks (uid, file_name, file_path, title, description) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $uid, $fileName, $filePath, $title, $description);

                

                if( $stmt->execute() ){
                    $_SESSION["success_message"] = "File Uploaded Successfully!!!";
                    header("Location: upload.php");
                }else{
                    $_SESSION["success_message"] = "Failed To Upload the File To The Database";
                    header("Location: upload.php");
                }
                $stmt->close();
                $conn->close();
                exit();
            } 
        } 
    }
?>
    
        