<!-- PHP code to manage session, handle form submission, and delete products -->
<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){
   // Handling form submission to add a new product
   
   // Retrieving and sanitizing form data
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   // Checking if the product name already exists
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exists!';
   }else{
      // Validating and moving the uploaded image file
      if($image_size > 2000000){
         $message[] = 'image size is too large';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         // Inserting the new product into the database
         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image]);

         $message[] = 'new product added!';
      }
   }
}

if(isset($_GET['delete'])){
   // Handling deletion of a product
   
   $delete_id = $_GET['delete'];

   // Retrieving product image information
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);

   // Deleting product image file
   unlink('../uploaded_img/'.$fetch_delete_image['image']);

   // Deleting product record from the database
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);

   // Deleting product from user carts
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);

   // Redirecting back to the products page
   header('location:products.php');
}
?>

<!-- HTML code for the Products page -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!-- Including the admin header -->
<?php include '../components/admin_header.php' ?>

<!-- Section for adding new products -->
<section class="add-products">
   <!-- Form for adding new products -->
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add product</h3>
      <!-- Input fields for product details -->
      <input type="text" required placeholder="Enter product name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Select category --</option>
         <option value="Main Dish">Main Dish</option>
         <option value="Drink">Drink</option>
         <option value="Dessert">Dessert</option>
      </select>
      <!-- Input field for product image -->
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <!-- Submit button for adding product -->
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>
</section>

<!-- Section for displaying existing products -->
<section class="show-products" style="padding-top: 0;">
   <!-- Container for displaying product boxes -->
   <div class="box-container">
      <?php
         // Fetching and displaying existing products
         $show_products = $conn->prepare("SELECT * FROM `products`");
         $show_products->execute();
         if($show_products->rowCount() > 0){
            while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <!-- Product box -->
      <div class="box">
         <!-- Displaying product image -->
         <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <!-- Displaying product details -->
         <div class="flex">
            <div class="price"><?= $fetch_products['price']; ?><span>PHP/-</span></div>
            <div class="category"><?= $fetch_products['category']; ?></div>
         </div>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <!-- Buttons for updating and deleting product -->
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">Delete</a>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>
   </div>
</section>

<!-- Including the admin script -->
<script src="../js/admin_script.js"></script>

</body>
</html>