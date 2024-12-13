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

// Check if the payment update form is submitted
if (isset($_POST['update_payment'])) {

   // Retrieve order ID and payment status from the form
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];

   // Prepare and execute a query to update the payment status of the order
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);

   // Display a success message
   $message[] = 'payment status updated!';

}

// Check if a 'delete' parameter is present in the URL
if (isset($_GET['delete'])) {
   // Retrieve the order ID to be deleted
   $delete_id = $_GET['delete'];
   
   // Prepare and execute a query to delete the order from the database
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);

   // Redirect back to the placed orders page
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Meta tags and stylesheets -->
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- External stylesheet and font-awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php

// Include the header section of the admin panel
include '../components/admin_header.php';

?>

<!-- Main section for displaying placed orders -->
<section class="placed-orders">

   <!-- Heading for the placed orders section -->
   <h1 class="heading">placed orders</h1>

   <!-- Container for order boxes -->
   <div class="box-container">

   <?php
      // Query to select all orders from the database
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();

      // Check if there are orders in the result
      if ($select_orders->rowCount() > 0) {
         // Loop through each order and display information
         while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <!-- Box for displaying individual order information -->
   <div class="box">
      <p> User ID : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total price : <span><?= $fetch_orders['total_price']; ?>PHP/-</span> </p>
      <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
      <!-- Form for updating payment status and deleting the order -->
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <!-- Dropdown for selecting payment status -->
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">pending</option>
            <option value="completed">completed</option>
         </select>
         <!-- Buttons for updating and deleting the order -->
         <div class="flex-btn">
            <input type="submit" value="update" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </div>
      </form>
   </div>
   <?php
      }
   } else {
      // Display a message if no orders are placed yet
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>

<!-- Include JavaScript file for additional functionality -->
<script src="../js/admin_script.js"></script>

</body>
</html>