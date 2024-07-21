<html>
<head>
<title>Login Form</title>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<?php
header("X-Content-Type-Options: nosniff");
session_start();
?>
<body>
<form action="admin_check.php" method="post">
<h1>Admin Login</h1>
User name: <input name="account" type="text" size="30" maxlength="100"><br><br>
Password: <input name="pwd" type="text" size="30" maxlength="100">
<div class="g-recaptcha" data-sitekey="6Lc4HyEoAAAAADSwOekjbwRXbQFNEdeHOuIMFFY-"></div>
<br><br>
<input name="submit" type="submit" value="submit">
</form>
</body>
</html>