<?php

// Including the database connection file
include 'components/connect.php';

// Starting the session
session_start();

// Initializing user_id variable
$user_id = '';

// Checking if the user is logged in and setting the user_id
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   // Redirecting to home.php if the user is not logged in
   $user_id = '';
   header('location:home.php');
};

// Checking if the submit button is clicked
if(isset($_POST['submit'])){

   // Constructing the full address from form inputs
   $address = $_POST['flat'] .', '.$_POST['building'].', '.$_POST['area'].', '.$_POST['town'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   // Updating the user's address in the database
   $update_address = $conn->prepare("UPDATE `users` set address = ? WHERE id = ?");
   $update_address->execute([$address, $user_id]);

   // Adding a success message to the message array
   $message[] = 'Address saved!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Address</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Including the user header -->
<?php include 'components/user_header.php' ?>

<!-- Section for the address update form -->
<section class="form-container">

   <!-- Form for updating the address -->
   <form action="" method="post">
      <h3>Your Address</h3>
      <input type="text" class="box" placeholder="Flat No." required maxlength="50" name="flat">
      <input type="text" class="box" placeholder="Building No." required maxlength="50" name="building">
      <input type="text" class="box" placeholder="Area Name" required maxlength="50" name="area">
      <input type="text" class="box" placeholder="Town Name" required maxlength="50" name="town">
      <input type="text" class="box" placeholder="City Name" required maxlength="50" name="city">
      <input type="text" class="box" placeholder="State Name" required maxlength="50" name="state">
      <input type="text" class="box" placeholder="Country Name" required maxlength="50" name="country">
      <input type="number" class="box" placeholder="Pin Code" required max="999999" min="0" maxlength="6" name="pin_code">
      <input type="submit" value="Save Address" name="submit" class="btn">
   </form>

</section>

<!-- Including the footer -->
<?php include 'components/footer.php' ?>

<!-- Including custom JavaScript file -->
<script src="js/script.js"></script>

</body>
</html>