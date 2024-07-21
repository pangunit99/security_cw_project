<html>
<head>
<title>Login Form</title>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<?php
header("X-Content-Type-Options: nosniff");
?>
<body>
<?php

$conn = new mysqli("localhost", "root", "Acv23354a", "cw2");

//lock login 30s when login fail 3 times
session_start();
if (isset($_SESSION["locked"]))
{
    $difference = time() - $_SESSION["locked"];
    if ($difference > 30)
    {
        unset($_SESSION["locked"]);
        unset($_SESSION["login_attempts"]);
    }
}

//login submit function
if(isset($_POST['submit'])){

    if(isset($_POST['g-recaptcha-response'])){
        $captcha=$_POST['g-recaptcha-response'];
    }
    if(!$captcha){
        echo '<h2>Please check the the captcha.</h2>';
        exit;
    }

    //google recaptcha I am not robot check
    $secretKey = "6Lc4HyEoAAAAAKhqsQUyB0tKnucMPmVNAzWHews0";
    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]) {

            // Check database connection
            if ($conn->connect_error) 
            {
                die("Connection failed: ". $conn->connect_error);
            } 


            $username = $_POST["username"]; 
            $pwd = $_POST["pwd"]; 


            $search_sql = $conn->prepare("SELECT salt, hash FROM medical_user where username = ?"); 
            $search_sql->bind_param("s", $username); 
            $search_sql->execute();
            $search_sql->store_result();

            //find user check password
            if($search_sql->num_rows > 0) 
            {
                $search_sql->bind_result($salt, $hash);
                $search_sql->fetch();
                
                // Compute hash value using salt in database and password from user input
                $pwdhash = hash("sha512", $salt . $pwd); 

                if(strcmp($hash, $pwdhash) == 0) 
                {
                    $_SESSION['medical'] = "yes";

                    echo '<script>;alert("Authentication success!");location.href="Medical_booking_table.php";</script>;';
                    
                }else{
                    echo '<script>;alert("The password is wrong!");location.href="Medical_login.php";</script>;';

                    //cal user login fail times
                    $_SESSION["login_attempts"] += 1;

                }
            }
            else
            {
                //cal user login fail times
                echo '<script>;alert("User name not exist!");location.href="Medical_login.php";</script>;';
                $_SESSION["login_attempts"] += 1;
            }

    } else {
            echo '<h2>You are bot!</h2>';
    }

}
mysqli_close($conn);
?>




<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<h1>Login</h1>
User name: <input name="username" type="text" size="30" maxlength="100"><br><br>
Password: <input name="pwd" type="password" size="30" maxlength="100">
<br><br>
<div class="g-recaptcha" data-sitekey="6Lc4HyEoAAAAADSwOekjbwRXbQFNEdeHOuIMFFY-"></div>

<?php

// In sign-in form submit button

if(isset($_SESSION["login_attempts"])){
    if ($_SESSION["login_attempts"] > 2)
    {
        $_SESSION["locked"] = time();
        echo "Please wait for 30 seconds";
    }
    else
    {
    ?>
    <input name="submit" type="submit" value="submit">
    
    <?php

    }
}else{
    ?>
    <input name="submit" type="submit" value="submit">
    <?php
}
?>
</form>






</body>
</html>