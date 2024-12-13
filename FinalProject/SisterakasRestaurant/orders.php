<?php
// orders.php

// Include the Database class
include 'classes/database.php'; 

// Include the Product class
include 'classes/product.php';

// Create an instance of the Database class
$databaseObj = new Database();
$conn = $databaseObj->getConnection();

// Create an instance of the Product class
$productObj = new Product();

// Starting the session
session_start();

// Initializing user_id variable
$user_id = '';

// Checking if the user is logged in and setting the user_id
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Setting user_id to an empty string and redirecting to home.php if user is not logged in
    $user_id = '';
    header('location:home.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- External CSS and Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Page heading with breadcrumb -->
<div class="heading">
    <h3>Orders</h3>
    <p><a href="home.php">Home</a> <span> / Orders</span></p>
</div>

<!-- Section for displaying user orders -->
<section class="orders">

    <!-- Title of the section -->
    <h1 class="title">Your Orders</h1>

    <!-- Container for displaying order information -->
    <div class="box-container">

        <?php
        // Checking if the user is not logged in
        if ($user_id == '') {
            // Displaying a message to login to see orders
            echo '<p class="empty">Please login to see your orders</p>';
        } else {
            // Fetching user orders
            $userOrders = $productObj->getUserOrders($user_id);

            // Checking if there are orders for the user
            if (!empty($userOrders)) {
                // Looping through each order and displaying its information
                foreach ($userOrders as $order) {
                    ?>
                    <!-- Box displaying order details -->
                    <div class="box">
                        <p>Placed on: <span><?= $order['placed_on']; ?></span></p>
                        <p>Name: <span><?= $order['name']; ?></span></p>
                        <p>Email: <span><?= $order['email']; ?></span></p>
                        <p>Number: <span><?= $order['number']; ?></span></p>
                        <p>Address: <span><?= $order['address']; ?></span></p>
                        <p>Payment method: <span><?= $order['method']; ?></span></p>
                        <p>Your orders: <span><?= $order['total_products']; ?></span></p>
                        <p>Total price: <span><?= $order['total_price']; ?>PHP/-</span></p>
                        <!-- Displaying payment status with color based on status -->
                        <p>Payment status: <span style="color:<?php echo ($order['payment_status'] == 'pending') ? 'red' : 'green'; ?>"><?= $order['payment_status']; ?></span></p>
                    </div>
                    <?php
                }
            } else {
                // Displaying a message if no orders are placed yet
                echo '<p class="empty">No orders placed yet!</p>';
            }
        }
        ?>

    </div>

</section>

<!-- Including the footer -->
<?php include 'components/footer.php'; ?>

<!-- Including custom JavaScript file -->
<script src="js/script.js"></script>

</body>
</html>