 <?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$currentDayOfWeek = date("l");
$servername = "localhost";
$username = "root";
$password = "";      
$database = "weather1";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    echo "Failed to connect: " . mysqli_connect_error();
    exit;
}

session_start();
if(isset($_SESSION['cityname'])){
    $cityName = $_SESSION['cityname'];
    if(empty($cityName)){
        $cityName = "Madison";
    }
}else{
    $cityName = "Madison";
}

// Fetch all data from the weatherData table   AND Day_Of_Week='$currentDayOfWeek' 
$selectAllData = "SELECT * FROM weatherData WHERE City='$cityName' ";
$result = mysqli_query($conn, $selectAllData);

if (!$result) {
    echo "Failed to fetch data: " . mysqli_error($conn);
    exit;
}

$rows = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    $data = json_encode($rows);
    echo $data;
    header('Content-Type: application/json');
}else{
    // $erroResponse = ['error'=>true,'message'=>'City not found'];
    // $data = json_decode($data);
    // echo $data;
    // header('Content-Type: application/json');
    if (empty($data)) {
        // Data not present
        $errorResponse = ['error' => true, 'message' => 'Data not found'];
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode($errorResponse);
        exit; // Stop script execution
}
}

mysqli_close($conn);
?>