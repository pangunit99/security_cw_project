<html>
<head>
<title>Create Medical staff account</title>
</head>
<body>

<?php
header("X-Content-Type-Options: nosniff");
session_start();
if (isset($_SESSION['admin'])) {

}else{
    header("Location: admin_login.php");
}
?>

<?php
    $PasswordErr = $UsernameErr = $NameErr =  "";
    $Username = $Password = $Name = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["Username"])) {
            $UsernameErr= "Name is required";
        } else {
            $Username = test_input($_POST["Username"]);

            if (!preg_match("/^[a-zA-Z0-9]{6,12}$/", $Username)) {
                $UsernameErr = "User ID should be composed within 6 to 12 alphanumeric characters ";
            }
        }
      
        if (empty($_POST["Password"])) {
            $PasswordErr = "Password is required";
        } else {
            $Password = test_input($_POST["Password"]);

            if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=[\]{};':\"\\|,.<>\/?]).{8,}$/", $Password)) {
                $PasswordErr = "Password should be composed with at least 8 alphanumeric characters and at least 1 number, 1 lower case and 1 upper case letter, 1 symbol ";
            }
        }
        
        if (empty($_POST["Name"])) {
            $NameErr = "Name is require";
        } else {
            $Name = test_input($_POST["Name"]);
            if (!preg_match("/^[A-Za-z]+([ -][A-Za-z]+)+$/", $Name)) {
                $NameErr = "Invalid english name format";
            }
        }


    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<h1>Create Medical account</h1>
Username: <input name="Username" type="text" size="30" maxlength="12">
<span class="error">* <?php echo $UsernameErr; ?> </span>  <br><br>

Password: <input name="Password" type="text" size="30" maxlength="100">
<span class="error">* <?php echo $PasswordErr; ?> </span>  <br><br>

Staff Name: <input name="Name" type="text" size="30" maxlength="50">
<span class="error">* <?php echo $NameErr; ?> </span>  <br><br>

<input name="submit" type="submit" value="submit">
</form>


<?php  
    if(isset($_POST['submit'])) {  
        if($UsernameErr == "" && $PasswordErr == "" && $NameErr =="") {

            //connect database
            $conn = new mysqli("localhost", "root", "Acv23354a", "cw2");

            // Check account on database
            $search_sql = $conn->prepare("SELECT * FROM medical_user where username = ?");
            $search_sql->bind_param("s", $Username); 
            $search_sql->execute();
            $search_sql->store_result();

            if($search_sql->num_rows > 0) 
            {
                echo "<h2>The user name is registered by others. Please use other user name</h2>";
            }
            else
            {

                // Generate hash with salt and password
                $salt = generateSalt(16);
                $pwdhash = hash("sha512", $salt . $Password);

                
                //save on database
                $insert_sql = $conn->prepare("INSERT INTO medical_user (name, username, salt, hash) VALUES (?, ?, ?, ?)");
                $insert_sql->bind_param("ssss",$Name ,$Username, $salt, $pwdhash);
                $insert_sql->execute();

                
                echo "<h2>Account create successful!</h2>";  
            }

            mysqli_close($conn);
        } else {  
            echo "<h3> <b>Please input correct data.</b> </h3>";  
        }  
    }



    function generateSalt($length)
        {
            $rand_str = "";
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            
            for($i = 0; $i < $length; $i++) 
            {
                $rand_str = $rand_str . $chars[rand(0, strlen($chars) - 1)];
            } 
            
            return $rand_str;
        }
?>






</body>
</html>