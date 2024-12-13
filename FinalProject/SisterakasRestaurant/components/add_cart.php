<?php

// Check if the form for adding to the cart is submitted
if(isset($_POST['add_to_cart'])){

   // Check if the user is not logged in
   if($user_id == ''){
      // Redirect to the login page if the user is not logged in
      header('location:login.php');
   }else{

      // Sanitize and retrieve product details from the form
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      // Check if the product is already in the user's cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      // If the product is already in the cart, show a message
      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{
         // If the product is not in the cart, insert it into the 'cart' table
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'added to cart!';
      }

   }

}

?>