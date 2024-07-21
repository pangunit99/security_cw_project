<html>
<head>
<title>Register Result</title>
</head>
<body>
<?php

// Create connection
$conn = new mysqli("localhost", "root", "Acv23354a", "cw2");

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

// Get user input from the form submitted before
$id = $_POST["id"]; 
$pwd = $_POST["pwd"];

// Set a flag to assume all user input follow the format
$allDataCorrect = true;
  
if($allDataCorrect)
{
    // Serach user table to see whether user name is exist
    
    // Start of 1a  
    $search_sql = $conn->prepare("SELECT * FROM hash where account = ?");
    $search_sql->bind_param("s", $id); 
    $search_sql->execute();
    $search_sql->store_result();
    // End of 1a

    // If login name can be found in table "user", forbid user register process

    if($search_sql->num_rows > 0) 
    {
        echo "<h2>The user name is registered by others. Please use other user name</h2>";
    }
    else
    {
        // Generate hash with salt and password
        
        // Start of 1c
        $salt = generateSalt(16);
        $pwdhash = hash("sha512", $salt . $pwd);
        // End of 1c
        
        // Start of 1d
        $insert_sql = $conn->prepare("INSERT INTO hash (account, salt, hash) VALUES (?, ?, ?)");
        $insert_sql->bind_param("sss", $id, $salt, $pwdhash);
        $insert_sql->execute();
        // End of 1d
        
        echo "<h2>Registration Success!!</h2>";
    }
}
else
{
    echo "<h3> $errMsg </h3>";
}
// Close connection
mysqli_close($conn);

// This function generate a random string with particular length
function generateSalt($length)
{
    $rand_str = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    
    // start of 1b
    for($i = 0; $i < $length; $i++) 
    {
        $rand_str = $rand_str . $chars[rand(0, strlen($chars) - 1)];
    } 
    // end of 1b
    
    return $rand_str;
}
?>
<a href="register_form.php">Go back to register page</a>
<br><br>
<a href="login_form.php">Go to login page</a>
</body>
</html>