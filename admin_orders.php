<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

// to update the order status 
// The isset() function is used to check if a form has been submitted or a query
// parameter exists in the URL
if (isset($_POST['update_order'])) {

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'Payment status has been updated!';

}

// to delete the record of the order from the database
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="orders-container">
      <section class="orders">

         <h1 class="title">Placed Orders</h1>

         <div class="box-container">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            if (mysqli_num_rows($select_orders) > 0) {
               while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                  ?>
                  <div class="box">
                     <p> User id : <span>
                           <?php echo $fetch_orders['user_id']; ?>
                        </span> </p>
                     <p> Placed on : <span>
                           <?php echo $fetch_orders['placed_on']; ?>
                        </span> </p>
                     <p> Name : <span>
                           <?php echo $fetch_orders['name']; ?>
                        </span> </p>
                     <p> Number : <span>
                           <?php echo $fetch_orders['number']; ?>
                        </span> </p>
                     <p> Email : <span>
                           <?php echo $fetch_orders['email']; ?>
                        </span> </p>
                     <p> Address : <span>
                           <?php echo $fetch_orders['address']; ?>
                        </span> </p>
                     <p> Total Products : <span>
                           <?php echo $fetch_orders['total_products']; ?>
                        </span> </p>
                     <p> Total Price : <span>Rs
                           <?php echo $fetch_orders['total_price']; ?>/-
                        </span> </p>
                     <p> Payment Method : <span>
                           <?php echo $fetch_orders['method']; ?>
                        </span> </p>
                     <form action="" method="post">
                        <!-- The hidden input field is named "order_id" and its value is set 
                        to the value of the "id" key from the $fetch_orders array using 
                        PHP echo -->
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                        <select name="update_payment">
                           <!-- The first option is selected and disabled, and it shows the current 
                           payment status of the order, which is retrieved from the $fetch_orders 
                           array using the payment_status key and displayed using echo. -->
                           <option value="" selected disabled>
                              <?php echo $fetch_orders['payment_status']; ?>
                                 </option>
                                 <option value="pending">pending</option>
                                 <option value="completed">completed</option>
                              </select>
                              <input type="submit" value="update" name="update_order" class="option-btn">

                              <!-- The code creates a hyperlink that includes the ID of the order
                              to be deleted as a GET parameter. When the hyperlink is clicked,
                              the browser sends a request to the "admin_orders.php" page with 
                              the "delete" parameter containing the ID of the order to be 
                              deleted. The PHP code on the "admin_orders.php" page checks for 
                              the presence of the "delete" parameter by isset() function
                              and uses its value in a SQL DELETE query to delete the 
                              corresponding order from the database. -->
                              <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>"
                           onclick="return confirm('Do you want to delete this order?');" class="delete-btn">delete</a>
                           <!-- a confirmation message pops on the screen -->
                        </form>
                  </div>
                  <?php
               }
            } else {
               echo '<p class="empty">No orders placed yet!</p>';
            }
            ?>
         </div>

      </section>

   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>