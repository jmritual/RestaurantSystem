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

// Checking if the user is logged in and setting the user_id
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

// Including the file to handle adding items to the cart
include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Section for displaying products based on the selected category -->
<section class="products">

   <!-- Displaying the title for the category -->
   <h1 class="title">food category</h1>

   <!-- Container for displaying products -->
   <div class="box-container">

      <?php
         // Getting the category from the URL
         $category = $_GET['category'];
         
         // Selecting products based on the category using the method from the Product class
         $products = $productObj->getProductsByCategory($category);
         
         // Checking if there are products in the selected category
         if(!empty($products)){
            foreach($products as $fetch_products){
      ?>
      <!-- Form for each product with add to cart functionality -->
      <form action="" method="post" class="box">
         <!-- Hidden input fields for product details -->
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <!-- Button for adding the product to the cart -->
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <!-- Displaying product image -->
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <!-- Displaying product name -->
         <div class="name"><?= $fetch_products['name']; ?></div>
         <!-- Displaying product price, quantity input, and add to cart button -->
         <div class="flex">
            <div class="price"><?= $fetch_products['price']; ?><span>PHP</span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>

   </div>

</section>

<!-- Including the footer -->
<?php include 'components/footer.php'; ?>

<!-- Including Swiper library for image slider -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Including custom JavaScript file -->
<script src="js/script.js"></script>

</body>
</html>