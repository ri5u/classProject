<?php

error_reporting(E_ALL);
ini_set("display_errors","1");

session_start();
require_once("db_connection.php");

if (!isset($_GET["query"]) || empty(trim($_GET["query"]))) {
    echo "Please enter an artist name.";
    exit();
}

$search = trim($_GET["query"]);

// Using prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM userInfo WHERE username = ?");
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Artist exists, redirect to profile
    header("Location: profile.php?user=" . urlencode($search));
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Pictoria</title>
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --purple: #81559b;
            --light-blue: #6c8bae;
            --cream: #f5f5f5;
            --error-red: #e74c3c;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .site-header {
            background: linear-gradient(145deg, var(--purple), var(--light-blue));
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .site-header h1 {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .site-header a {
            color: white;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: bold;
            transition: opacity 0.3s ease;
            display: inline-block;
        }

        .site-header a:hover {
            opacity: 0.9;
        }

        .search-container {
            max-width: 800px;
            margin: 120px auto 50px;
            padding: 3rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.1),
                inset 0 0 0 1px rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }

        .search-container:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.15),
                inset 0 0 0 1px rgba(255, 255, 255, 0.4);
        }

        .search-icon {
            font-size: 5rem;
            color: var(--purple);
            margin-bottom: 1.5rem;
            opacity: 0.8;
            animation: bounce 1s ease infinite;
        }

        .search-message {
            color: var(--purple);
            font-size: 1.8rem;
            margin-bottom: 2rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .search-query {
            color: var(--light-blue);
            font-weight: bold;
            background: linear-gradient(145deg, var(--purple), var(--light-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            display: inline-block;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2.5rem;
            background: linear-gradient(145deg, var(--purple), var(--light-blue));
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-top: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            background: linear-gradient(145deg, var(--light-blue), var(--purple));
        }

        .back-button i {
            font-size: 1.2rem;
        }

        .search-suggestions {
            margin-top: 2.5rem;
            color: #666;
            background: rgba(245, 245, 245, 0.8);
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .search-suggestions p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: var(--purple);
            font-weight: 500;
        }

        .search-suggestions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .search-suggestions li {
            margin: 0.8rem 0;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .search-suggestions li:hover {
            transform: translateX(5px);
        }

        .search-suggestions a {
            color: var(--light-blue);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .search-suggestions a:hover {
            color: var(--purple);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .search-container {
                margin: 50px 20px;
                padding: 2rem;
            }

            .search-message {
                font-size: 1.5rem;
            }

            .search-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <h2 class="search-message">
            No artist found with the username 
            <span class="search-query">'<?php echo htmlspecialchars($search); ?>'</span>
        </h2>
        
        <div class="search-suggestions">
            <p>Try these suggestions:</p>
            <ul>
                <li>• Check your spelling and try searching again</li>
                <li>• Browse artists on the <a href="explore.php">explore page</a></li>
            </ul>
        </div>

        <a href="../index.php" class="back-button">
            <i class="fas fa-home"></i> Back to Home
        </a>
    </div>
</body>
</html>
