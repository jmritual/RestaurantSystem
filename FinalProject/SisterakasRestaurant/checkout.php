<?php

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
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   // Redirect to login page if the user is not logged in
   header('location:login.php');
   exit();
};

// Handling the form submission for placing an order
if(isset($_POST['submit'])){
   // Sanitizing and retrieving form data
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $address = $_POST['address'];
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   // Checking if the cart is not empty
   $check_cart = $productObj->getCartItemsByUserId($user_id);

   if(count($check_cart) > 0){
      // Checking if the address is provided
      if($address == ''){
         $message[] = 'Please add your address!';
      }else{
         $message[] = $productObj->placeOrder($user_id, $name, $number, $email, $method, $address, $total_products, $total_price);
      }
   }else{
      $message[] = 'Your cart is empty';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Header section with navigation links -->
<div class="heading">
   <h3>checkout</h3>
   <p><a href="home.php">home</a> <span> / checkout</span></p>
</div>

<!-- Section for displaying order summary and checkout form -->
<section class="checkout">

   <!-- Title for the order summary -->
   <h1 class="title">order summary</h1>

   <!-- Form for submitting the order -->
   <form action="" method="post">

      <!-- Container for displaying cart items and total -->
      <div class="cart-items">
         <h3>cart items</h3>
         <?php
            // Initializing variables for total and cart items
            $grand_total = 0;
            $cart_items[] = '';
            // Selecting items from the cart for the user
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            // Checking if there are items in the cart
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  // Constructing cart items and calculating total
                  $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
         <!-- Displaying individual cart items -->
         <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price"><?= $fetch_cart['price']; ?>PHP x <?= $fetch_cart['quantity']; ?></span></p>
         <?php
               }
            }else{
               echo '<p class="empty">your cart is empty!</p>';
            }
         ?>
         <!-- Displaying grand total and View cart button -->
         <p class="grand-total"><span class="name">Grand total :</span><span class="price"><?= $grand_total; ?>PHP</span></p>
         <a href="cart.php" class="btn">View cart</a>
      </div>

      <!-- Hidden input fields for passing data to the form submission -->
      <input type="hidden" name="total_products" value="<?= $total_products; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
      <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
      <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
      <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
      <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

      <!-- Container for displaying user information and address -->
      <div class="user-info">
         <h3>your info</h3>
         <!-- Displaying user information -->
         <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
         <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
         <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
         <!-- Button for updating user information -->
         <a href="update_profile.php" class="btn">update info</a>
         <h3>delivery address</h3>
         <!-- Displaying delivery address -->
         <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['address'];} ?></span></p>
         <!-- Button for updating delivery address -->
         <a href="update_address.php" class="btn">update address</a>
         <!-- Dropdown for selecting payment method -->
         <select name="method" class="box" required>
            <option value="" disabled selected>Select payment method --</option>
            <option value="Cash on delivery">Cash on delivery</option>
            <option value="Gcash">GCash</option>
         </select>
         <!-- Submit button for placing the order -->
         <input type="submit" value="place order" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
      </div>

   </form>
   
</section>

<!-- Including the footer -->
<?php include 'components/footer.php'; ?>

<!-- Including custom JavaScript file -->
<script src="js/script.js"></script>

</body>
</html>