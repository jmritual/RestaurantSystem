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

// Handling deletion of a cart item
if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $message[] = $productObj->deleteCartItem($cart_id);
}

// Handling deletion of all cart items
if(isset($_POST['delete_all'])){
   $message[] = $productObj->deleteAllCartItems($user_id);
}

// Handling updating quantity of a cart item
if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $message[] = $productObj->updateCartItemQuantity($cart_id, $qty);
}

// Initializing the grand total variable
$grand_total = 0;

// Get cart items for the user
$cartItems = $productObj->getCartItemsByUserId($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Displaying heading section -->
<div class="heading">
   <h3>shopping cart</h3>
   <p><a href="home.php">home</a> <span> / cart</span></p>
</div>

<!-- Section for displaying cart products -->
<section class="products">

   <!-- Displaying the cart title -->
   <h1 class="title">your cart</h1>

   <!-- Container for displaying cart items -->
   <div class="box-container">

      <?php
         // Initializing and fetching the grand total
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <!-- Form for each cart item -->
      <form action="" method="post" class="box">
         <!-- Hidden input for cart item id -->
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <!-- Button for deleting the cart item -->
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('delete this item?');"></button>
         <!-- Displaying cart item image -->
         <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
         <!-- Displaying cart item name -->
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <!-- Displaying cart item price, quantity, and editing option -->
         <div class="flex">
            <div class="price"><?= $fetch_cart['price']; ?><span>PHP</span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
         <!-- Displaying sub total for the cart item -->
         <div class="sub-total"> sub total : <span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>PHP/-</span> </div>
      </form>
      <?php
               // Adding the sub total to the grand total
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">your cart is empty</p>';
         }
      ?>

   </div>

   <!-- Displaying the total and proceed to checkout button -->
   <div class="cart-total">
      <p>cart total : <span><?= $grand_total; ?>PHP</span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

   <!-- Displaying delete all and continue shopping buttons -->
   <div class="more-btn">
      <!-- Form for deleting all cart items -->
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('delete all from cart?');">delete all</button>
      </form>
      <!-- Link for continuing shopping -->
      <a href="home.php" class="btn">continue shopping</a>
   </div>

</section>

<!-- Including the footer -->
<?php include 'components/footer.php'; ?>

<!-- Including custom JavaScript -->
<script src="js/script.js"></script>

</body>
</html>