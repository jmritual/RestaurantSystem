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

// Check if the delete request is received via GET
if (isset($_GET['delete'])) {
   // Retrieve the user ID to be deleted
   $delete_id = $_GET['delete'];
   
   // Delete user data from 'users' table
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);

   // Delete user orders from 'orders' table
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);

   // Delete user cart items from 'cart' table
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);

   // Redirect to the users accounts page after deletion
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Meta tags and stylesheets -->
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>

   <!-- External stylesheet and font-awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php

// Include the header section of the admin panel
include '../components/admin_header.php';

?>

<!-- Section for managing user accounts -->
<section class="accounts">

   <!-- Heading for the user accounts section -->
   <h1 class="heading">users account</h1>

   <!-- Container for displaying user accounts -->
   <div class="box-container">

   <?php
      // Retrieve all user accounts from the 'users' table
      $select_account = $conn->prepare("SELECT * FROM `users`");
      $select_account->execute();
      // Check if there are user accounts available
      if ($select_account->rowCount() > 0) {
         // Loop through each user account
         while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {  
   ?>
   <!-- Display user details in a box -->
   <div class="box">
      <p> User ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <!-- Link to delete the user account with confirmation -->
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('delete this account?');">delete</a>
   </div>
   <?php
      }
   } else {
      // Display a message if no user accounts are available
      echo '<p class="empty">no accounts available</p>';
   }
   ?>

   </div>

</section>

<!-- Include the JavaScript file for the admin panel -->
<script src="../js/admin_script.js"></script>

</body>
</html>