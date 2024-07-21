<html>
<head>
<title>Login Result</title>
</head>
<body>
<?php

// Create connection
$conn = new mysqli("localhost", "root", "", "test");

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: ". $conn->connect_error);
} 

/* Get user input which name is "id" and "pwd" 
(assume the id and pwd has correct format) */
$id = $_POST["id"]; 
$pwd = $_POST["pwd"]; 

// Select database to search the coressponding user row
    
// Start of 2a
$search_sql = $conn->prepare("SELECT salt, hash FROM userhash where id = ?"); 
$search_sql->bind_param("s", $id); 
$search_sql->execute();
$search_sql->store_result();
// End of 2a

// If login name can be found in table "userhash"
if($search_sql->num_rows > 0) 
{
    $search_sql->bind_result($salt, $hash);
    $search_sql->fetch();
       
    // Compute hash value using salt in database and password from user input
    $pwdhash = hash("sha512", $salt . $pwd); // 2b

    /* Check whether the hash value in database and the hase value 
    computed in step 6 are the same */
    if(strcmp($hash, $pwdhash) == 0) // 2c
    {
	   echo "<h2>Authentication success!</h2>";
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

// Close connection
mysqli_close($conn);
?>
</body>
</html>

