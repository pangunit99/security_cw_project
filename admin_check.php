<html>
<head>
<title>Login Result</title>
</head>
<body>
<?php
// Create connection
$conn = new mysqli("localhost", "root", "Acv23354a", "cw2");
session_start();
if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
  }
  if(!$captcha){
    echo '<h2>Please check the the captcha.</h2>';
    exit;
  }

$secretKey = "6Lc4HyEoAAAAAKhqsQUyB0tKnucMPmVNAzWHews0";
$ip = $_SERVER['REMOTE_ADDR'];
// post request to server
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
$response = file_get_contents($url);
$responseKeys = json_decode($response,true);
// should return JSON with success as true
if($responseKeys["success"]) {

        // Check connection
        if ($conn->connect_error) 
        {
            die("Connection failed: ". $conn->connect_error);
        } 


        $account = $_POST["account"]; 
        $pwd = $_POST["pwd"]; 


        $search_sql = $conn->prepare("SELECT salt, hash FROM hash where account = ?"); 
        $search_sql->bind_param("s", $account); 
        $search_sql->execute();
        $search_sql->store_result();

        if($search_sql->num_rows > 0) 
        {
            $search_sql->bind_result($salt, $hash);
            $search_sql->fetch();
            
            // Compute hash value using salt in database and password from user input
            $pwdhash = hash("sha512", $salt . $pwd); 

            if(strcmp($hash, $pwdhash) == 0) 
            {
                $_SESSION['admin'] = "yes";
                echo '<script>;alert("Authentication success!");location.href="admin_create_account.php";</script>;';
            }
            else
            {
                echo "<h2>The password is wrong, authentication failed</h2>";
            }
        }
        else
        {
            echo "<h2>User name not exist, authentication failed</h2>";
        }

} else {
        echo '<h2>You are bot!</h2>';
}




mysqli_close($conn);
?>
</body>
</html>

