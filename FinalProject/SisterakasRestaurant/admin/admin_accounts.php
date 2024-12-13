<?php

// Include file for establishing a database connection
include '../components/connect.php';

// Start a session and retrieve the admin ID
session_start();
$admin_id = $_SESSION['admin_id'];

// Redirect to the admin login page if admin ID is not set
if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Check if a 'delete' parameter is present in the URL
if (isset($_GET['delete'])) {
    // Retrieve the admin ID to be deleted
    $delete_id = $_GET['delete'];

    // Prepare and execute a query to delete the admin from the database
    $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
    $delete_admin->execute([$delete_id]);

    // Redirect back to the admin accounts page
    header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Meta tags and stylesheets -->
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admins accounts</title>

   <!-- External stylesheet -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php

// Include the header section of the admin panel
include '../components/admin_header.php';

?>

<!-- Main section for displaying admin accounts -->
<section class="accounts">

   <!-- Heading for the admin accounts section -->
   <h1 class="heading">admins account</h1>

   <!-- Container for admin account boxes -->
   <div class="box-container">

   <!-- Box for registering a new admin -->
   <div class="box">
      <p>Register new admin</p>
      <a href="register_admin.php" class="option-btn">register</a>
   </div>

   <?php

      // Query to select all admin accounts from the database
      $select_account = $conn->prepare("SELECT * FROM `admin`");
      $select_account->execute();

      // Check if there are admin accounts in the result
      if ($select_account->rowCount() > 0) {

         // Loop through each admin account and display information
         while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
   ?>

   <!-- Box for displaying individual admin account information -->
   <div class="box">
      <p> Admin ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      
      <!-- Buttons for deleting and updating admin accounts -->
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('delete this account?');">delete</a>
         
         <?php
            // If the current admin's ID matches the displayed admin's ID, show the update button
            if ($fetch_accounts['id'] == $admin_id) {
               echo '<a href="update_profile.php" class="option-btn">update</a>';
            }
         ?>
      </div>
   </div>

   <?php
      }
   } else {
      // Display a message if no admin accounts are available
      echo '<p class="empty">no accounts available</p>';
   }
   ?>

   </div>

</section>

<!-- Include JavaScript file for additional functionality -->
<script src="../js/admin_script.js"></script>

</body>
</html>