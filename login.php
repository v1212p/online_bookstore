<?php

include 'config.php';
// session_start() : used to start a new or resume an existing session. 
// A session is a way to store information (such as user preferences, login status, or 
// shopping cart contents) across multiple page requests made by the same user.
session_start();

// isset($_POST['submit']) is a PHP function that checks whether a form has been submitted
// using the HTTP POST method and whether a particular form field 
// (in this case, a button with the name "submit") has been included in the request.
if (isset($_POST['submit'])) {
   // The function escapes special characters in the string by adding a backslash before them,
   // which prevents them from being interpreted as part of the query syntax.
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   //md5() is a built-in PHP function that is used to create a one-way hash of a string.
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   //  form data submitted via the POST method is sent to the server in the
   //  request body, and in PHP, this data is stored in the $_POST super-global array 

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select_users) > 0) {

      // mysqli_fetch_assoc() function is used to fetch a single 
      // row of data from a MySQL result set, as obtained from a SELECT query. 
      $row = mysqli_fetch_assoc($select_users);

      if ($row['user_type'] == 'admin') {
         // $_SESSION is a super-global array that is used to store and access 
         // session data across different pages or requests on a website.
         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      } elseif ($row['user_type'] == 'user') {

         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         // it sends a Location header to the browser with the value of home_page.php, 
         // causing the browser to redirect to that page.
         header('location:home.php');

      }

   } else {
      $message[] = 'Incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- <link rel> is an HTML tag used to include external resources, such as stylesheets or web fonts.
   The rel attribute stands for "relationship" i.e. the relationship between the current document and
   the resource being linked. -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>

   <div class="form-container">

      <form action="" method="post">
         <h3>Login</h3>
         <input type="email" name="email" placeholder="Enter email" required class="box">
         <input type="password" name="password" placeholder="Enter password" required class="box">
         <input type="submit" name="submit" value="Login" class="btn">
         <!-- isset($_POST['submit']) it checks if name="submit"-->
         <p>Don't have an account? <a href="register.php">Register now</a></p>
      </form>

   </div>

</body>

</html>