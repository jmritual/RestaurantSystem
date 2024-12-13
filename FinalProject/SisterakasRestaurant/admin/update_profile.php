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

// Check if the update profile form is submitted
if (isset($_POST['submit'])) {

   // Retrieve and sanitize the updated name
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Check if the provided name is not empty
   if (!empty($name)) {
      // Prepare and execute a query to check if the new username is taken
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);
      // Display a message if the username is already taken
      if ($select_name->rowCount() > 0) {
         $message[] = 'username already taken!';
      } else {
         // Update the username in the database
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
      }
   }

   // Set a default empty password value
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

   // Retrieve the old, previous, and new passwords
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   // Check if the old password is not the default empty value
   if ($old_pass != $empty_pass) {
      // Check if the old password matches the previous password in the database
      if ($old_pass != $prev_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         // Check if the new password and confirm password match
         $message[] = 'confirm password not matched!';
      } else {
         // Check if the new password is not the default empty value
         if ($new_pass != $empty_pass) {
            // Update the password in the database
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'password updated successfully!';
         } else {
            $message[] = 'please enter a new password!';
         }
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
   <title>profile update</title>

   <!-- External stylesheet and font-awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php

// Include the header section of the admin panel
include '../components/admin_header.php';

?>

<!-- Section for updating the admin profile -->
<section class="form-container">

   <!-- Form for updating the admin profile -->
   <form action="" method="POST">
      <h3>update profile</h3>
      <!-- Input field for updating the admin username -->
      <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
      <!-- Input fields for updating passwords -->
      <input type="password" name="old_pass" maxlength="20" placeholder="enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <!-- Submit button for updating the admin profile -->
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>

<!-- Include the JavaScript file for the admin panel -->
<script src="../js/admin_script.js"></script>

</body>
</html>