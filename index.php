<?php 
    session_start();
    require_once("php/fetch_images.php");
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pictorio</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- <script src="js/index.js" defer></script> -->

    <script>

        //Hightlights image changing dynamically
        document.addEventListener("DOMContentLoaded", ()=>{ //eventlistener to change the image on interval.
            const imageArray = <?php echo $imagePathJson; ?>;
            const highligthsImg = document.querySelector(".highlights-image > img");
            console.log(imageArray);
            function changeImg(){
                const randImg = imageArray[Math.floor(Math.random() * imageArray.length)];
                highligthsImg.src = randImg;
            }

            if(imageArray.length > 0){
                changeImg();
                setInterval(changeImg, 2000);
            }
        })

        //Showcase images and artists chaging dynamically

        document.addEventListener("DOMContentLoaded", () => {
            const randomArtist = <?php echo $randomArtistJson; ?>;
            console.log(randomArtist);

            const items = document.querySelectorAll(".item");
            Object.keys(randomArtist).forEach((username, index) => {
                if (items[index]) {
                    const link = items[index].querySelector(".item-link");
                    link.href = `php/profile.php?user=${encodeURIComponent(username)}`;
                    link.querySelector("img").src = randomArtist[username];
                    link.querySelector(".text").textContent = username;
                }
            }); 
        });

    </script>
</head>
<body>
    <!-- HEADER PART -->
    <header>
        <div class="logo">Pictorio</div>
        <nav>
            <ul>
                <form class="search-form" action="php/search.php" method="GET">
                    <input type="text" name="query" placeholder="Search Artists" required>
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>

                <?php if(isset($_SESSION["username"])): ?>
                    <li><a href="php/profile.php?user=<?= $_SESSION['username'] ?>"><?= $_SESSION["username"] ?></a></li>
                    <li><a href="php/upload.php">Uplaod</a></li>
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
            <img alt="random images">
            <figcaption>Mualani From Genshin Impact</figcaption>
        </div>
    </div>

    <!-- TOP PICKS SECTION -->

    <div class="title">
        SOME RANDOM INFORMATION
    </div>

     <div class="showcase">
        
        <div class="item">
            <a class="item-link" href="#">
                <img src="" alt="">
                <div class="text"></div>
            </a>
        </div>

        <div class="item">
            <a class="item-link" href="#">
                <img src="" alt="">
                <div class="text"></div>
            </a>
        </div>

        <div class="item">
            <a class="item-link" href="#">
                <img src="" alt="">
                <div class="text"></div>
            </a>
        </div>

        <div class="item">
            <a class="item-link" href="#">
                <img src="" alt="">
                <div class="text"></div>
            </a>
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
      <?php if(!isset($_SESSION["username"])): ?>
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
        <?php endif; ?>

     <footer>
        <div class="copyright">
            COPYRIGHT © The Mualani Project 2024
        </div>
     </footer>

</body>
</html>