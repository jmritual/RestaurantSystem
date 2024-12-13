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
   // Setting user_id to an empty string and redirecting to home page if the user is not logged in
   $user_id = '';
   header('location:home.php');
};

// Including the script for adding items to the cart
include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Section for displaying food categories -->
<section class="category">

   <!-- Title for the food category section -->
   <h1 class="title">Food Category</h1>

   <!-- Container for category boxes -->
   <div class="box-container">

      <!-- Category box for Main Dishes -->
      <a href="category.php?category=Main Dish" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Main dishes</h3>
      </a>

      <!-- Category box for Drinks -->
      <a href="category.php?category=Drink" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Drinks</h3>
      </a>

      <!-- Category box for Desserts -->
      <a href="category.php?category=Dessert" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Desserts</h3>
      </a>

   </div>

</section>

<!-- Section for displaying food menu -->
<section class="products">

   <!-- Title for the food menu section -->
   <h1 class="title">Food Menu</h1>

   <!-- Container for displaying product boxes -->
   <div class="box-container">

      <?php
         // Using the method from the Product class to get all products
         $products = $productObj->getAllProducts();

         // Checking if there are products available
         if(!empty($products)){
            foreach($products as $fetch_products){
      ?>
      <!-- Form for adding products to the cart -->
      <form action="" method="post" class="box">
         <!-- Hidden input fields for passing product details to the form submission -->
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <!-- Button for adding the product to the cart -->
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <!-- Product image -->
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <!-- Category link for the product -->
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <!-- Product name -->
         <div class="name"><?= $fetch_products['name']; ?></div>
         <!-- Price and quantity input field -->
         <div class="flex">
            <div class="price"><?= $fetch_products['price']; ?><span>PHP</span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         }else{
            // Displayed if there are no products available
            echo '<p class="empty">No products added yet!</p>';
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