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
   // Setting user_id to an empty string if the user is not logged in
   $user_id = '';
};

// Including the add_cart.php file
include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Section for the search form -->
<section class="search-form">
   <!-- Search form -->
   <form method="post" action="">
      <input type="text" name="search_box" placeholder="Search here..." class="box">
      <button type="submit" name="search_btn" class="fas fa-search"></button>
   </form>
</section>

<!-- Section for displaying products based on search results -->
<section class="products" style="min-height: 100vh; padding-top:0;">

   <!-- Container for displaying products -->
   <div class="box-container">

      <?php
         // Checking if the search form is submitted
         if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
            // Retrieving the search term from the form
            $search_box = $_POST['search_box'];

            // Using the method from the Product class to search for products
            $products = $productObj->searchProducts($search_box);

            // Checking if there are products matching the search term
            if(!empty($products)){
               // Displaying products
               foreach($products as $fetch_products){
      ?>
      <!-- Form for adding products to the cart -->
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex">
            <div class="price"><?= $fetch_products['price']; ?><span>PHP</span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
               }
            }else{
               // Displaying a message if no products are found
               echo '<p class="empty">No products added yet!</p>';
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