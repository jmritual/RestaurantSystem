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
   // Setting user_id to an empty string if user is not logged in
   $user_id = '';
};

// Checking if the login form is submitted
if(isset($_POST['submit'])){

   // Retrieving and sanitizing user inputs
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Selecting user from the database based on provided email and hashed password
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);

   // Fetching user data
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   // Checking if a user is found with the provided credentials
   if($select_user->rowCount() > 0){
      // Setting user_id in the session and redirecting to home page
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      // Displaying an error message if login credentials are incorrect
      $message[] = 'Incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- External CSS and Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- Including the user header -->
<?php include 'components/user_header.php'; ?>

<!-- Section for displaying the login form -->
<section class="form-container">

   <!-- Login form -->
   <form action="" method="post">
      <h3>Login Now</h3>
      <!-- Input fields for email and password -->
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <!-- Submit button -->
      <input type="submit" value="Login Now" name="submit" class="btn">
      <!-- Link to register page for new users -->
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>

</section>

<!-- Including the footer -->
<?php include 'components/footer.php'; ?>

<!-- Including custom JavaScript file -->
<script src="js/script.js"></script>

</body>
</html>