<html>
<head>
<title>Booking Form</title>
</head>
<body>
<?php
header("X-Content-Type-Options: nosniff");
?>
<?php
    $cnameErr = $enameErr = $hkidErr = $bookdatesErr =  "";
    $cname = $ename = $hkid = $gender= $bookdates= $time= $venues="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["cname"])) {
            $cnameErr = "Name is required";
        } else {
            $cname = test_input($_POST["cname"]);
            if (!preg_match("/\p{Han}+/u", $cname)) {
                $cnameErr = "please input Chinese name in 3-6 chinese characters";
            }
        }
      
        if (empty($_POST["ename"])) {
            $enameErr = "English name is required";
        } else {
            $ename = test_input($_POST["ename"]);
            if (!preg_match("/^[A-Za-z]+([ -][A-Za-z]+)+$/", $ename)) {
                $enameErr = "Invalid english name format";
            }
        }
        
        if (empty($_POST["hkid"])) {
            $hkidErr = "HKID card number is required";
        } else {
            $hkid = test_input($_POST["hkid"]);
            if (!preg_match('/^[A-Z]{1,2}[0-9]{6}\([0-9A]\)$/', $hkid)) {
                $hkidErr = "please input correct hkid card number like A123456(7)";
            }    
        }

        if (empty($_POST["bookdates"])) {
            $bookdatesErr = "please select booking day";
        } else {
            $bookdates = test_input($_POST["bookdates"]);
            if ($bookdates <= date('Y-m-d')) {
                $bookdatesErr = "please select correct day";
            }    
        }

        $gender = test_input($_POST["gender"]);
        $time = test_input($_POST["time"]);
        $venues = test_input($_POST["venues"]);

    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>



<?php  
    if(isset($_POST['submit'])) {  
        if($cnameErr == "" && $enameErr == "" && $hkidErr == "" && $bookdatesErr=="") {
            $hide=1;
            echo "<h2>Your Input:</h2>";  
            echo "Name: " .$cname;  
            echo "<br>";  
            echo "ename: " .$ename;  
            echo "<br>";  
            echo "hkid: " .$hkid;  
            echo "<br>";  
            echo "gender: " .$gender;  
            echo "<br>";  
            echo "bookdates: " .$bookdates;  
            echo "<br>";  
            echo "time: " .$time;  
            echo "<br>";  
            echo "venues: " .$venues;  

            $conn = new mysqli("localhost", "root", "Acv23354a", "cw2");

            // Check connection
            if ($conn->connect_error) 
            {
                die("Connection failed: ". $conn->connect_error);
            } 


            $cipher = "aes-256-cbc";
            
            // The key must be 256 bits
            $key = "gepiOMjbWnQSGgp9VDgimccjXR7FeiSz";
            
            if (in_array($cipher, openssl_get_cipher_methods()))
            {
                // Declare the length of IV
                $ivlen = openssl_cipher_iv_length($cipher);
                
                // Generate random IV
                $iv = openssl_random_pseudo_bytes($ivlen);
                
                // Encrypt plaintext to ciphertext
                $ciphertext = openssl_encrypt($hkid, $cipher, $key, $options=0, $iv, $tag);
                //iv to hex save on database
                $ivHex = bin2hex($iv);
            }
            

            $insert_sql = $conn->prepare("INSERT INTO booking_data (cname, ename, hkid, gender, bookdates,time,venues,e) VALUES (?, ?, ?, ?, ?,?,?,?)");  // 2c
            $insert_sql->bind_param("ssssssss", $cname, $ename, $ciphertext, $gender, $bookdates, $time, $venues,$ivHex); 
            $insert_sql->execute();
            echo "<h2>Booking Success!!</h2>";
            mysqli_close($conn);
        } else {  
            echo "<h3> <b>You Have some error input.</b> </h3>";  
        }  
    }  

?>  


<?php
if(!isset($hide)){
?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1>Booking Form</h1>
                Chinese  Name: <input name="cname" type="text" size="30" maxlength="6">
                <span class="error">* <?php echo $cnameErr; ?> </span>  <br><br>

                English Name:  <input name="ename" type="text" size="30" maxlength="10">
                <span class="error">* <?php echo $enameErr; ?> </span>  <br><br>

                gender:        <select name="gender">
                                        <option value="F">Female</option>
                                        <option value="M">Male</option>
                                    </select><br><br>

                HKID Card Number: <input name="hkid" type="text" size="30" maxlength="15" placeholder="eg: A123456(7)">
                <span class="error">* <?php echo $hkidErr; ?> </span>  <br><br>

                brand of vaccine: <select name="vaccine">
                                        <option value="BioNTech">BioNTech</option>
                                        <option value="Sinovac">Sinovac</option>
                                    </select><br><br>

                dates:           <input type="date" id="dates" name="bookdates">
                <span class="error">* <?php echo $bookdatesErr; ?> </span>  <br><br>

                Time:
                <select name="time" id="time">
                    <option value="9:00am">9:00am</option>
                    <option value="9:30am">9:30am</option>
                    <option value="10:00am">10:00am</option>
                    <option value="10:30am">10:30am</option>
                    <option value="11:00am">11:00am</option>
                    <option value="11:30am">11:30am</option>
                    <option value="12:00pm">12:00pm</option>
                    <option value="12:30pm">12:30pm</option>
                    <option value="1:00pm">1:00pm</option>
                    <option value="1:30pm">1:30pm</option>
                    <option value="2:00pm">2:00pm</option>
                    <option value="2:30pm">2:30pm</option>
                    <option value="3:00pm">3:00pm</option>
                    <option value="3:30pm">3:30pm</option>
                    <option value="4:00pm">4:00pm</option>
                    <option value="4:30pm">4:30pm</option>
                    <option value="5:00pm">5:00pm</option>
                    <option value="5:30pm">5:30pm</option>
                    <option value="6:00pm">6:00pm</option>
                    <option value="6:30pm">6:30pm</option>
                    <option value="7:00pm">7:00pm</option>
                </select><br><br>

                venues for selection: <select name="venues">
                                        <option value="TWEH">Tung Wah Eastern Hospital</option>
                                        <option value="POWH">Prince of Wales Hospital</option>
                                    </select><br><br>

                <br><br>
                <input name="submit" type="submit" value="submit">
                </form>

<?php
}

?>


</body>
</html>