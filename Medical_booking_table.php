<html>
<head>
<title>Login Form</title>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<?php
header("X-Content-Type-Options: nosniff");
session_start();
if (!isset($_SESSION['medical']) || $_SESSION['medical'] !== "yes") {
    // Redirect the user to a login page or display an error message
    header("Location: Medical_login.php");
    exit;
}
?>
<body>
    
<?php
        $conn = new mysqli("localhost", "root", "Acv23354a", "cw2");
        $search_sql = $conn->prepare("SELECT * FROM booking_data "); 
        $search_sql->execute();
        $search_sql->store_result();
        $cipher = "aes-256-cbc";
            
        // The key must be 256 bits
        $key = "gepiOMjbWnQSGgp9VDgimccjXR7FeiSz";


        
        if($search_sql->num_rows > 0) 
        {
            $search_sql->bind_result($cname,$ename,$hkid,$gender,$bookdate,$time,$venues,$e);;
            ?>
            <table border="1">
                <tr>
                    <th>cname</th>
                    <th>ename</th>
                    <th>HKID</th>
                    <th>gender</th>
                        <th>bookdate</th>
                        <th>time</th>
                        <th>venues</th>
                        <th>Print</th>
                </tr>

                <?php
                    while($search_sql->fetch()){
                    $iv=hex2bin($e);
                    // Decrypt ciphertext to plaintext
                    $original_plaintext = openssl_decrypt($hkid, $cipher, $key, $options=0, $iv)
                ?>
                <tr>
                    <td><?php echo $cname?></td>
                    <td><?php echo $ename?></td>
                    <td><?php echo $original_plaintext?></td>
                    <td><?php echo $gender?></td>
                    <td><?php echo $bookdate?></td>
                    <td><?php echo $time?></td>
                    <td><?php echo $venues?></td>
                    <?php
                        printf("<td><a href='#'>print</a></td>");
                    ?>
                </tr>

                <?php
                    };
                ?>
            </table>

<?php

        }
?>



</body>
</html>