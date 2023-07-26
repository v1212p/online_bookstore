<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}
;

if (isset($_POST['add_product'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'Book name already exists';
   } else {
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');

      if ($add_product_query) {
         if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Book added successfully!';
         }
      } else {
         $message[] = 'Book could not be added!';
      }
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/' . $fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="show-prod-wrapper">
      <section class="add-products">

         <h1 class="title">Shop Books</h1>

         <form action="" method="post" enctype="multipart/form-data">
            <h3>Add product</h3>
            <input type="text" name="name" class="box" placeholder="Enter book name" required>
            <input type="number" min="0" name="price" class="box" placeholder="Enter book price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="add book" name="add_product" class="btn">
         </form>

      </section>

      <!-- show products  -->

      <section class="show-products">

         <div class="box-container">

            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
               while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                  ?>
                  <div class="box">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                     <div class="name">
                        <?php echo $fetch_products['name']; ?>
                     </div>
                     <div class="price">Rs
                        <?php echo $fetch_products['price']; ?>/-
                     </div>
                     <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn"
                        onclick="return confirm('Confirm deletion of this book?');">delete</a>
                  </div>
                  <?php
               }
            } else {
               echo '<p class="empty">No books added yet!</p>';
            }
            ?>
         </div>

      </section>
   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>