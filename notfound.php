
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
      <link rel="stylesheet" href="style.css"> 
     <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="main">
         <form action="index.php" method="post">
            <div class="search-box">
                <input type="text" placeholder="CityName" name="cityname">
                <button  id="btn" name="citysubmit" >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            <div class="not-found">
                <h5>City Not Found !</h5>
             </div>
            </form>
           
    
</div>
        <footer>
            <p>&copy;2024 @anisha_gautam(2408636). All rights reserved.</p>
        </footer>
        <script src="./script.js"></script>
</body>

</html>
               