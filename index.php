<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <!-- HEADER PART -->
    <header>
        <div class="logo">Header Logo</div>
        <nav>
            <ul>
                <?php if(isset($_SESSION["username"])): ?>
                    <li><a href="profile.php"><?= $_SESSION["username"]?></a></li>  <!--?= is shorthand for ?php -->
                    <li><a href="php/logout.php">Log Out</a></li>
                <?php else: ?>
                    <li><a href="php/login.php">Log In</a></li>
                    <li><a href="php/signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="front">
        <div class="highlights">
            <div class="text">This Website is awesome</div>
            <p>This is some subtext that is related to the 
                website.
            </p>
            <div class="explore">
                <a href="php/explore.php" class="explore-btn">Explore</a>
            </div>
        </div>

        <div class="highlights-image">     
            <img src="images/mualani.jpeg" alt="Genshin Impact Mualani">
            <figcaption>Mualani From Genshin Impact</figcaption>
        </div>
    </div>

    <!-- TOP PICKS SECTION -->

    <div class="title">
        SOME RANDOM INFORMATION
    </div>

     <div class="showcase">
        
        <div class="item">
            <img src="images/mualani.jpeg">
            <div class="text">more mualani</div>
        </div>

        <div class="item">
            <img src="images/mualani.jpeg">
            <div class="text">more mualani</div>
        </div>

        <div class="item">
            <img src="images/mualani.jpeg">
            <div class="text">more mualani</div>
        </div>

        <div class="item">
            <img src="images/mualani.jpeg">
            <div class="text">more mualani</div>
        </div>


     </div>

     <!-- QUOTES -->

     <div class="quote">
        <div class="text">
            Life can get hard, can you?
            <div class="cite">
                - Mualani, People Of The Springs
            </div>
        </div>
     </div>

     <!-- PROMOTION -->
     <div class="signup">
        <div class="box">
            <div class="text">
                <b>Want to see more of mualani!</b>
                <br>Sign up to get the best of mualani art from 
                the artists around the world.
            </div>
            <div class="signup-btn">
                <a href="signup.php">Sign Up</a>
            </div>
        </div>
     </div>

     <footer>
        <div class="copyright">
            COPYRIGHT © The Mualani Project 2024
        </div>
     </footer>
</body>
</html>