<?php

// Include file for establishing a database connection
include '../components/connect.php';

// Start a session and retrieve the admin ID from the session variable
session_start();
$admin_id = $_SESSION['admin_id'];

// Redirect to the admin login page if admin ID is not set
if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Check if the registration form is submitted
if (isset($_POST['submit'])) {

   // Retrieve and sanitize user input for username
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Retrieve, sanitize, and hash user input for password
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Retrieve, sanitize, and hash user input for confirmed password
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Prepare and execute a query to check if the entered username already exists
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   // Check if the username already exists
   if ($select_admin->rowCount() > 0) {
      $message[] = 'username already exists!';
   } else {
      // Check if the entered password and confirmed password match
      if ($pass != $cpass) {
         $message[] = 'confirm password not matched!';
      } else {
         // Insert the new admin's information into the database
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'new admin registered!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Meta tags and stylesheets -->
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- External stylesheet and font-awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php

// Include the header section of the admin panel
include '../components/admin_header.php';

?>

<!-- Section for the registration form -->
<section class="form-container">

   <!-- Registration form -->
   <form action="" method="POST">
      <h3>register new</h3>
      
      <!-- Input field for username -->
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Input field for password -->
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Input field for confirmed password -->
      <input type="password" name="cpass" maxlength="20" required placeholder="confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Submit button -->
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>