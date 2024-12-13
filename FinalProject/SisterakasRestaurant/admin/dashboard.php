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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Meta tags and stylesheets -->
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- External stylesheet and font-awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php

// Include the header section of the admin panel
include '../components/admin_header.php';

?>

<!-- Main section for the admin dashboard -->
<section class="dashboard">

   <!-- Heading for the admin dashboard -->
   <h1 class="heading">dashboard</h1>

   <!-- Container for dashboard boxes -->
   <div class="box-container">

      <!-- Box displaying welcome message and update profile button -->
      <div class="box">
         <h3>welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">update profile</a>
      </div>

      <!-- Box displaying total pending orders and a link to view orders -->
      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
               $total_pendings += $fetch_pendings['total_price'];
            }
         ?>
         <h3><?= $total_pendings; ?><span>PHP/-</span></h3>
         <p>total pendings</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <!-- Box displaying total completed orders and a link to view orders -->
      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
               $total_completes += $fetch_completes['total_price'];
            }
         ?>
         <h3><?= $total_completes; ?><span>PHP/-</span></h3>
         <p>total completes</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <!-- Box displaying total number of orders and a link to view orders -->
      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>total orders</p>
         <a href="placed_orders.php" class="btn">see orders</a>
      </div>

      <!-- Box displaying total number of products and a link to view products -->
      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $numbers_of_products = $select_products->rowCount();
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>products added</p>
         <a href="products.php" class="btn">see products</a>
      </div>

      <!-- Box displaying total number of user accounts and a link to view users -->
      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $numbers_of_users = $select_users->rowCount();
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>users accounts</p>
         <a href="users_accounts.php" class="btn">see users</a>
      </div>

      <!-- Box displaying total number of admin accounts and a link to view admins -->
      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admin`");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $numbers_of_admins; ?></h3>
         <p>admins</p>
         <a href="admin_accounts.php" class="btn">see admins</a>
      </div>

   </div>

</section>

<!-- Include JavaScript file for additional functionality -->
<script src="../js/admin_script.js"></script>

</body>
</html>