<?php

// Include file for establishing a database connection
include '../components/connect.php';

// Start a session
session_start();

// Check if the login form is submitted
if (isset($_POST['submit'])) {

   // Retrieve and sanitize the entered username
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Retrieve, sanitize, and hash the entered password
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Prepare and execute a query to check if the entered credentials are valid
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   
   // Check if the query returned any rows (valid credentials)
   if ($select_admin->rowCount() > 0) {
      // Fetch the admin ID from the result
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      
      // Set the admin ID in the session variable
      $_SESSION['admin_id'] = $fetch_admin_id['id'];

      // Redirect to the admin dashboard
      header('location:dashboard.php');
   } else {
      // If credentials are not valid, display an error message
      $message[] = 'incorrect username or password!';
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
   <title>login</title>

   <!-- External stylesheet and font-awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php
// Display error messages, if any
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- Login form section -->
<section class="form-container">

   <!-- Login form -->
   <form action="" method="POST">
      <h3>login now</h3>
      
      <!-- Input field for username -->
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Input field for password -->
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Submit button -->
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

</section>

</body>
</html>