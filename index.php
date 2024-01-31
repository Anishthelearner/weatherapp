<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "weather1";

    $conn = mysqli_connect($servername,$username,$password,$database);
    if ($conn){
       
    }else{
        echo "Failed to connect".mysqli_connect_error();
    }

function fetchWeatherData($cityname) {

    $apiEndpoint = 'https://api.openweathermap.org/data/2.5/weather?units=metric&appid=72ca5d48b415850897dbcc076e3e7af2&q=';
    $city = urlencode($cityname);

    // Make the API request
    $apiUrl = $apiEndpoint . $city;
    $apiData = file_get_contents($apiUrl);
    // Check if the request was successful
    $httpStatusCode = $http_response_header[0];
    if ($httpStatusCode != 'HTTP/1.1 200 OK' || $httpStatusCode == 404) {
        header('location:notfound.php');
        // Handle error, e.g., return an error response
         return ['status' => 'error', 'message' => 'Failed to fetch API data. HTTP Status Code: ' . $httpStatusCode];
       
        
    }else{
         // Decode the JSON data
    $decodedData = json_decode($apiData, true);

    // Check if JSON decoding was successful
    if ($decodedData === NULL) {
        // Handle error, e.g., return an error response
        return ['status' => 'error', 'message' => 'Failed to decode API data'];
    }

    return $decodedData;
    }
   
}
   
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cityName = $_POST["cityname"];
    // Validate and process data as needed
    if(empty($cityName)){
        $cityName = "Madison";
    }
    // Store data in session
    $_SESSION["cityname"] = $cityName;
}else{
    $cityName = "Madison";
}

    $data = fetchWeatherData($cityName);
    if (isset($data['status']) && $data['status'] == '404') {
        // Handle API error, e.g., return an error response
        echo json_encode($data);
        exit();
    }
    $city = $data['name'];
    $temperature = $data['main']['temp'];
    $pressure = $data['main']['pressure'];
    $humidity = $data['main']['humidity'];
    $weather_description = $data['weather'][0]['description'];
    $wind_speed = $data['wind']['speed'];
    date_default_timezone_set('Asia/Kathmandu');
    $currentDate = date("Y-m-d H:i:s");
    $currentDayOfWeek = date("l");


    $existingData = "SELECT * FROM weatherData WHERE City='$city' AND Day_Of_Week='$currentDayOfWeek'";
    $result = mysqli_query($conn, $existingData);

    if (mysqli_num_rows($result) > 0) {
    // Data for the same city and day of the week already exists, perform an UPDATE
    $updateData = "UPDATE weatherData SET 
    Temperature=$temperature, 
    Pressure=$pressure, 
    Humidity=$humidity, 
    Weather_Description='$weather_description', 
    Wind_Speed=$wind_speed,
    Date_='$currentDate'
    WHERE City='$city' AND Day_Of_Week='$currentDayOfWeek'";


    if (mysqli_query($conn, $updateData)) {
        // echo "Data updated for $city on $currentDayOfWeek";
    } else {
        echo "Failed to update data: " . mysqli_error($conn);
    }
} else {
    // Data doesn't exist, perform an INSERT
    $insertData = "INSERT INTO weatherData (City, Temperature, Pressure, Humidity, Weather_Description, Wind_Speed, Date_, Day_Of_Week)
                   VALUES ('$city', $temperature, $pressure, $humidity, '$weather_description', $wind_speed, '$currentDate', '$currentDayOfWeek')";

    if (mysqli_query($conn, $insertData)) {
        echo "Data inserted for $city on $currentDayOfWeek";
    } else {
        echo "Failed to insert data: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
      <link rel="stylesheet" href="./style.css"> 
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
            </form>
             
             <div class="weather-box">
                 <div class="contain">
                     <div class="row1">
                     <div class="contryname">
                        <h4>City Name</h4>
                         <p class="time"></p>
                     </div>
                     <div class="temp">
                        <img src="" alt="img">
                        <p></p>
                        <h4 id="temp">20</h4>
                       
                     </div>
                 </div>
                 <div class="row2">
                    <div class="pressure">
                        <div class="h">
                            <div class="material-symbols-outlined">
                                compare_arrows
                            </div>
                                <h4>Pressure</h4>
                        </div>
                       <span></span>
                     </div>
                     <div class="humi">
                      <div class="h">
                        <div class="material-symbols-outlined">
                            humidity_percentage
                        </div>
                        <h4>Humidity</h4>
                      </div>
                         <span class="value"></span>
                     </div>
                     <div class="wind">
                        <div class="h">
                            <div class="material-symbols-outlined">
                                air
                            </div>
                            <h4>Wind</h4>
                        </div>
                        <span></span>
                     </div>
                 </div>
                 
            </div>
             <div class="weekly">
                <div class="day1">
                    <h4>Sun</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                 <div class="day2">
                    <h4>Mon</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                 <div class="day3">
                    <h4>Tue</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                 <div class="day4">
                    <h4>Wed</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                 <div class="day5">
                    <h4>Thur</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                 <div class="day6">
                    <h4>Fri</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                 <div class="day7">
                    <h4>Sat</h4>
                    <img src="" alt="img">
                    <p id="wdesc">description</p>
                    <p id="wtemp">Temperature: N/A</p>
                    <p id="wpress">Pressure: N/A</p>
                    <p id="wwind">Wind: N/A</p>
                    <p id="whumi">Humidity: N/A</p>
                 </div>
                </div>
              
    
</div>
        <footer>
            <p>&copy;2024 @anisha_gautam(2408636). All rights reserved.</p>
        </footer>
        <script src="./script.js"></script>
</body>

</html>
               