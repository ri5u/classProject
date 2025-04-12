<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
        <h2>UPLOAD ART</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title">

            <label for="description">Description:</label>
            <input type="text" id="description" name="description">

            <label for="artwork">Choose art to upload:</label>
            <input type="file" id="artwork" name="artwork" accept="image/*" required>

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

                $allowedType = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webm'];
                $fileType = mime_content_type($_FILES["artwork"]["tmp_name"]);
                if(!in_array($fileType, $allowedType)){
                    exit("Invalid Image");
                }
                
                $destinationDir = "../uploads";
                $originalFileName = basename($_FILES["artwork"]["name"]);
                $destinationFileName = $destinationDir."/".$originalFileName;

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
                
                $sql = "INSERT INTO artworks (uid, file_name, file_path, title, description) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $uid, $fileName, $filePath, $title, $description);

                if( $stmt->execute() ){
                    echo "Artwork Successfully Uploaded To The Database";
                }else{
                    echo "Error Uploading to the database".$stmt->error;
                }

                $stmt->close();
                $conn->close();
            } 
        } 
    }
?>
    
        